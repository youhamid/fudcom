<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Client;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ClientController extends Controller
{

    /**
     * @Route("/admin/clients", name="clients")
     */
    public function ClientsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
                // La méthode findAll retourne les activites de la base de données
                $listeClients = $em->getRepository('AppBundle:Client')->findAll();
                return $this->render('client/liste.html.twig',array(
        'listeClients' => $listeClients
        ));
    }

    /**
     * @Route("/admin/ajouter_client", name="ajouter_client")
     */
    
    public function addClientAction(Request $request)
    {
    	// On crée un objet Activite
    $client = new Client();

    $form = $this->createFormBuilder($client)
            ->add('nom', TextType::class)
            ->add('user', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username',
                    'label' => 'Affectation'))
            ->add('save', SubmitType::class, array('label' => 'Enregitrer client'))
            ->getForm();

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $client = $form->getData();

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        $this->addFlash('success', 'Ligne ajoutée avec succés');

        return $this->redirectToRoute('clients');
    }

    
    return $this->render('client/ajout.html.twig', array(
      'form' => $form->createView(),
    ));
    }


    /**
     * Modifier un client existant.
     *
     * @Route("/admin/client/{id}/edit", requirements={"id": "\d+"}, name="modifier_client")
     */
    
    public function ModifierClientAction(Client $client, Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();
        $editForm = $this->createFormBuilder($client)
            ->add('nom', TextType::class,  [
                'attr' => ['autofocus' => true]
            ])
            ->add('user', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username'))
            ->getForm();

        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_client', ['id' => $client->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Modification réussie");
            return $this->redirectToRoute('clients');
        }

        return $this->render('client/edit.html.twig', [
            'client'        => $client,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Suppression client.
     *
     * @Route("/admin/client/{id}/supprimer", name="supprimer_client")
     */
    public function deleteClientAction(Request $request, Client $client)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_client', ['id' => $client->getId()]))
            ->setMethod('DELETE')
            ->getForm();;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($client);
            $entityManager->flush();

            $this->addFlash('success', 'Suppression réussie');
        }

        return $this->redirectToRoute('clients');
    }

}


