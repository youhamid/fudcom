<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Activite;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
                // La méthode findAll retourne les taches de la base de données
                if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    $listTaches = $em->getRepository('AppBundle:Tache')->findAll();
                } else {
                    $listTaches = $em->getRepository('AppBundle:Tache')->findByUser($this->getUser());
                }
                return $this->render('tache/liste.html.twig',array(
        'listeTaches' => $listTaches
        ));
    }

}


