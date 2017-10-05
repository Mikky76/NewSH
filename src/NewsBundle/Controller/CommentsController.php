<?php
// src/NewsBundle/Controller/CommentsController.php

namespace NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use NewsBundle\Entity\News;
use NewsBundle\Entity\Comment;
use Symfony\Component\Form\Form;
use NewsBundle\Form\NewsType;
use NewsBundle\Form\NewsEditType;
use NewsBundle\Form\CommentType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RequestStack;


class CommentsController    extends Controller
{
	/**
	 * @Security("has_role('ROLE_USER')")
	 *
	 * @Route("/add/{id}/comment", name="sh_news_add_comment", requirements={"id": "\d+"})
	 */
	public function addCommentAction(News $news, Request $request)
	{
		//On récupère la News
		$comment = new Comment();
		$comment->setNews($news);
		//On récupère l'utilisateur et on le met comme Auteur
		$user = $this->getUser();
		$comment->setAuthor($user);

		$comment->setIp($request->getClientIp());

		$form = $this->createForm(CommentType::class, $comment);

		if ($form->handleRequest($request)->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$em->persist($comment);
			$em->flush();
			$this->get('session')->getFlashBag()->add('info', 'Commentaire bien enregistré !');
			// On redirige vers la page de la News
			return $this->redirect($this->generateUrl('sh_news_view', array('id' => $news->getId())));
		}

		$this->get('session')->getFlashBag()->add('error', 'Votre formulaire contient des erreurs');

		// On réaffiche le formulaire sans redirection (sinon on perd les informations du formulaire)
		return $this->forward('NewsBundle:News:view', array(
			'id' => $news->getId(),
			'form' => $form
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @Route("/remove/comment/{id}", name="sh_news_remove_comment", requirements={"id": "\d+"})
	 */
	public function removeCommentAction(Comment $comment, Request $request)
	{
	// Formulaire vide qui ne contiendra que le champ CSRF pour éviter faille
		$form = $this->createFormBuilder()->getForm();

		// Si le commentaire n'existe pas, on affiche une erreur 404
		if ($comment === null) {
		  throw $this->createNotFoundException("Commentaire d'id ".$id." n'existe pas.");
		}

		if ($form->handleRequest($request)->isValid()) { // Vérifie que le CSRF
		// On supprime le commentaire
			$em = $this->getDoctrine()->getManager();
			$em->remove($comment);
			$em->flush();

			$this->get('session')->getFlashBag()->add('info', 'Commentaire bien supprimé');

			return $this->redirect($this->generateUrl('sh_news_view', array('id' => $comment->getNews()->getId())));
		}
		
		// Si la requÃªte est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render('NewsBundle:News:removeComment.html.twig', array(
			'comment' => $comment,
			'form' => $form->createView()
		));
	}
}