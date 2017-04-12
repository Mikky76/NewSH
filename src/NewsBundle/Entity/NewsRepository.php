<?php
// src/NewsBundle/Entity/NewsRepository.php

namespace NewsBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NewsRepository extends EntityRepository
{
  public function getNews($page, $nbPerPage)
  {
    $query = $this->createQueryBuilder('n')
		// Jointure sur l'attribut image
		->leftJoin('n.image', 'i')
		->addSelect('i')
		->orderBy('n.date', 'DESC')
		->getQuery()
    ;

    $query
      // On définit la news à partir de laquelle commencer la liste
      ->setFirstResult(($page-1) * $nbPerPage)
      // Ainsi que le nombre d'annonce à afficher sur une page
      ->setMaxResults($nbPerPage)
    ;

    return new Paginator($query, true);
  }
}