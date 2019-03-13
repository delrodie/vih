<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CallcenterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', IntegerType::class,['attr'=>['class'=>'form_input', 'placeholder'=>"Le numero de telephone du correspondant", 'autocomplete'=>"off"]])
            ->add('position', TextType::class,['attr'=>['class'=>'form_input', 'placeholder'=>"La situation géographique du correspondant", 'autocomplete'=>"off"]])
            ->add('preoccupation', TextareaType::class,['attr'=>['class'=>'form_textarea', 'placeholder'=>"Les préoccupations soumises"]])
            ->add('solution', TextareaType::class,['attr'=>['class'=>'form_textarea', 'placeholder'=>"Les solutions proposées"]])
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
            'data_class' => 'AppBundle\Entity\Callcenter'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_callcenter';
    }


}
