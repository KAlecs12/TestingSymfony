<?php

namespace App\Form;

use App\Entity\Cheval;
use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idPro', EntityType::class, [
                'label' => 'Selection du Professionnel : ',
                'mapped' => false,
                'class' => User::class,
                'choice_label' => 'firstName',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles = :uroles')
                        ->setParameter('uroles', '["ROLE_PRO"]');
                },
            ])
            ->add('sujet')
            ->add('message', TextareaType::class, [
            'label' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
