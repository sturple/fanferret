<?php

namespace FanFerret\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    private function getQuestionType(\FanFerret\QuestionBundle\Entity\Question $q)
    {
        $params = $q->getParams();
        if (!isset($params->type)) throw new \InvalidArgumentException('Question parameter "type" missing');
        $val = $params->type;
        if (!is_string($val)) throw new \InvalidArgumentException('Question parameter "type" not string');
        return $val;
    }

    private function getQuestion(\FanFerret\QuestionBundle\Entity\Question $q)
    {
        $type = $this->getQuestionType($q);
        if ($type === 'open') return new \FanFerret\QuestionBundle\Question\OpenQuestion($q);
        throw new \LogicException(
            sprintf(
                'Unrecognized question type "%s"',
                $type
            )
        );
    }

    private function getQuestions(\FanFerret\QuestionBundle\Entity\Survey $s)
    {
        $retr = [];
        foreach ($s->getQuestionGroups() as $qg) {
            foreach ($qg->getQuestions() as $q) {
                $retr[] = $this->getQuestion($q);
            }
        }
        return $retr;
    }

    public function surveyAction(\Symfony\Component\HttpFoundation\Request $request, $token)
    {
        //  Attempt to retrieve the appropriate SurveySession
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\SurveySession::class);
        $session = $repo->getByToken($token);
        if (is_null($session)) throw $this->createNotFoundException(
            sprintf(
                'No SurveySession with token "%s"',
                $token
            )
        );
        //  Ensure the SurveySession has not been completed
        //  (you cannot redo a survey)
        if (!is_null($session->getCompleted())) throw $this->createNotFoundException(
            sprintf(
                'SurveySession with token "%s" has been completed',
                $token
            )
        );
        //  Create form
        $survey = $session->getSurvey();
        $qs = $this->getQuestions($survey);
        $fb = $this->createFormBuilder();
        foreach ($qs as $q) $q->addToFormBuilder($fb);
        $form = $fb->getForm();
        //  Handle form submission
        $form->handleRequest($request);
        if ($form->isValid()) {
            $session->setCompleted(new \DateTime());
            $em = $doctrine->getManager();
            $em->persist($session);
            $data = $form->getData();
            foreach ($qs as $q) {
                $ans = $q->getAnswer($data);
                $em->persist($ans);
                $t = $ans->getTestimonial();
                //  TODO: Other handling of testimonials
                if (!is_null($t)) $em->persist($t);
            }
            $em->flush();
        }
        return $this->render('FanFerretQuestionBundle:Default:form.html.twig',['form' => $form->createView()]);
    }
}
