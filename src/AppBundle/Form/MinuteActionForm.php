<?php

namespace AppBundle\Form;

use AppBundle\Entity\MinuteAction;
use function Sodium\add;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MinuteActionForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('proposer')
            ->add('doer')
            ->add('deadline');
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
