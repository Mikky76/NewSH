<?php
// src/NewsBundle/Form/NewsEditType.php

namespace NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsEditType extends NewsType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		Parent::buildForm($builder, $options);
        $builder->remove('date');
    }
}