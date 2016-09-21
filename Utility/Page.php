<?php

namespace FanFerret\QuestionBundle\Utility;

class Page
{
    private $num;
    private $count;

    public function __construct($num, $count) {
        $this->num = $num;
        $this->count = $count;
        if ($this->num < 1) throw new \InvalidArgumentException(
            'Page number must be strictly positive'
        );
        if ($this->count < 1) throw new \InvalidArgumentException(
            'Number of results per page must be strictly positive'
        );
    }

    public function getPageNumber()
    {
        return $this->num;
    }

    public function getResultsPerPage()
    {
        return $this->count;
    }

    public function addToQueryBuilder(\Doctrine\ORM\QueryBuilder $qb)
    {
        return $qb->setMaxResults($this->count)
            ->setFirstResult($this->getOffset());
    }

    public function getOffset()
    {
        return $this->count * ($this->num - 1);
    }

    public function getNumberOfPages($results)
    {
        $retr = intval($results / $this->count);
        if ($retr === 0) return 1;
        if (($retr % $this->count) !== 0) return $retr + 1;
        return $retr;
    }
}
