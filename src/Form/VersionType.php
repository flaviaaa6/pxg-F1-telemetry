<?php

namespace App\Form;

use App\Entity\Logiciel;
use App\Entity\User;
use App\Entity\Version;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class,
                [
                    'label'=>'Version number',
                ]
            )
            ->add('commentaires', TextareaType::class,
                [
                    'label'=>'comments',
                ]
            )
            ->add('urlMac', null)
            ->add('urlWin',null)

            ->add('logiciel', EntityType::class,
                [
                    'class' => Logiciel::class,
                    'choice_label' => 'nom',
                    'label'=>'logiciel',
                    'required' => false,
                ]
            )


            ->add('Save', SubmitType::class,
                [
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Version::class,
        ]);
    }
}
