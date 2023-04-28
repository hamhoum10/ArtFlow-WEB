<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('nbPlace')
            ->add('nbPlace', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 1, 'max' => 255, 'minMessage' => 'nombre de place doit etre superieur {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
            ->add('dateres')
          //  ->add('idClient')
            //->add('idEvent')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
