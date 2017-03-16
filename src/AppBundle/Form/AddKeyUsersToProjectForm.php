<?php

namespace AppBundle\Form;

use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\ProjectRole;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddKeyUsersToProjectForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('projectRole',EntityType::class, [
            'class' => ProjectRole::class,


    ]);
//            ->add('subFamily', EntityType::class, [
//                'class' => SubFamily::class,
//                'placeholder' => 'Choose a Sub Family',
//                'query_builder' => function(SubFamilyRepository $repo) {
//                    return $repo->createAlphabeticalQueryBuilder();
//                }
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_add_key_users_to_project_form';
    }
}
