<?php

namespace FanFerret\QuestionBundle\Controller;

class AdminController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
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
            'session' => $session
        ]);
    }

    private function getSurveyBySlug($slug, $sluggroup = null)
    {
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\Survey::class);
        $survey = $repo->getBySlug($slug,$sluggroup);
        if (is_null($survey)) throw $this->createNotFoundException(
            sprintf(
                'Could not find Survey entity %s',
                $this->formatSlug($slug,$sluggroup)
            )
        );
        return $survey;
    }

    private function formatSlug($slug, $sluggroup = null)
    {
        if (is_null($sluggroup)) return $slug;
        return sprintf('%s/%s',$sluggroup,$slug);
    }

    public function deliveryAction(\Symfony\Component\HttpFoundation\Request $request, $slug, $sluggroup = null)
    {
        $survey = $this->getSurveyBySlug($slug,$sluggroup);
        return $this->deliveryActionImpl($request,$survey);
    }

    private function commentCardsActionImpl(\FanFerret\QuestionBundle\Entity\Survey $survey, $page, $perpage)
    {
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
            'survey' => $survey
        ]);
    }

    public function commentCardsAction($page, $perpage, $slug, $sluggroup = null)
    {
        $survey = $this->getSurveyBySlug($slug,$sluggroup);
        return $this->commentCardsActionImpl($survey,$page,$perpage);
    }
}
