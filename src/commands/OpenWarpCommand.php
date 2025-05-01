<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class OpenWarpCommand extends Command {
    public function __construct() {
        parent::__construct("openwarp", "Open a warp");
        $this->setPermission("essentialsz.command.openwarp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /openwarp <name>");
            return;
        }

        if (EssentialsZPlugin::getInstance()->getWarp()->setWarpOpen($args[0], true)) {
            $sender->sendMessage(TextFormat::GREEN . "Warp {$args[0]} is now open.");
        } else {
            $sender->sendMessage(TextFormat::RED . "Warp {$args[0]} not found.");
        }
    }
}
