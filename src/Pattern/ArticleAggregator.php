<?php

namespace App\Pattern;

/**
 * @class ArticleAggregator
 */
class ArticleAggregator
{
    private array $dbSources = array();
    private array $rssSources = array();

    public function addDatabase($database): void{
        $this->dbSources[] = $database;
    }

    public function getDatabaseSources(): array{
        return $this->dbSources;
    }

    public function addRss($rss): void{
        $this->rssSources[] = $rss;
    }

    public function getRssSources(): array{
        return $this->rssSources;
    }
}