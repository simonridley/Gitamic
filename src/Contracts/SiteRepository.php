<?php

namespace SimonHamp\Gitamic\Contracts;

use Illuminate\Support\Collection;

interface SiteRepository
{
    public function getFilesOfType($type): Collection;

    public function getUnstagedFiles(): Collection;

    public function getUntrackedFiles(): Collection;

    public function getStagedFiles(): Collection;

    public function getPendingFiles(): Collection;

    public function stage($files, $args = []);

    public function unstage($files, $args = []);

    public function remove($files, $args = []);

    public function commit($message);

    public function upToDate(): bool;
}
