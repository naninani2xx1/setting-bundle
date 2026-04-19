<?php

namespace AmzsCMS\SettingBundle\Entity;

use AmzsCMS\CoreBundle\Traits\Doctrine\Timestampable;
use AmzsCMS\SettingBundle\Constant\SettingType;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AmzsCMS\SettingBundle\Repository\SettingRepository")
 * @ORM\Table(name="amzs_setting")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @ORM\HasLifecycleCallbacks
 */
class Setting
{
    use Timestampable;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /** @ORM\Column(type="string", length=255, unique=true) */
    private $settingKey;

    /** @ORM\Column(type="text", nullable=true) */
    private $settingValue;

    /** @ORM\Column(type="string", nullable="true") */
    private $settingType;

    /** @ORM\Column(type="text", nullable="true") */
    private $description;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getSettingKey()
    {
        return $this->settingKey;
    }

    /**
     * @param mixed $settingKey
     */
    public function setSettingKey($settingKey): void
    {
        $this->settingKey = $settingKey;
    }

    /**
     * @return mixed
     */
    public function getSettingValue()
    {
        return $this->settingValue;
    }

    /**
     * @param mixed $settingValue
     */
    public function setSettingValue($settingValue): void
    {
        $this->settingValue = $settingValue;
    }

    /**
     * @return mixed
     */
    public function getSettingType()
    {
        return $this->settingType;
    }

    /**
     * @param mixed $settingType
     */
    public function setSettingType($settingType): void
    {
        $this->settingType = $settingType;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
    
    // custom
    public function isSettingTypeImage(): bool
    {
        return $this->settingType === SettingType::SETTING_TYPE_IMAGE;
    }

    public function isSettingTypeJson(): bool
    {
        return $this->settingType === SettingType::SETTING_TYPE_JSON;
    }
}