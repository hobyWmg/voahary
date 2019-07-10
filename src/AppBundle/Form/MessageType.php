<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add('sujet',TextType::class,array(
        //     'label' => 'Sujet',
        //     'attr'=>array('class'=>'form-control','placeholder'=>''),
        //     'required' => true
        //     ))
        ->add('text',CKEditorType::class,array(
            'label' => "",
            'required' => false
        ))
        ->add('papier',CheckboxType::class,array(
            'label' => "Un document papier a été envoyé en guise de réponse",
            'required' => false
        ))
        ->add('file', FileType::class, [
            'label' => 'Document',
            'required' => false
        ]);
        
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Message'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_message';
    }


}
