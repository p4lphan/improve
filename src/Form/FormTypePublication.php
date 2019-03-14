<?php
namespace App\Form;

use App\Entity\Publication;
use App\Entity\TypePublication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FormTypePublication extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {           
        $builder
               ->add('name'        , TextType::class,      ['label' => 'Nom'])
               ->add('valid'       , ChoiceType::class,    ['label' => 'Publiée','choices'=>['Oui'=>true,'Non'=>false]])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypePublication::class,
        ]);
    }
}