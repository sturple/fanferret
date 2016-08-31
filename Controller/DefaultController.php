<?php

namespace FanFerret\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
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
        //  Mark the survey as seen
        $em = $doctrine->getManager();
        if (!$session->getSeen()) {
            $session->setSeen(new \DateTime());
            $em->persist($session);
            $em->flush();
        }
        //  TODO: Decide on language somehow
        $translator = new \FanFerret\QuestionBundle\Internationalization\Translator('en-CA');
        $twig = $this->get('twig');
        $survey = new \FanFerret\QuestionBundle\Survey\Survey(
            $session->getSurvey(),
            new \FanFerret\QuestionBundle\Question\QuestionFactory(
                $translator,
                $twig,
                $this->get('fan_ferret_question.token_generator')
            ),
            $twig
        );
        //  Create form
        $fb = $this->createFormBuilder();
        $survey->addToFormBuilder($fb);
        $form = $fb->getForm();
        //  Handle form submission
        $form->handleRequest($request);
        if ($form->isValid()) {
            $session->setCompleted(new \DateTime());
            $survey->getAnswers($session,$form->getData());
            $em->persist($session);
            $em->flush();
        }
        //  Render
        return $this->render('FanFerretQuestionBundle:Default:form.html.twig',['survey' => $survey->render($form->createView())]);
    }
}
