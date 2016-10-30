<?php
// serc/AppBundle/DataFixtures/ORM/LoadClient.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Client;

class LoadClient implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des activite
    $noms = array(
      'Client1',
      'Client2',
      'Client3',
      'Client4',
      'Client5'
    );

    foreach ($noms as $nom) {
      // On crée le client
      $client = new Client();
      $client->setNom($nom);

      // On le persiste
      $manager->persist($client);
    }

    // On déclenche l'enregistrement de tout les cleints
    $manager->flush();
  }
}