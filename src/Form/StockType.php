<?php

namespace App\Form;
use Symfony\Component\Form\FormTypeInterface;
use App\Entity\Stock;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name_produit')
            ->add('artiste')
            ->add('addres')
            ->add('user_name')
            ->add('date_entr')
            ->add('id_commende')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
