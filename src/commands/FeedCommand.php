<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FeedCommand extends Command
{
    public function __construct() {
        parent::__construct("feed", "Feed yourself or another player");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player && empty($args)) {
            $sender->sendMessage(TextFormat::RED . "Usage: /feed <player>");
            return;
        }

        if (empty($args)) {
            if (!$sender->hasPermission("essentialsz.command.feed")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to feed yourself.");
                return;
            }

            $sender->getHungerManager()->setFood(20);
            $sender->sendMessage(TextFormat::GREEN . "You have been fed.");
            return;
        }

        if (!$sender->hasPermission("essentialsz.command.feed.others")) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to feed others.");
            return;
        }

        $target = $sender->getServer()->getPlayerExact($args[0]);
        if (!$target instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Player not found.");
            return;
        }

        $target->getHungerManager()->setFood(20);
        $target->sendMessage(TextFormat::GREEN . "You have been fed.");
        $sender->sendMessage(TextFormat::GREEN . "{$target->getName()} has been fed.");
    }
}
