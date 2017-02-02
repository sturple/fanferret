<?php

namespace FanFerret\QuestionBundle\Command;

use Symfony\Component\Console\Question\Question;

class DeleteSurveySessionCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('fanferret:delete:surveysession');
        $this->setDescription('Deletes Survey session by id ');
        $this->setHelp('Deletes a survey session by pass argument id.');
        $this->setDefinition(
            new \Symfony\Component\Console\Input\InputDefinition([
                new \Symfony\Component\Console\Input\InputArgument(
                    'survey_id',
                    \Symfony\Component\Console\Input\InputArgument::REQUIRED
                )
            ])
        );
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $survey_id = $input->getArgument('survey_id');
        $doctrine = $this->getContainer()->get('doctrine');
        $repository = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\SurveySession::class);
        $result = $repository->findOneById(intval($survey_id));
        if ($result){
            $helper = $this->getHelper('question');
            $question = new Question(
                '<question>Are you sure you want to delete this survey session. All testimonial and question asnwers will be perminatly deleted'.         
                ' TOKEN: '.
                $result->getToken() .
                " (y/n)</question> \r\n"
            );
            $question->setAutocompleterValues(array('y','Y','n','N'));
            $yesorno = strtolower($helper->ask($input, $output, $question));
            if ($yesorno == 'y'){
                $output->writeln('<fg=green;bg=red>DELETING survey session with id: '. $survey_id .'</>');
                $em = $doctrine->getManager();
                $em->remove($result);
                $em->flush();
            }
            else {
                $output->writeln('<info>Cancelled Deleting survey session with id: '. $survey_id .'</info>');
            }

        }
        else {
          $output->writeln('<error>ERROR Could not find Survey Session with id: '. $survey_id. '</error>'  );
        }
    }
}