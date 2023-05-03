<?php

namespace App\Form;

use App\Entity\LignePanier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LignePanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder //presque kol shay will be added from other tables only the quantity eli tzid wala tn9aseha fi panier
            //->add('prixUnitaire')
            ->add('quantity')
            //->add('nomArticle')
            //->add('description')
            //->add('nomArtiste')
            //->add('prenomArtiste')
            //->add('idArticle')
            //->add('idPanier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => LignePanier::class,
        ]);
    }
}
