<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestionnaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class'=>'form_input', 'placeholder'=>"Nom du gestionnaire"]])
            ->add('prenom', TextType::class,['attr'=>['class'=>'form_input', 'placeholder'=>"Prenoms du gestionnaire"]])
            ->add('contact', TextType::class,['attr'=>['class'=>'form_input', 'placeholder'=>"Contact du gestionnaire"]])
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('user', EntityType::class,[
                'attr'=>['class'=>'form_input'],
                'class'=>'AppBundle:User',
                'query_builder'=>function(EntityRepository $entityRepository){
                    return $entityRepository->findUser();
                },
                'choice_label' => 'username'
            ])
            ->add('zone', EntityType::class,[
                'attr'=>['class'=>'cs-select cs-skin-overlay selectoptions'],
                'class'=> 'AppBundle\Entity\Zone',
                'query_builder'=> function(EntityRepository $entityRepository){
                    return $entityRepository->findZone();
                }
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Gestionnaire',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_gestionnaire';
    }


}
