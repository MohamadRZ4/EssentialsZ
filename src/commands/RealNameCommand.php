<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class RealNameCommand extends Command
{
    public function __construct()
    {
        parent::__construct("realname", "Shows the real name of a player", "/realname <nickname>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be executed by a player.");
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage("Usage: /realname <nickname>");
            return;
        }

        $nicknameInput = preg_replace('/§[0-9a-fk-or]/i', '', $args[0]);

        $onlinePlayers = EssentialsZPlugin::getInstance()->getServer()->getOnlinePlayers();

        foreach ($onlinePlayers as $online) {
            $user = EssentialsZPlugin::getInstance()->getUserManager()->getUser($online);
            $nickname = $user->getNickname();
            $cleanedNickname = preg_replace('/§[0-9a-fk-or]/i', '', $nickname);

            if (strcasecmp($cleanedNickname, $nicknameInput) === 0) {
                $sender->sendMessage("The real name of player '{$args[0]}' is " . $online->getName() . ".");
                return;
            }
        }

        $sender->sendMessage("Player with the nickname '{$args[0]}' not found online.");
    }
}