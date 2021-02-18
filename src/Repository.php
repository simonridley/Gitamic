<?php

namespace SimonHamp\Gitamic;

use Illuminate\Support\Str;
use Statamic\Entries\Entry;
use Statamic\Facades\Stache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Gitonomy\Git\Diff\File as GitFile;
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

    public function stage($files, $args = [])
    {
        $args = array_merge(array_values($files), array_values($args));
        return $this->repo->run('add', $args);
    }

    public function unstage($files, $args = [])
    {
        $args = array_merge(array_values($files), array_values($args), ['--staged']);
        return $this->repo->run('restore', $args);
    }

    public function remove($files, $args = [])
    {
        $args = array_merge(array_values($files), array_values($args), []);
        return $this->repo->run('rm', $args);
    }

    public function commit($message)
    {
        return $this->repo->run('commit', ['-m ' . addslashes($message)]);
    }

    public function push()
    {
        return $this->repo->run('push');
    }

    public function upToDate(): bool
    {
        $this->repo->run('fetch', ['--all']);

        $status = $this->repo->run('status');

        return ! Str::contains($status, 'Your branch is behind')
            && ! Str::contains($status, 'Your branch is ahead');
    }

    protected function getFileDetails($relative_path, $id): Collection
    {
        $path = base_path($relative_path);
        $file = File::name($path);
        $extension = '.' . File::extension($path);
        $last_modified = Carbon::createFromTimestamp(File::lastModified($path))->format('Y-m-d H:i:s');
        $type = File::type($path);
        $size = File::size($path);
        $is_content = empty($file) ? false : Str::startsWith($path, app('filesystems.paths.content'));
        $entry_path = Str::replaceFirst('content' . DIRECTORY_SEPARATOR, '', $relative_path);

        // Replace taxonomy term path with the real deal
        $entry_path = preg_replace('#taxonomies/(.*?)/(.*)\.yaml#', 'taxonomies/$1/terms/$2/default', $entry_path);

        $clean_path = Str::replaceLast($extension, '', $entry_path);
        $edit_url = null;

        if ($is_content) {
            $edit_url = implode(DIRECTORY_SEPARATOR, [config('statamic.cp.route'), $clean_path]);
        }

        $collection = explode(DIRECTORY_SEPARATOR, $entry_path)[1] ?? null;

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
