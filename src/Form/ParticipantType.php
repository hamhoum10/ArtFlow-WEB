<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Participant;
use App\Entity\Enchere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('id', EntityType::class, [
                'label'=> 'Clients',
                'class' => Client::class,
                'choice_label' =>  function (Client $client) {
                    return sprintf('%s', $client    ->getFirstname());
                },
                'placeholder' => 'user',
            ])
            ->add('ide', EntityType::class, [
                'label'=> 'user',
                'class' => Enchere::class,
                'choice_label' =>  function (Enchere $enchere) {
                    return sprintf('%s', $enchere->getTitre());
                },
                'placeholder' => ' Enchere',
            ])


        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
