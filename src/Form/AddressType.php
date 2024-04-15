<?php

namespace App\Form;

use App\Entity\Address;
use App\Enum\AddressTypeEnum;
use App\Form\FormUtils\FormTypeUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('line1', TextType::class)
            ->add('line2', TextType::class, [
                'required' => false,
            ])
            ->add('zipcode', TextType::class)
            ->add('city', TextType::class)
            ->add('region', TextType::class)
            ->add('country', CountryType::class, [
                'autocomplete' => true,
            ])
            ->add('type', ChoiceType::class, [
                'choices' => FormTypeUtils::makeChoices([AddressTypeEnum::PERSONAL->value, AddressTypeEnum::PROFESSIONAL->value])
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
