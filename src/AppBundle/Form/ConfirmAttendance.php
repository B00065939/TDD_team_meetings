<?php

namespace AppBundle\Form;

use AppBundle\Entity\MeetingAttendance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfirmAttendance extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('attendance', ChoiceType::class, array(
            'label' => "Select your attendance",
            'choices' => array(
                'Maybe' => "Maybe",
                'Yes' => "Yes",
                'No' => "No",
            )))
            ->add('apologiesNote', TextareaType::class, array(
                "label" => "Apologies note",
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
        return 'app_bundle_confirm_attendance';
    }
}
