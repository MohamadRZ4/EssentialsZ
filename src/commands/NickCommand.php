<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\settings\SettingPaths;
use MohamadRZ\EssentialsZ\user\User;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class NickCommand extends Command
{
    private EssentialsZPlugin $plugin;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("nick", "Change your nickname or others'", "/nick <nickname> | /nick <player> <nickname>", ["nickname"]);
        $this->setPermission("essentialsz.command.nick");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        if (count($args) === 0) {
            $sender->sendMessage(TextFormat::YELLOW . "Usage: /nick <nickname> or /nick <player> <nickname>");
            return;
        }

        $targetName = null;
        $nickname = null;

        if (strtolower($args[0]) === "reset") {
            if (!$sender instanceof Player && !($sender instanceof CommandSender)) {
                $sender->sendMessage(TextFormat::RED . "Only players or the console can reset nicknames.");
                return;
            }

            if (count($args) === 1) {
                if (!$sender->hasPermission("essentialsz.command.nick.reset.others")) {
                    $sender->sendMessage(TextFormat::RED . "You don't have permission to reset others' nicknames.");
                    return;
                }

                $targetName = $args[1];
                $nickname = null;
            } else {
                $targetName = $sender->getName();
                $nickname = null;
            }
        } elseif (count($args) === 1) {
            if (!$sender instanceof Player) {
                $sender->sendMessage(TextFormat::RED . "Only players can set their own nickname.");
                return;
            }

            $targetName = $sender->getName();
            $nickname = $args[0];
        } elseif (count($args) >= 2) {
            if (!$sender->hasPermission("essentialsz.command.nick.others")) {
                $sender->sendMessage(TextFormat::RED . "You don't have permission to set others' nicknames.");
                return;
            }

            $targetName = array_shift($args);
            $nickname = implode(" ", $args);
        }

        $userManager = $this->plugin->getUserManager();
        $targetUser = $userManager->getUserByNameFlexible($targetName);
        if ($targetUser === null) {
            $sender->sendMessage(TextFormat::RED . "User not found or never joined: $targetName");
            return;
        }

        $result = $this->setNickname($sender, $targetUser, $nickname);
        if ($result !== true) {
            $sender->sendMessage(TextFormat::RED . $result);
        } else {
            $sender->sendMessage(TextFormat::GREEN . "Nickname for $targetName set to: " . TextFormat::RESET . $nickname);
        }
    }

    public function setNickname(CommandSender $sender, User $user, ?string $nickname): string|bool
    {
        $config = $this->plugin->getConfig();

        if ($nickname === null) {
            $user->setNickname("none");
            return true;
        }

        $prefix = $this->plugin->getSettings()->getSettings(SettingPaths::NICKNAME_PREFIX);
        $maxLength = $this->plugin->getSettings()->getSettings(SettingPaths::MAX_NICK_LENGTH);
        $allowedRegex = $this->plugin->getSettings()->getSettings(SettingPaths::ALLOWED_NICKS_REGEX);
        $blacklist = $this->plugin->getSettings()->getSettings(SettingPaths::NICK_BLACKLIST);
        $ignoreColors = $this->plugin->getSettings()->getSettings(SettingPaths::IGNORE_COLORS_IN_MAX_NICK_LENGTH);

        $allowUnsafe = $sender->hasPermission("essentialsz.command.nick.allowunsafe");
        $bypassBlacklist = $sender->hasPermission("essentialsz.command.nick.blacklist.bypass");
        $hidePrefix = $sender->hasPermission("essentialsz.command.nick.hideprefix");

        if (!$bypassBlacklist) {
            foreach ($blacklist as $blocked) {
                if (@preg_match("/$blocked/i", $nickname)) {
                    return "This nickname is not allowed.";
                }
            }
        }

        $cleaned = $ignoreColors ? preg_replace('/§[0-9a-fk-or]/i', '', $nickname) : $nickname;
        if (!$allowUnsafe && strlen($cleaned) > $maxLength) {
            return "Nickname is too long (Max: $maxLength characters).";
        }

        if (!$allowUnsafe && !preg_match("/$allowedRegex/", $nickname)) {
            return "Nickname contains invalid characters.";
        }

        $finalNick = $hidePrefix ? $nickname : $prefix . $nickname;

        $user->setNickname($finalNick);
        return true;
    }
}
