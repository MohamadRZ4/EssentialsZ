<?php

namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class MsgCommand extends Command
{
    private EssentialsZPlugin $plugin;

    public function __construct(EssentialsZPlugin $plugin)
    {
        parent::__construct("msg", "Send a private message to another player", "/msg <player> <message>", ["message"]);
        $this->setPermission("essentialsz.command.msg");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        if (count($args) < 2) {
            $sender->sendMessage(TextFormat::YELLOW . "Usage: /msg <player> <message>");
            return;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
            return;
        }

        $targetName = array_shift($args);
        $message = implode(" ", $args);

        $targetPlayer = $this->plugin->getServer()->getPlayerExact($targetName);

        if ($targetPlayer === null) {
            $sender->sendMessage(TextFormat::RED . "Player $targetName not found.");
            return;
        }

        $config = $this->plugin->getConfig();

        $maxMessageLength = $config->get("message-max-length", 200);
        $messageCooldown = $config->get("message-cooldown", 5);

        if (strlen($message) > $maxMessageLength) {
            $sender->sendMessage(TextFormat::RED . "Your message is too long. Max length is $maxMessageLength characters.");
            return;
        }

        $lastMessageTime = $this->plugin->getUserManager()->getUser($sender)->getTempData("msg-time");
        if ($lastMessageTime !== null && (time() - $lastMessageTime) < $messageCooldown) {
            $sender->sendMessage(TextFormat::RED . "You need to wait before sending another message.");
            return;
        }

        $sender->sendMessage(TextFormat::AQUA . "You -> " . TextFormat::WHITE . $targetPlayer->getName() . ": " . TextFormat::RESET . $message);
        $targetPlayer->sendMessage(TextFormat::AQUA . $sender->getName() . " -> You: " . TextFormat::RESET . $message);

        $this->plugin->getUserManager()->getUser($sender)->setTempData("msg-time",time());
    }
}
