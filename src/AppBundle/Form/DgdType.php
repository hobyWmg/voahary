<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DgdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('contrevenants',TextType::class, array(
                'label' => 'Contrevenants',
                'attr'=>array('class'=>'form-control','placeholder'=>'')))
            ->add('numero',TextType::class, array(
                'label' => 'Numéro',
                'attr'=>array('class'=>'form-control','placeholder'=>'')))
            ->add('infraction',TextareaType::class,[
                'label' => 'Infraction',
                'required' => false
            ])
            ->add('valeurCaf',TextareaType::class,[
                'label' => 'Valeur CAF',
                'required' => false
            ])
            ->add('dcDe',TextareaType::class,[
                'label' => 'DC / DE',
                'required' => false
            ])
            ->add('situation',TextType::class, array(
                'label' => 'Situation',
                'required' => false,
                'attr'=>array('class'=>'form-control','placeholder'=>'')))
            ->add('marchandises',TextareaType::class,[
                'label' => 'Marchandises',
                'required' => false
            ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Dgd'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_dgd';
    }


}
