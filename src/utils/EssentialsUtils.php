<?php

namespace MohamadRZ\EssentialsZ\utils;

use pocketmine\entity\effect\Effect;
use pocketmine\entity\effect\VanillaEffects;

class EssentialsUtils
{

    public static function getEffectByName(string $name): ?Effect
    {
        return match (strtolower($name)) {
            "speed" => VanillaEffects::SPEED(),
            "slowness" => VanillaEffects::SLOWNESS(),
            "haste" => VanillaEffects::HASTE(),
            "mining_fatigue", "miningfatigue" => VanillaEffects::MINING_FATIGUE(),
            "strength" => VanillaEffects::STRENGTH(),
            "instant_health", "instanthealth" => VanillaEffects::INSTANT_HEALTH(),
            "instant_damage", "instantdamage" => VanillaEffects::INSTANT_DAMAGE(),
            "jump_boost", "jumpboost" => VanillaEffects::JUMP_BOOST(),
            "nausea" => VanillaEffects::NAUSEA(),
            "regeneration" => VanillaEffects::REGENERATION(),
            "resistance" => VanillaEffects::RESISTANCE(),
            "fire_resistance", "fireresistance" => VanillaEffects::FIRE_RESISTANCE(),
            "water_breathing", "waterbreathing" => VanillaEffects::WATER_BREATHING(),
            "invisibility" => VanillaEffects::INVISIBILITY(),
            "blindness" => VanillaEffects::BLINDNESS(),
            "night_vision", "nightvision" => VanillaEffects::NIGHT_VISION(),
            "hunger" => VanillaEffects::HUNGER(),
            "weakness" => VanillaEffects::WEAKNESS(),
            "poison" => VanillaEffects::POISON(),
            "wither" => VanillaEffects::WITHER(),
            "health_boost", "healthboost" => VanillaEffects::HEALTH_BOOST(),
            "absorption" => VanillaEffects::ABSORPTION(),
            "saturation" => VanillaEffects::SATURATION(),
            "levitation" => VanillaEffects::LEVITATION(),
            "fatal_poison", "fatalpoison" => VanillaEffects::FATAL_POISON(),
            "conduit_power", "conduitpower" => VanillaEffects::CONDUIT_POWER(),
            default => null,
        };
    }
}