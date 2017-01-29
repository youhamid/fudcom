<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Tache;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
               if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
                    $listeTaches = $em->getRepository('AppBundle:Tache')->findAll();
                } else {
                    $listeTaches = $em->getRepository('AppBundle:Tache')->findByUser($this->getUser());
                }
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
        $user = $this->getUser();
        $username = $user->getUsername();
        $tache->setUser($user);

        $form = $this->createFormBuilder($tache)
                ->add('jour', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy'))
                 ->add('client', EntityType::class, array(
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'nom'))
                ->add('activite', EntityType::class, array(
                    'class' => 'AppBundle:Activite',
                    'choice_label' => 'nom'))
                ->add('duree', ChoiceType::class, array(
                    'choices'  => array(
                    '0.25' => '0.25','0.50' => '0.50','1' => '1.00','2' => '2.00','3' => '3.00','4' => '4.00', '5' => '5.00','6' => '6.00','7' => '7.00','8' => '8.00', '9' => '9.00'),
                    'invalid_message' => 'La duree doit être un chiffre'))
                ->add('description', TextareaType::class)
                ->add('user', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'username',
                'disabled' => 'true'))
                ->add('dateCreation', DateType::class, array(
                    'widget' => 'single_text',
                    'format' => 'd/M/y h:m:s',
                    'disabled' => 'true'))
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
                'format' => 'dd/MM/yyyy'))
            ->add('client', EntityType::class, array(
                'class' => 'AppBundle:Client',
                'choice_label' => 'nom'))
            ->add('activite', EntityType::class, array(
                'class' => 'AppBundle:Activite',
                'choice_label' => 'nom'))
            ->add('duree', ChoiceType::class, array(
                    'choices'  => array(
                    '0.25' => '0.25','0.50' => '0.50','1' => '1.00','2' => '2.00','3' => '3.00','4' => '4.00', '5' => '5.00','6' => '6.00','7' => '7.00','8' => '8.00', '9' => '9.00'),
                    'invalid_message' => 'La duree doit être un chiffre'))
            ->add('description', TextareaType::class)
            ->add('user', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'username',
                'disabled' => 'true'))
            ->add('dateCreation', DateType::class, array(
                'widget' => 'single_text',
                'format' => 'd/M/y h:m:s',
                'disabled' => 'true'))
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

     /**
     * @Route("/chercher_taches", name="chercher_taches")
     */
    
    public function rechercherTachesAction(Request $request)
    {

        $form = $this->createFormBuilder()
                ->add('annee', ChoiceType::class, array(
                    'choices'  => array(
                     Date('Y') => Date('Y'),  Date('Y') + 1 => Date('Y')+1)))
                 ->add('mois', ChoiceType::class, array(
                    'choices'  => array(
                     '01'=>'01','02'=>'02','03'=>'03','04'=>'04','05'=>'05','06'=>'06','07'=>'07','08'=>'08','09'=>'09','10'=>'10','11'=>'11','12'=>'12')))
                 ->add('client', EntityType::class, array(
                    'class' => 'AppBundle:Client',
                    'choice_label' => 'nom',
                    'placeholder' => '...ALL...',
                    'empty_data'  => null,
                    'required' => false))
                ->add('user', EntityType::class, array(
                    'class' => 'AppBundle:User',
                    'choice_label' => 'username',
                    'placeholder' => '...ALL...',
                    'empty_data'  => null,
                    'required' => false))
                ->add('Rechercher', SubmitType::class, array('label' => 'Rechercher'))
                ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             $entityManager = $this->getDoctrine()->getManager();
             $queryBuilder = $entityManager->createQueryBuilder();
             $dateDebut = \DateTime::createFromFormat('dmY', '01'.$form->get('mois')->getData().$form->get('annee')->getData()); 
             $dateFin = \DateTime::createFromFormat('dmY', '30'.$form->get('mois')->getData().$form->get('annee')->getData());
             $client = $form->get('client')->getData();
             $user = $form->get('user')->getData();

       if(!is_null($user) and !is_null($client)){
                $queryBuilder = $queryBuilder
               ->select('c.nom')
               ->addselect('u.username')
               ->addselect('SUM(t.duree) AS duree')
               ->from('AppBundle:Tache', 't')
               ->join('t.client', 'c')
               ->join('t.user', 'u')
               ->andwhere('t.client = :client')
               ->andwhere('t.user = :user')
               ->andwhere('t.jour >= :dateDebut')
               ->andwhere('t.jour < :dateFin')
               ->setParameter('dateDebut', $dateDebut)
               ->setParameter('dateFin', $dateFin)
               ->setParameter('user', $user)
               ->setParameter('client', $client)
               ->groupBy('c.nom')
               ->addgroupBy('u.username');
            };
            
            
            if(!is_null($client) and is_null($user)){
               $queryBuilder = $queryBuilder
               ->select('c.nom')
               ->addselect('u.username')
               ->addselect('SUM(t.duree) AS duree')
               ->from('AppBundle:Tache', 't')
               ->join('t.client', 'c')
               ->join('t.user', 'u')
               ->andwhere('t.client = :client')
               ->andwhere('t.jour >= :dateDebut')
               ->andwhere('t.jour < :dateFin')
               ->setParameter('dateDebut', $dateDebut)
               ->setParameter('dateFin', $dateFin)
               ->setParameter('client', $client)
               ->groupBy('c.nom')
               ->addgroupBy('u.username');
            };

            if(!is_null($user) and is_null($client)){
                $queryBuilder = $queryBuilder
               ->select('c.nom')
               ->addselect('u.username')
               ->addselect('SUM(t.duree) AS duree')
               ->from('AppBundle:Tache', 't')
               ->join('t.client', 'c')
               ->join('t.user', 'u')
               ->andwhere('t.user = :user')
               ->andwhere('t.jour >= :dateDebut')
               ->andwhere('t.jour < :dateFin')
               ->setParameter('dateDebut', $dateDebut)
               ->setParameter('dateFin', $dateFin)
               ->setParameter('user', $user)
               ->groupBy('c.nom')
               ->addgroupBy('u.username');
            };

            if(is_null($client) and is_null($user)){
               $queryBuilder = $queryBuilder
               ->select('c.nom')
               ->addselect('\'\' AS username')
               ->addselect('SUM(t.duree) AS duree')
               ->from('AppBundle:Tache', 't')
               ->join('t.client', 'c')
               ->join('t.user', 'u')
               ->andwhere('t.jour >= :dateDebut')
               ->andwhere('t.jour < :dateFin')
               ->setParameter('dateDebut', $dateDebut)
               ->setParameter('dateFin', $dateFin)
               ->groupBy('c.nom')
               ->orderby('duree', 'DESC');
            };
            $query = $queryBuilder->getQuery();
            $listeTaches = $query->getResult();

            return $this->render('tache/suivi.html.twig',array(
                'listeTaches' => $listeTaches
            ));
    }

    
    return $this->render('tache/ajout.html.twig', array(
      'form' => $form->createView(),
    ));
    }
}


