<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class CloseWarpCommand extends Command {
    public function __construct() {
        parent::__construct("closewarp", "Close a warp");
        $this->setPermission("essentialsz.command.closewarp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /closewarp <name>");
            return;
        }

        if (EssentialsZPlugin::getInstance()->getWarp()->setWarpOpen($args[0], false)) {
            $sender->sendMessage(TextFormat::GREEN . "Warp {$args[0]} is now closed.");
        } else {
            $sender->sendMessage(TextFormat::RED . "Warp {$args[0]} not found.");
        }
    }
}
