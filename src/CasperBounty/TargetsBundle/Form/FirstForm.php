<?php

namespace CasperBounty\TargetsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FirstForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder ->add('host', TextareaType::class)
            ->add('type', ChoiceType::class, array(
                'choices' => array(
                    'ip' => 'ip',
                    'domain' => 'domain'
                ),
                'choice_attr' => function ($val, $key, $index) {
                    // adds a class like attending_yes, attending_no, etc
                    return ['class' => 'attending_' . strtolower($key)];
                },
            ))
            ->add('save', SubmitType::class, array('label' => 'Create Post'));

        
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getName()
    {
        return 'casper_bounty_targets_bundle_first_form';
    }
}
