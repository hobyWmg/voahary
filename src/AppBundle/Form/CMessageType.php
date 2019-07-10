<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\User;
use AppBundle\Entity\Entite;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Repository\EntiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CMessageType extends MessageType
{
    private $em;
    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];
        parent::buildForm($builder, $options);
        $builder->remove('file');
        if($user->getEntite()){
        $builder->add('entiteReceiver',EntityType::class, array(
            'class' =>  'AppBundle:Entite',
            'choice_label'  =>  'abreviation',
            'expanded'  =>  false,
            'multiple'  =>  false,
            'required'  =>  true,
            'placeholder' => 'Veuillez sélectionner *',
            'query_builder' => function(EntiteRepository $repo) use  ($user) {
                return $repo->findEntiteWithoutMeQuery($user);
            }
        ));
    }else{
        $builder->add('entiteReceiver',EntityType::class, array(
            'class' =>  'AppBundle:Entite',
            'choice_label'  =>  'abreviation',
            'expanded'  =>  false,
            'multiple'  =>  false,
            'required'  =>  true,
            'placeholder' => 'Veuillez sélectionner *',
        ));
    }
        $typologies = array();
        $builder->add('typologie', EntityType::class, array(
            'required' => true,
            'placeholder' => 'Veuillez choisir une entité',
            'class' => 'AppBundle:Typologie',
            'choice_label'  =>  'sujet',
            // 'choices' => $typologies,
            'expanded'  =>  false,
            'multiple'  =>  false,
        ));
        $builder->add('startDate', DateType::class, [
            'label' => "Du",
            'required' => true,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'doDatepicker form-control',
                'placeholder' => 'Du'
            )]
        )
        ->add('endDate', DateType::class, [
            'label' => "Au",
            'required' => false,
            'widget' => 'single_text',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'doDatepicker form-control',
                'placeholder' => 'Au'
            )]
        );
    }
    // function onPreSubmit(FormEvent $event) {
    //     $form = $event->getForm();
    //     $data = $event->getData();
    //     dump($data);die;
        
    //     // Search for selected E and convert it into an Entity
    //     $entite = $this->em->getRepository('AppBundle:Entite')->find($data['city']);
        
    //     $this->addElements($form, $entite);
    // }

    // function onPreSetData(FormEvent $event) {
    //     $message = $event->getData();
    //     $form = $event->getForm();

    //     // When you create a new person, the City is always empty
    //     $entite = $message->getEntite() ? $message->getEntite() : null;
        
    //     $this->addElements($form, $entite);
    // }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Message'
        ));
        $resolver->setRequired('user');
    // type validation - User instance or int, you can also pick just one.
        $resolver->setAllowedTypes('user', array(User::class, 'int'));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_c_message';
    }


}
