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

class ActiviteController extends Controller
{
    /**
     * @Route("/activites", name="activites")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
                $listActivites = $em->getRepository('AppBundle:Activite')->findAll();
                return $this->render('activite/liste.html.twig',array(
        'listeActivites' => $listActivites
        ));
    }

    /**
     * @Route("/ajouter_activite", name="ajouter_activite")
     */
    
    public function addActiviteAction(Request $request)
    {
    	
    $activite = new Activite();

    $form = $this->createFormBuilder($activite)
            ->add('nom', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Enregitrer Activité'))
            ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $activite = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($activite);
        $em->flush();

        $this->addFlash('success', 'Ligne ajoutée avec succés');

        return $this->redirectToRoute('homepage');
    }

    
    return $this->render(':activite:ajout.html.twig', array(
      'form' => $form->createView(),
    ));
    }

    /**
     * Modifier une activité.
     *
     * @Route("/activite/{id}/edit", requirements={"id": "\d+"}, name="modifier_activite")
     */
    
    public function ModifierActiviteAction(Activite $activite, Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();
        $editForm = $this->createFormBuilder($activite)
            ->add('nom', TextType::class,  [
                'attr' => ['autofocus' => true]
            ])
            ->getForm();

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_activite', ['id' => $activite->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Modification réussie");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('activite/edit.html.twig', [
            'activite'        => $activite,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Suppression activité.
     *
     * @Route("activite/{id}/supprimer", name="supprimer_activite")
     */
    public function deleteActiviteAction(Request $request, Activite $activite)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_activite', ['id' => $activite->getId()]))
            ->setMethod('DELETE')
            ->getForm();;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($activite);
            $entityManager->flush();

            $this->addFlash('success', 'Suppression réussie');
        }

        return $this->redirectToRoute('homepage');
    }

}


