<?php

namespace FanFerret\QuestionBundle\Repository;

/**
 * SurveySessionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SurveySessionRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * Attempts to retrieve the SurveySession
     * which has a particular token.
     *
     * @param string $token
     *
     * @return SurveySession
     */
    public function getByToken($token)
    {
        $qb = $this->createQueryBuilder('ss')
            ->andWhere('ss.token = :token')
            ->setParameter('token',$token)
            ->setMaxResults(1);
        $q = $qb->getQuery();
        $arr = $q->getResult();
        if (count($arr) !== 1) return null;
        return $arr[0];
    }

    /**
     * Attempts to retrieve all SurveySession entities
     * which are not completed, have a certain number of
     * notifications, and whose last notification was sent
     * a certain amount of time ago.
     *
     * @param int $count
     * @param DateInterval|null $since
     *
     * @return array
     */
    public function getByNotification($count, \DateInterval $since = null)
    {
        //  Sanity check arguments
        if (($count === 0) && !is_null($since)) throw new \InvalidArgumentException(
            'If $count is 0 $since must be NULL'
        );
        if ($count < 0) throw new \InvalidArgumentException(
            'Negative count'
        );
        //  Build query
        $qb = $this->createQueryBuilder('ss');
        $count_expr = $qb->expr()->count('sn.id');
        $having_count_expr = $qb->expr()->eq($count_expr,$count);
        $completed_expr = $qb->expr()->isNull('ss.completed');
        $qb->leftJoin('ss.surveyNotifications','sn')
            ->andWhere($completed_expr)
            ->addGroupBy('ss.id')
            ->andHaving($having_count_expr);
        //  Handle date/time constraint
        if (!is_null($since)) {
            //  Sanity check
            $now = new \DateTime();
            $when = clone $now;
            $when->sub($since);
            if ($when->getTimestamp() > $now->getTimestamp()) throw new \InvalidArgumentException(
                '$since is negative interval'
            );
            //  Add to query
            $max_expr = $qb->expr()->max('sn.when');
            $having_max_expr = $qb->expr()->gte($max_expr,':when');
            $qb->andHaving($having_max_expr)
                ->setParameter('when',$when);
        }
        //  Execute query
        $q = $qb->getQuery();
        return $q->getResult();
    }
}
