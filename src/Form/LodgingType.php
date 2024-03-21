<?php

namespace App\Form;

use App\Entity\Bed;
use App\Entity\File;
use App\Entity\Lodging;
use App\Entity\Reservation;
use App\Form\Style\FormTypeStyle;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LodgingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        new FormTypeStyle();

        $builder
            ->add('name', TextType::class, FormTypeStyle::textTypeField(
                label: "Nom de l'appartement",
                placeholder: "Ebene"
            ))
            ->add('capacity', IntegerType::class, [
                'row_attr' => [
                    'class' => 'py-5'
                ],
                'label_attr' => [
                    'class' => 'py-2'
                ],
                'attr' => [
                    'class' => 'py-2 ps-4 d-flex flex-column'
                ]
            ])
            ->add('roomCount', IntegerType::class)
            ->add('surface', NumberType::class)
            ->add('bathroomCount', IntegerType::class)
            ->add('toiletCount', IntegerType::class)
            ->add('tvService', CheckboxType::class)
            ->add('washer', CheckboxType::class)
            ->add('waterHeater', CheckboxType::class)
            ->add('parking', CheckboxType::class)
            ->add('gate', CheckboxType::class)
            ->add('animalAllowed', CheckboxType::class)
            ->add('terrace', CheckboxType::class)
            ->add('terraceSurface', NumberType::class)
            ->add('floor', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('priceByNight', NumberType::class)
            ->add('beds', EntityType::class, [
                'class' => Bed::class,
                'choice_label' => function (Bed $bed) {
                    return 'l - ' . $bed->getWidth() . ' X h - ' . $bed->getHeight();
                },
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('files', EntityType::class, [
                'class' => File::class,
                'choice_label' => function (File $file) {
                    return $file->getFileName();
                },
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('reservations', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => function (Reservation $reservation) {
                    return $reservation->getUser()->getLastname() . ' - ' . $reservation->getArrivalDate()->format('d-m-Y') . ' - ' . $reservation->getDepartureDate()->format('d-m-Y');
                },
                'multiple' => true,
                'autocomplete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
