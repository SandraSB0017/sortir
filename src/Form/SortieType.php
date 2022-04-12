<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateHeureDebut', DateType::class)
            ->add('duree', TimeType::class)
            ->add('dateLimiteInscription', DateType::class)
            ->add('nbInscriptionsMax')
            ->add('infosSortie', TextareaType::class)
            ->add('etat', EntityType::class, [
                'class'=>Etat::class,
                'choice_label'=>'libelle'])

            ->add('lieu', EntityType::class,['class'=>Lieu::class,
                'choice_label'=>'nom'])
            ->add('campus',EntityType::class, ['class'=>Campus::class,
                'choice_label'=>'nom'])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
