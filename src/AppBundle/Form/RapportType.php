<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('date', TextType::class,['attr'=>['class'=>'form_input', 'placeholder'=>'Format: 2019-02-09']])
            ->add('ville', TextType::class,['attr'=>['class'=>'form_input']])
            ->add('quartier', TextType::class,['attr'=>['class'=>'form_input'], 'required'=> false])
            ->add('population', IntegerType::class,['attr'=>['class'=>'form_input', 'placeholder'=>'Nombre de personnes sensibilisées']])
            ->add('strategie', TextareaType::class,['attr'=>['class'=>'form_textarea']])
            ->add('difficulte', TextareaType::class,['attr'=>['class'=>'form_textarea']])
            ->add('solution', TextareaType::class,['attr'=>['class'=>'form_textarea'], 'required'=> false])
            //->add('statut')
            //->add('slug')->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Rapport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_rapport';
    }


}
