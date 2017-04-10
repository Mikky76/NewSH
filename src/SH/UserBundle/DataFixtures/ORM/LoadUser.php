<?php
// src/SH/UserBundle/DataFixtures/ORM/LoadUser.php

namespace SH\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use SH\UserBundle\Entity\User;

class LoadUser implements FixtureInterface, ContainerAwareInterface
{

  public function setContainer(ContainerInterface $container = null)
  {
      $this->container = $container;
  }
	
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('Colt', 'Lecteur', 'User');
	
	$Manager = $this->container->get('fos_user.user_manager');
    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = $Manager->createUser();

      // Le nom d'utilisateur et le mot de passe sont identiques
      $user->setUsername($name);
      $user->setPlainPassword($name);
	  
	  //Adresse mail
      $user->setemail('xxx@xxx.fr');

      // On ne se sert pas du sel pour l'instant
      // $user->setSalt('');
      // On définit uniquement le role ROLE_USER qui est le role de base
      $user->setroles(array('ROLE_USER'));

      // On le persiste
      // $manager->persist($user);
	  $Manager->updateUser($user);
    }
	

    // On déclenche l'enregistrement
    // $manager->flush();
  }
}