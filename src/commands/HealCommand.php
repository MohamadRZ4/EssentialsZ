<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class HealCommand extends Command
{
    public function __construct() {
        parent::__construct("heal", "Heal yourself or another player");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player && empty($args)) {
            $sender->sendMessage(TextFormat::RED . "Usage: /heal <player>");
            return;
        }

        if (empty($args)) {
            if (!$sender->hasPermission("essentialsz.command.heal")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to heal yourself.");
                return;
            }

            $sender->setHealth($sender->getMaxHealth());
            $sender->sendMessage(TextFormat::GREEN . "You have been healed.");
            return;
        }

        if (!$sender->hasPermission("essentialsz.command.heal.others")) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to heal others.");
            return;
        }

        $target = $sender->getServer()->getPlayerExact($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Player not found.");
            return;
        }

        $target->setHealth($target->getMaxHealth());
        $target->sendMessage(TextFormat::GREEN . "You have been healed.");
        $sender->sendMessage(TextFormat::GREEN . "{$target->getName()} has been healed.");
    }
}
