<?php

namespace FanFerret\QuestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    private function getQuestion(\FanFerret\QuestionBundle\Entity\Question $q, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t)
    {
        $type = $q->getType();
        $twig = $this->get('twig');
        if ($type === 'open') return new \FanFerret\QuestionBundle\Question\OpenQuestion($q,$t,$twig,$this->get('fan_ferret_question.token_generator'));
        if ($type === 'polar') return new \FanFerret\QuestionBundle\Question\PolarQuestion($q,$t,$twig);
        if ($type === 'checklist') return new \FanFerret\QuestionBundle\Question\ChecklistQuestion($q,$t,$twig);
        if ($type === 'rating') return new \FanFerret\QuestionBundle\Question\RatingQuestion($q,$t,$twig);
        throw new \LogicException(
            sprintf(
                'Unrecognized question type "%s"',
                $type
            )
        );
    }

    private function getQuestions(\FanFerret\QuestionBundle\Entity\Survey $s, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $t)
    {
        $gs = [];
        foreach ($s->getQuestionGroups() as $qg) {
            $qs = [];
            foreach ($qg->getQuestions() as $q) {
                $qs[] = $this->getQuestion($q,$t);
            }
            $gs[] = (object)[
                'group' => $qg,
                'questions' => $qs
            ];
        }
        return $gs;
    }

    private function traverseQuestions(array $gs)
    {
        foreach ($gs as $g) {
            foreach ($g->questions as $q) {
                yield $q;
            }
        }
    }

    private function getGroupTemplate(\FanFerret\QuestionBundle\Entity\QuestionGroup $group)
    {
        $params = $group->getParams();
        if (!isset($params->template)) return 'FanFerretQuestionBundle:Group:default.html.twig';
        if (!is_string($params->template)) throw new \InvalidArgumentException('Expected "template" to be a string');
        return $params->template;
    }

    private function renderSurvey($template, \Symfony\Component\Form\FormInterface $form, array $groups)
    {
        $gs = array_map(function ($group) {
            $ctx = [
                'questions' => $group->questions,
                'group' => $group->group
            ];
            return new \FanFerret\QuestionBundle\Utility\Renderable(
                $this->getGroupTemplate($group->group),
                $ctx,
                $this->get('twig')
            );
        },$groups);
        $ctx = [
            'groups' => $gs,
            'form' => $form->createView()
        ];
        return $this->render($template,$ctx);
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
        //  TODO: Decide on language somehow
        $translator = new \FanFerret\QuestionBundle\Internationalization\Translator('en-CA');
        $gs = $this->getQuestions($survey,$translator);
        $fb = $this->createFormBuilder();
        foreach ($this->traverseQuestions($gs) as $q) $q->addToFormBuilder($fb);
        $form = $fb->getForm();
        //  Handle form submission
        $form->handleRequest($request);
        if ($form->isValid()) {
            $session->setCompleted(new \DateTime());
            $em = $doctrine->getManager();
            $em->persist($session);
            $data = $form->getData();
            foreach ($this->traverseQuestions($gs) as $q) {
                $ans = $q->getAnswer($data);
                $ans->setSurveySession($session);
                $em->persist($ans);
                $t = $ans->getTestimonial();
                //  TODO: Other handling of testimonials
                if (!is_null($t)) $em->persist($t);
            }
            $em->flush();
        }
        //  Render
        return $this->renderSurvey('FanFerretQuestionBundle:Default:form.html.twig',$form,$gs);
    }
}
