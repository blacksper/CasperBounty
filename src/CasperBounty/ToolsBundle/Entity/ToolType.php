<?php
/**
 * Created by PhpStorm.
 * User: Егор
 * Date: 31.07.2017
 * Time: 14:54
 */

namespace CasperBounty\ToolsBundle\Entity;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ToolType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        return array('data_class'=>'CasperBounty\ToolsBundle\Entity\Tools');

    }

    public function getParent()
    {
        return 'text';
    }

}