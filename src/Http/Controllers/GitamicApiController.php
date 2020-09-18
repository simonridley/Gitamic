<?php

namespace SimonHamp\Gitamic\Http\Controllers;

use SimonHamp\Gitamic\Contracts\SiteRepository;

class GitamicApiController
{
    public function status(SiteRepository $git)
    {
        $untracked = $git->getUntrackedFiles();
        $staged = $git->getStagedFiles();

        $meta = [
            'untracked_count' => $untracked->count(),
            'staged_count' => $staged->count(),
        ];

        return response()->json([
            'untracked' => $untracked->all(),
            'staged' => $staged->all(),
            'meta' => $meta
        ]);
    }

    public function actions($type)
    {
        return response()->json([
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
