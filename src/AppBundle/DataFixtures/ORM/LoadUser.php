<?php
// serc/AppBundle/DataFixtures/ORM/LoadUser.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUser implements FixtureInterface, ContainerAwareInterface
{ 

/** @var ContainerInterface */
private $container;

/**
 * {@inheritdoc}
 */
 public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $adminUser = new User();
        $adminUser->setUsername('admin');
        $adminUser->setEmail('admin@abc.abc');
        $encodedPassword = $passwordEncoder->encodePassword($adminUser, 'admin');
        $adminUser->setPassword($encodedPassword);
        $adminUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        $testUser = new User();
        $testUser->setUsername('test');
        $testUser->setEmail('test@abc.abc');
        $encodedPassword = $passwordEncoder->encodePassword($testUser, 'test');
        $testUser->setPassword($encodedPassword);
        $testUser->setRoles(['ROLE_USER']);
        $manager->persist($testUser);

        $manager->flush();
    }

/**
* {@inheritdoc}
*/
public function setContainer(ContainerInterface $container = null)
    {
    $this->container = $container;
    }
}