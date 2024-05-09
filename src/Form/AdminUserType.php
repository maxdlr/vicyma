<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\RoleEnum;
use App\Form\FormUtils\FormTypeUtils;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => FormTypeUtils::makeChoices(array_map(fn(RoleEnum $roleEnum) => $roleEnum->value, RoleEnum::cases())),
                'multiple' => true,
                'autocomplete' => true
            ])
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('phoneNumber', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
