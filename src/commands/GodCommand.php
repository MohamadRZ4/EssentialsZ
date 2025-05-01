<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\utils\EssentialsUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class GodCommand extends Command
{
    private EssentialsZPlugin $plugin;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("god", "Toggles God Mode for the player", "/god", ["gm"]);
        $this->setPermission("essentialsz.command.god");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be run by a player.");
            return false;
        }

        $player = $sender;
        $user = EssentialsZPlugin::getInstance()->getUserManager()->getUser($player);
        $isInGodMode = $user->hasTempData("isGodMode");

        if ($isInGodMode) {
            $player->sendMessage(TextFormat::GREEN . "You are no longer in God Mode.");
            $user->unsetTempData("isGodMode");
        } else {
            $player->sendMessage(TextFormat::GREEN . "You are now in God Mode.");
            $user->setTempData("isGodMode", true);
        }

        return true;
    }
}
