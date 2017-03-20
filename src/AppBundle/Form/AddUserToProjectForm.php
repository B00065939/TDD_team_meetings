<?php

namespace AppBundle\Form;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\User;

use AppBundle\Repository\ProjectRepository;
use AppBundle\Repository\ProjectRoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddUserToProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('project', EntityType::class, [
                'class' => Project::class,
                'label' => "Select a project",
                'query_builder' => function (ProjectRepository $repo) {
                    return $repo->createUnlockedProjectsQueryBuilder();
                }
            ])
            ->add('projectRole', EntityType::class, [
                'class' => ProjectRole::class,
                'label' => "Choose user role in the project",
                'query_builder' => function (ProjectRoleRepository $repo) {
                    return $repo->createNotKeyRoleQueryBuilder();
                }

            ])
            ->add('userToAdd', EntityType::class, [
                'class' => User::class,
                'label' => "Select user to add to the project",
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_add_user_to_project';
    }
}
