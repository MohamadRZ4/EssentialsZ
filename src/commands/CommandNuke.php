<?php

namespace MohamadRZ\EssentialsZ\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\object\PrimedTNT;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class CommandNuke extends Command {


    public function __construct() {
        parent::__construct("nuke", "Spawn TNT around players", null, ["n"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
            return;
        }

        $targets = [];
        if (count($args) > 0) {
            foreach ($args as $arg) {
                $targetPlayer = $sender->getServer()->getPlayerExact($arg);
                if ($targetPlayer !== null) {
                    $targets[] = $targetPlayer;
                }
            }
        } else {
            $targets[] = $sender;
        }

        foreach ($targets as $target) {
            if ($target === null) {
                continue;
            }

            $target->sendMessage(TextFormat::GREEN . "Nuke activated!");

            $loc = $target->getPosition();
            $world = $loc->getWorld();

            if ($world !== null) {
                for ($x = -10; $x <= 10; $x += 5) {
                    for ($z = -10; $z <= 10; $z += 5) {
                        $tnt = new PrimedTNT($world, $loc->add($x, 0, $z));
                        $tnt->spawnToAll();
                        $tnt->setNameTag("nuke!");
                    }
                }
            }
        }
    }
}
