<?php

namespace App\Form;

use App\Entity\Evemt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EvemtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEvemt')
            ->add('description')
            ->add('finishHour')
            ->add('startHour')
            ->add('location')
            ->add('capacity')
            //->add('image')
            ->add('image', FileType::class, [
                'required' => true,
                'data_class' => null,
                'attr' => [
                    'style' => 'width: 100%;'
                ]
            ])

            ->add('name')
            ->add('prix')
            ->add('username')
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
