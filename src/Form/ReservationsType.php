<?php

namespace App\Form;

use App\Entity\Reservations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationsType extends AbstractType
{

    private const WEEKLY_SLOTS = [
        0 => ["12:00", "12:30", "13:00", "19:00", "19:30", "20:00"],
        1 => [],
        2 => ["19:00", "19:30", "20:00", "20:30", "21:00"],
        3 => ["19:00", "19:30", "20:00", "20:30", "21:00"],
        4 => ["19:00", "19:30", "20:00", "20:30", "21:00"],
        5 => ["19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00"],
        6 => ["19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00"],
    ];


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $today = new \DateTimeImmutable();
        $todayWeekday = (int) $today->format('w');



        $builder
            ->add('customer_name', TextType::class, [
                'label' => 'Nom complet',
            ])
            ->add('customer_email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('customer_phone', TextType::class, [
                'label' => 'Téléphone',
            ])
            ->add('guests', ChoiceType::class, [
                'label' => 'Convives',
                'placeholder' => false,
                'choices' => [
                    '1 personne'  => 1,
                    '2 personnes' => 2,
                    '3 personnes' => 3,
                    '4 personnes' => 4,
                    '5 personnes' => 5,
                    '6 personnes' => 6,
                    '7 personnes' => 7,
                    '8 personnes' => 8,
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('reservation_date', DateType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'data' => $today,
            ])
            ->add('reservation_time', TextType::class, [
                'mapped' => false,
                'required' => true,
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Demandes particulières',
                'required' => false,
            ])

            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                $data = $event->getData();
                if (empty($data['reservation_date']) || empty($data['reservation_time'])) {
                    return;
                }
                $date = new \DateTimeImmutable($data['reservation_date']);
                $weekday = (int) $date->format('w');
                $allowed = self::WEEKLY_SLOTS[$weekday] ?? [];

                if (!in_array($data['reservation_time'], $allowed, true)) {
                    // créneau invalide => on vide la valeur, le form sera invalide
                    $data['reservation_time'] = null;
                    $event->setData($data);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservations::class,
        ]);
    }
}
