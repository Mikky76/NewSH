<?php
// src/SH/NewsBundle/Entity/NewsRepository.php

namespace SH\NewsBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
  public function getCommentsByNews($newsId, $limit)
  {
    $query = $this->createQueryBuilder('c')
        ->orderBy('c.date', 'DESC')
        ->where('c.news = :id')
          ->setParameter('id', $newsId)
    ;

    // Puis on ne retourne que $limit résultats
    $query->setMaxResults($limit);

    // Enfin, on retourne le résultat
    return $query
      ->getQuery()
      ->getResult()
      ;

  }
  
  
  public function isFlood($ip, $secondes)
  {
    $query = $this->createQueryBuilder('c')
               ->select('COUNT(c)')
               ->where('c.ip = :ip')
                 ->setParameter('ip', $ip)
               ->andWhere('c.date >= :date')
                 ->setParameter('date', new \Datetime('-'.$secondes.'seconds'));
    return (bool) $query->getQuery()->getSingleScalarResult();
  }
}