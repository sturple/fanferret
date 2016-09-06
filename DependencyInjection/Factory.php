<?php

namespace FanFerret\QuestionBundle\DependencyInjection;

class Factory
{
    public static function createSurvey(
        \FanFerret\QuestionBundle\Entity\Survey $survey,
        \Symfony\Component\DependencyInjection\ContainerInterface $container,
        $language = null
    ) {
        if (is_null($language)) $language = $survey->getLanguage();
        $translator = new \FanFerret\QuestionBundle\Internationalization\Translator($language);
        $twig = $container->get('twig');
        $tokens = $container->get('fan_ferret_question.token_generator');
        $swift = $container->get('swiftmailer.mailer');
        $qfactory = new \FanFerret\QuestionBundle\Question\QuestionFactory($translator,$twig,$tokens);
        $rfactory = new \FanFerret\QuestionBundle\Rule\RuleFactory($twig,$swift);
        return new \FanFerret\QuestionBundle\Survey\Survey(
            $survey,
            $qfactory,
            $rfactory,
            $tokens,
            $twig
        );
    }
}
