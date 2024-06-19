<?php

namespace App\Form;

use App\Entity\Lodging;
use App\Entity\Message;
use App\Entity\Reservation;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
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
        $builder->add('content', TextareaType::class);

        if ($options['isReply'] === false) {
            $builder
                ->add('subject', TextType::class)
                ->add('lodging', EntityType::class, [
                    'class' => Lodging::class,
                    'choice_label' => function (Lodging $lodging) {
                        return $lodging->getName();
                    },
                    'empty_data' => null,
                    'required' => false,
                ])
                ->add('reservation', EntityType::class, [
                    'class' => Reservation::class,
                    'query_builder' => function (EntityRepository $er) use ($options): QueryBuilder {
                        return $er->createQueryBuilder('r')
                            ->where('r.user = :user')
                            ->setParameter('user', $options['user']);
                    },
                    'choice_label' => function (Reservation $reservation) use ($options) {
                        return $options['user']->getFirstname() . ' '
                            . $options['user']->getLastname() . ' - ('
                            . $reservation->getArrivalDate()->format('d-m-Y') . ' -> '
                            . $reservation->getDepartureDate()->format('d-m-Y') . ')';
                    },
                    'empty_data' => null,
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'user' => null,
            'isReply' => false,
        ]);
    }
}
