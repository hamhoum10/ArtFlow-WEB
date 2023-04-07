<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom')
            ->add('nom')
            ->add('numero')
            //->add('status') par default "en attente" tantque malmesh payment
            //->add('totalAmount') iji mel ligne panier
            //->add('createdAt') par default ell wa9t eli amlt creation lel commande
            ->add('codepostal')
            ->add('adresse')
            //->add('id_panier') //mn7othesh el id panier ama tw n7otouha
            //->add('idPanier')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
