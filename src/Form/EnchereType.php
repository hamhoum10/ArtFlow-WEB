<?php

namespace App\Form;

use App\Entity\Enchere;



use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnchereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre',TextType::class)
            ->add('description',TextType::class)
            ->add('prixdepart',TextType::class)
            ->add('dateLimite',DateType::class)
            ->add('Image', FileType::class,
                ['label' => 'image',
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Enchere::class,
        ]);
    }
}
