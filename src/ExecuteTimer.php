<?php

namespace MohamadRZ\EssentialsZ;

class ExecuteTimer
{
    private array $times;

    public function __construct()
    {
        $this->times = [];
    }

    public function start(): void
    {
        $this->times = [];
        $this->mark("start");
    }

    public function mark(string $label): void
    {
        if (count($this->times) > 0 || $label === "start") {
            $this->times[] = new ExecuteRecord($label, microtime(true));
        }
    }

    public function end(): string
    {
        $output = "execution time: ";
        $time0 = 0;
        $time1 = 0;
        $time2 = 0;
        $duration = 0;

        foreach ($this->times as $pair) {
            $mark = $pair->getMark();
            $time2 = $pair->getTime();
            if ($time1 > 0) {
                $duration = ($time2 - $time1) * 1000;
                $output .= $mark . ": " . number_format($duration, 3) . "ms - ";
            } else {
                $time0 = $time2;
            }
            $time1 = $time2;
        }
        $duration = ($time1 - $time0) * 1000;
        $output .= "Total: " . number_format($duration, 3) . "ms";

        $this->times = [];
        return $output;
    }
}

class ExecuteRecord
{
    private string $mark;
    private float $time;

    public function __construct(string $mark, float $time)
    {
        $this->mark = $mark;
        $this->time = $time;
    }

    public function getMark(): string
    {
        return $this->mark;
    }

    public function getTime(): float
    {
        return $this->time;
    }
}
