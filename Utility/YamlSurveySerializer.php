<?php

namespace FanFerret\QuestionBundle\Utility;

/**
 * Serializes Survey entities to and from YAML
 * strings
 */
class YamlSurveySerializer implements SurveySerializer
{
    private function parseYaml($str)
    {
        $yaml = \Symfony\Component\Yaml\Yaml::parse($str,\Symfony\Component\Yaml\Yaml::PARSE_OBJECT_FOR_MAP);
        if (!is_object($yaml)) throw new \InvalidArgumentException('Expected root of YAML structure to be object');
        return $yaml;
    }

    private function expected($property, $type)
    {
        throw new \InvalidArgumentException(
            sprintf(
                'Expected "%s" to be %s',
                $property,
                $type
            )
        );
    }

    private function noProperty($property)
    {
        throw new \InvalidArgumentException(
            sprintf(
                'No property "%s"',
                $property
            )
        );
    }

    private function getOptionalProperty($obj, $property)
    {
        return isset($obj->$property) ? $obj->$property : null;
    }

    private function getOptionalString($obj, $property)
    {
        $retr = $this->getOptionalProperty($obj,$property);
        if (is_null($retr)) return null;
        if (!is_string($retr)) $this->expected($property,'string');
        return $retr;
    }

    private function getString($obj, $property)
    {
        $retr = $this->getOptionalString($obj,$property);
        if (is_null($retr)) $this->noProperty($property);
        return $retr;
    }

    private function getOptionalObject($obj, $property)
    {
        $retr = $this->getOptionalProperty($obj,$property);
        if (is_null($retr)) return null;
        if (!is_object($retr)) $this->expected($property,'object');
        //  TODO: Fix this
        return $retr;
    }

    private function getObject($obj, $property)
    {
        $retr = $this->getOptionalObject($obj,$property);
        if (is_null($retr)) $this->noProperty($property);
        return $retr;
    }

    private function getOptionalArray($obj, $property)
    {
        $retr = $this->getOptionalProperty($obj,$property);
        if (is_null($retr)) return null;
        if (!is_array($retr)) $this->expected($property,'array');
        return $retr;
    }

    private function getArray($obj, $property)
    {
        $retr = $this->getOptionalArray($obj,$property);
        if (is_null($retr)) $this->noProperty($property);
        return $retr;
    }

    private function getOptionalInteger($obj, $property)
    {
        $retr = $this->getOptionalProperty($obj,$property);
        if (is_null($retr)) return null;
        if (!is_integer($retr)) $this->expected($property,'integer');
        return $retr;
    }

    private function getInteger($obj, $property)
    {
        $retr = $this->getOptionalInteger($obj,$property);
        if (is_null($retr)) $this->noProperty($property);
        return $retr;
    }

    private function forEachObject(array $arr, $represents, $callable)
    {
        foreach ($arr as $obj) {
            if (!is_object($obj)) throw new \InvalidArgumentException(
                sprintf(
                    'Expected %s to be represented by object',
                    $represents
                )
            );
            yield $callable($obj);
        }
    }

    private function getRule($obj)
    {
        $r = new \FanFerret\QuestionBundle\Entity\Rule();
        $r->setParams($this->getObject($obj,'params'));
        $r->setType($this->getString($obj,'type'));
        return $r;
    }

    private function getRules(array $arr)
    {
        return $this->forEachObject($arr,'Rule',function ($obj) {   return $this->getRule($obj);    });
    }

    private function getQuestion($obj)
    {
        $q = new \FanFerret\QuestionBundle\Entity\Question();
        $q->setOrder($this->getInteger($obj,'order'));
        $q->setParams($this->getObject($obj,'params'));
        $q->setType($this->getString($obj,'type'));
        foreach ($this->getRules($this->getArray($obj,'rules')) as $r) {
            $r->addQuestion($q);
            $q->addRule($r);
        }
        return $q;
    }

    private function getQuestions(array $arr)
    {
        return $this->forEachObject($arr,'Question',function ($obj) {  return $this->getQuestion($obj);    });
    }

    private function getQuestionGroup($obj)
    {
        $qg = new \FanFerret\QuestionBundle\Entity\QuestionGroup();
        $qg->setOrder($this->getInteger($obj,'order'));
        $qg->setParams($this->getObject($obj,'params'));
        foreach ($this->getQuestions($this->getArray($obj,'questions')) as $q) {
            $q->setQuestionGroup($qg);
            $qg->addQuestion($q);
        }
        return $qg;
    }

    private function getQuestionGroups(array $arr)
    {
        return $this->forEachObject($arr,'QuestionGroup',function ($obj) { return $this->getQuestionGroup($obj);   });
    }

    private function getSurvey($obj)
    {
        $s = new \FanFerret\QuestionBundle\Entity\Survey();
        $s->setSlug($this->getString($obj,'slug'));
        $s->setSlugGroup($this->getOptionalString($obj,'slugGroup'));
        $s->setParams($this->getObject($obj,'params'));
        $s->setLanguage($this->getString($obj,'language'));
        foreach ($this->getQuestionGroups($this->getArray($obj,'questionGroups')) as $qg) {
            $s->addQuestionGroup($qg);
            $qg->setSurvey($s);
        }
        return $s;
    }
    
    public function fromString($str)
    {
        $yaml = $this->parseYaml($str);
        return [$this->getSurvey($yaml)];
    }
}
