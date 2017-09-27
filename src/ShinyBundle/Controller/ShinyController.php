<?php

namespace ShinyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ShinyBundle\Entity\Pokemon;
use ShinyBundle\Entity\Shiny;
use ShinyBundle\Entity\Generation;

class ShinyController extends Controller
{
    /**
     *
     * @Route("/", name="sh_shiny")
     */
    public function indexAction()
    {
        return $this->render('ShinyBundle::index.html.twig');
    }

    /**
     *
     * @Route("/shinydex/{page}", name="sh_shiny_dex", requirements={"page": "\d+"})
     */
    public function DexAction($page = 1)
    {
        if ($page < 1) {
            throw $this->createNotFoundException('Page "'.$page.'" inexistante!');
        }

        $nbPerPage = 28;

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('ShinyBundle:Generation');

        $generation = $repository->findOneBy(
            array(),
            array('id' => 'DESC')
        );


        $allpokemon = $this->getDoctrine()
            ->getManager()
            ->getRepository('ShinyBundle:Pokemon')
            ->getPokemon($page, $nbPerPage);

        // On calcule le nombre total de pages
        $nbPages = ceil(count($allpokemon)/$nbPerPage);

        //S'il y a pas de News, par défaut, $nbPages est à 1
        if ($nbPages == 0) {
            $nbPages = 1;
        }

        // Si la page n'existe pas, on retourne une 404
        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        $repository2 = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('ShinyBundle:Sprite');

        foreach ($allpokemon as $pokemon) {
            $sprites[] = $repository2->findOneBy(
                array('pokemon' => $pokemon,
                    'generation' => $generation
                )
            );
        }

        // Vérification que le Shiny existe bien
        if (null === $allpokemon) {
            throw $this->createNotFoundException("Il n'y a pas de pokémon!");
        }

        return $this->render('ShinyBundle::shinydex.html.twig', array(
            'allpokemon' => $allpokemon,
            'nbPages'  => $nbPages,
            'page'     => $page,
            'sprites' => $sprites
        ));
    }

    /**
     *
     * @Route("/shinydex/pokemon/{id}", name="sh_shiny_pokemon", requirements={"id": "\d+"})
     */
    public function PokemonAction($id)
{
    $repository = $this
        ->getDoctrine()
        ->getManager()
        ->getRepository('ShinyBundle:Pokemon');

    $pokemon = $repository->find($id);

    // Vérification que le pokemon existe bien
    if (null === $pokemon) {
        throw $this->createNotFoundException("La pokemon ayant l'id ".$id." n'existe pas.");
    }

    return $this->render('ShinyBundle::pokemon.html.twig', array(
        'pokemon' => $pokemon,
    ));
}
}

