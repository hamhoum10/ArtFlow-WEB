<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Artiste;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomArticle')
            ->add('price')
            ->add('type')
            ->add('Image', FileType::class,
                ['label' => 'image',
                    'multiple' => false,
                    'mapped' => false,
                    'required' => false])

            ->add('description')
            ->add('quantity')
            ->add('idArtiste', EntityType::class, [
                'label'=> 'artistes',
                'class' => Artiste::class,
                'choice_label' =>  function (Artiste $Artiste) {
                    return sprintf('%s', $Artiste->getUsername());
                },
                'placeholder' => 'Choosiness Artiste',
            ])
            ->add('idCategorie', EntityType::class, [
                'label'=> 'Categories',
                'class' => Categorie::class,
                'choice_label' =>  function (Categorie $idcategorie) {
                    return sprintf('%d', $idcategorie->getIdCategorie());
                },
                'placeholder' => 'Choosiness Categories',
            ])
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
