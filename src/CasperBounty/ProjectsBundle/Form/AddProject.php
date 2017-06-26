<?php

namespace CasperBounty\ProjectsBundle\Form;

use CasperBounty\ProjectsBundle\Entity\Projects;
//use CasperBounty\TargetsBundle\Entity\Targets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddProject extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array(
                'label'  => 'Enter project name ',)
            )
            ->add('save',SubmitType::class,array(
                'label'  => 'Create project',
                    'attr'=>array('class'=>'btn btn-success')
                    )

            );

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'=>Projects::class,
        ));
    }

    public function getName()
    {
        return 'casper_bounty_projects_bundle_add_project';
    }
}
