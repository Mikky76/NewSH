<?php
// src/SH/NewsBundle/Form/NewsEditType.php

namespace SH\NewsBundle\Form;

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