<?php

namespace MohamadRZ\EssentialsZ;

use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;

class Warp {

    private $data;

    public function __construct() {
        $this->data = new Config(EssentialsZPlugin::getInstance()->getDataFolder() . "/warps.yml", Config::YAML);
    }

    public function setWarp(Player $player, string $warpName): bool {
        $world = $player->getWorld();
        $position = $player->getPosition();

        $this->data->set($warpName, [
            'world' => $world->getFolderName(),
            'x' => $position->getX(),
            'y' => $position->getY(),
            'z' => $position->getZ(),
            'yaw' => $player->getLocation()->getYaw(),
            'pitch' => $player->getLocation()->getPitch()
        ]);

        $this->data->save();
        $player->sendMessage(TextFormat::GREEN . "Warp {$warpName} has been set successfully.");
        return true;
    }

    public function teleportToWarp(Player $player, string $warpName): bool {
        if (!$this->data->exists($warpName)) {
            $player->sendMessage(TextFormat::RED . "Warp {$warpName} does not exist.");
            return false;
        }

        $warpData = $this->data->get($warpName);

        $level = $player->getServer()->getWorldManager()->getWorldByName($warpData['world']);
        $location = new Location($warpData['x'], $warpData['y'], $warpData['z'], $level, $warpData['yaw'], $warpData['pitch']);

        $player->teleport($location);
        $player->sendMessage(TextFormat::GREEN . "Teleported to warp {$warpName}.");
        return true;
    }

    public function getWarpList(): array {
        return array_keys($this->data->getAll());
    }

    public function removeWarp(string $warpName): bool {
        if (!$this->data->exists($warpName)) {
            return false;
        }

        $this->data->remove($warpName);
        $this->data->save();
        return true;
    }

    public function isWarpOpen(string $warpName): bool {
        if (!$this->data->exists($warpName)) return false;
        return $this->data->get($warpName)['open'] ?? true;
    }

    public function setWarpOpen(string $warpName, bool $open): bool {
        if (!$this->data->exists($warpName)) return false;
        $warpData = $this->data->get($warpName);
        $warpData['open'] = $open;
        $this->data->set($warpName, $warpData);
        $this->data->save();
        return true;
    }

}
