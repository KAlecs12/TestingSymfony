<?php

namespace App\Form;

use App\Entity\Cheval;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class LinkHorseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', EntityType::class, [
                'label' => 'Selection du Cheval : ',
                'mapped' => false,
                'class' => Cheval::class,
                'choice_label' => 'nom'
            ])
            ->add('firstName', EntityType::class, [
                'label' => 'Selection du Client : ',
                'mapped' => false,
                'class' => User::class,
                'choice_label' => 'firstName'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cheval::class,
        ]);
    }
}