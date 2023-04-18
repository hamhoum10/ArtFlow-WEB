<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Participant;
use App\Entity\Enchere;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

             ->add('montant', TextType::class)

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








    public function validate($value, Constraint $constraint)
    {
        $encheres = $this->context->getRoot()->getData();
        $lastMontant = 0;

        foreach ($encheres as $enchere) {
            if ($enchere->getIde() == $encheres[count($encheres)-1]->getIde()) {
                $lastMontant = $enchere->getMontant();
                break;
            }
        }

        if ($value <= $lastMontant) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ lastMontant }}', $lastMontant)
                ->addViolation();
        }
    }









}
