<?php

namespace App\Form;

use App\Entity\Lodging;
use App\Entity\Reservation;
use App\Form\FormUtils\FormTypeUtils;
use App\Repository\LodgingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function __construct(private readonly LodgingRepository $lodgingRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adultCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(6)
            ])
            ->add('childCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(6)
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
            ])
            ->add('price', NumberType::class)
//            ->add('lodgings', ChoiceType::class, [
//                'choices' => FormTypeUtils::makeChoices($this->lodgingRepository->findAll())
//            ])
        ;
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
