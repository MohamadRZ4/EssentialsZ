<?php

namespace MohamadRZ\EssentialsZ\utils;

use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\Position;
use pocketmine\world\World;
use pocketmine\world\WorldCreationOptions;

class WorldUtils
{
    /**
     * Get a World by its name
     *
     * @param string $worldName
     * @return World|null
     */
    public static function getWorldByName(string $worldName): ?World
    {
        return server::getInstance()->getWorldManager()->getWorldByName($worldName);
    }

    /**
     * Check if a World exists
     *
     * @param string $worldName
     * @return bool
     */
    public static function worldExists(string $worldName): bool
    {
        return self::getWorldByName($worldName) !== null;
    }

    /**
     * Create a new world
     *
     * @param string $worldName
     * @param string $generatorType
     * @param bool $seed
     * @param bool $loadWorld
     * @return bool
     */
    public static function createWorld(string $worldName, string $generatorType = "flat", bool $seed = false, bool $loadWorld = true): bool
    {
        if (self::worldExists($worldName)) {
            return false; // World already exists
        }

        $generatorClass = self::getGeneratorId($generatorType);

        if (!$generatorClass) {
            return false; // Invalid generator type
        }

        // Create the world with a specified generator (flat, normal, etc.)
        Server::getInstance()->getWorldManager()->generateWorld($worldName, WorldCreationOptions::create()
            ->setSeed($seed)
            ->setGeneratorClass($generatorClass)
        );

        return false;
    }

    /**
     * Get the generator class based on the type
     *
     * @param string $generatorType
     * @return string|null
     */
    private static function getGeneratorId(string $type): ?string {
        $type = strtolower(trim($type));

        $map = [
            "normal" => "vanilla_normal",
            "classic" => "vanilla_normal",
            "superflat" => "flat",
            "flat" => "flat",
            "nether_old" => "nether",
            "normal_old" => "normal",
            "vanilla" => "vanilla_normal",
            "nether" => "vanilla_nether",
            "end" => "ender",
        ];

        return $map[$type] ?? null;
    }


    /**
     * Unload a world
     *
     * @param string $worldName
     * @param bool $save
     * @return bool
     */
    public static function unloadWorld(string $worldName, bool $save = true): bool
    {
        $world = self::getWorldByName($worldName);
        if ($world) {
            return server::getInstance()->getWorldManager()->unloadWorld($world, $save);
        }
        return false;
    }

    /**
     * Delete a world from the server
     *
     * @param string $worldName
     * @return bool
     */
    public static function deleteWorld(string $worldName): bool
    {
        $world = self::getWorldByName($worldName);
        if ($world) {
            self::unloadWorld($worldName);
            $worldPath = server::getInstance()->getDataPath() . "worlds/" . $worldName;
            if (is_dir($worldPath)) {
                // Delete all files and subfolders
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($worldPath, \RecursiveDirectoryIterator::SKIP_DOTS),
                    \RecursiveIteratorIterator::CHILD_FIRST
                );
                foreach ($files as $fileinfo) {
                    $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                    $todo($fileinfo->getRealPath());
                }
                return rmdir($worldPath);
            }
        }
        return false;
    }

    /**
     * Teleport a player to a specific world and coordinates
     *
     * @param string $worldName
     * @param float $x
     * @param float $y
     * @param float $z
     * @param string $playerName
     * @return bool
     */
    public static function teleportToWorld(string $worldName, float $x, float $y, float $z, string $playerName, ?float $yaw, ?float $pitch): bool
    {
        $world = self::getWorldByName($worldName);
        $player = Server::getInstance()->getPlayerExact($playerName);

        if ($world && $player) {
            $yaw = $yaw ?? $player->getLocation()->getYaw();
            $pitch = $pitch ?? $player->getLocation()->getPitch();

            $location = new Location($x, $y, $z, $world, $yaw, $pitch);
            $player->teleport($location);
            return true;
        }
        return false;
    }

    /**
     * Get a list of all worlds on the server
     *
     * @return string[]
     */
    public static function getAllWorlds(): array
    {
        $worlds = server::getInstance()->getWorldManager()->getWorlds();
        $worldNames = [];
        foreach ($worlds as $world) {
            $worldNames[] = $world->getDisplayName();
        }
        return $worldNames;
    }

    /**
     * Get the spawn coordinates of a world
     *
     * @param string $worldName
     * @return Location|null
     */
    public static function getWorldSpawnLocation(string $worldName): ?Position
    {
        $world = self::getWorldByName($worldName);
        if ($world) {
            return $world->getSpawnLocation();
        }
        return null;
    }

    /**
     * Set the spawn location of a world
     *
     * @param string $worldName
     * @param float $x
     * @param float $y
     * @param float $z
     * @return bool
     */
    public static function setWorldSpawnLocation(string $worldName, float $x, float $y, float $z): bool
    {
        $worldManager = Server::getInstance()->getWorldManager();

        $world = $worldManager->getWorldByName($worldName);

        if (!$world instanceof World) {
            $loadSuccess = $worldManager->loadWorld($worldName);
            if (!$loadSuccess) return false;

            $world = $worldManager->getWorldByName($worldName);
            if (!$world instanceof World) return false;
        }

        $world->setSpawnLocation(new Vector3($x, $y, $z));
        return true;
    }

    /**
     * Check if a world is loaded
     *
     * @param string $worldName
     * @return bool
     */
    public static function isWorldLoaded(string $worldName): bool
    {
        $world = self::getWorldByName($worldName);
        return $world !== null && $world->isLoaded();
    }

    /**
     * Generate a random world name
     *
     * @return string
     */
    public static function generateRandomWorldName(): string
    {
        return "world_" . bin2hex(random_bytes(5)); // Generates a random world name like "world_abcdef123"
    }
}
