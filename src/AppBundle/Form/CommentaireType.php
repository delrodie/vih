<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->rapport = $options['rapport'];
        $rapport = $this->rapport;

        $builder
            ->add('contenu', TextareaType::class,['attr'=>['class'=>'form_textarea', 'placeholder'=>"Rédigez votre observation de ce point"]])
            //->add('statut')
            //->add('publiePar')->add('modifiePar')->add('publieLe')->add('modifieLe')
            ->add('rapport', EntityType::class,[
                'attr'=>['class'=>'form_input'],
                'class'=>'AppBundle:Rapport',
                'query_builder'=> function(EntityRepository $repository) use ($rapport){
                    return $repository->findRapport($rapport);
                },
                'choice_label'=> 'id'
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Commentaire',
            'rapport'=> null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commentaire';
    }


}
