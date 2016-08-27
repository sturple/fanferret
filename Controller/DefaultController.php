<?php

namespace FanFerret\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    private function getQuestionName(\FanFerret\QuestionBundle\Entity\Question $q)
    {
        //  The name is obtained by concatenating
        //  the ID of the QuestionGroup with the
        //  ID of the Question separated by an
        //  underscore
        return sprintf(
            '%d_%d',
            $q->getQuestionGroup()->getId(),
            $q->getId()
        );
    }

    private function createSurveyForm(\FanFerret\QuestionBundle\Entity\Survey $s)
    {
        $fb = $this->createFormBuilder();
        foreach ($s->getQuestionGroups() as $qg) {
            foreach ($qg->getQuestions() as $q) {
                $fb->add(
                    $this->getQuestionName($q),
                    \Symfony\Component\Form\Extension\Core\Type\HiddenType::class
                );
            }
        }
        return $fb->getForm();
    }

    private function getQuestionType($obj)
    {
        if (!isset($obj->type)) throw new \LogicException('Question parameters "type" property missing');
        if (!is_string($obj->type)) throw new \LogicException('Question parameters "type" property is not a string');
        return $obj->type;
    }

    private function getQuestionAnswer(\FanFerret\QuestionBundle\Entity\Question $q, $data)
    {
        $params = $q->getParams();
        $type = $this->getQuestionType($params);
        //  TODO: Actually implement question logic
        $ans = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $ans->setQuestion($q);
        //  TODO: Actually set a real value
        $ans->setValue('');
        return $ans;
    }

    private function getQuestionAnswers(\FanFerret\QuestionBundle\Entity\Survey $s, array $data)
    {
        foreach ($s->getQuestionGroups() as $qg) {
            foreach ($qg->getQuestions() as $q) {
                $name = $this->getQuestionName($q);
                $item = $data[$name];
                yield $this->getQuestionAnswer($q,$item);
            }
        }
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
        //  Handle form creation and submission
        $survey = $session->getSurvey();
        $form = $this->createSurveyForm($survey);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $session->setCompleted(new \DateTime());
            $em = $doctrine->getManager();
            $em->persist($session);
            $data = $form->getData();
            foreach ($this->getQuestionAnswers($survey,$data) as $qa) {
                $qa->setSurveySession($session);
                $em->persist($qa);
                $t = $qa->getTestimonial();
                //  TODO: Other handling of testimonials
                if (!is_null($t)) $em->persist($t);
            }
            $em->flush();
        }
        return $this->render('FanFerretQuestionBundle:Default:form.html.twig',['form' => $form->createView()]);
    }
}
