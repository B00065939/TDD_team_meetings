<?php

namespace AppBundle\Form;

use AppBundle\Entity\Meeting;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class NewMeetingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('chair', EntityType::class, [
                'class' => User::class,
                'label' => "Meeting Chairman"
            ])
            ->add('secretary', EntityType::class, [
                'class' => User::class,
                'label' => "Meeting Secretary"
            ])
            ->add('mDateTime', DateTimeType::class, [
                'label' => "Date and Time of meeting"
            ])
        ->add('agendaDeadline', DateTimeType::class,[
            'label' => "Agenda Deadline"
        ])
        ->add('duration', NumberType::class,[
            'label'=>"Duration"
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
