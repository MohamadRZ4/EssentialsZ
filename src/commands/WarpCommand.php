<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class WarpCommand extends Command {
    public function __construct() {
        parent::__construct("warp", "Teleport to a warp");
        $this->setPermission("essentialsz.command.warp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Only players can use this command.");
            return;
        }

        if (!$this->testPermission($sender)) return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /warp <name>");
            return;
        }

        $warp = EssentialsZPlugin::getInstance()->getWarp();
        if (!$warp->isWarpOpen($args[0]) && !$sender->hasPermission("essentialsz.warp.bypass")) {
            $sender->sendMessage(TextFormat::RED . "This warp is currently closed.");
            return;
        }

        $warp->teleportToWarp($sender, $args[0]);
    }
}
