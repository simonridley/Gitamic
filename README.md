# Gitamic
Gitamic adds an interactive git UI to Statamic so that you can have more control over your commits. It's great for more
complex sites that rely on live-publishing changes from your Statamic CP back to your git repository.

## Setup
Once Gitamic is installed you can use it immediately. It relies on wherever your site is running to contain a git
repository.

Gitamic **does not** require Statamic Pro, nor [Statamic's Git integration](https://statamic.dev/git-integration) to be
enabled. If however you do have Statamic's Git integration enabled, you may find it better to *disable* its automatic
commits feature.

To do this you can set the following key in your `.env` file:

```
STATAMIC_GIT_AUTOMATIC=false
```

## Security
If you discover any security related issues, please email simon.hamp@me.com.

## License
Gitamic is proprietary software. A single purchase entitles you to use Gitamic on one Statamic site.

Unauthorized copying, distribution or sale of this package, in whole or part, via any medium is strictly prohibited

Copyright (C) 2021 Simon Hamp - All Rights Reserved
