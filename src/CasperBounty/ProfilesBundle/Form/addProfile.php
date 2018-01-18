<?php

namespace CasperBounty\ProfilesBundle\Form;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class addProfile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder ->add('name', TextType::class,array('label' => false))
            ->add('cmd', TextType::class,array('label' => false))
            //->add('toolId',EntityType::class,array('class'=>'CasperBounty\ToolsBundle\Entity\Tools','attr'=>array('class'=>'toolId')))
            ->add('toolId',EntityType::class,array('class'=>'CasperBounty\ToolsBundle\Entity\Tools', //создать свой тип чтоб не рендерить все тулзени
                'choice_label' => null,
                'attr'=>array('class'=>'toolId'),
                'label'=>false))
            ->add('save',SubmitType::class,array(
                'label'  => 'Add profile',
                'attr'=>array('class'=>'btn btn-primary btn-sm')
            ));
            //$builder->setAction('casper_bounty_projects_targetsToProjectFromList');
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'casper_bounty_profiles_bundleadd_profile';
    }
}
