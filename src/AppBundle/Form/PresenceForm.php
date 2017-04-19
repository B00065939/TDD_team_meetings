<?php

namespace AppBundle\Form;

use AppBundle\Entity\MeetingAttendance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('presence', ChoiceType::class, array(
            'label' => "Select your attendance",
            'choices' => array(
                'for whole meeting' => "for whole meeting",
                'arrived late' => "arrived late",
                'left early' => "left early",
                'arrived late and left early' => "arrived late and left early",
                'absent' => "absent",
            )))
            ->add('note', TextareaType::class, array(
                "label" => "Note",
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MeetingAttendance::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_attendance_form';
    }
}
