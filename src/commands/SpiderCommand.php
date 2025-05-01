<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\user\User;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SpiderCommand extends Command
{
    private EssentialsZPlugin $plugin;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("spider", "Enable or disable spider mode for yourself or others", "/spider [player]", []);
        $this->setPermission("essentialsz.command.spider");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        if (count($args) > 1) {
            $sender->sendMessage(TextFormat::YELLOW . "Usage: /spider [player]");
            return;
        }

        if ($sender instanceof Player) {
            $player = $sender;
        } elseif ($sender instanceof CommandSender) {
            $sender->sendMessage(TextFormat::RED . "You need to specify a player when running this command from the console.");
            return;
        }

        if (count($args) === 0) {
            $target = $player;
        } else {
            $targetName = $args[0];
            $target = $this->plugin->getServer()->getPlayerExact($targetName);
            if ($target === null) {
                $sender->sendMessage(TextFormat::RED . "Player $targetName not found.");
                return;
            }
        }

        if ($sender instanceof Player && !$sender->hasPermission("essentialsz.command.spider.others") && $sender !== $target) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to toggle spider mode for others.");
            return;
        }

        $user = $this->plugin->getUserManager()->getUser($target);

        if ($user->hasTempData("spider_mode") && $user->getTempData("spider_mode") === true) {
            $target->sendMessage(TextFormat::GREEN . "Spider mode disabled.");
            $target->setCanClimbWalls(false);
            $user->unsetTempData("spider_mode");
        } else {
            $target->sendMessage(TextFormat::GREEN . "Spider mode enabled!");
            $target->setCanClimbWalls(true);
            $user->setTempData("spider_mode", true);
        }

        $sender->sendMessage(TextFormat::GREEN . "You have toggled spider mode for " . $target->getName());
    }
}
