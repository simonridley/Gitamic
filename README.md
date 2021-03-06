# Gitamic
Gitamic adds an interactive git UI to Statamic so that you can have more control over your commits. It's great for more
complex sites that rely on live-publishing changes from your Statamic CP back to your git repository.

## Features
- See changes to files (a.k.a. your 'working tree')
- Stage and unstage changes
- Commit changes
- Push changes

And even more features are coming as Gitamic is under active development!

**COMING SOON**
- Pro version with even more powerful features!

## Installation and Setup
### Statamic CP
Using the Statamic CP is the easiest way to install Gitamic. Under **Tools > Addons**, use the search to find 'Gitamic'
and install. All done.

### Composer
You can also install via Composer:

```bash
composer require simonhamp/gitamic
```

Once Composer has finished downloading the necessary dependencies, you'll need to update your application's
`config/statamic/editions.php` to indicate that you're using the `basic` edition of Gitamic:

```php
    'addons' => [
        'simonhamp/gitamic' => 'basic',
    ],
```

Gitamic is a premium add-on, so a license is required for all versions. You may download and install it for free to
trial it, but you must purchase a license in order to use this add-on in production.

You can purchase a license from the [Statamic Marketplace](https://statamic.com/addons/simonhamp/gitamic).

Once Gitamic is installed, you can use it immediately. It relies on wherever your site is running to contain a git
repository.

#### Statamic Pro
Gitamic **does not** require Statamic Pro, nor [Statamic's Git integration](https://statamic.dev/git-integration) to be
enabled. If however you do have Statamic's Git integration enabled, you may find it better to _disable_ its 'automatic
commits' feature so that Statamic doesn't automatically commit every change, leaving you to do it via Gitamic, if that's
what you're into.

To do this you can set the following key in your `.env` file:

```dotenv
STATAMIC_GIT_AUTOMATIC=false
```

## Bugs and Feature Requests
If you experience any problems with Gitamic or would like to make a feature request, please
[raise an issue](https://github.com/simonhamp/Gitamic/issues) using the appropriate template.

## Security
If you discover any security related issues, please **do not** raise an issue on GitHub. Email simon.hamp@me.com
instead.

Please note that I will not respond to feature requests or bug reports at this email address.

## License
See [LICENSE](https://github.com/simonhamp/Gitamic/blob/main/LICENSE.md)
