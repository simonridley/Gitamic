<?php

namespace SimonHamp\Gitamic\Http\Controllers;

use Barryvdh\Debugbar\Facade as Debugbar;
use Gitonomy\Git\Repository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Statamic\Entries\Entry;
use Statamic\Facades\Stache;
use Statamic\Support\Str;

class GitamicApiController
{
    public function status()
    {
        $repo = new Repository(base_path());

        $working = $repo->getWorkingCopy();

        Debugbar::info($working->getUntrackedFiles());
        Debugbar::info($working->getDiffStaged());

        $untracked = collect($working->getUntrackedFiles())
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

        $meta = [
            'untracked_count' => $untracked->count(),
        ];

        return json_encode(['untracked' => $untracked->all(), 'meta' => $meta]);
    }

    public function actions($type)
    {
        return json_encode([
            [
                'title' => 'Add to Git',
                'handle' => 'gitadd',
                'buttonText' => 'Add to Git',
                'description' => 'Are you sure?',
                'fields' => ['path'],
            ],
            [
                'title' => 'Gitignore',
                'handle' => 'gitignore',
                'buttonText' => 'Add to .gitignore file',
                'description' => 'Are you sure?',
                'fields' => ['path'],
            ]
        ]);
    }
}
