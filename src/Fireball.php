<?php

namespace MohamadRZ\EssentialsZ;

use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\projectile\Projectile;
use pocketmine\entity\projectile\Throwable;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;

class Fireball extends Throwable
{

    public static function getNetworkTypeId(): string
    {
        return EntityIds::FIREBALL;
    }
}