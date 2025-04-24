<?php

namespace MohamadRZ\EssentialsZ\utils;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\entity\Location;
use pocketmine\math\Vector3;

class PositionUtils
{
    /**
     * Convert a position to a human-readable string.
     * @param Vector3 $position The position to convert.
     * @return string The position as a string in "X, Y, Z" format.
     */
    public static function positionToString(Vector3 $position): string
    {
        return "{$position->getX()}, {$position->getY()}, {$position->getZ()}";
    }

    /**
     * Convert a position string to a Vector3 object.
     * @param string $positionStr The position string in the format "X, Y, Z".
     * @return Vector3 The corresponding Vector3 object.
     * @throws \InvalidArgumentException if the string format is incorrect.
     */
    public static function stringToPosition(string $positionStr): Vector3
    {
        $parts = explode(", ", $positionStr);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException("Invalid position string format.");
        }
        return new Vector3((float) $parts[0], (float) $parts[1], (float) $parts[2]);
    }

    /**
     * Calculate the distance between two positions.
     * @param Vector3 $position1 The first position.
     * @param Vector3 $position2 The second position.
     * @return float The distance between the two positions.
     */
    public static function calculateDistance(Vector3 $position1, Vector3 $position2): float
    {
        return $position1->distance($position2);
    }

    /**
     * Calculate the direction from one position to another as a normalized vector.
     * @param Vector3 $from The start position.
     * @param Vector3 $to The target position.
     * @return Vector3 The direction as a normalized vector.
     */
    public static function calculateDirection(Vector3 $from, Vector3 $to): Vector3
    {
        return $to->subtract($from->getX(), $from->getY(), $from->getZ())->normalize();
    }

    /**
     * Get a position that is offset from the original position by a certain amount.
     * @param Vector3 $original The original position.
     * @param float $offsetX The offset along the X-axis.
     * @param float $offsetY The offset along the Y-axis.
     * @param float $offsetZ The offset along the Z-axis.
     * @return Vector3 The new position with the offset applied.
     */
    public static function applyOffset(Vector3 $original, float $offsetX, float $offsetY, float $offsetZ): Vector3
    {
        return $original->add($offsetX, $offsetY, $offsetZ);
    }

    /**
     * Get the vector from one position to another.
     * @param Vector3 $from The start position.
     * @param Vector3 $to The target position.
     * @return Vector3 The vector representing the direction from one to the other.
     */
    public static function getVectorBetween(Vector3 $from, Vector3 $to): Vector3
    {
        return $to->subtract($from->getX(), $from->getY(), $from->getZ());
    }

    /**
     * Convert a vector to a normalized direction.
     * @param Vector3 $vector The vector to normalize.
     * @return Vector3 The normalized direction vector.
     */
    public static function normalizeVector(Vector3 $vector): Vector3
    {
        return $vector->normalize();
    }

    /**
     * Check if two positions are close to each other within a specified tolerance.
     * @param Vector3 $position1 The first position.
     * @param Vector3 $position2 The second position.
     * @param float $tolerance The maximum allowed difference.
     * @return bool True if the positions are within tolerance, false otherwise.
     */
    public static function arePositionsClose(Vector3 $position1, Vector3 $position2, float $tolerance = 0.1): bool
    {
        return $position1->distance($position2) <= $tolerance;
    }

    // Convert Vector3 to String
    public static function vectorToString(Vector3 $vector): string {
        return "{$vector->getX()}, {$vector->getY()}, {$vector->getZ()}";
    }

    // Convert String to Vector3
    public static function stringToVector(string $vectorStr): Vector3 {
        $parts = explode(", ", $vectorStr);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException("Invalid Vector string format.");
        }
        return new Vector3((float)$parts[0], (float)$parts[1], (float)$parts[2]);
    }

    // Convert Location to String (Including Yaw and Pitch)
    public static function locationToString(Location $location): string {
        return "{$location->getX()}, {$location->getY()}, {$location->getZ()}, {$location->getYaw()}, {$location->getPitch()}, {$location->getWorld()->getFolderName()}";
    }

// Convert String to Location (Including Yaw and Pitch)
    public static function stringToLocation(string $locationStr): Location {
        $parts = explode(", ", $locationStr);
        if (count($parts) !== 6) { // Now expecting 6 parts: X, Y, Z, Yaw, Pitch, World
            throw new \InvalidArgumentException("Invalid Location string format.");
        }

        $x = (float)$parts[0];
        $y = (float)$parts[1];
        $z = (float)$parts[2];
        $yaw = (float)$parts[3];
        $pitch = (float)$parts[4];
        $worldName = $parts[5];

        // Assuming you can get the world object by its name (adjust this part as needed).
        $world = EssentialsZPlugin::getInstance()->getServer()->getWorldManager()->getWorldByName($worldName);
        if (!$world) {
            throw new \InvalidArgumentException("World not found.");
        } elseif (!$world->isLoaded()) {
            EssentialsZPlugin::getInstance()->getServer()->getWorldManager()->loadWorld($worldName);
        }

        return new Location($x, $y, $z, $world, $yaw, $pitch);
    }
}
