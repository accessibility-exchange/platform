# The Accessibility Exchange

[![Project license](https://badgen.net/github/license/accessibility-exchange/platform)](https://github.com/accessibility-exchange/platform/releases/latest)
[![Latest release](https://badgen.net/github/release/accessibility-exchange/platform)](https://github.com/accessibility-exchange/platform/releases/latest)
[![Check status](https://badgen.net/github/checks/accessibility-exchange/platform/dev)](https://github.com/accessibility-exchange/platform/actions)
[![Code coverage](https://badgen.net/codecov/c/github/accessibility-exchange/platform)](https://codecov.io/gh/accessibility-exchange/platform/)
[![Localization status](https://badges.crowdin.net/tae/localized.svg)](https://crowdin.com/project/tae)

The Accessibility Exchange is a two-year initiative managed by the
[Institute for Research and Development on Inclusion and Society (IRIS)](https://irisinstitute.ca/) that sets out to
create an online platform which will support processes where people with disabilities have the power to make sure that policies,
programs, and services by federally regulated organizations are accessible to them and respect their human rights. Current
consultation processes are built on a foundation of systemic ableism—they lack accountability, follow-through, and don't
honour the expertise of people with disabilities.

The Accessibility Exchange platform is co-designed and developed by the [Inclusive Design Research Centre](https://idrc.ocadu.ca/)
at [OCAD University](https://ocadu.ca).

## Technical Details

The platform is built as a progressive web application using the [Laravel 10](https://laravel.com/docs/10.x) framework.

## Installation

For general deployment information, please see the Laravel 10.x [deployment documentation](https://laravel.com/docs/10.x/deployment).

The platform requires the following:

-   [PHP](https://www.php.net/supported-versions.php) >= 8.1 with [required extensions](https://laravel.com/docs/10.x/deployment#server-requirements)
-   [MySQL](https://dev.mysql.com/downloads/) >= 5.7
-   [Composer](https://getcomposer.org) >= 2.0
-   [Node](https://nodejs.org) >= 18

Optionally you may wish to install [NVM](https://github.com/nvm-sh/nvm) to make node version management easier.

The deployment process should follow all the recommended [optimization processes](https://laravel.com/docs/10.x/deployment#optimization).

## Development environments

In development environments, a deployment should be followed by running a fresh migration and the development database seeder:

```bash
php artisan migrate:fresh --seeder DevSeeder
```

_**NOTE:** This will overwrite all existing database tables._

## Production environments

In production environments, a deployment should be followed by running all available migrations:

```bash
php artisan migrate
```

## Development

Local development uses [Laravel Herd](https://herd.laravel.com/docs/1/getting-started/about-herd).

1. Install [Herd](https://herd.laravel.com).
2. Install [Xdebug](https://herd.laravel.com/docs/1/advanced-usage/xdebug) or [PCOV](https://herd.laravel.com/docs/1/advanced-usage/additional-extensions) for code coverage.
3. Fork and clone the project repository (easiest with the [Github CLI](https://cli.github.com/)):

    ```bash
    gh repo fork accessibility-exchange/platform --clone
    cd platform
    ```

4. Create a `.env` file from the included example file:

    ```bash
    cp .env.example .env
    ```

    Then, change the `APP_ENV` value to `local`:

    ```dotenv
    APP_ENV=local
    ```

5. Generate an encryption key for [CipherSweet](https://github.com/spatie/laravel-ciphersweet):

    ```bash
    openssl rand -hex 32
    ```

    Add it to your `.env` file:

    ```dotenv
    CIPHERSWEET_KEY="<your key>"
    ```

6. Install Composer and NPM dependencies:

    ```bash
    # install composer dependencies
    composer install

    # To use the version of npm specified in .nvmrc.
    # requires https://github.com/nvm-sh/nvm
    nvm use

    # install node dependencies
    npm ci
    ```

7. Generate an application key:

     ```bash
     php artisan key:generate
     ```

8. Create a database for development and one for running tests:

    ```bash
    mysql -uroot -e "create database accessibilityexchange;"
    mysql -uroot -e "create database tae-test;"
    ```

9. Run the required database migrations:

     ```bash
     php artisan migrate
     ```

10. Download the application fonts:

    ```bash
    php artisan google-fonts:fetch
    ```

11. Tell Herd to serve the application:

      ```bash
      herd link
      ```

12. Install [Mailpit](https://github.com/axllent/mailpit) so that you can access transactional email from the platform:

    ```bash
    brew install mailpit
    brew services start mailpit
    ```

    Then, make sure that your `.env` file contains the following values:

    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=127.0.0.1
    MAIL_PORT=1025
    ```

    You will now be able to access mail that the platform sends by visiting http://127.0.0.1:8025 or http://localhost:8025. For more information and additional configuration options, [read the Mailpit documentation](https://github.com/axllent/mailpit).

For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/10.x). Here's an overview
of how some key tasks can be carried out using Valet:

- [Composer](https://getcomposer.org) commands may be executed by using `composer <command>`.
- [NVM](https://github.com/nvm-sh/nvm) commands may be executed by using `nvm <command>`.
- [NPM](https://docs.npmjs.com/cli) commands may be executed by using `npm <command>`.
- [Artisan](https://laravel.com/docs/10.x/artisan) commands may be executed by using `php artisan <command>`.

### Running tests

The project uses [Pest](http://pestphp.com) for testing. For more information about testing Laravel, [read the documentation](https://laravel.com/docs/10.x/testing).

### Development workflow

- This project uses [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/), enforced by [commitlint](https://commitlint.js.org/).
    All commit messages and pull request titles must follow these standards.
- The [`dev`](https://github.com/accessibility-exchange/platform/tree/dev) branch contains features
    that have been prototyped and gone through one or more co-design sessions.
- Feature development must take place in a fork, in a branch based on the `dev` branch. Feature branches
    must be named according to the format `feat/<feature>`.
- Before opening a pull request, developers should run `composer format && composer analyze && php artisan test --coverage` to ensure that their code is properly formatted, does not cause static analysis errors, and passes tests. Depending on the code coverage, more tests may need to be written to ensure that code coverage does not drop.
- Once a feature is ready to merge into `dev`, the merge must be performed using a [squash commit](https://docs.github.com/en/github/collaborating-with-pull-requests/incorporating-changes-from-a-pull-request/about-pull-request-merges#squash-and-merge-your-pull-request-commits).
- The [`production`](https://github.com/accessibility-exchange/platform/tree/production) branch contains refined
  features that are considered production-ready.
- Prereleases must be tagged from the `dev` branch.
- Releases must be tagged from the `production` branch.

### Working with markdown

In other Laravel applications you may see methods such as [`Str::markdown()`](https://laravel.com/docs/10.x/helpers#method-str-markdown)
and [`Str::inlineMarkdown()`](https://laravel.com/docs/10.x/helpers#method-str-inline-markdown) used. In general we attempt
to avoid using these methods and instead favour using the provided `safe_markdown()` and `safe_inlineMarkdown` helpers. These
methods will escape HTML used in a markdown string, strip unsafe links, and escape replacements. They are also tied into
the localization system, and will populate their strings into the string packages, just as `__()` would.

The `safe_markdown()` and `safe_inlineMarkdown()` methods should not be called with `{!!  !!}` as their output will safely
pass through `{{  }}`. This provides an additional layer of protection in cases where you may have mixed types output
to the template or make a mistake.

```php
{{ safe_markdown('**hello :location**', ['location' => '**World**']) }}
{{-- <p><strong>Hello **World**</strong></p> --}}
```

If you need to unescape a replacement you can use a `!` at the start of the placeholder name (e.g. `:!placeholder`).

```php
{{ safe_markdown('**hello :!location**', ['location' => '<em>World</em>']) }}
{{-- <p><strong>Hello <em>World</em></strong></p> --}}
```

There are some cases where you may still wish to use the `Str` markdown helpers, such as when handling admin input (e.g.
resource collection information). In these special cases, make sure to call the Laravel markdown helpers with the
`config('markdown')` argument to escape HTML and remove unsafe links.

```php
{!! Str::markdown('<em>Hello **World**</em>', config('markdown')) !!}
{{-- <p>&lt;em&gt;Hello <strong>World</strong>&lt;/em&gt;</p> --}}
```

#### Mail notification templates

By default Laravel supports a mixture of markdown and HTML in mail notification templates. However, in this application
we've modified the templates to only support HTML. This aligns the behaviour of the mail templates with that of the site's
blade templates.

## Supported application environments

The application environment is set by specifying the `APP_ENV` environment variable. See [Environment Configuration](https://laravel.com/docs/10.x/configuration#environment-configuration) docs for more information.

| `APP_ENV` | Description |
| --- | ---- |
| local | For local development; i.e. on a developers machine. |
| dev | For nightly builds build and deployed from the "dev" branch. |
| staging | For deploys from the "staging" branch. Used to test changes in a production like environment before going live. |
| production | For deploys from the "production" branch. The live production released code. |

Amongst other things, the application environment can be used to prevent tasks from running or requiring confirmation before running, e.g. in production running `php artisan migrate:fresh` requires confirmation. It can also be used to limit output in blade templates using the `@env()` or `@production` directives (See: [Environment Directives](https://laravel.com/docs/10.x/blade#environment-directives) docs)

## Custom Artisan Commands

### db:refresh

_**NOTE:** Excluded from running during tests or on production._

#### Purpose

* Backs up filament tables to JSON files that are included in the config **backup.filament_seeders.tables**. [optional, run if `--backup` is used]
* Runs a fresh migration that will truncate all tables and run all migrations.
* Runs the **DevSeeder** seeder.

#### Options

| option | Description |
| --- | ---- |
| `--backup` | Whether to Backs up filament tables to JSON files that are included in the config **backup.filament_seeders.tables**. |

### deploy:global

#### Purpose

Runs other console commands in order and should be commands that are only run once across multiple deploying container.

### deploy:local

#### Purpose

Runs other console commands in order and should be commands that should be run on each deploying container.

### notifications:remove:old

#### Purpose

Removes older notifications.

#### Options

| option | Description |
| --- | ---- |
| `--days=` | _*required_ - The number of days which notifications older than will be deleted from the notifications database table. |

### db:seed:backup

#### Purpose

Takes filament tables and backs them up to JSON files so that they can be used by seeders to repopulate the tables.

#### Options

| option | Description |
| --- | ---- |
| `--a\|all` | Whether to run through all available backups/restores in config. |

* When used by itself it backups all the tables found in config **backup.filament_seeders.tables**.
* When used with `--restore` option it will run all seeder classed found in **backup.filament_seeders.classes**.

---

| option | Description |
| --- | ---- |
| `--from` | Override the environment tag that is being handled. |

* Available environments are found in config **backup.filament_seeders.environments**.
* When used by default backup it will tag the json files with environment tag.
* When used with `--delete` option will change the files being deleted to those tagged with the specific environment.
* When used with `--restore` option will restore from files tagged with the environment that you specify.

---

| option | Description |
| --- | ---- |
| `--remove` | Remove backed up files |

* When used by itself it will remove all of the backed up JSON files found in **backup.filament_seeders.tables**.
* When used with `--table=` it will remove only the JSON files related to the table(s) (can pass multiple values, each needs to be prefixed by `--table=`.)

---

| option | Description |
| --- | ---- |
| `--restore` | Restore the filament table |

* Will not run during tests or on production.
* When used without options it will prompt with available classes found in **backup.filament_seeders.classes**, user can choose multiple by separating choices by commas and will run the chosen seeder classes.
* When used with `--all` option it will run all seeder classes found in **backup.filament_seeders.classes**.

---

| option | Description |
| --- | ---- |
| `--truncate` | Whether to truncate the table before seeding it. |

* When used with `--restore` it will truncate the tables before seeding them.

---

| option | Description |
| --- | ---- |
| `--t\|table=` | Create/remove specific table file |

* When used by itself it will backup the specified table(s) to a JSON file (can pass multiple values, each needs to be prefixed by `--table=`.)
* When used with `--remove` it will remove only the JSON files related to the table(s) (can pass multiple values, each needs to be prefixed by `--table=`.)


## License

The Accessibility Exchange platform is available under the [BSD 3-Clause License](https://github.com/accessibility-exchange/platform/blob/main/LICENSE.md).
