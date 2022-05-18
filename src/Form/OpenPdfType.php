<?php

namespace App\Form;

use App\Entity\Facture;
use App\Entity\User;
use App\Repository\FactureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class OpenPdfType extends AbstractType
{

    public function __construct(FactureRepository $factureRepository, TokenStorageInterface $token)
    {
        $this->factureRepository = $factureRepository;
        $this->token = $token;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titre', EntityType::class, [
                'label' => 'Nom : ',
                'mapped' => false,
                'class' => Facture::class,
                'choice_label' => 'titre',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                            ->where('u.idUser = :uid')
                            ->setParameter('uid', $this->token->getToken()->getUser()->getId());
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facture::class,
        ]);
    }
}
