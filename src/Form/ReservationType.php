<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\FormUtils\FormTypeUtils;
use DateInterval;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $now = new DateTime('now');
        $builder
            ->add('adultCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices($options['lodging']->getCapacity())
            ])
            ->add('childCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices($options['lodging']->getCapacity())
            ])
            ->add('arrivalDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'view_timezone' => 'Africa/Dakar',
            ])
            ->add('departureDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'view_timezone' => 'Africa/Dakar',
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
