<?php

namespace SimonHamp\Gitamic;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Statamic\Entries\Entry;
use Statamic\Facades\Stache;
use Gitonomy\Git\Repository as GitRepository;


class Repository implements Contracts\SiteRepository
{
    protected $repo;

    public function __construct($path)
    {
        $this->repo = new GitRepository(base_path());
    }

    public function getUntrackedFiles(): Collection
    {
        return collect($this->repo->getWorkingCopy()->getUntrackedFiles())
            ->transform(function ($relative_path, $id) {
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

                return compact('id', 'relative_path', 'path', 'last_modified', 'type', 'size', 'is_content', 'edit_url');
            });
    }

    public function getStagedFiles(): Collection
    {
        return collect($this->repo->getWorkingCopy()->getDiffStaged()->getFiles())
            ->transform(function ($file, $id) {
                return $file;

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

                return compact('id', 'relative_path', 'path', 'last_modified', 'type', 'size', 'is_content', 'edit_url');
            });
    }
}
