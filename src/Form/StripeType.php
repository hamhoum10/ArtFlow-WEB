<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class StripeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class)
            ->add('name', TextType::class)
            ->add('cvc', TextType::class)
            ->add('expireMonth', IntegerType::class)
            ->add('expireYear', IntegerType::class)
            //->add('submit', SubmitType::class, ['label' => 'Pay'])
        ;
    }
}