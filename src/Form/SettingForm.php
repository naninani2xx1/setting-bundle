<?php

namespace AmzsCMS\SettingBundle\Form;


use AmzsCMS\CoreBundle\Traits\Form\FormButtonsTrait;
use AmzsCMS\SettingBundle\Constant\SettingType;
use AmzsCMS\SettingBundle\Entity\Setting;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SettingForm extends AbstractType
{
    use FormButtonsTrait;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Setting $setting */
        $setting = $options['data'];

        $builder
            ->add('settingType', SettingTypeOption::class, [
                'data' =>  is_integer($setting->getId()) ? $setting->getSettingType() : SettingType::SETTING_TYPE_KEY_VALUE,
            ])
            ->add('settingKey', TextType::class, [
                'attr' => ['class' => 'form-control fs-7'],
                'label' => 'Setting key',
                'required' => true,
                'row_attr' => ['class' => 'mb-5'],
                'label_attr' => ['class' => 'form-label my-2'],
                'constraints' => [new NotBlank()]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control fs-7',
                    'rows' => 3,
                    'placeholder' => 'Description for the key',
                ],
                'label' => 'Description',
                'required' => false,
                'row_attr' => ['class' => 'mb-5'],
                'label_attr' => ['class' => 'form-label my-2']
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);

        $this->addActionButtons($builder, [
            'submit_label' => ($setting instanceof Setting && $setting->getId()) ? 'Save' : 'Add',
        ]);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $data = $event->getData();
        $settingType = $data instanceof Setting ? $data->getSettingType() : null;

        // Truyền thêm dữ liệu cũ vào nếu cần để hiển thị khi Edit
        $currentValue = $data instanceof Setting ? $data->getSettingValue() : null;

        $this->addSettingValueField($event->getForm(), $settingType, $currentValue);
    }

    public function onPreSubmit(FormEvent $event): void
    {
        $data = $event->getData();
        $settingType = $data['settingType'] ?? null;
        $this->addSettingValueField($event->getForm(), $settingType, $data);
    }

    /**
     * Hàm dùng chung để tạo field dynamic
     */
    private function addSettingValueField(FormInterface $form, ?string $settingType, $data = null): void
    {
        // 1. Loại bỏ field cũ
        if ($form->has('settingValue')) {
            $form->remove('settingValue');
        }

        // 2. Logic thêm field dựa trên type
        switch ($settingType) {
            case SettingType::SETTING_TYPE_KEY_VALUE:
                $form->add('settingValue', TextType::class, [
                    'attr' => ['class' => 'form-control fs-7'],
                    'label' => 'Setting value',
                    'row_attr' => ['class' => 'mb-5'],
                    'label_attr' => ['class' => 'form-label my-2'],
                ]);
                break;

            case SettingType::SETTING_TYPE_JSON:
                $arrCollection = new ArrayCollection();
                if(is_string($data)){
                    $data = json_decode($data, true);
                    foreach ($data as $value) {
                        if(is_string($value)) continue;
                        $arrCollection->add($value);
                    }
                }

                $form->add('settingValue', CollectionType::class, [
                    'entry_type' => ItemCollectionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'by_reference' => false,
                    'data' => $arrCollection, // Load dữ liệu cũ nếu có
                    'mapped' => false, // Chú ý: Cần xử lý thủ công trong Controller khi lưu JSON
                ]);
                break;

            default:
                $form->add('settingValue', HiddenType::class, ['required' => true]);
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}