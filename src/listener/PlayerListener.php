<?php

namespace MohamadRZ\EssentialsZ\listener;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\utils\PositionUtils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

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
}