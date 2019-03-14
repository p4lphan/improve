<?php
namespace App\Form;

use App\Entity\Publication;
use App\Entity\TypePublication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class FormPublication extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options=[])
    {
       
        $builder
                ->add('valid'       , ChoiceType::class,    ['label' => 'Publiée','choices'=>['Oui'=>true,'Non'=>false]])
                ->add('id_type'     , EntityType::class,    [
                    'class' => TypePublication::class,
                    'label' =>'Catégorie',
                    'choice_label'=>'name',
                    'query_builder' => function (EntityRepository $er) {
                    
                        return $er->createQueryBuilder('tp')
                                ->where('tp.valid = 1')
                                ->orderBy('tp.name', 'ASC');
                        }
                    ,])               
                ->add('name'        , TextType::class,      ['label' => 'Nom'                ])
                ->add('content'     , TextType::class,      ['label' => 'Contenu'            ])
                ->add('author'      , TextType::class,      ['label' => 'Auteur'             ,'data'=>'Auteur inconnu'])
                ->add('filepath'    , TextType::class,      ['label' => "Chemin de l'image"  ])               
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,            
        ]);
    }
}