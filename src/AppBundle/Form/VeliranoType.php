<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;



class VeliranoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('promise',TextareaType::class,[
            'label' => 'Promise'
        ])
        ->add('comment',TextareaType::class,[
            'label' => 'Commentaire'
        ])
        ->add('percent',TextType::class,[
            'label' => 'Percent'
        ])
        ->add('status',ChoiceType::class,[
            'choices' => [
                'NOT STARTED' => 'Tsy mbola natomboka ',
                'IN PROGRESS'  => 'Efa natomboka',
                'NOT REALIZED'  => 'Tsy tanteraka'
            ],
            'expanded' => false,
            'multiple' => false,
        ]);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Velirano'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_velirano';
    }


}
