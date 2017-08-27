<?php

namespace CasperBounty\ServicesBundle\Form;

use CasperBounty\ProfilesBundle\Entity\Profiles;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class profileToService extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('profileId',EntityType::class,array('class'=>'CasperBounty\ProfilesBundle\Entity\Profiles', //создать свой тип чтоб не рендерить все тулзени
                'choice_label' => function ($profile) {
                    return $profile->getName();
                },
                'choice_attr' => function($profile, $key, $index) {
                    return ['class' => 'optooo','data-toggle'=>'tooltip','title'=>$profile->getCmd()];
                },
                'attr'=>array('class'=>'tryam form-control','data-actions-box'=>true,'dropdownAlignRight'=>1),
                'multiple'=>1,
                'label'=>false,
//                'group_by' => function($val, $key, $index) {
//                    if ($val ) {
//                        return 'Soon';
//                    } else {
//                        return 'Later';
//                    }
//                }
                ))

            ->add('save',SubmitType::class,array(
                'label'  => 'Add profile',
                'attr'=>array('class'=>'btn btn-sm btn-primary')
            ));
        //$builder->setAction('casper_bounty_projects_targetsToProjectFromList');

    }

    public function configureOptions(OptionsResolver $resolver)
    {


    }

    public function getBlockPrefix()
    {
        return 'casper_bounty_services_profileToScenario';
    }
}
