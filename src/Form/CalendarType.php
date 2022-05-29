<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre : '])
            ->add('start', DateTimeType::class, [
                'label' => 'Début : ',
                'date_widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Fin : ',
                'date_widget' => 'single_text'
            ])
            ->add('description',TextareaType::class,[
                'label' => 'Une Description : ',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de rendez-vous : ',
                'choices'=> [
                    'Professionel'=> 'pro',
                    'Installation'=> 'installation'
                ]
            ])
//            ->add('background_color', ColorType::class)
//            ->add('border_color', ColorType::class)
//            ->add('text_color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
