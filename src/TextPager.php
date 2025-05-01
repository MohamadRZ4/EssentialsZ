<?php

namespace MohamadRZ\EssentialsZ;

class TextPager
{
    private string $title;
    private array $lines;
    private int $linesPerPage;

    public function __construct(string $title = "", array $lines = [], int $linesPerPage = 5)
    {
        $this->title = $title;
        $this->lines = $lines;
        $this->linesPerPage = $linesPerPage;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setLines(array $lines): void
    {
        $this->lines = $lines;
    }

    public function setLinesPerPage(int $count): void
    {
        $this->linesPerPage = $count;
    }

    public function getPage(int $page): array
    {
        $totalPages = $this->getTotalPages();

        if ($totalPages === 0) {
            return ["§cNo information available to display."];
        }

        $page = max(1, min($page, $totalPages));

        $start = ($page - 1) * $this->linesPerPage;
        $pageLines = array_slice($this->lines, $start, $this->linesPerPage);

        $header = "§7--- §b" . $this->title . " §7(Page $page/$totalPages) ---";
        $footer = "§7-------------------------";

        return array_merge([$header], $pageLines, [$footer]);
    }

    public function getTotalPages(): int
    {
        return (int)ceil(count($this->lines) / $this->linesPerPage);
    }
}
