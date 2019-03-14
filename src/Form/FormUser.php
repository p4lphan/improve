<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username'        , TextType::class,      ['label'=>'Identifiant','disabled'=>$options["disabled"]])
            ->add('name'            , TextType::class,      ['label'=>'Nom de famille','disabled'=>$options["disabled"],'empty_data'=>''])
            ->add('firstname'       , TextType::class,      ['label'=>'Prénom','disabled'=>$options["disabled"],'empty_data'=>''])
            ->add('email'           , TextType::class,      ['label'=>'Email','disabled'=>$options["disabled"],'empty_data'=>''])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
