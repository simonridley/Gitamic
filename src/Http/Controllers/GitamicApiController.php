<?php

namespace SimonHamp\Gitamic\Http\Controllers;

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
            'meta' => $meta
        ]);
    }

    public function actions($type)
    {
        switch ($type)
        {
            case 'unstaged':
                return response()->json([
                    [
                        'title' => 'Stage',
                        'handle' => 'add',
                        'buttonText' => 'Stage',
                        'description' => 'Are you sure?',
                        'fields' => ['path'],
                    ],
                    [
                        'title' => 'Ignore',
                        'handle' => 'ignore',
                        'buttonText' => 'Add to .gitignore file',
                        'description' => 'Are you sure?',
                        'fields' => ['path'],
                    ],
                ]);
            case 'staged':
                return response()->json([
                    [
                        'title' => 'Unstage',
                        'handle' => 'remove',
                        'buttonText' => 'Unstage',
                        'description' => 'Are you sure?',
                        'fields' => ['path'],
                    ],
                ]);
        }
    }

    public function doAction(SiteRepository $git, $type)
    {
        $action = request()->input('action');
        $selections = request()->input('selections');

        $files = $git->getFilesOfType($type)->only($selections)->map(function ($file) {
            return $file->get('path');
        });

        $output = $git->$action($files->all());

        return response()->json(['action' => 'getStatus']);
    }
}
