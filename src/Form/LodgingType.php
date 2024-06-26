<?php

namespace App\Form;

use App\Entity\BedType;
use App\Entity\Lodging;
use App\Form\FormUtils\FormTypeUtils;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('tvService', CheckboxType::class, [
                'required' => false
            ])
            ->add('airConditioning', CheckboxType::class, [
                'required' => false
            ])
            ->add('washer', CheckboxType::class, [
                'required' => false
            ])
            ->add('waterHeater', CheckboxType::class, [
                'required' => false
            ])
            ->add('parking', CheckboxType::class, [
                'required' => false
            ])
            ->add('gate', CheckboxType::class, [
                'required' => false
            ])
            ->add('animalAllowed', CheckboxType::class, [
                'required' => false
            ])
            ->add('terrace', CheckboxType::class, [
                'required' => false
            ])
            ->add('terraceSurface', NumberType::class)
            ->add('floor', ChoiceType::class, [
                'choices' => FormTypeUtils::makeIntChoices(3)
            ])
            ->add('description', TextareaType::class)
            ->add('priceByNight', NumberType::class, [
                'scale' => 2,
            ])
            ->add('beds', EntityType::class, [
                'class' => BedType::class,
                'choice_label' => function (BedType $bed) {
                    return 'l - ' . $bed->getWidth() . ' X h - ' . $bed->getHeight();
                },
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('photos', FileType::class, [
                ...FormTypeUtils::makeFileUploadParameters(true),
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lodging::class,
        ]);
    }
}
