<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchForm extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
            "allow_extra_fields" => true
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', EntityType::class,[
                'label' => 'Campus',
                'required' => false,
                'class'=> Campus::class,
                'choice_label'=> 'nom'
            ])
            ->add('q', TextType::class, [
                'label' => 'Le nom de la sortie contient :',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher'
                ]
            ])
            ->add('dateDebut', DateType::class,[
                'label' => 'Entre ',
                'required' => false,
                'widget'=>'single_text'
            ])

            ->add('dateFin', DateType::class,[
                'label' => 'et ',
                'required' => false,
                'widget'=>'single_text'
            ])

            ->add('organisateur', CheckboxType::class,[
                'label'=>'Sorties dont je suis l\'organisateur(trice)',
                'required' => false
            ])
            ->add('inscrit', CheckboxType::class,[
                'label'=>'Sorties auxquelles je suis inscrit(e)',
                'required' => false
            ])
            ->add('nonInscrit', CheckboxType::class,[
                'label'=>'Sorties auxquelles je ne suis pas inscrit(e)',
                'required' => false
            ])
            ->add('sortiePassees', CheckboxType::class,[
                'label'=>'Sorties passées',
                'required' => false
            ]);



    }
}