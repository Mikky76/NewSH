<?php
// src/SH/NewsBundle/Controller/NewsController.php

namespace SH\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SH\NewsBundle\Entity\News;
use SH\NewsBundle\Entity\Comment;
use Symfony\Component\Form\Form;
use SH\NewsBundle\Form\NewsType;
use SH\NewsBundle\Form\NewsEditType;
use SH\NewsBundle\Form\CommentType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RequestStack;

class NewsController  extends Controller
{
    public function indexAction($page)
    {
		if ($page < 1) {
		  throw $this->createNotFoundException('Page "'.$page.'" inexistante!');
		}

		// Ici je fixe le nombre d'annonces par page à 3
		// Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
		$nbPerPage = 3;
	
    // On récupère l'objet Paginator
		$listNews = $this->getDoctrine()
		->getManager()
		->getRepository('SHNewsBundle:News')
		->getNews($page, $nbPerPage)
		;

		// On calcule le nombre total de pages
		$nbPages = ceil(count($listNews)/$nbPerPage);
		
		//S'il y a pas de News, par défaut, $nbPages est à 1
		if ($nbPages == 0) {
			$nbPages = 1;
		}
		
		// Si la page n'existe pas, on retourne une 404
		if ($page > $nbPages) {
			throw $this->createNotFoundException("La page ".$page." n'existe pas.");
		}
		
        return $this->render('SHNewsBundle:News:index.html.twig', array(
			'listNews' => $listNews,
			'nbPages'  => $nbPages,
			'page'     => $page
		));
    }
	
	public function menuAction($limit)
	{
		$listNews = $this->getDoctrine()
		  ->getManager()
		  ->getRepository('SHNewsBundle:News')
		  ->findBy(
				array(),                 // Pas de critère
				array('date' => 'desc'), // On trie par date décroissante
				$limit,                  // On sélectionne $limit annonces
				0                        // À partir du premier
		);

		return $this->render('SHNewsBundle:News:menu.html.twig', array(
		'listNews' => $listNews
		));
	}	
 
	public function viewAction($id, Form $form = null)
	{
		$em = $this->getDoctrine()->getManager();
		// Récupère la News
		$news = $em->getRepository('SHNewsBundle:News')->find($id);

		// On vérifie que la news existe bien
		if (null === $news) {
		  throw $this->createNotFoundException("La news d'id ".$id." n'existe pas.");
		}
		//Récupère les commmentaires
		$comments = $em->getRepository('SHNewsBundle:Comment')
			->getCommentsByNews($id, 10);

		// Formulaire de commentaires
		if (null === $form) {
			$comment = new Comment();
			$form = $this->createForm(CommentType::class, $comment);
		}

		return $this->render('SHNewsBundle:News:view.html.twig', array(
		  	'news' => $news,
			'form' => $form->createView(),
			'comments' => $comments
		));
	}
	
	/**
	 * @Security("has_role('ROLE_AUTEUR')")
	 */
    public function addAction(Request $request)
	{
		$news = new News();
		
		$user = $this->getUser();
		$news->setAuthor($user);
		
		$form = $this->createForm(NewsType::class, $news);

		// On vérifie que les valeurs entrées sont correctes
		if ($form->handleRequest($request)->isValid()) {

		  $em = $this->getDoctrine()->getManager();
		  $em->persist($news);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('notice', 'News bien enregistrée.');

		  return $this->redirect($this->generateUrl('sh_news_view', array('id' => $news->getId())));
		}
		
		//Si pas d'envoi de formulaire ou si non valide, on affiche le formulaire
		return $this->render('SHNewsBundle:News:add.html.twig', array(
		  'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_AUTEUR')")
	 */
	public function editAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$news = $em->getRepository('SHNewsBundle:News')->find($id);

		if ($news == null) {
		  throw $this->createNotFoundException("La News ".$id." n'existe pas.");
		}
		
		$form = $this->createForm(NewsEditType::class, $news);

		if ($form->handleRequest($request)->isValid()) {
		  // Inutile de persister, News déja existante
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('notice', 'News bien modifiée.');

		  return $this->redirect($this->generateUrl('sh_news_view', array('id' => $news->getId())));
		}

		return $this->render('SHNewsBundle:News:edit.html.twig', array(
		  'form'   => $form->createView(),
		  'news' => $news // News envoyé  à la vue si jamais elle veut l'afficher
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$news = $em->getRepository('SHNewsBundle:News')->find($id);

		// Si la News n'existe pas, on affiche une erreur 404
		if ($news == null) {
		  throw $this->createNotFoundException("La News d'id ".$id." n'existe pas.");
		}

		// Formulaire vide qui ne contiendra que le champ CSRF
		// Cela permet de protéger la suppression de la News contre cette faille
		$form = $this->createFormBuilder()->getForm();

		if ($form->handleRequest($request)->isValid()) {
		  $em->remove($news);
		  $em->flush();

		  $request->getSession()->getFlashBag()->add('info', "La News a bien été supprimée.");

		  return $this->redirect($this->generateUrl('sh_news_home'));
		}

		// Si la requête est en GET, on affiche une page de confirmation avant de supprimer
		return $this->render('SHNewsBundle:News:delete.html.twig', array(
		  'news' => $news,
		  'form'   => $form->createView()
		));
	}

	/**
	 * @Security("has_role('ROLE_USER')")
	 */
	public function addCommentAction(News $news, Request $request)
	{
		//On récupère la News
		$comment = new Comment();
		$comment->setNews($news);
		//On récupère l'utilisateur et on le met comme Auteur
		$user = $this->getUser();
		$comment->setAuthor($user);

//		$comment->setIp($this->request->server->get('REMOTE_ADDR'));
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
		return $this->forward('SHNewsBundle:News:view', array(
			'id' => $news->getId(),
			'form' => $form
		));
	}
	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function removeCommentAction(Comment $comment, Request $request)
	{
	// Formulaire vide qui ne contiendra que le champ CSRF pour éviter faille
		$form = $this->createFormBuilder()->getForm();

		// Si le commentaire n'existe pas, on affiche une erreur 404
		if ($comment == null) {
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
		return $this->render('SHNewsBundle:News:removeComment.html.twig', array(
			'comment' => $comment,
			'form' => $form->createView()
		));
	}
}