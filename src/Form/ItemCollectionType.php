<?php

declare(strict_types=1);

namespace Amzs\SettingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('key', TextType::class, [
            'attr' => [
                'class' => 'form-control fs-7',
            ],
            'row_attr' => [
                'class' => 'mb-5',
            ],
            'label_attr' => [
                'class' => 'form-label my-2',
            ]
        ]);
        $builder->add('value', TextType::class, [
            'attr' => [
                'class' => 'form-control fs-7',
            ],
            'row_attr' => [
                'class' => 'mb-5',
            ],
            'label_attr' => [
                'class' => 'form-label my-2',
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
