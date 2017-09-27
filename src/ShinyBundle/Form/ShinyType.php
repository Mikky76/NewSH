<?php

namespace ShinyBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use ShinyBundle\Entity\Version;
use Doctrine\ORM\EntityRepository;


class ShinyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('version', EntityType::class, array(
                'placeholder' => '----Choisir la version----',
                'class' => 'ShinyBundle:Version',
                'choice_label' => 'name',
                'multiple' => false,
            ))
            ->add('nickname', TextType::class, array(
                'required' => false,
            ))
            ->add('sexe', ChoiceType::class, array(
                'choices' => array(
                    'Mâle' => '&#9794;',
                    'Femelle' => '&#9792;',
                    'Asexué' => 'Asexué',
                ),
                'expanded' => true,
            ))
            ->add('level', IntegerType::class, array(
                'attr' => array(
                    'min' => '1',
                    'max' => '100',
                    'placeholder' => '1',
                )
            ))
            ->add('nature', EntityType::class, array(
                'class' => 'ShinyBundle:Nature',
                'choice_label' => 'name',
                'multiple' => false,
            ))
            ->add('date', DatetimeType::class, array(
                'widget' => 'single_text',
                'format' =>'dd/MM/yyyy HH:mm',
                'html5' => false,
            ))
            ->add('number', TextType::class, array(
                'required' => false,
            ))
            ->add('recit', CKEditorType::class, array(
                'config' => array(
                    'placeholder' => 'Raconter votre recherche, sa capture,...'
                )))
            ->add('videos', CollectionType::class, array(
                'required' => true,
                'entry_type' => VideoType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('pictures', CollectionType::class, array(
                'required' => true,
                'entry_type' => PictureType::class,
                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'));

        $formModifier = function (FormInterface $form, Version $version = null) {
            $locations = null === $version ? array() : $version->getLocations();
            $methodes = null === $version ? array() : $version->getMethodes();
            $generation = null === $version ? array() : $version->getGeneration();

            $form->add('location', EntityType::class, array(
                'class' => 'ShinyBundle:Location',
                'choice_label' => 'name',
                'multiple' => false,
                'choices' => $locations
                ))
                ->add('methode', EntityType::class, array(
                    'class' => 'ShinyBundle:Methode',
                    'choice_label' => 'name',
                    'multiple' => false,
                    'choices' => $methodes
                ))
                ->add('pokemon', EntityType::class, array(
                    'class' => 'ShinyBundle:Pokemon',
                    'query_builder' => function (EntityRepository $er) use ($generation){
                        return $er->createQueryBuilder('p')
                            //->join('p.generation', 'g')
                            //->addSelect('g')
                            ->where('p.generation <= :gen')
                            ->setParameter('gen', $generation);
                    },
                    'choice_label' => 'name',
                    'multiple' => false
                ))
                ->add('pokeball', EntityType::class, array(
                    'class' => 'ShinyBundle:Pokeball',
                    'query_builder' => function (EntityRepository $er) use ($generation){
                        return $er->createQueryBuilder('p')
                            ->where('p.generation <= :gen')
                            ->setParameter('gen', $generation);
                    },
                    'choice_label' => 'name',
                    'multiple' => false
                ))
            ;
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event)  use ($formModifier){

                $shiny = $event->getData();

                $formModifier($event->getForm(), $shiny->getVersion());

                if (null === $shiny) {
                    return;
                }
            }
        );

        $builder->get('version')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {

                $version = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $version);
            }
        );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ShinyBundle\Entity\Shiny'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'shinybundle_shiny';
    }


}
