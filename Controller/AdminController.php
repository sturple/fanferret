<?php

namespace FanFerret\QuestionBundle\Controller;

class AdminController extends BaseController
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

    private function importCheck(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        //  Some check as comment cards: Must have admin
        //  privileges
        return $this->commentCardsCheck($survey);
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

    private function createSurveySessionImpl()
    {
        $retr = new \FanFerret\QuestionBundle\Entity\SurveySession();
        $tokens = $this->get('fan_ferret_question.token_generator');
        $retr->setToken($tokens->generate());
        $retr->setCreated(new \DateTime());
        return $retr;
    }

    private function createSurveySession(array $data)
    {
        $session = $this->createSurveySessionImpl();
        $session->setRoom($data['room']);
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
            $em = $this->getEntityManager();
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
        $repo = $this->getSurveyRepository();
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

    private function getSingleButtonForm()
    {
        return $this->createFormBuilder()
            ->add('email',\Symfony\Component\Form\Extension\Core\Type\TextType::class)
            ->add('submit',\Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm();
    }

    private function createSingleButtonSurveySession(array $data)
    {
        $session = $this->createSurveySessionImpl();
        $session->setEmail($data['email']);
        return $session;
    }

    private function singleButtonDeliveryActionImpl(\Symfony\Component\HttpFoundation\Request $request, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        if (!$this->deliveryCheck($survey)) throw $this->createAccessDeniedException();
        $form = $this->getSingleButtonForm();
        $form->handleRequest($request);
        $session = null;
        if ($form->isValid()) {
            $session = $this->createSingleButtonSurveySession($form->getData());
            $survey->addSurveySession($session);
            $session->setSurvey($survey);
            $em = $this->getEntityManager();
            $em->persist($session);
            $notification = $this->createSurveyFromSurveySession($session)->sendNotification($session,1,true);
            $em->persist($notification);
            $em->flush();
            $form = $this->getSingleButtonForm();
        }
        return $this->render('FanFerretQuestionBundle:Admin:singlebuttondelivery.html.twig',[
            'form' => $form->createView(),
            'session' => $session,
            'survey' => $survey,
            'comment_cards' => $this->commentCardsCheck($survey)
        ]);
    }

    public function singleButtonDeliveryAction(\Symfony\Component\HttpFoundation\Request $request, $group, $property, $survey)
    {
        $entity = $this->getSurveyBySlug($group,$property,$survey);
        return $this->singleButtonDeliveryActionImpl($request,$entity);
    }

    private function commentCardsActionImpl(\FanFerret\QuestionBundle\Entity\Survey $survey, $page, $perpage)
    {
        if (!$this->commentCardsCheck($survey)) throw $this->createAccessDeniedException();
        //  TODO: Cap number of results per page?
        $page = new \FanFerret\QuestionBundle\Utility\Page(intval($page),intval($perpage));
        $repo = $this->getSurveySessionRepository();
        $sessions = $repo->getPage($survey,$page);
        $results = count($survey->getSurveySessions());
        return $this->render('FanFerretQuestionBundle:Admin:commentcards.html.twig',[
            'page' => $page->getPageNumber(),
            'per_page' => $page->getResultsPerPage(),
            'count' => $results,
            'pages' => $page->getNumberOfPages($results),
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

    public function listAction($page, $perpage)
    {
        $page = new \FanFerret\QuestionBundle\Utility\Page(intval($page),intval($perpage));
        $repo = $this->getSurveyRepository();
        $user = $this->getUser();
        $surveys = $repo->getByUser($user,$page);
       
        $surveys = array_map(function (\FanFerret\QuestionBundle\Entity\Survey $survey) {
            return (object)[
                'survey' => $survey,
                'delivery' => $this->deliveryCheck($survey),
                'comment_cards' => $this->commentCardsCheck($survey)
                
            ];
        },$surveys);
        $results = $repo->getCountByUser($user);
        return $this->render('FanFerretQuestionBundle:Admin:surveys.html.twig',[
            'surveys' => $surveys,
            'page' => $page->getPageNumber(),
            'per_page' => $page->getResultsPerPage(),
            'pages' => $page->getNumberOfPages($results),
            'count' => $results
        ]);
    }

    private function getMissingEmailsForm()
    {
        return $this->createFormBuilder()
            ->add('file',\Symfony\Component\Form\Extension\Core\Type\FileType::class)
            ->add('submit',\Symfony\Component\Form\Extension\Core\Type\SubmitType::class)
            ->getForm();
    }

    private function getReportEmailExtractor(\FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        //  TODO: Customize per survey?
        return new \FanFerret\QuestionBundle\Interop\CsvReportEmailExtractor(1,0,'Y-m-d H:i',new \DateTimeZone('America/Vancouver'));
    }

    private function missingEmailsCreateSurveySessions(\Symfony\Component\HttpFoundation\Request $request, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        //  No emails to create SurveySession entities for
        if (!$request->request->has('emails')) return;
        $emails = $request->request->get('emails');
        if (!is_array($emails)) throw $this->createBadRequestException('Not array');
        $all_string = array_reduce($emails,function ($carry, $item) {
            return is_string($item) ? $carry : false;
        },true);
        if (!$all_string) throw $this->createBadRequestException('Not all strings');
        $em = $this->getEntityManager();
        $survey_obj = $this->createSurvey($survey);
        $sessions = array_map(function ($email) use ($em, $survey, $survey_obj) {
            $session = $this->createSurveySessionImpl()
                ->setEmail($email)
                ->setSurvey($survey);
            $survey->addSurveySession($session);
            $notification = $survey_obj->sendNotification($session,1,true);
            $em->persist($session);
            $em->persist($notification);
            return $session;
        },$emails);
        $em->flush();
        return $sessions;
    }

    private function missingEmailsGetEmails(\Symfony\Component\HttpFoundation\File\UploadedFile $file, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        $extractor = $this->getReportEmailExtractor($survey);
        $mime = $file->getClientMimeType();
        if (!in_array($mime,$extractor->getMimeTypes(),true)) throw $this->createBadRequestException(
            sprintf(
                'Unsupported MIME type "%s"',
                $mime
            )
        );
        $path = $file->getRealPath();
       
        $str = @file_get_contents($path);
        if ($str === false) throw $this->createInternalServerErrorException(
            sprintf(
                'Could not read file %s',
                $path
            )
        );
        $result = $extractor->extract($mime,$str);
        $start = $result->getStart();
        $emails = $result->getEmails();
        $repo = $this->getSurveySessionRepository();
        return $repo->getMissingEmails($survey,$emails,$start);
    }

    private function missingEmailsActionImpl(\Symfony\Component\HttpFoundation\Request $request, \FanFerret\QuestionBundle\Entity\Survey $survey)
    {
        if (!$this->importCheck($survey)) throw $this->createAccessDeniedException();
        $form = $this->getMissingEmailsForm();
        $form->handleRequest($request);
        $emails = null;
        $sessions = null;
        //  Check to see if we're handling emails
        if ($request->request->has('submit') && ($request->request->get('submit') === 'emails')) {
            $sessions = $this->missingEmailsCreateSurveySessions($request,$survey);
        //  Otherwise try and handle file upload
        } else if ($form->isValid()) {
            $data = $form->getData();
            $file = $data['file'];
            $emails = $this->missingEmailsGetEmails($file,$survey);
            $form = $this->getMissingEmailsForm();
        }
        return $this->render('FanFerretQuestionBundle:Admin:import.html.twig',[
            'form' => $form->createView(),
            'emails' => $emails,
            'sessions' => $sessions,
            'survey' => $survey
          
        ]);
    }

    public function missingEmailsAction(\Symfony\Component\HttpFoundation\Request $request, $group, $property, $survey)
    {
        $entity = $this->getSurveyBySlug($group,$property,$survey);
        return $this->missingEmailsActionImpl($request,$entity);
    }
}
