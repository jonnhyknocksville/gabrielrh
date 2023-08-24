<?php

namespace App\Form;

use App\Entity\Mission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('school')
            ->add('course')
            ->add('startTime')
            ->add('scheduleTime')
            ->add('hours')
            ->add('personInCharge')
            ->add('address')
            ->add('city')
            ->add('postalCode')
            ->add('intervention')
            ->add('missionReference')
            ->add('remuneration')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
