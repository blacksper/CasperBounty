<?php

namespace CasperBounty\ProjectsBundle\Form;

use CasperBounty\TargetsBundle\Entity\Targets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class addTargetsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$entityManager = $options['em'];


//        $builder->add('entities', 'collection', array(
//            'type' => new Targets(),
//            'allow_add' => true,
//            'allow_delete' => true,
//            'by_reference' => false,
//        'options' => array('test'=>'test') // <-- THIS
//    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {

    }

    public function getBlockPrefix()
    {
        return 'casper_bounty_targets_bundleadd_targets_form';
    }
}
