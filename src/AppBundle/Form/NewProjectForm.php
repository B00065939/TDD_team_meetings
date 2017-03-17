<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => "Project title"
            ])
            ->add('projectLeader', EntityType::class, [
                'class' => User::class,
                'label' => "Select project leader",
                'query_builder' => function (UserRepository $repo) {
                    return $repo->createAlphabeticalUSERQueryBuilder();
                }
            ])
            ->add('projectSecretary', EntityType::class, [
                'class' => User::class,
                'label' => "Select project secretary"
            ])
            ->add('lock', CheckboxType::class, [
                'label' => "Lock this project"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_new_project_form';
    }
}
