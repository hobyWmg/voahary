<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class GtaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
/*
 * $builder
 */
         $builder
             ->add('numPlaque',TextType::class, array(
                'label' => 'Numéro du plaque',
                'attr'=>array('class'=>'form-control','placeholder'=>'')))
             ->add('daty', DateType::class, [
                     'label' => "Date",
                     'required' => true,
                     'widget' => 'single_text',
                     'format' => 'dd/MM/yyyy',
                     'attr' => array(
                         'class' => 'doDatepicker form-control',
                         'placeholder' => ''
                     )]
             )
             ->add('infractions',TextareaType::class,[
                'label' => 'Infractions',
                'required' => false,
            ])
            ->add('suspect',CheckboxType::class,array(
                'label' => "Véhicule suspect",
                'required' => false
            ))
            ->add('lera',TextType::class, array(
                'label' => 'Heure',
                'required' => false,
                'attr'=>array('class'=>'form-control','placeholder'=>'')));

    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Gta'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_gta';
    }


}
