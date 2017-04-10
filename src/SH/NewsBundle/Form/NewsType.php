<?php
// src/SH/NewsBundle/Form/NewsType.php

namespace SH\NewsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use SH\NewsBundle\Form\ImageType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		  ->add('date',      DateType::class)
		  ->add('title')
		  ->add('content')
		  ->add('image',    ImageType::class)
		  ->add('save',      SubmitType::class, array('label' => 'Valider'))
        ;
		
		$builder->addEventListener(

		  FormEvents::PRE_SET_DATA,
		  function(FormEvent $event) { 
			// On récupère la News
			$news = $event->getData();

			// Si la News n'existe pas on sort de la fonction
			if (null === $news) {
			  return;
			}

			if (!$news->getPublished() || null === $news->getId()) {
			  // Si la news n'est pas publiée, ou si elle n'existe pas encore en base (id est null),
			  // alors on ajoute le champ published
			  $event->getForm()->add('published', CheckboxType::class, array('required' => false));
			} else {
			  // Sinon, on le supprime
			  $event->getForm()->remove('published');
			}
		  }
		);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SH\NewsBundle\Entity\News'
        ));
    }
}
