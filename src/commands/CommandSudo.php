<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class CommandSudo extends Command {

    public function __construct() {
        parent::__construct("sudo", "Execute a command as another player", "/sudo <player> <command>", ["runas"]);
        $this->setPermission("essentials.sudo");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
            return;
        }

        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::RED . "Usage: /sudo <player> <command>");
            return;
        }

        $targetName = $args[0];
        $command = implode(" ", array_slice($args, 1));

        $targetPlayer = Server::getInstance()->getPlayerExact($targetName);
        if ($targetPlayer === null) {
            $sender->sendMessage(TextFormat::RED . "Player not found.");
            return;
        }

        if (!$sender->hasPermission("essentials.sudo")) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        $this->executeCommandAsPlayer($targetPlayer, $command);

        $sender->sendMessage(TextFormat::GREEN . "Executed command '$command' as player $targetName.");
    }

    private function executeCommandAsPlayer(Player $player, string $command): void {
        Server::getInstance()->dispatchCommand($player, $command);
    }
}
