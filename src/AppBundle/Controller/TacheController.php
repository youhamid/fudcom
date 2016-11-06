<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Tache;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TacheController extends Controller
{

    /**
     * @Route("/taches", name="taches")
     */
    public function TachesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
                $listeTaches = $em->getRepository('AppBundle:Tache')->findAllJoined();
                return $this->render('tache/liste.html.twig',array(
        'listeTaches' => $listeTaches
        ));
    }

    /**
     * @Route("/ajouter_tache", name="ajouter_tache")
     */
    
    public function addTacheAction(Request $request)
    {
        $tache = new Tache();

        $form = $this->createFormBuilder($tache)
                ->add('jour', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'))
                 ->add('client', EntityType::class, array(
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'nom'))
                ->add('activite', EntityType::class, array(
                    'class' => 'AppBundle:Activite',
                    'choice_label' => 'nom'))
                ->add('duree', NumberType::class, array(
                'invalid_message' => 'La duree doit être un chiffre'))
                ->add('description', TextareaType::class)
                ->add('save', SubmitType::class, array('label' => 'Enregitrer tache'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tache = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($tache);
            $em->flush();

            $this->addFlash('success', 'Ligne ajoutée avec succés');

            return $this->redirectToRoute('taches');
    }

    
    return $this->render('tache/ajout.html.twig', array(
      'form' => $form->createView(),
    ));
    }


    /**
     * Modifier une tache existant.
     *
     * @Route("/tache/{id}/edit", requirements={"id": "\d+"}, name="modifier_tache")
     */
    
    public function ModifierTacheAction(Tache $tache, Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();
        $editForm = $this->createFormBuilder($tache)
            ->add('jour', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd'))
                 ->add('client', EntityType::class, array(
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'nom'))
                ->add('activite', EntityType::class, array(
                    'class' => 'AppBundle:Activite',
                    'choice_label' => 'nom'))
                ->add('duree', NumberType::class, array(
                'invalid_message' => 'La duree doit être un chiffre'))
                ->add('description', TextareaType::class)
            ->getForm();

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_tache', ['id' => $tache->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Modification réussie");
            return $this->redirectToRoute('taches');
        }

        return $this->render('tache/edit.html.twig', [
            'tache'        => $tache,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Suppression tache.
     *
     * @Route("/tache/{id}/supprimer", name="supprimer_tache")
     */
    public function deleteTacheAction(Request $request, Tache $tache)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_tache', ['id' => $tache->getId()]))
            ->setMethod('DELETE')
            ->getForm();;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($tache);
            $entityManager->flush();

            $this->addFlash('success', 'Suppression réussie');
        }

        return $this->redirectToRoute('taches');
    }

}


