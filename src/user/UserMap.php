<?php

namespace MohamadRZ\EssentialsZ\user;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\utils\Config;

class UserMap
{
    private Config $config;

    public function __construct() {
        $configFile = EssentialsZPlugin::getInstance()->getDataFolder() . "usermap.yml";

        if (!file_exists($configFile)) {
            $this->config = new Config($configFile, Config::YAML,[]);
        } else {
            $this->config = new Config($configFile, Config::YAML);
        }
    }
 
    public function set(string $username, string $xuid): void {
        $this->config->set($username, $xuid);
        $this->config->save();
    }

    public function get(string $username): ?string {
        return $this->config->get($username, null);
    }

    public function remove(string $username): void {
        $this->config->remove($username);
        $this->config->save();
    }

    public function exists(string $username): bool {
        return $this->config->exists($username);
    }

    public function getAll(): array {
        return $this->config->getAll();
    }
}
