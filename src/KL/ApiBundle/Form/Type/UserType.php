<?php

/**
 * Created by PhpStorm.
 * User: dvine
 * Date: 08/04/2018
 * Time: 12:41
 */

namespace KL\ApiBundle\Form\Type;

use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\TextType;
use KL\ApiBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
$builder
    ->add('email')
    ->add('nom')
    ->add('prenom')
    ->add('actif');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
           'csrf_protection' => false
        ]);
    }
}