<?php

namespace App\Form;

use App\Entity\Bed;
use App\Entity\Media;
use App\Entity\Lodging;
use App\Entity\Reservation;
use App\Form\FormUtils\FormTypeUtils;
use App\Form\Style\FormTypeStyle;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Symfony\Component\String\u;

class LodgingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('capacity', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(6)
            ])
            ->add('roomCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(10)
            ])
            ->add('surface', NumberType::class, [
                'scale' => 2,
            ])
            ->add('bathroomCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(3)
            ])
            ->add('toiletCount', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(3)
            ])
            ->add('tvService', CheckboxType::class)
            ->add('airConditioning', CheckboxType::class)
            ->add('washer', CheckboxType::class)
            ->add('waterHeater', CheckboxType::class)
            ->add('parking', CheckboxType::class)
            ->add('gate', CheckboxType::class)
            ->add('animalAllowed', CheckboxType::class)
            ->add('terrace', CheckboxType::class)
            ->add('terraceSurface', NumberType::class)
            ->add('floor', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(3)
            ])
            ->add('description', TextareaType::class)
            ->add('priceByNight', NumberType::class, [
                'scale' => 2,
            ])
            ->add('beds', EntityType::class, [
                'class' => Bed::class,
                'choice_label' => function (Bed $bed) {
                    return 'l - ' . $bed->getWidth() . ' X h - ' . $bed->getHeight();
                },
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('photos', FileType::class, FormTypeUtils::makeFileUploadParameters(true));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
