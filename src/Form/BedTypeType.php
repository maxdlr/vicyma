<?php

namespace App\Form;

use App\Entity\BedType;
use App\Form\FormUtils\FormTypeUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BedTypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('height', ChoiceType::class, [
                'choices' => FormTypeUtils::makeChoices(['180', '190', '200']),
                'required' => true,
            ])
            ->add('width', ChoiceType::class, [
                'choices' => FormTypeUtils::makeChoices(['140', '160', '180', '200']),
                'required' => true,
            ])
            ->add('isExtra', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BedType::class,
        ]);
    }
}
