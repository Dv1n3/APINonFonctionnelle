<?php
/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 08/04/2018
 * Time: 14:09
 */

namespace KL\ApiBundle\Form\Type;

use KL\ApiBundle\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
            'csrf_protection' => false
        ]);
    }
}