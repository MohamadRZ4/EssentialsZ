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
    private mixed $nickname;
    /**
     * @var ?Player
     */
    private ?Player $parent;

    public function __construct($xuid, $data)
    {
        $this->parent = null;
        $this->xuid = $xuid;
        $this->username = $data["username"];
        $this->joinTime = $data["joinTime"];
        $this->quitTime = $data["quitTime"];
        $this->firstJoinTime = $data["firstJoinTime"];
        $this->lastPosition = PositionUtils::stringToPosition($data["lastPosition"]);
        $this->isJaild = $data["isJaild"];
        $this->nickname = $data["nickname"];
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
     * @return mixed
     */
    public function getNickname(): mixed
    {
        return $this->nickname;
    }

    /**
     * @param mixed $nickname
     */
    public function setNickname(mixed $nickname): void
    {
        $this->nickname = $nickname;
        $this->getParent()?->setDisplayName($this->getNickname());
    }

    /**
     * @return Player|null
     */
    public function getParent(): ?Player
    {
        return $this->parent;
    }

    /**
     * @param Player|null $parent
     */
    public function setParent(?Player $parent): void
    {
        $this->parent = $parent;
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
            "nickname" => $this->nickname,
        ];
    }
}