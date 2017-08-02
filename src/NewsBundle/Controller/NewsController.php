<?php
// srcBundle/ControllerController.php

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

class NewsController  extends Controller
{
	/**
	 *
	 * @Route("/{page}", name="sh_news_home", requirements={"page": "\d+"})
	 */
	public function indexAction($page = 1)
    {
		if ($page < 1) {
		  throw $this->createNotFoundException('Page "'.$page.'" inexistante!');
		}

		// Ici je fixe le nombre d'annonces par page à 3
		// Mais bien sûr il faudrait utiliser un paramètre, et y accéder via $this->container->getParameter('nb_per_page')
		$nbPerPage = 4;
	
    // On récupère l'objet Paginator
		$listNews = $this->getDoctrine()
		->getManager()
		->getRepository('NewsBundle:News')
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
		
        return $this->render('NewsBundle:News:index.html.twig', array(
			'listNews' => $listNews,
			'nbPages'  => $nbPages,
			'page'     => $page
		));
    }

	/**
	 * Matches / exactly
	 *
	 * @Route("/view/{id}", name="sh_news_view", requirements={"id": "\d+"})
	 */
	public function viewAction($id, Form $form = null)
	{
		$em = $this->getDoctrine()->getManager();
		// Récupère la News
		$news = $em->getRepository('NewsBundle:News')->find($id);

		// On vérifie que la news existe bien
		if (null === $news) {
		  throw $this->createNotFoundException("La news d'id ".$id." n'existe pas.");
		}
		//Récupère les commmentaires
		$comments = $em->getRepository('NewsBundle:Comment')
			->getCommentsByNews($id, 10);

		// Formulaire de commentaires
		if (null === $form) {
			$comment = new Comment();
			$form = $this->createForm(CommentType::class, $comment);
		}

		return $this->render('NewsBundle:News:view.html.twig', array(
		  	'news' => $news,
			'form' => $form->createView(),
			'comments' => $comments
		));
	}
	
	/**
	 * @Security("has_role('ROLE_AUTOR')")
	 *
	 * @Route("/add", name="sh_news_add")
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
		return $this->render('NewsBundle:News:add.html.twig', array(
		  'form' => $form->createView(),
		));
	}

	/**
	 * @Security("has_role('ROLE_AUTOR')")
	 *
	 * @Route("/edit/{id}", name="sh_news_edit", requirements={"id": "\d+"})
	 */
	public function editAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$news = $em->getRepository('NewsBundle:News')->find($id);

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

		return $this->render('NewsBundle:News:edit.html.twig', array(
		  'form'   => $form->createView(),
		  'news' => $news // News envoyé  à la vue si jamais elle veut l'afficher
		));
	}

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @Route("/delete/{id}", name="sh_news_delete", requirements={"id": "\d+"})
	 */
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$news = $em->getRepository('NewsBundle:News')->find($id);

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
		return $this->render('NewsBundle:News:delete.html.twig', array(
		  'news' => $news,
		  'form'   => $form->createView()
		));
	}

}