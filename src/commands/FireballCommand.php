<?php
/*
namespace MohamadRZ\EssentialsZ\commands;

use MohamadRZ\EssentialsZ\Fireball;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\projectile\{Arrow, Projectile, Snowball, Egg, Throwable};
use pocketmine\entity\Location;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\HugeExplodeSeedParticle;
use MohamadRZ\EssentialsZ\EssentialsZPlugin;

class FireballCommand extends Command {

    private EssentialsZPlugin $plugin;

    private array $projectileMap = [
        "fireball" => Fireball::class,
        "arrow" => Arrow::class,
        "snowball" => Snowball::class,
        "egg" => Egg::class,
    ];

    public function __construct(EssentialsZPlugin $plugin) {
        parent::__construct("fireball", "Launch a projectile", "/fireball [type] [speed] [ride]");
        $this->setPermission("essentialsz.command.fireball");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$this->testPermission($sender)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission.");
            return;
        }

        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "Only players can use this command.");
            return;
        }

        $type = $args[0] ?? "fireball";
        $speed = isset($args[1]) ? max(0, min(5, (float)$args[1])) : 2.0;
        $ride = isset($args[2]) && strtolower($args[2]) === "ride";

        if (!isset($this->projectileMap[$type])) {
            $sender->sendMessage(TextFormat::RED . "Invalid projectile type.");
            return;
        }

        $perm = "essentialsz.command.fireball." . strtolower($type);
        if (!$sender->hasPermission($perm)) {
            $sender->sendMessage(TextFormat::RED . "You don't have permission to use $type.");
            return;
        }

        $location = $sender->getLocation();
        $direction = $sender->getDirectionVector()->normalize()->multiply($speed);

        $projectileClass = $this->projectileMap[$type];
        $spawnLoc = Location::fromObject($location->add(0, $sender->getEyeHeight(), 0), $sender->getWorld(), $sender->getLocation()->getYaw(), $sender->getLocation()->getPitch());

        $projectile = new $projectileClass($spawnLoc, $sender);
        $sender->getWorld()->addEntity($projectile);
        $projectile->setMotion($direction);

        if ($ride) {
            $projectile->addPassenger($sender);
        }

        $sender->sendMessage(TextFormat::GREEN . "Launched $type!");
    }
}*/
