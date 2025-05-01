<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class DelWarpCommand extends Command {
    public function __construct() {
        parent::__construct("delwarp", "Delete a warp");
        $this->setPermission("essentialsz.command.delwarp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /delwarp <name>");
            return;
        }

        if (EssentialsZPlugin::getInstance()->getWarp()->removeWarp($args[0])) {
            $sender->sendMessage(TextFormat::GREEN . "Warp {$args[0]} removed.");
        } else {
            $sender->sendMessage(TextFormat::RED . "Warp {$args[0]} not found.");
        }
    }
}
