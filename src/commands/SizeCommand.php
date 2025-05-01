<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\settings\SettingPaths;
use MohamadRZ\EssentialsZ\utils\EssentialsUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\settings\Settings;

class SizeCommand extends Command
{
    private EssentialsZPlugin $plugin;
    private Settings $settings;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("size", "Change or view player size", "/size [size|reset] [player]");
        $this->setPermission("essentialsz.size");
        $this->plugin = $plugin;
        $this->settings = $this->plugin->getSettings();
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$sender instanceof Player && count($args) < 2) {
            $sender->sendMessage("Usage: /size [size|reset] <player>");
            return;
        }

        $target = $sender instanceof Player ? $sender : null;
        $size = null;

        if (isset($args[1])) {
            if (!$sender->hasPermission("essentialsz.size.others")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to change others' size.");
                return;
            }

            $target = Server::getInstance()->getPlayerExact($args[1]);
            if (!$target) {
                $sender->sendMessage(TextFormat::RED . "Player not found.");
                return;
            }
        }

        if ($target instanceof Player) {
            $xuid = $target->getXuid();
            $user = $this->plugin->getUserManager()->getUser($target);
            $lastUsed = $user->getTempData("size_last_used", 0);
            $now = time();

            $cooldown = $this->settings->getSettings(SettingPaths::SIZE_COOLDOWN);
            if ($cooldown !== -1 && !$sender->hasPermission("essentialsz.size.bypasscooldown")) {
                if ($now - $lastUsed < $cooldown) {
                    $remaining = $cooldown - ($now - $lastUsed);
                    $sender->sendMessage(TextFormat::RED . "Please wait $remaining seconds before changing size again.");
                    return;
                }
            }

            $worlds = $this->settings->getSettings(SettingPaths::ALLOWED_WORLDS);
            $blacklistedWorlds = $this->settings->getSettings(SettingPaths::BLACKLISTED_WORLDS);

            if (in_array(strtolower($target->getWorld()->getFolderName()), array_map("strtolower", $blacklistedWorlds))) {
                $sender->sendMessage(TextFormat::RED . "You cannot change size in this world (blacklisted).");
                return;
            }

            if (!empty($worlds) && !in_array(strtolower($target->getWorld()->getFolderName()), array_map("strtolower", $worlds))) {
                $sender->sendMessage(TextFormat::RED . "You cannot change size in this world.");
                return;
            }

            if (!isset($args[0])) {
                $sender->sendMessage(TextFormat::YELLOW . "Current size: " . number_format($target->getScale(), 2));
                return;
            }

            if (strtolower($args[0]) === "reset") {
                $default = 1;
                $target->setScale($default);
                $user->setSize($default);
                $user->setTempData("size_last_used", $now);
                $sender->sendMessage(TextFormat::GREEN . "Size reset to default.");
                return;
            }

            if (!is_numeric($args[0])) {
                $sender->sendMessage(TextFormat::RED . "Invalid size value.");
                return;
            }

            $size = floatval($args[0]);

            $min = $this->settings->getSettings(SettingPaths::MIN_SIZE);
            $max = $this->settings->getSettings(SettingPaths::MAX_SIZE);
            if (($min != -1 && $size < $min) || ($max != -1 && $size > $max)) {
                $sender->sendMessage(TextFormat::RED . "Size must be between $min and $max.");
                return;
            }

            $target->setScale($size);
            $user->setSize($size);
            $user->setTempData("size_last_used", $now);
            $sender->sendMessage(TextFormat::GREEN . "Size changed to $size.");
        }
    }
}
