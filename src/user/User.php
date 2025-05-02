<?php

namespace MohamadRZ\EssentialsZ\user;

namespace MohamadRZ\EssentialsZ\user;

use MohamadRZ\EssentialsZ\EssentialsZPlugin;
use MohamadRZ\EssentialsZ\utils\PositionUtils;
use pocketmine\player\Player;
use pocketmine\utils\Config;

class User
{
    private string $xuid;
    private string $username;
    private int $joinTime;
    private mixed $isJaild;
    private mixed $quitTime;
    private mixed $firstJoinTime;
    private mixed $lastPosition;
    /** @var ?Player */
    private ?Player $parent;
    /** @var array<string, mixed> */
    private array $tempData = [];

    public function __construct($xuid, $data)
    {
        $this->parent = null;
        $this->xuid = $xuid;
        $this->username = $data["username"];
        $this->joinTime = $data["joinTime"];
        $this->quitTime = $data["quitTime"];
        $this->firstJoinTime = $data["firstJoinTime"];
        $this->lastPosition = $data["lastPosition"] !== null ? PositionUtils::stringToPosition($data["lastPosition"]) : "";
        $this->isJaild = $data["isJailed"];
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getXuid(): string
    {
        return $this->xuid;
    }

    /**
     * @return int
     */
    public function getJoinTime(): int
    {
        return $this->joinTime;
    }

    /**
     * @return mixed
     */
    public function getFirstJoinTime(): mixed
    {
        return $this->firstJoinTime;
    }

    /**
     * @return mixed
     */
    public function getIsJaild(): mixed
    {
        return $this->isJaild;
    }

    /**
     * @return mixed
     */
    public function getLastPosition(): mixed
    {
        return $this->lastPosition;
    }

    /**
     * @return mixed
     */
    public function getQuitTime(): mixed
    {
        return $this->quitTime;
    }

    /**
     * @param mixed $firstJoinTime
     */
    public function setFirstJoinTime(mixed $firstJoinTime): void
    {
        $this->firstJoinTime = $firstJoinTime;
    }

    /**
     * @param mixed $isJaild
     */
    public function setIsJaild(mixed $isJaild): void
    {
        $this->isJaild = $isJaild;
    }

    /**
     * @param int $joinTime
     */
    public function setJoinTime(int $joinTime): void
    {
        $this->joinTime = $joinTime;
    }

    /**
     * @param mixed $lastPosition
     */
    public function setLastPosition(mixed $lastPosition): void
    {
        $this->lastPosition = $lastPosition;
    }

    /**
     * @param mixed $quitTime
     */
    public function setQuitTime(mixed $quitTime): void
    {
        $this->quitTime = $quitTime;
    }

    /**
     * @return Player|null
     */
    public function getParent(): ?Player
    {
        return $this->parent;
    }

    public function setSize(int $size): void
    {
        $this->getParent()?->setScale($size);
    }

    /**
     * @param Player|null $parent
     */
    public function setParent(?Player $parent): void
    {
        $this->parent = $parent;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setTempData(string $key, mixed $value): void
    {
        $this->tempData[$key] = $value;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getTempData(string $key): mixed
    {
        return $this->tempData[$key] ?? null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function hasTempData(string $key): bool
    {
        return array_key_exists($key, $this->tempData);
    }

    /**
     * @param string $key
     * @return void
     */
    public function unsetTempData(string $key): void
    {
        unset($this->tempData[$key]);
    }

    public function getData(): array
    {
        return [
            "xuid" => $this->xuid,
            "username" => $this->username,
            "joinTime" => $this->joinTime,
            "quitTime" => $this->quitTime,
            "firstJoinTime" => $this->firstJoinTime,
            "lastPosition" => $this->lastPosition,
            "isJaild" => $this->isJaild,
        ];
    }

    public function setFlyEnabled(bool $enabled): void
    {
        $this->getParent()?->setAllowFlight($enabled);
        $this->getParent()?->setFlying($enabled);
    }
}