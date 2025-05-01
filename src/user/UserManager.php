<?php

namespace MohamadRZ\EssentialsZ\user;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\utils\Config;

use pocketmine\player\Player;

class UserManager {
    private EssentialsZPlugin $plugin;
    private UserMap $usernameMap;
    private string $playersDirectory;
    /** @var User[] */
    private array $users = [];

    public function __construct(EssentialsZPlugin $plugin) {
        $this->plugin = $plugin;
        $this->usernameMap = new UserMap();
        $this->playersDirectory = $plugin->getDataFolder() . "players/";
        @mkdir($this->playersDirectory);
    }

    public function getUser(Player $player): User {
        $xuid = $player->getXuid();
        $username = $player->getName();

        if (!isset($this->users[$xuid])) {
            $user = $this->load($xuid, $username);
        } else {
            $user = $this->users[$xuid];
        }

        $user->setParent($player);
        return $user;
    }

    public function getUserByXuid(string $xuid): ?User {
        if (!isset($this->users[$xuid])) {
            if (!$this->userDataExists($xuid)) return null;
            return $this->load($xuid, null);
        }
        return $this->users[$xuid];
    }

    public function getUserByNameFlexible(string $username): ?User {
        $player = $this->plugin->getServer()->getPlayerExact($username);
        if ($player !== null) {
            return $this->getUser($player);
        }

        $xuid = $this->usernameMap->get($username);
        return $xuid !== null ? $this->getUserByXuid($xuid) : null;
    }

    public function userDataExists(string $xuid): bool {
        return file_exists($this->playersDirectory . $xuid . ".yml");
    }

    public function hasUser(string $xuid): bool {
        return isset($this->users[$xuid]);
    }

    public function load(string $xuid, ?string $username): User {
        $file = $this->playersDirectory . $xuid . ".yml";
        $username = $username ?? "Unknown";

        $config = new Config($file, Config::YAML, [
            "xuid" => $xuid,
            "username" => $username,
            "firstJoinTime" => time(),
            "lastLoginTime" => time(),
            "joinTime" => time(),
            "quitTime" => "none",
            "lastPosition" => null,
            "isJailed" => false,
            "nickname" => "none"
        ]);

        $user = new User($xuid, $config->getAll());
        $this->users[$xuid] = $user;
        $this->usernameMap->set($username, $xuid);

        return $user;
    }

    public function save(string $xuid): void {
        if (!isset($this->users[$xuid])) return;

        $user = $this->users[$xuid];
        $file = $this->playersDirectory . $xuid . ".yml";
        $config = new Config($file, Config::YAML);
        $config->setAll($user->getData());
        $config->save();
    }

    public function unload(string $xuid): void {
        unset($this->users[$xuid]);
    }

    public function saveAll(): void {
        foreach ($this->users as $xuid => $user) {
            $this->save($xuid);
        }
    }

    public function userExists(string $username): bool {
        return $this->usernameMap->exists($username);
    }

    public function remove(string $xuid): void {
        $file = $this->playersDirectory . $xuid . ".yml";
        if (file_exists($file)) unlink($file);
        unset($this->users[$xuid]);
    }
}

