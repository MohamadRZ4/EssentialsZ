<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CreateWarpCommand extends Command {
    public function __construct() {
        parent::__construct("createwarp", "Create a warp");
        $this->setPermission("essentialsz.command.createwarp");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Only players can use this command.");
            return;
        }

        if (!$this->testPermission($sender)) return;

        if (empty($args[0])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /createwarp <name>");
            return;
        }

        EssentialsZPlugin::getInstance()->getWarp()->setWarp($sender, $args[0]);
    }
}
