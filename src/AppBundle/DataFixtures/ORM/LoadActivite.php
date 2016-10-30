<?php
// src/AppBundle/DataFixtures/ORM/LoadActivite.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Activite;

class LoadActivitey implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Liste des activite
    $noms = array(
      'Préparation consultation',
      'Réunion avancement',
      'Visite client',
      'Rédaction rapport',
      'Réseau'
    );

    foreach ($noms as $nom) {
      // On crée l'activité'
      $activite = new Activite();
      $activite->setNom($nom);

      // On la persiste
      $manager->persist($activite);
    }

    // On déclenche l'enregistrement de toutes les activités
    $manager->flush();
  }
}