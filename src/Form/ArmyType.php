<?php

namespace App\Form;

use App\Entity\Army;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArmyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('units', RangeType::class, [ 'attr' => [ 'min' => 80, 'max' => 100 ] ])
            ->add('attack_strategy', ChoiceType::class, [
                'choices' => [
                    'Weakest' => Army::ATTACK_WEAKEST,
                    'Strongest' => Army::ATTACK_STRONGEST,
                    'Random' => Army::ATTACK_RANDOM,
                ],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Army::class,
        ]);
    }
}
