<?php

namespace FanFerret\QuestionBundle\Controller;

class AdminController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    protected function getUser()
    {
        $retr = parent::getUser();
        if (!($retr instanceof \FanFerret\QuestionBundle\Entity\User)) throw new \LogicException(
            'Expected user to be represented by User entity'
        );
        return $retr;
    }

    private function isAdmin()
    {
        $u = $this->getUser();
        return $u->hasRole('ROLE_ADMIN');
    }

    private function doesAclApply(\FanFerret\QuestionBundle\Entity\Acl $acl, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        $s = $acl->getSurvey();
        if (!is_null($s)) {
            return $s->getId() === $survey->getId();
        }
        $property = $survey->getProperty();
        if (is_null($property)) return false;
        $p = $acl->getProperty();
        if (!is_null($p)) {
            return $p->getId() === $property->getId();
        }
        $group = $property->getGroup();
        if (is_null($group)) return false;
        $g = $acl->getGroup();
        if (!is_null($g)) {
            return $g->getId() === $group->getId();
        }
        return false;
    }

    private function getApplicableAcls(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        foreach ($this->getUser()->getAcls() as $acl) {
            if ($this->doesAclApply($acl,$survey)) yield $acl;
        }
    }

    private function deliveryCheck(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        if ($this->isAdmin()) return true;
        foreach ($this->getApplicableAcls($survey) as $acl) {
            return true;
        }
        return false;
    }

    private function commentCardsCheck(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        if ($this->isAdmin()) return true;
        foreach ($this->getApplicableAcls($survey) as $acl) {
            if ($acl->getRole() === 'ROLE_ADMIN') return true;
        }
        return false;
    }

    private function getForm()
    {
        return $this->createFormBuilder()
            ->add('first_name',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('last_name',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('email',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('room',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('checkout',\Symfony\Component\Form\Extension\Core\Type\DateType::class,['widget' => 'single_text'])
            ->add('submit',\Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm();
    }

    private function createSurveySession(array $data)
    {
        $session = new \FanFerret\QuestionBundle\Entity\SurveySession();
        $session->setRoom($data['room']);
        $tokens = $this->get('fan_ferret_question.token_generator');
        $session->setToken($tokens->generate());
        $session->setCreated(new \DateTime());
        $session->setCheckout($data['checkout']);
        $session->setFirstName($data['first_name']);
        $session->setLastName($data['last_name']);
        $session->setEmail($data['email']);
        return $session;
    }

    private function deliveryActionImpl(\Symfony\Component\HttpFoundation\Request $request, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        if (!$this->deliveryCheck($survey)) throw $this->createAccessDeniedException();
        $form = $this->getForm();
        $form->handleRequest($request);
        $session = null;
        if ($form->isValid()) {
            $session = $this->createSurveySession($form->getData());
            $survey->addSurveySession($session);
            $session->setSurvey($survey);
            $doctrine = $this->getDoctrine();
            $em = $doctrine->getManager();
            $em->persist($session);
            $em->flush();
            $form = $this->getForm();
        }
        return $this->render('FanFerretQuestionBundle:Admin:delivery.html.twig',[
            'form' => $form->createView(),
            'session' => $session,
            'survey' => $survey,
            'comment_cards' => $this->commentCardsCheck($survey)
        ]);
    }

    private function getSurveyBySlug($group, $property, $survey)
    {
        $slug = [$property,$survey];
        if (!is_null($group)) array_unshift($slug,$group);
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\Survey::class);
        $retr = $repo->getBySlug($slug);
        if (is_null($retr)) throw $this->createNotFoundException(
            sprintf(
                'Could not find Survey entity %s',
                $this->formatSlug($group,$property,$survey)
            )
        );
        return $retr;
    }

    private function formatSlug($group, $property, $survey)
    {
        $retr = sprintf('%s/%s',$property,$survey);
        if (!is_null($group)) $retr = sprintf('%s/%s',$group,$retr);
        return $retr;
    }

    public function deliveryAction(\Symfony\Component\HttpFoundation\Request $request, $group, $property, $survey)
    {
        $entity = $this->getSurveyBySlug($group,$property,$survey);
        return $this->deliveryActionImpl($request,$entity);
    }

    private function commentCardsActionImpl(\FanFerret\QuestionBundle\Entity\Survey $survey, $page, $perpage)
    {
        if (!$this->commentCardsCheck($survey)) throw $this->createAccessDeniedException();
        $page = intval($page);
        if ($page <= 0) throw $this->createNotFoundException(
            'Expected strictly positive page number'
        );
        //  Make it zero relative
        --$page;
        //  TODO: Cap this?
        $perpage = intval($perpage);
        if ($perpage <= 0) throw $this->createNotFoundException(
            'Expected strictly positive number of results per page'
        );
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\SurveySession::class);
        $sessions = $repo->getPage($survey,$page,$perpage);
        $results = count($survey->getSurveySessions());
        $pages = intval($results / $perpage);
        if (($results === 0) || (($results % $perpage) !== 0)) ++$pages;
        return $this->render('FanFerretQuestionBundle:Admin:commentcards.html.twig',[
            'page' => $page,
            'per_page' => $perpage,
            'count' => $results,
            'pages' => $pages,
            'sessions' => $sessions,
            'survey' => $survey,
            'delivery' => $this->deliveryCheck($survey)
        ]);
    }

    public function commentCardsAction($page, $perpage, $group, $property, $survey)
    {
        $entity = $this->getSurveyBySlug($group,$property,$survey);
        return $this->commentCardsActionImpl($entity,$page,$perpage);
    }
}
