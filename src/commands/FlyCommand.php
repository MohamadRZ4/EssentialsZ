<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\user\User;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class FlyCommand extends Command
{
    private EssentialsZPlugin $plugin;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("fly", "Enable or disable flying for yourself or another player", "/fly [player]", []);
        $this->setPermission("essentialsz.command.fly");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        $targetPlayer = null;

        if (count($args) === 0) {
            if (!$sender instanceof Player) {
                $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
                return;
            }
            $targetPlayer = $sender;
        } else {
            if (!$sender->hasPermission("essentialsz.command.fly.others")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to fly other players.");
                return;
            }

            $targetPlayerName = $args[0];
            $targetPlayer = $this->plugin->getServer()->getPlayerExact($targetPlayerName);

            if ($targetPlayer === null) {
                $sender->sendMessage(TextFormat::RED . "Player not found: $targetPlayerName");
                return;
            }
        }

        if ($targetPlayer instanceof Player) {
            $this->toggleFly($sender, $targetPlayer);
        }
    }

    private function toggleFly(CommandSender $sender, Player $player): void
    {
        $user = $this->plugin->getUserManager()->getUser($player);

        if ($player->getAllowFlight()) {
            $user->setFlyEnabled(false);
            $player->sendMessage(TextFormat::GREEN . "Flying disabled.");
            if ($sender === $player) {
                $sender->sendMessage(TextFormat::GREEN . "Flying disabled for " . $player->getName());
            }
        } else {
            $user->setFlyEnabled(true);
            $player->sendMessage(TextFormat::GREEN . "Flying enabled.");
            if ($sender === $player) {
                $sender->sendMessage(TextFormat::GREEN . "Flying enabled for " . $player->getName());
            }
        }
    }
}
