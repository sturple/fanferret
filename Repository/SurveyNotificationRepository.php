<?php

namespace FanFerret\QuestionBundle\Repository;

/**
 * SurveyNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SurveyNotificationRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Attempts to retrieve the SurveyNotification
     * which has a particular token.
     *
     * @param string $token
     *
     * @return SurveyNotification|null
     */
    public function getByToken($token)
    {
        $qb = $this->createQueryBuilder('sn');
        $where = $qb->expr()->eq('sn.token',':token');
        $qb->andWhere($where)
            ->setParameter('token',$token)
            ->setMaxResults(1);
        $q = $qb->getQuery();
        $arr = $q->getResult();
        if (count($arr) !== 1) return null;
        return $arr[0];
    }
}
