<?php

namespace FanFerret\QuestionBundle\Command;

class ImportYamlCommand extends \Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('fanferret:import:yaml');
        $this->setDescription('Imports a survey from a YAML file.');
        $this->setHelp('Imports a YAML file to generate a Survey entity which is then persisted via Doctrine.');
        $this->setDefinition(
            new \Symfony\Component\Console\Input\InputDefinition([
                new \Symfony\Component\Console\Input\InputArgument(
                    'filename',
                    \Symfony\Component\Console\Input\InputArgument::REQUIRED
                )
            ])
        );
    }

    protected function execute(\Symfony\Component\Console\Input\InputInterface $input, \Symfony\Component\Console\Output\OutputInterface $output)
    {
        $filename = $input->getArgument('filename');
        $str = @file_get_contents($filename);
        if ($str === false) throw new \InvalidArgumentException(
            sprintf(
                'Could not read file %s',
                $filename
            )
        );
        $parser = new \FanFerret\QuestionBundle\Utility\YamlSurveySerializer();
        $surveys = $parser->fromString($str);
        if (count($surveys) !== 1) throw new \InvalidArgumentException(
            'Expected exactly one survey'
        );
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $em->persist($surveys[0]);
        $em->flush();
    }
}
