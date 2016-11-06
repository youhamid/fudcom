<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserController extends Controller
{
    
    /**
     * @Route("/admin/users", name="users")
     */
    public function usersAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
                $listUsers = $em->getRepository('AppBundle:User')->findAll();
                return $this->render('user/liste.html.twig',array(
        'listeUsers' => $listUsers
        ));
    }
    
    /**
     * @Route("/admin/ajouter_user", name="ajouter_user")
     */
    
    public function addUserAction(Request $request)
    {
    	
    $user = new User();

    $form = $this->createForm(UserType::class, $user)
    ->add('save', SubmitType::class, array('label' => 'Enregitrer utilisateur'));
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();

        $passwordEncoder = $this->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('success', 'Ligne ajoutée avec succés');

        return $this->redirectToRoute('users');
    }

    
    return $this->render(':user:ajout.html.twig', array(
      'form' => $form->createView(),
    ));
    }

    /**
     * Modifier une activité.
     *
     * @Route("/admin/user/{id}/edit", requirements={"id": "\d+"}, name="modifier_user")
     */
    
    public function ModifierUserAction(User $user, Request $request)
    {
    	$entityManager = $this->getDoctrine()->getManager();
        $editForm = $this->createForm(UserType::class, $user);
    
        $deleteForm = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_user', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();

        $editForm->handleRequest($request);
        $passwordEncoder = $this->get('security.password_encoder');
        $encodedPassword = $passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', "Modification réussie");
            return $this->redirectToRoute('users');
        }

        return $this->render('user/edit.html.twig', [
            'user'        => $user,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ]);
    }

    /**
     * Suppression utilisateur.
     *
     * @Route("admin/user/{id}/supprimer", name="supprimer_user")
     */
    public function deleteUserAction(Request $request, User $user)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('supprimer_user', ['id' => $user->getId()]))
            ->setMethod('DELETE')
            ->getForm();;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Suppression réussie');
        }

        return $this->redirectToRoute('users');
    }

}


