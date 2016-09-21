<?php

namespace FanFerret\QuestionBundle\Question;

/**
 * A convenience base class for questions.
 */
abstract class Question implements QuestionInterface
{
    use \FanFerret\QuestionBundle\Utility\HasObject {
        \FanFerret\QuestionBundle\Utility\HasObject::getTranslator as getTranslatorBase;
    }

    private $q;
    private $t;

    /**
     * Creates a Question object.
     *
     * @param $question
     *  The Question entity which the newly-created
     *  object shall represent.
     * @param $translator
     *  A TranslatorInterface object which the newly-created
     *  object my use to obtain localized strings.
     */
    public function __construct(\FanFerret\QuestionBundle\Entity\Question $question, \FanFerret\QuestionBundle\Internationalization\TranslatorInterface $translator = null)
    {
        $this->q = $question;
        $this->t = $translator;
    }

    public function getEntity()
    {
        return $this->q;
    }

    public function getAnswer(array $data)
    {
        $retr = new \FanFerret\QuestionBundle\Entity\QuestionAnswer();
        $retr->setQuestion($this->q);
        return $retr;
    }

    protected function getName()
    {
        return sprintf(
            '%d_%d',
            $this->q->getQuestionGroup()->getId(),
            $this->q->getId()
        );
    }

    private function getTranslator()
    {
        if (is_null($this->t)) return $this->getTranslatorBase();
        return $this->t;
    }

    private function getDefaultObject()
    {
        return $this->q->getParams();
    }

    protected function getRenderContext(array $ctx = [])
    {
        return array_merge(
            [
                'title' => $this->getTranslatableString('title'),
                'entity' => $this->q,
                'name' => $this->getName()
            ],
            $ctx
        );
    }
}
