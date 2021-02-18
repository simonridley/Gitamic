<?php

namespace SimonHamp\Gitamic\Http\Controllers;

use Illuminate\Support\Str;
use SimonHamp\Gitamic\Contracts\SiteRepository;

class GitamicApiController
{
    public function status(SiteRepository $git)
    {
        $unstaged = $git->getUnstagedFiles();
        $staged = $git->getStagedFiles();

        $meta = [
            'unstaged_count' => $unstaged->count(),
            'staged_count' => $staged->count(),
        ];

        return response()->json([
            'unstaged' => $unstaged->all(),
            'staged' => $staged->all(),
            'meta' => $meta,
            'up_to_date' => $git->upToDate(),
        ]);
    }

    public function actions($type)
    {
        $method = Str::camel("get_{$type}_actions");
        return response()->json($this->{$method}());
    }

    public function doAction(SiteRepository $git, $type)
    {
        $action = request()->input('action');
        $selections = request()->input('selections');

        $files = $git->getFilesOfType($type)->only($selections)->map(function ($file) {
            return $file->get('path');
        });

        $git->$action($files->all());

        return response()->json(['action' => 'getStatus']);
    }

    public function commit(SiteRepository $git)
    {
        $result = $git->commit(request()->input('commit_message'));

        return response()->json(['result' => $result]);
    }

    protected function getUnstagedActions()
    {
        return [
            [
                'title' => 'Stage',
                'handle' => 'stage',
                'buttonText' => 'Stage',
                'fields' => ['path'],
            ],
        ];
    }

    protected function getStagedActions()
    {
        return [
            [
                'title' => 'Unstage',
                'handle' => 'unstage',
                'buttonText' => 'Unstage',
                'fields' => ['path'],
            ],
        ];
    }
}
