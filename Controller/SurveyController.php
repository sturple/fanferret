<?php

namespace FanFerret\QuestionBundle\Controller;

class SurveyController extends BaseController
{
    private function getSurveySession($token)
    {
        $repo = $this->getSurveySessionRepository();
        $session = $repo->getByToken($token);
        if (is_null($session)) throw $this->createNotFoundException(
            sprintf(
                'No SurveySession with token "%s"',
                $token
            )
        );
        return $session;
    }

    public function surveyAction(\Symfony\Component\HttpFoundation\Request $request, $token)
    {
        //  Attempt to retrieve the appropriate SurveySession
        $session = $this->getSurveySession($token);
        //  Ensure the SurveySession has not been completed
        //  (you cannot redo a survey)
        if (!is_null($session->getCompleted())) throw $this->createNotFoundException(
            sprintf(
                'SurveySession with token "%s" has been completed',
                $token
            )
        );
        //  Mark the survey as seen
        $em = $this->getEntityManager();
        if (!$session->getSeen()) {
            $session->setSeen(new \DateTime());
            $em->persist($session);
            $em->flush();
        }
        $survey = $this->createSurveyFromSurveySession($session);
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
            $survey->sendAdminNotification($session,0,true);
            return $this->redirectToRoute('fanferret_survey_finish',['token' => $token]);
        }
        //  Render
        return $this->render('FanFerretQuestionBundle:Default:form.html.twig',['survey' => $survey->render($session,$form->createView())]);
    }

    public function finishAction($token)
    {
        $session = $this->getSurveySession($token);
        //  Survey must be completed in order for you
        //  to view completion
        if (is_null($session->getCompleted())) throw $this->createNotFoundException(
            sprintf(
                'SurveySession with token "%s" has not been completed',
                $token
            )
        );
        $survey = $this->createSurveyFromSurveySession($session);
        return $this->render('FanFerretQuestionBundle:Default:finish.html.twig',['finish' => $survey->renderFinish($session)]);
    }

    public function stylesAction($id)
    {
        $id = intval($id);
        $repo = $this->getSurveyRepository();
        $survey = $repo->findOneById($id);
        if (is_null($survey)) throw $this->createNotFoundException(
            sprintf(
                'No Survey with ID %d',
                $id
            )
        );
        $survey = $this->createSurvey($survey);
        $css = $survey->renderStyles()->render();
        return new \Symfony\Component\HttpFoundation\Response($css,200,[
            'Content-Type' => 'text/css; charset=utf-8'
        ]);
    }
}
