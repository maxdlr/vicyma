<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\FormUtils\FormTypeUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adultCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices($options['lodging']->getCapacity())
            ])
            ->add('childCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices($options['lodging']->getCapacity())
            ])
            ->add('arrivalDate', null, [
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('departureDate', null, [
                'widget' => 'single_text',
                'html5' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'lodging' => null,
            'user' => null
        ]);
    }
}
