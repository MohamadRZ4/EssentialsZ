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

    public function getSettings($k)
    {
        return $this->settings->get($k);
    }
}