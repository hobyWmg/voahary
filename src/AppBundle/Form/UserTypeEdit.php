<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class UserTypeEdit extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder
            // ->add('username', null, array('label' => 'Nom d\'utilisateur', 'translation_domain' => 'FOSUserBundle','attr'=>array('class'=>'form-control','placeholder'=>'')))
            ->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'translation_domain' => 'FOSUserBundle',
            ],
            'first_options' => [
                'label' => 'Mot de passe',
            ],
            'second_options' => [
                'label' => 'Répeter le mot de passe',
            ],
            'invalid_message' => 'fos_user.password.mismatch',
            'required' => false
        ])
        ->add('enabled', CheckboxType::class, [
            'label' => 'Status',
            'required' => false,
        ])
        // ->add('roles', ChoiceType::class, [
        //     'constraints' => [
        //         new Assert\NotBlank(),
        //     ],
        //     'label' => 'Rôle',
        //     'multiple' => false,
        //     'expanded' => false,
        //     'mapped' => false,
        //     'choices'  => array(
        //         'Super Admin' => 'ROLE_SUPER_ADMIN',
        //         'Point folcal' => 'ROLE_FOCAL',
        //         'Officier de liaison' => 'ROLE_OFFICIER',
        //     ),
        // ])
        ->add('firstname', TextType::class, [
            'constraints' => [new Assert\NotBlank()],
            'label'=>'Prénom'
        ])
        ->add('lastname', TextType::class, [
            'constraints' => [new Assert\NotBlank()],
            'label'=>'Nom'
        ])
        ->add('matricule', TextType::class, [
            'label'=>'Matricule'
        ])
        ->add('filePhoto', FileType::class, [
            'label' => 'Photo de profil',
            'required' => false
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
        ])
        ->add('entite', EntityType::class, [
            'label' => 'Entité',
            'class' =>  'AppBundle:Entite',
            'choice_label'  =>  'nom',
            // 'mapped' => false,
            'expanded'  =>  false,
            'multiple'  =>  false,
            'required'  =>  false,
            'placeholder' => 'Veuillez choisir',
            'empty_data'  => null,
            'query_builder'  => function (EntityRepository $er) {
                $query = $er->createQueryBuilder('e')
                    ->orderBy('e.nom', 'ASC');
                return $query;
            },
        ])
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user_edit';
    }


}
