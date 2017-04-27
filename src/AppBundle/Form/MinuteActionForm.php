<?php

namespace AppBundle\Form;

use AppBundle\Entity\MinuteAction;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinuteActionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $deadline = new \DateTime();
        date_add($deadline, date_interval_create_from_date_string('1 day'));
        $builder
            ->add('title')
            ->add('description')
            ->add('proposer')
            ->add('doer')
            ->add('deadline', DateTimeType::class,[
                'label' => 'Action Deadline',
                'data' => $deadline
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MinuteAction::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_minute_action_form';
    }
}
