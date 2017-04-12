<?php
// src/SH/NewsBundle/Validator/Antiflood.php

namespace NewsBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Antiflood extends Constraint
{
  public $message = "Vous avez déjà posté un commentaire il y a moins de 15 secondes, merci d'attendre un peu.";
  
  
  public function validatedBy()
  {
    return 'sh_news_antiflood'; // Ici, on fait appel à l'alias du service
  }
}