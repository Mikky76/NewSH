<?php

namespace ShinyBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use ShinyBundle\Form\ShinyType;
use ShinyBundle\Entity\Shiny;
use Doctrine\Common\Collections\ArrayCollection;
use ShinyBundle\Services\reCaptcha;


class RecensementController extends Controller
{
    /**
     *
     * @Route("/view/{id}", name="sh_shiny_view", requirements={"id": "\d+"})
     */
    public function viewAction($id)
    {
        //On récupère le shiny
        $em = $this->getDoctrine()->getManager();
        $shiny = $em->getRepository('ShinyBundle:Shiny')->find($id);

        // Vérification que le Shiny existe bien
        if (null === $shiny) {
            throw $this->createNotFoundException("Ce Pokémon chromatique n'existe pas.");
        }

        return $this->render('ShinyBundle:recensement:view.html.twig', array(
            'shiny' => $shiny,
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @Route("/add", name="sh_shiny_add")
     */
    public function addAction(Request $request)
    {
        $shiny = new Shiny();

        $user = $this->getUser();
        $shiny->setUser($user);

        $form = $this->createForm(ShinyType::class, $shiny);

        $reCaptcha = $this->get('shiny.recaptcha');

        // On vérifie que les valeurs entrées sont correctes
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() &&
            $reCaptcha->captchaverify($request->get('g-recaptcha-response'))) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($shiny);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'shiny bien enregistré!');

            return $this->redirect($this->generateUrl('sh_shiny_view', array('id' => $shiny->getId())));
        }
        # check if captcha response isn't get throw a message
        if($request->isMethod('POST') && !$reCaptcha->captchaverify($request->get('g-recaptcha-response'))){
            $this->addFlash('warning', 'Merci de valider le CAPTCHA!');
        }

        $referer = $request->headers->get('referer');
        //Si pas d'envoi de formulaire ou si non valide, on affiche le formulaire
        return $this->render('ShinyBundle:recensement:add.html.twig', array(
            'form' => $form->createView(),
            'referer' => $referer,
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @Route("/edit/{id}", name="sh_shiny_edit", requirements={"id": "\d+"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shiny = $em->getRepository('ShinyBundle:Shiny')->find($id);

        if ($shiny === null) {
            throw $this->createNotFoundException("Le shiny ".$id." n'existe pas!");
        }elseif (!$shiny->isTrainer($this->getUser())){
            throw $this->createNotFoundException("Vous n'êtes pas le dresseur de ce pokémon shiny!");
        }

        //Création d'une collection de nos photos et de nos videos de la base de données
        $originalPictures = new ArrayCollection();
        foreach ($shiny->getPictures() as $picture) {
            $originalPictures->add($picture);
        }

        $originalVideos = new ArrayCollection();
        foreach ($shiny->getVideos() as $video) {
            $originalVideos->add($video);
        }

        $form = $this->createForm(ShinyType::class, $shiny);

        $reCaptcha = $this->get('shiny.recaptcha');

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid() &&
            $reCaptcha->captchaverify($request->get('g-recaptcha-response'))) {

            //On supprime la relation entre shiny et picture si des photos ont été retirées
            foreach ($originalPictures as $picture) {
                if (false === $shiny->getPictures()->contains($picture)) {
                    // On supprime l'image
                    $shiny->removePicture($picture);
                    //On supprime la photo de la base de données.
                    $em->persist($picture);
                    $em->remove($picture);
                }
            }
            foreach ($originalVideos as $video) {
                if (false === $shiny->getVideos()->contains($video)) {
                    // On supprime la video
                    $shiny->removeVideo($video);
                    //On supprime la video de la base de données.
                    $em->persist($video);
                    $em->remove($video);
                }
            }

            $em->persist($shiny);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Shiny a bien été modifié!');

            return $this->redirect($this->generateUrl('sh_shiny_view', array('id' => $shiny->getId())));
        }

        # check if captcha response isn't get throw a message
        if($request->isMethod('POST') && !$reCaptcha->captchaverify($request->get('g-recaptcha-response'))){
            $this->addFlash('warning', 'Merci de valider le CAPTCHA!');
        }

        $referer = $request->headers->get('referer');

        return $this->render('ShinyBundle:recensement:edit.html.twig', array(
            'form'   => $form->createView(),
            'referer' => $referer,
            'shiny' => $shiny, // shiny envoyé  à la vue si jamais elle veut l'afficher
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @Route("/delete/{id}", name="sh_shiny_delete", requirements={"id": "\d+"})
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shiny = $em->getRepository('ShinyBundle:Shiny')->find($id);

        // Si la shiny n'existe pas, on affiche une erreur 404
        if ($shiny === null) {
            throw $this->createNotFoundException("Le shiny d'id ".$id." n'existe pas.");
        }elseif (!$shiny->isTrainer($this->getUser())){
            throw $this->createNotFoundException("Vous n'êtes pas le dresseur de ce pokémon shiny!");
        }

        // Formulaire vide qui ne contiendra que le champ CSRF
        // Cela permet de protéger la suppression du shiny contre cette faille
        $form = $this->createFormBuilder()->getForm();

        if ($form->handleRequest($request)->isValid()) {
            $em->remove($shiny);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', "Le shiny a bien été supprimé.");

            return $this->redirect($this->generateUrl('sh_home'));
        }

        // Si la requête est en GET, on affiche une page de confirmation avant de supprimer
        return $this->render('ShinyBundle:recensement:delete.html.twig', array(
            'shiny' => $shiny,
            'form'   => $form->createView()
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     *
     * @Route("/mycollection", name="sh_shiny_collection")
     */
    public function CollectionAction()
    {
        $collection = $this->getUser()->getShinies();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('ShinyBundle:Sprite')
        ;

        $sprites = null;

        foreach ($collection as $shiny) {
            $sprites[] = $repository->findOneBy(
                array('pokemon' => $shiny->getPokemon()->getId(),
                    'generation' => $shiny->getVersion()->getGeneration()->getId()
                )
            );
        }
        // Vérification que le membre à bien une collection
        if (null === $collection) {
            throw $this->createNotFoundException("Vous n'avez pas de shiny recensé!");
        }

        return $this->render('ShinyBundle:recensement:collection.html.twig', array(
            'collection' => $collection,
            'sprites' => $sprites
        ));
    }

}