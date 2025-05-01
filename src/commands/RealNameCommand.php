<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class RealNameCommand extends Command
{
    private $plugin;
    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("realname", "Shows the real name of a player", "/realname <nickname>");
        $this->setPermission("essentialsz.command.realname");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("This command can only be executed by a player.");
            return;
        }

        if (count($args) < 1) {
            $sender->sendMessage("Usage: /realname <nickname>");
            return;
        }

        $normalize = fn(string $text) => strtolower(preg_replace('/§[0-9a-fk-or]/i', '', $text));

        $nicknameInput = $normalize($args[0]);

        foreach ($this->plugin->getServer()->getOnlinePlayers() as $online) {
            $user = $this->plugin->getUserManager()->getUser($online);
            $nickname = $user->getNickname();
            if ($normalize($nickname) === $nicknameInput) {
                $sender->sendMessage("The real name of player '{$args[0]}' is " . $online->getName() . ".");
                return;
            }
        }

        $sender->sendMessage("Player with the nickname '{$args[0]}' not found online.");
    }
}