<?php

namespace ShinyBundle\Repository;

/**
 * ShinyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ShinyRepository extends \Doctrine\ORM\EntityRepository
{
    public function getSprite($id_gen, $id_pokemon)
    {
        $query = $this->createQueryBuilder('s')
            // Jointure sur l'attribut image
            ->leftJoin('s.pokemon', 'p')
            ->addSelect('p')
            ->leftJoin('p.sprite', 'sp')
            ->addSelect('sp')
            ->where('sp.generation_id = $id_gen AND sp.pokemon_id = $id_pokemon')
            ->getQuery()
        ;

        return new Paginator($query, true);
    }
}
