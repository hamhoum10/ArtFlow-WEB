<?php

namespace App\Form;

use App\Entity\Evemt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;


class EvemtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEvemt')
//            ->add('description')
            ->add('description', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 5, 'max' => 255, 'minMessage' => 'Description doit etre superieur {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('finishHour')
            ->add('finishHour', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 2, 'max' => 255, 'minMessage' => 'finish Hour must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('startHour')
            ->add('startHour', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 2, 'max' => 255, 'minMessage' => 'startHour must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('location')
            ->add('location', null, [


                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 5, 'max' => 255, 'minMessage' => 'location must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('capacity')
            ->add('capacity', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 2, 'max' => 255, 'minMessage' => 'capacity must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
           // ->add('image')
           ->add('image', FileType::class, [
               'required' => true,
               'data_class' => null,
               'attr' => [
                   'style' => 'width: 100%;'
               ]
           ])

//            ->add('name')
            ->add('name', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 6, 'max' => 255, 'minMessage' => 'name must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('prix')
            ->add('prix', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 1, 'max' => 255, 'minMessage' => 'price must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
//            ->add('username')
            ->add('username', null, [

                'constraints' => [
                    new Assert\NotBlank(['message' => 'Description cannot be blank.']),
                    new Assert\Length(['min' => 4, 'max' => 255, 'minMessage' => 'username must be at least {{ limit }} characters.', 'maxMessage' => 'Description cannot be longer than {{ limit }} characters.']),
                ],
            ])
          //  ->add('idArtiste')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evemt::class,
        ]);
    }
}
