<?php

namespace MohamadRZ\EssentialsZ;

/*use IvanCraft623\RankSystem\RankSystem;*/
use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\settings\SettingPaths;
use MohamadRZ\EssentialsZ\utils\PositionUtils;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\chat\LegacyRawChatFormatter;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PlayerListener implements Listener
{

    private EssentialsZPlugin $ess;

    public function __construct(EssentialsZPlugin $ess)
    {
        $this->ess = $ess;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $this->ess->getUserManager()->getUser($player);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $position = PositionUtils::positionToString($player->getPosition());
        $this->ess->getUserManager()->getUser($player)->setLastPosition($position);
        $this->ess->getUserManager()->save($player->getXuid());
        $this->ess->getUserManager()->unload($player->getXuid());
    }

    public function onPlayerMove(PlayerMoveEvent $event): void
    {
        $player = $event->getPlayer();
        $user = $this->ess->getUserManager()->getUser($player);
        $cfg = $this->ess->getSettings();

        if (true) {
            $currentWorld = $player->getWorld()->getFolderName();
            $blacklistedWorlds = $cfg->getSettings(SettingPaths::BLACKLISTED_WORLDS);
            $allowedWorlds = $cfg->getSettings(SettingPaths::ALLOWED_WORLDS);

            if (!is_array($blacklistedWorlds)) {
                $blacklistedWorlds = [];
            }
            if (!is_array($allowedWorlds)) {
                $allowedWorlds = [];
            }

            if (in_array(strtolower($currentWorld), array_map("strtolower", $blacklistedWorlds))) {
                if ($player->getScale() != 1) {
                    $user->setTempData("previous_size", $player->getScale());
                    $player->setScale(1);
                }
            } elseif (!empty($allowedWorlds) && in_array(strtolower($currentWorld), array_map("strtolower", $allowedWorlds))) {
                if ($user->hasTempData("previous_size")) {
                    $previousSize = $user->getTempData("previous_size");
                    $player->setScale($previousSize);
                    $user->unsetTempData("previous_size");
                }
            } else {
                if ($user->hasTempData("previous_size")) {
                    $previousSize = $user->getTempData("previous_size");
                    $player->setScale($previousSize);
                    $user->unsetTempData("previous_size");
                }
            }

        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            $user = $this->ess->getUserManager()->getUser($entity);
            if ($user->hasTempData("isGodMode")) {
                $event->cancel();
            }
        }
    }

    public function onCommand(CommandEvent $event): void {
        $player = $event->getSender();
        $command = $event->getCommand();

        if (!$player instanceof Player) return;

        if ($player->hasPermission("essentialsz.command.bypass.cooldown")) {

            $user = EssentialsZPlugin::getInstance()->getUserManager()->getUser($player);
            if ($user->getTempData("isSocialSpyEnabled")) {
                foreach (EssentialsZPlugin::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer) {
                    $onlineUser = EssentialsZPlugin::getInstance()->getUserManager()->getUser($onlinePlayer);
                    if ($onlineUser->getTempData("isSocialSpyEnabled") && $onlinePlayer !== $player) {
                        $onlinePlayer->sendMessage("[" . $player->getDisplayName() . "] used command: " . $command);
                    }
                }
            }

            return;
        }

        $config = EssentialsZPlugin::getInstance()->getConfig();
        $cooldown = $this->getCooldownForCommand($command, $config);

        if ($cooldown === null) {
            return;
        }

        $playerName = $player->getName();
        $cooldowns = EssentialsZPlugin::getInstance()->getDataFolder() . "cooldowns.json";

        if (!file_exists($cooldowns)) {
            file_put_contents($cooldowns, json_encode([]));
        }

        $cooldownsData = json_decode(file_get_contents($cooldowns), true);

        if (isset($cooldownsData[$playerName][$command]) && time() < $cooldownsData[$playerName][$command]) {
            $timeLeft = $cooldownsData[$playerName][$command] - time();
            $player->sendMessage(TextFormat::RED . "You must wait $timeLeft seconds before using this command again.");
            $event->cancel(true);
            return;
        }

        $user = EssentialsZPlugin::getInstance()->getUserManager()->getUser($player);
        if ($user->getTempData("isSocialSpyEnabled")) {
            foreach (EssentialsZPlugin::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer) {
                $onlineUser = EssentialsZPlugin::getInstance()->getUserManager()->getUser($onlinePlayer);
                if ($onlineUser->getTempData("isSocialSpyEnabled") && $onlinePlayer !== $player) {
                    $onlinePlayer->sendMessage("[" . $player->getDisplayName() . "] used command: " . $command);
                }
            }
        }

        $cooldownsData[$playerName][$command] = time() + $cooldown;
        file_put_contents($cooldowns, json_encode($cooldownsData));
    }
    private function getCooldownForCommand(string $command, $config): ?int {
        if (isset($config->get("commands_cooldown")[$command]["cooldown"])) {
            return $config->get("commands_cooldown")[$command]["cooldown"];
        }
        return null;
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getEntity();

        if ($player instanceof Player) {
            if ($player->isFlying() && ($player->isSurvival() || $player->isAdventure())) {
                $player->setAllowFlight(false);
                $player->setFlying(false);
            }
        }
    }


    public function onChat(PlayerChatEvent $event): void {
/*        $player = $event->getPlayer();
        $rankSession = RankSystem::getInstance()->getSessionManager()->get($player);

        $nickname = $this->ess->getUserManager()->getUser($player)->getNickname();
        if ($rankSession->isInitialized()) {
            $formatted = $rankSession->getChatFormatter()->format($nickname, $event->getMessage());
            $event->setFormatter(new LegacyRawChatFormatter($formatted));
        } else {
            $event->setFormatter(new LegacyRawChatFormatter($nickname . ": " . $event->getMessage()));
        }*/
    }
}