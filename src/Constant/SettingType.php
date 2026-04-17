<?php

namespace AmzsCMS\SettingBundle\Constant;

class SettingType
{
    private function __construct()
    {
    }

    public const SETTING_TYPE_JSON = 'json';
    public const SETTING_TYPE_IMAGE = 'image';
    public const SETTING_TYPE_KEY_VALUE = 'key_value';

    public static function all(): array
    {
        return array(self::SETTING_TYPE_JSON, self::SETTING_TYPE_IMAGE, self::SETTING_TYPE_KEY_VALUE);
    }

    public static function getReadable(string $type): string
    {
        switch ($type) {
            case self::SETTING_TYPE_JSON:
                return 'List item';
            case self::SETTING_TYPE_IMAGE:
                return 'Image';
            case self::SETTING_TYPE_KEY_VALUE:
                return 'Key&value';
                default:
                    return 'Unknown type';
        }
    }

    // lấy choices cho form
    public static function loadForm(): array
    {
        return array(
            self::getReadable(SettingType::SETTING_TYPE_IMAGE) => SettingType::SETTING_TYPE_IMAGE,
            self::getReadable(SettingType::SETTING_TYPE_KEY_VALUE) => SettingType::SETTING_TYPE_KEY_VALUE,
            self::getReadable(SettingType::SETTING_TYPE_JSON) => SettingType::SETTING_TYPE_JSON,
        );
    }
}