<?php

namespace App\Form;

use App\Entity\Composant;
use App\Entity\Marque;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model')
            ->add('price')
            ->add('marque', EntityType::class, [
                'class' => Marque::class,
                'choice_label' => function (Marque $marque): string {
                return $marque->getName();
                },
                'label' => "Quelle est la marque de cette voiture",
            ])
            ->add('composant', EntityType::class, [
                'class' => Composant::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
