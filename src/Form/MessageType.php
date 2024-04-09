<?php

namespace App\Form;

use App\Entity\Lodging;
use App\Entity\Message;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class)
            ->add('content', TextareaType::class)
            ->add('lodging', EntityType::class, [
                'class' => Lodging::class,
                'choice_label' => function (Lodging $lodging) {
                    return $lodging->getName();
                },
            ])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => function (Reservation $reservation) use ($options) {
                    //todo: make $reservation the user's
                    return $options['user']->getFirstname() . ' '
                        . $options['user']->getLastname() . ' - ('
                        . $reservation->getArrivalDate()->format('d-m-Y') . ' -> '
                        . $reservation->getDepartureDate()->format('d-m-Y') . ')';
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'user' => null,
            'reservations' => []
        ]);
    }
}
