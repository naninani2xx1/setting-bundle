<?php

declare(strict_types=1);

namespace Amzs\SettingBundle\Form;

use Amzs\SettingBundle\Constant\SettingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingTypeOption extends AbstractType
{
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => SettingType::loadForm(),
            'data' => SettingType::SETTING_TYPE_KEY_VALUE,
            'placeholder' => null,
            'expanded' => true,
            'multiple' => false,
            'attr' => [
                'class' => 'fs-7 dynamic-trigger',
            ],
            'label' => 'Setting type',
            'row_attr' => [
                'class' => 'mb-5',
            ],
            'label_attr' => [
                'class' => 'form-label my-2',
            ]
        ]);
    }
}
