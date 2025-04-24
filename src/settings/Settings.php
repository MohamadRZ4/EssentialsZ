<?php

namespace MohamadRZ\EssentialsZ\settings;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\utils\Config;

class Settings
{
    private Config $settings;

    public function __construct()
    {
        $this->settings = new Config(EssentialsZPlugin::getInstance()->getDataFolder() . "settings.yml", Config::YAML);
    }

    public function getSettings(): Config
    {
        return $this->settings;
    }

    public function getOpsColorName()
    {
        return $this->settings->get(SettingPaths::OPS_NAME_COLOR);
    }
    public function getNickNamePrefix()
    {
        return $this->settings->get(SettingPaths::NICKNAME_PREFIX);
    }
    public function getMaxNickLength()
    {
        return $this->settings->get(SettingPaths::MAX_NICK_LENGTH);
    }
    public function getAllowedNicksRegex()
    {
        return $this->settings->get(SettingPaths::ALLOWED_NICKS_REGEX);
    }
    public function getNickBlackList()
    {
        return $this->settings->get(SettingPaths::NICK_BLACKLIST);
    }
    public function getIgnoreColorsInMaxNickLength()
    {
        return $this->settings->get(SettingPaths::IGNORE_COLORS_IN_MAX_NICK_LENGTH);
    }
}