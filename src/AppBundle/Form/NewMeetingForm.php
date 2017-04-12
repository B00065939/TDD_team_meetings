<?php

namespace AppBundle\Form;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\User;
use AppBundle\Repository\ProjectHasUserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NewMeetingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $mDateTime = new \DateTime();
        date_add($mDateTime, date_interval_create_from_date_string('1 day'));

        $aDateTime = new \DateTime();
        date_add($aDateTime, date_interval_create_from_date_string('23 hour'));

        $builder
            ->add('chair', EntityType::class, [
                'class' => User::class,
                'label' => "Meeting Chairman",
                'placeholder' => 'Project Leader as default'
            ])
            ->add('secretary', EntityType::class, [
                'class' => User::class,
                'label' => "Meeting Secretary",
                'placeholder' => 'Project Secretary as default'
            ])
            ->add('mDateTime', DateTimeType::class, [
                'label' => "Date and Time of meeting",
                'data' => $mDateTime
            ])
            ->add('agendaDeadline', DateTimeType::class, [
                'label' => "Agenda Deadline",
                'data' => $aDateTime
            ])
            ->add('duration', IntegerType::class, [
                'data' => 90,

            ])
            ->add('location',TextType::class,[
                'label' => "Meeting location"
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Meeting::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_new_meeting_form';
    }
}
