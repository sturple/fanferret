<?php

namespace FanFerret\QuestionBundle\Survey;

/**
 * An interface which may be implemented to implement
 * the logic and functionality of a survey.
 */
interface SurveyInterface
{
    /**
     * Retrieves the Survey entity underlying the
     * Survey object.
     *
     * @return
     *  The Survey entity.
     */
    public function getEntity();

    /**
     * Adds all questions the Survey object manages
     * to a form builder.
     *
     * @param $fb
     *  The form builder.
     */
    public function addToFormBuilder(\Symfony\Component\Form\FormBuilderInterface $fb);
    
    /**
     * Retrieves all answers from all managed questions.
     *
     * @param $session
     *  The current SurveySession entity.  QuestionAnswer
     *  entities shall be added to this object as appropriate.
     * @param array $data
     *  The associative array of data retrieved from the
     *  Symfony form.
     */
    public function getAnswers(\FanFerret\QuestionBundle\Entity\SurveySession $session, array $data);

    /**
     * Renders the survey.
     *
     * @param $session
     *  The current SurveySession entity.
     * @param $fv
     *  A FormView object created from the form builder
     *  this survey populated.
     *
     * @return Renderable
     */
    public function render(\FanFerret\QuestionBundle\Entity\SurveySession $session, \Symfony\Component\Form\FormView $fv);

    /**
     * Renders the survey's finish screen.
     *
     * @param SurveySession $session
     *  The session for which a finish screen shall be
     *  rendered.
     *
     * @return Renderable
     */
    public function renderFinish(\FanFerret\QuestionBundle\Entity\SurveySession $session);

    /**
     * Renders the survey's stylesheet.
     *
     * @return Renderable
     */
    public function renderStyles();

    /**
     * Sends the Nth notification if appropriate.
     *
     * @param SurveySession $session
     *  The SurveySession entity for which to send the
     *  notification.
     * @param int $num
     *  The number N of the notification to send.  For
     *  example the first notification has an N of 1.
     * @param bool $force
     *  If \em true the notification shall always be
     *  sent and this method shall not return null, if
     *  \em false the Survey object implementation may
     *  apply internal logic and decide not to send the
     *  notification.  Defaults to \em false.
     *
     * @return SurveyNotification|null
     *  If a notification was sent a SurveyNotification
     *  entity is returned, otherwise null is returned.
     *  If a SurveyNotification entity is returned it is
     *  expected to be associated with the Survey associated
     *  with this object.
     */
    public function sendNotification(\FanFerret\QuestionBundle\Entity\SurveySession $session, $num, $force = false);
}
