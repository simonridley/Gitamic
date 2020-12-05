<?php

namespace SimonHamp\Gitamic;

use Illuminate\Support\Str;
use Statamic\Entries\Entry;
use Statamic\Facades\Stache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Gitonomy\Git\Diff\File as GitFile;
use Barryvdh\Debugbar\Facade as Debugbar;
use Gitonomy\Git\Repository as GitRepository;

class Repository implements Contracts\SiteRepository
{
    protected $repo;

    public function __construct($path)
    {
        $this->repo = new GitRepository(base_path());
    }

    public function getFilesOfType($type): Collection
    {
        $method = Str::camel("get_{$type}_files");

        return $this->$method();
    }

    public function getUnstagedFiles(): Collection
    {
        return $this->getUntrackedFiles()->merge($this->getPendingFiles())->keyBy('id');
    }

    public function getUntrackedFiles(): Collection
    {
        return collect($this->repo->getWorkingCopy()->getUntrackedFiles())
            ->transform(function ($relative_path, $id) {
                return $this->getFileDetails($relative_path, $id);
            });
    }

    public function getStagedFiles(): Collection
    {
        return collect($this->repo->getWorkingCopy()->getDiffStaged()->getFiles())
            ->transform(function (GitFile $file, $id) {
                return $this->getFileDetails($file->getName(), $id);
            });
    }

    public function getPendingFiles(): Collection
    {
        return collect($this->repo->getWorkingCopy()->getDiffPending()->getFiles())
            ->transform(function (GitFile $file, $id) {
                return $this->getFileDetails($file->getName(), $id);
            });
    }

    public function add($files, $args = [])
    {
        $args = $files + $args;
        return $this->repo->run('add', $args);
    }

    public function remove($files, $args = [])
    {
        $args = $files + $args + ['--cached'];
        return $this->repo->run('rm', $args);
    }

    protected function getFileDetails($relative_path, $id): Collection
    {
        $path = base_path($relative_path);
        $extension = '.' . File::extension($path);
        $last_modified = Carbon::createFromTimestamp(File::lastModified($path))->format('Y-m-d H:i:s');
        $type = File::type($path);
        $size = File::size($path);
        $is_content = Str::startsWith($path, app('filesystems.paths.content'));
        $entry_path = Str::replaceFirst('content' . DIRECTORY_SEPARATOR, '', $relative_path);
        $collection = explode(DIRECTORY_SEPARATOR, $entry_path)[1];
        $edit_url = DIRECTORY_SEPARATOR
            . config('statamic.cp.route')
            . DIRECTORY_SEPARATOR
            . Str::replaceLast($extension, '', $entry_path);

        $is_entry = false;
        try {
            /** @var Entry $entry */
            $entry = Stache::store("entries::$collection")->makeItemFromFile($path, File::get($path));
            $is_entry = true;
            $edit_url = $entry->editUrl();
        } catch (\Exception | \ErrorException | \Error $e) {
            // Something went wrong, so it's not an entry
        }

        return collect(compact(
            'id', 'relative_path', 'path', 'last_modified', 'type', 'size', 'is_content', 'is_entry', 'edit_url'
        ));
    }
}
