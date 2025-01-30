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

-   [PHP](https://www.php.net/supported-versions.php) >= 8.2 with [required extensions](https://laravel.com/docs/10.x/deployment#server-requirements)
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

The application can also be run without the dev/test data but still needs to be seeded with the required data:

```bash
php artisan migrate:fresh --seed
```

_**NOTE:** This will overwrite all existing database tables._

## Production environments

In production environments, a deployment should be followed by running all available migrations:

```bash
php artisan migrate
```

If this is the first installation and there is no pre-existing data in the database the database must be seeded with:

```bash
php artisan db:seed
```

## Development

### Local Development Using Herd

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

8. Create the testing env file

    ```bash
    cp .env .env.testing
    ```

    Change the `APP_ENV` value to `local`:

    ```dotenv
    APP_ENV=testing
    ```

    Change the `DB_DATABASE` value to `tae-testing`:

    ```dotenv
    DB_DATABASE=tae-test
    ```

9. Create a database for development and one for running tests:
    ```bash
    mysql -uroot -e "create database accessibilityexchange;"
    mysql -uroot -e "create database tae-test;"
    ```
10. Run the required database migrations:
     ```bash
     php artisan migrate
     php artisan migrate --env=testing
     ```
11. Download the application fonts:
    ```bash
    php artisan google-fonts:fetch
    ```
12. Tell Herd to serve the application:
      ```bash
      herd link
      ```
13. Install [Mailpit](https://github.com/axllent/mailpit) so that you can access transactional email from the platform:
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
of how some key tasks can be carried out using Herd:
- [Composer](https://getcomposer.org) commands may be executed by using `composer <command>`.
- [NVM](https://github.com/nvm-sh/nvm) commands may be executed by using `nvm <command>`.
- [NPM](https://docs.npmjs.com/cli) commands may be executed by using `npm <command>`.
- [Artisan](https://laravel.com/docs/10.x/artisan) commands may be executed by using `php artisan <command>`.

Herd supports debuging via XDebug. The article "[Activating XDebug on Visual Studio Code & Laravel Herd](https://thomashysselinckx.medium.com/activating-xdebug-on-visual-studio-code-laravel-herd-cfd0553d26e0)" can help if you are having trouble getting it setup with VS Code.

### Local development using Docker and Nix  

#### Setup Instructions  
1. Install [Nix](https://nixos.org/download/) for your system.  
2. Run `nix-shell`.  
3. If you are wanting to run Docker, follow the steps for your platform:  
   - **Linux**: On Linux, there are added aliases `dstart` & `dstop` that will start and stop the Docker daemon, which runs using rootlesskit.  
     - When using rootless, ensure that it is set up and allowed to run on privileged ports. See: [Exposing Privileged Ports](https://github.com/rootless-containers/rootlesskit/blob/master/docs/port.md#exposing-privileged-ports).  
     - You will also want to change the socket path with the following command:  
       ```sh
       export DOCKER_HOST=unix:///run/user/1000/docker.sock
       ```  
   - **Other Systems**: You will need to have Docker installed and running.  

#### Docker Compose Aliases  
These aliases simplify working with `docker-compose` using the `docker-compose.local.yml` file:  

- `dc` → Shortcut for `docker-compose -f docker-compose.local.yml`  
- `dcbp` → Build the `platform.test` service: `docker-compose -f docker-compose.local.yml build platform.test`  
- `dcup` → Start services in detached mode: `docker-compose -f docker-compose.local.yml up -d`  
- `dcd` → Stop and remove containers: `docker-compose -f docker-compose.local.yml down`  
- `dil` → List Docker images: `docker image ls`  
- `dirm` → Remove Docker images: `docker image rm`  
- `dvl` → List Docker volumes: `docker volume ls`  
- `dvrm` → Remove Docker volumes: `docker volume rm`  

#### Kubernetes Aliases  
These aliases simplify working with `kubectl` in different namespaces:  

- `kcd` → Shortcut for `kubectl -n iris-accessibility-development`  
- `kcs` → Shortcut for `kubectl -n iris-accessibility-staging`  
- `kcp` → Shortcut for `kubectl -n iris-accessibility-production`  

#### Pod Flushing Functions  

##### `kflush` Function  
The `kflush` function executes Laravel deployment commands (`php artisan deploy:local` and `php artisan deploy:global`) inside running `app-` pods for a given namespace.  

###### Usage:  
```sh
kflush <namespace>
```  
Example:  
```sh
kflush development
```  
This will:  
1. Find all running pods with the `app-` prefix in the `iris-accessibility-<namespace>` namespace.  
2. Execute `php artisan deploy:local` in each pod.  
3. Execute `php artisan deploy:global` in the first matching pod.  

##### `kflushall` Function  
Flushes all `app-` pods in all environments (`development`, `staging`, `production`).  

###### Usage:  
```sh
kflushall
```  
This iterates through all environments and runs `kflush` for each.  

##### Namespace-Specific Flush Aliases  
For convenience, predefined aliases allow flushing without specifying the namespace:  

- `kdflush` → Runs `kflush development`  
- `ksflush` → Runs `kflush staging`  
- `kpflush` → Runs `kflush production`  

#### Environment Setup  
If the `.env` file does not exist, the script automatically generates it using `.env.local.template` and random secrets:  
- `CIPHERSWEET_KEY` (32-byte hex string)  
- `DB_PASSWORD` (16-byte hex string)  
- `DB_ROOT_PASSWORD` (24-byte hex string)  
- `REDIS_PASSWORD` (20-byte hex string)  
- `APP_KEY` (generated using `php artisan key:generate`)  
- `WWWUSER` (set to current user ID)  

Ensure `.env.local.template` is available before running the script.  

#### Rootless Docker Support  
For users running `dockerd-rootless`, the script provides:  
- Aliases:  
  ```sh
  alias dstart="dockerd-rootless&"
  alias dstop="pkill dockerd"
  ```  
- Instructions to set the correct Docker socket:  
  ```sh
  export DOCKER_HOST=unix://$XDG_RUNTIME_DIR/docker.sock
  ```  
- To allow privileged ports, run:  
  ```sh
  echo 1 | sudo tee /proc/sys/net/ipv4/ip_unprivileged_port_start
  ```  

#### Troubleshooting

**Changes are missing in the container**

- Rebuild the container and relaunch with the following command `dc build platform.test && dc up -d`.

**Cannot reach site using browser**

- Visit the site using the SSL proxy to make sure assets load [https://localhost](https://localhost).
- Check that all containers are up and running using the following command `docker ps -a` and check for container with the name `platform.test` and check the status column to see if it says **Up**.
- If it's not up then try to check logs to see if there is an error with the command `dc logs -f platform.test`.  This should help you resolve what might be missing.

### Running tests

The project uses [Pest](http://pestphp.com) for testing. For more information about testing Laravel, [read the documentation](https://laravel.com/docs/10.x/testing).

If you make changes to the database, you may need to run the migrations in the test database.

```bash
php artisan migrate --env=testing
```

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

### app:refresh-dev

#### Purpose

_**NOTE:** Does not run in the `production` environment._

Runs a development database refresh. Places the site in maintenance mode while the database is being refreshed and reseeded.

### seo:clear

#### Purpose

Removes the robots.txt and sitemap files.

### seo:generate

#### Purpose

Generates the robots.txt and sitemap files.

### seo:clear-robots

#### Purpose

Removes the robots.txt file.

### seo:generate-robots

#### Purpose

Generates the robots.txt file.

### seo:clear-sitemap

#### Purpose

Removes the sitemap file.

### seo:generate-sitemap

#### Purpose

Generates the sitemap file.

## License

The Accessibility Exchange platform is available under the [BSD 3-Clause License](https://github.com/accessibility-exchange/platform/blob/main/LICENSE.md).
