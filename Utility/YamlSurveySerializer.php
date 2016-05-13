<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Serializes Survey entities to and from YAML
 * strings
 */
class YamlSurveySerializer implements SurveySerializer
{
    
    private $defaultLanguage='en';
    
    /**
     * Sets the default language which will be used by
     * this serializer.
     *
     * @param string $lang
     *  The IETF language tag.
     *
     * @return YamlSurveySerializer
     */
    public function setDefaultLanguage($lang)
    {
        $this->defaultLanguage=$lang;
        return $this;
    }
    
    private function raise(...$args)
    {
        //  TODO: Use different/better type
        throw new \RuntimeException(sprintf(...$args));
    }
    
    private function extract(array $arr, $key)
    {
        if (!isset($arr[$key])) $this->raise('No key "%s"',$key);
        return $arr[$key];
    }
    
    private function extractArray(array $arr, $key)
    {
        $v=$this->extract($arr,$key);
        if (!is_array($v)) $this->raise('"%s" is not array',$key);
        return $v;
    }
    
    private function extractString(array $arr, $key)
    {
        $v=$this->extract($arr,$key);
        if (!is_string($v)) $this->raise('"%s" is not string',$key);
        return $v;
    }
    
    private function fetchQuestionGroups(array $arr)
    {
        return $this->extractArray($arr,'questionGroup');
    }
    
    private function fetchQuestions(array $arr)
    {
        return $this->extractArray($arr,'questions');
    }
    
    private function parseYaml($str)
    {
        $yaml=\Symfony\Component\Yaml\Yaml::parse($str);
        if (!is_array($yaml)) $this->raise('Expected root of YAML structure to be array, got %s',gettype($yaml));
        return $yaml;
    }
    
    private function checkArray($obj)
    {
        if (!is_array($obj)) $this->raise('Expected an array, got %s',gettype($obj));
    }
    
    private function getQuestion(array $q)
    {
        //  TODO: Do a better job of this
        return (object)$q;
    }
    
    private function getQuestions($name, array $qs)
    {
        //  TODO: Do order properly once we have a stable
        //  sort
        $i=0;
        foreach ($this->extractArray($qs,$name) as $q)
        {
            $this->checkArray($q);
            $type=$this->extractString($q,'type');
            if ($type==='group') $this->raise('Unexpected group question among non-groups');
            $retr=new \FanFerret\QuestionBundle\Entity\Question();
            $retr->setOrder(++$i)->setParams($this->getQuestion($q));
            yield $retr;
        }
    }
    
    private function isQuestionGroups($name, array $qs)
    {
        foreach ($this->extractArray($qs,$name) as $q)
        {
            $this->checkArray($q);
            return $this->extractString($q,'type')==='group';
        }
        return false;
    }
    
    private function getSingleQuestionGroup($name, array $qs)
    {
        $retr=new \FanFerret\QuestionBundle\Entity\QuestionGroup();
        $retr->setOrder(1);
        foreach ($this->getQuestions($name,$qs) as $q)
        {
            $retr->addQuestion($q);
            $q->setQuestionGroup($retr);
        }
        yield $retr;
    }
    
    private function getMultipleQuestionGroups($name, array $qs, array $seen)
    {
        if (in_array($name,$seen,true)) $this->raise('Cycle on "%s"',$name);
        $seen[]=$name;
        $arr=$this->extractArray($qs,$name);
        //  TODO: Sort for order (needs stable sort for
        //  sane behaviour where there's no order)
        foreach ($arr as $q)
        {
            $this->checkArray($q);
            if ($this->extractString($q,'type')!=='group') $this->raise('Unexpected non-group question among groups');
            $set=$this->extractString($q,'set');
            if ($this->isQuestionGroups($set,$qs))
            {
                foreach ($this->getMultipleQuestionGroups($set,$qs,$seen) as $qg) yield $qg;
                continue;
            }
            //  Actually build a question group
            $retr=new \FanFerret\QuestionBundle\Entity\QuestionGroup();
            $t=new \FanFerret\QuestionBundle\Entity\QuestionGroupTranslation();
            $t->setLanguage($this->defaultLanguage)->setText($this->extractString($q,'title'))->setQuestionGroup($retr);
            $retr->addTranslation($t);
            foreach ($this->getQuestions($set,$qs) as $qu)
            {
                $qu->setQuestionGroup($retr);
                $retr->addQuestion($qu);
            }
            yield $retr;
        }
    }
    
    private function getQuestionGroupsImpl($name, array $qs, array $seen)
    {
        if (!$this->isQuestionGroups($name,$qs)) return $this->getSingleQuestionGroup($name,$qs);
        return $this->getMultipleQuestionGroups($name,$qs,$seen);
    }
    
    private function getQuestionGroups($name, array $qs)
    {
        //  TODO: Eliminate this once order is set
        //  by all code paths
        $i=0;
        foreach ($this->getQuestionGroupsImpl($name,$qs,[]) as $qg)
        {
            $qg->setOrder(++$i);
            yield $qg;
        }
    }
    
    private function toSurvey(array $g, array $qs)
    {
        $retr=new \FanFerret\QuestionBundle\Entity\Survey();
        $retr->setSlug($this->extractString($g,'slug'));
        $t=new \FanFerret\QuestionBundle\Entity\SurveyTranslation();
        $t->setLanguage($this->defaultLanguage)->setText($this->extractString($g,'title'))->setSurvey($retr);
        $retr->addTranslation($t);
        foreach ($this->getQuestionGroups($this->extractString($g,'id'),$qs) as $qg)
        {
            $retr->addQuestionGroup($qg);
            $qg->setSurvey($retr);
        }
        return $retr;
    }
    
    public function fromString($str)
    {
        $yaml=$this->parseYaml($str);
        $groups=$this->fetchQuestionGroups($yaml);
        $questions=$this->fetchQuestions($yaml);
        return array_map(function ($group) use ($questions) {
            $this->checkArray($group);
            return $this->toSurvey($group,$questions);
        },$groups);
    }
    
}
