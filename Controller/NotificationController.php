<?php

namespace FanFerret\QuestionBundle\Controller;

class NotificationController extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    private function seenActionImpl($token)
    {
        $doctrine = $this->getDoctrine();
        $repo = $doctrine->getRepository(\FanFerret\QuestionBundle\Entity\SurveyNotification::class);
        $notification = $repo->getByToken($token);
        if (is_null($notification)) return;
        if (!is_null($notification->getSeen())) return;
        $notification->setSeen(new \DateTime());
        $em = $doctrine->getManager();
        $em->persist($notification);
        $em->flush();
    }

    public function seenAction($token)
    {
        $this->seenActionImpl($token);
        $file = __DIR__ . '/../Resources/images/transparent.png';
        $png = file_get_contents($file);
        if ($png === false) throw new \RuntimeException(
            sprintf(
                'Could not read file %s',
                $file
            )
        );
        $retr = new \Symfony\Component\HttpFoundation\Response();
        $retr->setContent($png);
        $retr->headers->set('Content-Type','image/png');
        return $retr;
    }
}
