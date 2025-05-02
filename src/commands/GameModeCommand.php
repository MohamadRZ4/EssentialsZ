<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class GameModeCommand extends Command
{
    public function __construct()
    {
        parent::__construct("gamemode", "Change player game mode", "/gamemode <mode> [player]");
        $this->setPermission("essentialsz.command.gamemode");
        $this->setAliases(["gm", "gmc", "gms", "gma", "gmsp"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        $modeMap = [
            "0" => GameMode::SURVIVAL(),
            "s" => GameMode::SURVIVAL(),
            "survival" => GameMode::SURVIVAL(),
            "1" => GameMode::CREATIVE(),
            "c" => GameMode::CREATIVE(),
            "creative" => GameMode::CREATIVE(),
            "2" => GameMode::ADVENTURE(),
            "a" => GameMode::ADVENTURE(),
            "adventure" => GameMode::ADVENTURE(),
            "3" => GameMode::SPECTATOR(),
            "sp" => GameMode::SPECTATOR(),
            "spectator" => GameMode::SPECTATOR()
        ];

        $modeKey = strtolower($commandLabel);
        $target = $sender instanceof Player ? $sender : null;

        if (in_array($modeKey, ["gmc", "gms", "gma", "gmsp"])) {
            $modeKey = match ($modeKey) {
                "gmc" => "c",
                "gms" => "s",
                "gma" => "a",
                "gmsp" => "sp"
            };
        } else {
            if (!isset($args[0])) {
                $sender->sendMessage(TextFormat::RED . "Usage: /$commandLabel <mode> [player]");
                return;
            }
            $modeKey = strtolower($args[0]);
            array_shift($args);
        }

        $gameMode = $modeMap[$modeKey] ?? null;
        if (!$gameMode instanceof GameMode) {
            $sender->sendMessage(TextFormat::RED . "Invalid gamemode: $modeKey");
            return;
        }

        // پرمیشن‌های گیم مودها
        if (!$this->hasPermissionForMode($sender, $modeKey)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to change to this gamemode.");
            return;
        }

        if (isset($args[0])) {
            $targetName = $args[0];
            $target = EssentialsZPlugin::getInstance()->getServer()->getPlayerExact($targetName);
            if (!$target) {
                $sender->sendMessage(TextFormat::RED . "Player not found: $targetName");
                return;
            }

            // پرمیشن تغییر گیم مود برای دیگران
            if (!$this->hasPermissionToChangeOther($sender)) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to change other player's gamemode.");
                return;
            }
        }

        if (!$target instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "You must specify a player.");
            return;
        }

        $target->setGamemode($gameMode);
        if ($target === $sender) {
            $target->sendMessage(TextFormat::GREEN . "Your game mode has been changed to " . $gameMode->name());
        } else {
            $sender->sendMessage(TextFormat::GREEN . "Changed " . $target->getName() . "'s game mode to " . $gameMode->name());
            $target->sendMessage(TextFormat::GREEN . "Your game mode has been changed to " . $gameMode->name());
        }
    }

    private function hasPermissionForMode(CommandSender $sender, string $modeKey): bool
    {
        switch ($modeKey) {
            case 's':
            case 'survival':
                return $sender->hasPermission("essentialsz.command.gamemode.survival");
            case 'c':
            case 'creative':
                return $sender->hasPermission("essentialsz.command.gamemode.creative");
            case 'a':
            case 'adventure':
                return $sender->hasPermission("essentialsz.command.gamemode.adventure");
            case 'sp':
            case 'spectator':
                return $sender->hasPermission("essentialsz.command.gamemode.spectator");
            default:
                return false;
        }
    }

    private function hasPermissionToChangeOther(CommandSender $sender): bool
    {
        return $sender->hasPermission("essentialsz.command.gamemode.others");
    }
}
