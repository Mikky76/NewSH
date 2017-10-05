<?php

namespace CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use NewsBundle\Entity\News;
use CoreBundle\Entity\Staticpage;
use CoreBundle\Form\StaticpageType;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RequestStack;


class CoreController extends Controller
{

    /**
     *
     * @Route("/", name="sh_home")
     */
    public function indexAction()
    {
        return $this->render('CoreBundle::index.html.twig');
    }


    /**
     *
     * @Route("/site/{namestatic}", name="sh_core_static", requirements={"namestatic": "\w+"})
     *
     */
    public function staticAction($namestatic)
    {
        $em = $this->getDoctrine()->getManager();
        $staticpage = $em->getRepository('CoreBundle:Staticpage')->findOneByNamePage($namestatic);

        if (null === $staticpage) {
            throw $this->createNotFoundException("La page ".$namestatic." n'existe pas.");
        }

        return $this->render('CoreBundle::static.html.twig', array(
            'staticpage' => $staticpage
        ));
    }

    /**
     * @Security("has_role('ROLE_AUTOR')")
     *
     * @Route("/site/edit/{namestatic}", name="sh_core_edit_static", requirements={"namestatic": "\w+"})
     */
    public function editstaticAction($namestatic, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $staticpage = $em->getRepository('CoreBundle:Staticpage')->findOneByNamePage($namestatic);
        
        if ($staticpage === null) {
            throw $this->createNotFoundException("La page ".$staticpage." n'existe pas.");
        }

        $form = $this->createForm(StaticpageType::class, $staticpage);

        if ($form->handleRequest($request)->isValid()) {
            // Inutile de persister, déja existante
            $em->flush();

            $request->getSession()->getFlashBag()->add('notice', 'Modifications effectuées!');

            return $this->redirect($this->generateUrl('sh_core_static', array('namestatic' => $staticpage->getNamePage())));
        }

        return $this->render('CoreBundle::editstatic.html.twig', array(
            'form'   => $form->createView(),
            'staticpage' => $staticpage
        ));
    }
}
