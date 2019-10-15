<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class CarouselType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre',
                'constraints' => [new Assert\NotBlank()],
                'label_attr' => array('class' => 'control-label'),
                'attr' => [
                    'placeholder' => 'Titre'
                ]
            ])
            // ->add('lienBouton', TextType::class, [
            //     'label' => 'Lien du bouton',
            //     'constraints' => [new Assert\NotBlank()],
            //     'attr' => [
            //         'placeholder' => 'Lien du bouton'
            //     ]
            // ])
            // ->add('texteBouton', TextType::class, [
            //     'label' => 'Titre du bouton',
            //     'constraints' => [new Assert\NotBlank()],
            //     'attr' => [
            //         'placeholder' => 'Titre du bouton'
            //     ]
            // ])
            ->add('ordre')
            ->add('description', TextareaType::class,
                ['label' => 'Description',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Description'
                    ]
                ]
            )
            ->add('fileImage', FileType::class, array(
                'required' => false,
                'data_class' => null,
                'label' => 'Image'
            ));

        // ->add('abstractUser');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Carousel'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_carousel';
    }


}
