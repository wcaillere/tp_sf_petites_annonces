<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre de l\'annonce'])
            ->add('text', TextareaType::class, ['label' => 'Description'])
            ->add('zip_code', TextType::class, ['label' => 'Code postal'])
            ->add('city', TextType::class, ['label' => 'ville'])
            ->add(
                'category', EntityType::class,
                [
                    'label' => 'Catégorie',
                    'class' => Category::class,
                    'choice_label' => 'name'
                ]
            )
            ->add('Valider', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
