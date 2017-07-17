<?php

namespace CasperBounty\ToolsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class addToolForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('name', TextType::class)
            ->add('cmdpath', TextType::class)
            ->add('save',SubmitType::class,array(
                'label'  => 'Add tool',
                'attr'=>array('class'=>'btn btn-success addHostButton')
            ));


    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'casper_bounty_tools_bundleadd_tool_form';
    }
}
