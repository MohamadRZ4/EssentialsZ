<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class WarpsCommand extends Command {
    public function __construct() {
        parent::__construct("warps", "List all warps");
        $this->setPermission("essentialsz.command.warps");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) return;

        $warps = EssentialsZPlugin::getInstance()->getWarp()->getWarpList();
        $list = implode(", ", $warps);
        $sender->sendMessage(TextFormat::YELLOW . "Warps: " . TextFormat::WHITE . $list);
    }
}
