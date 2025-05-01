<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\block\VanillaBlocks;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\Position;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class BreakCommand extends Command
{
    public function __construct()
    {
        parent::__construct("break", "Break the block you are looking at", "/break", []);
        $this->setPermission("essentialsz.command.break");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use this command.");
            return;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
            return;
        }

        $player = $sender;
        $eyePos = $player->getEyePos();
        $direction = $player->getDirectionVector();
        $world = $player->getWorld();

        $maxDistance = 5;
        $block = null;

        for ($i = 0; $i <= $maxDistance; $i += 0.5) {
            $pos = $eyePos->addVector($direction->multiply($i));
            $block = $world->getBlockAt((int)$pos->x, (int)$pos->y, (int)$pos->z);

            if (!$block->isTransparent()) {
                break;
            }
        }

        if ($block === null || $block->isTransparent()) {
            $player->sendMessage(TextFormat::RED . "No valid block in sight.");
            return;
        }

        if ($block->getTypeId() === VanillaBlocks::BEDROCK()->getTypeId() && !$player->hasPermission("essentialsz.command.break.bedrock")) {
            $player->sendMessage(TextFormat::RED . "You are not allowed to break Bedrock.");
            return;
        }

        $blockBreakEvent = new BlockBreakEvent($player, $block, VanillaItems::NETHERITE_PICKAXE(), true);

        if (!$blockBreakEvent->isCancelled()) {
            $world->setBlock($block->getPosition(), VanillaBlocks::AIR());

            $player->sendMessage(TextFormat::GREEN . "Block broken successfully.");
        } else {
            $player->sendMessage(TextFormat::RED . "You cannot break this block.");
        }
    }
}
