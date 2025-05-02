<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\user\User;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CommandSocialSpy extends Command {

    public function __construct() {
        parent::__construct("socialspy", "essentials.socialspy");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
            return;
        }

        if (!$sender->hasPermission("essentials.socialspy")) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        $user = EssentialsZPlugin::getInstance()->getUserManager()->getUser($sender);
        $this->handleToggleWithArgs($sender, $user, $args);
    }

    protected function handleToggleWithArgs(CommandSender $sender, User $user, array $args): void {
        if (empty($args)) {
            $this->togglePlayer($sender, $user);
        } else {
            if (!$sender->hasPermission("essentials.socialspy.others")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
                return;
            }
            $targetPlayer = $sender->getServer()->getPlayerExact($args[0]);
            if ($targetPlayer === null) {
                $sender->sendMessage(TextFormat::RED . "Player not found.");
                return;
            }
            $targetUser = EssentialsZPlugin::getInstance()->getUserManager()->getUser($targetPlayer);
            $this->togglePlayer($sender, $targetUser);
        }
    }

    protected function togglePlayer(CommandSender $sender, User $user): void {
        $enabled = !$user->getTempData("isSocialSpyEnabled");
        $user->setTempData("isSocialSpyEnabled", true);

        $message = $enabled ? "enabled" : "disabled";
        $user->getParent()->sendMessage(TextFormat::GREEN . "Social spy $message.");

        if (!$sender instanceof Player || $sender !== $user->getParent()) {
            $sender->sendMessage(TextFormat::GREEN . $user->getParent()->getDisplayName() . "'s social spy has been $message.");
        }
    }
}
