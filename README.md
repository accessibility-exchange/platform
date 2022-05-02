# The Accessibility Exchange

[![Project license](https://badgen.net/github/license/accessibility-exchange/platform)](https://github.com/accessibility-exchange/platform/releases/latest)
[![Latest release](https://badgen.net/github/release/accessibility-exchange/platform)](https://github.com/accessibility-exchange/platform/releases/latest)
[![Check status](https://badgen.net/github/checks/accessibility-exchange/platform/dev)](https://github.com/accessibility-exchange/platform/actions)
[![Code coverage](https://badgen.net/codecov/c/github/accessibility-exchange/platform)](https://codecov.io/gh/accessibility-exchange/platform/)
[![Localization status](https://badges.crowdin.net/accessibility-in-action/localized.svg)](https://crowdin.com/project/accessibility-in-action)

The Accessibility Exchange is a two-year initiative managed by the
[Institute for Research and Development on Inclusion and Society (IRIS)](https://irisinstitute.ca/) that sets out to
create an online platform which will support processes where people with disabilities have the power to make sure that policies,
programs, and services by federally regulated organizations are accessible to them and respect their human rights. Current
consultation processes are built on a foundation of systemic ableism—they lack accountability, follow-through, and don't
honour the expertise of people with disabilities.

The Accessibility Exchange platform is co-designed and developed by the [Inclusive Design Research Centre](https://idrc.ocadu.ca/)
at [OCAD University](https://ocadu.ca).

## Technical Details

The platform is built as a progressive web application using the [Laravel 8](https://laravel.com/docs/8.x) framework.

## Installation

For general deployment information, please see the Laravel 8.x [deployment documentation](https://laravel.com/docs/8.x/deployment).

The platform requires the following:

-   [PHP](https://www.php.net/supported-versions.php) >= 8.0 with [required extensions](https://laravel.com/docs/8.x/deployment#server-requirements)
-   [MySQL](https://dev.mysql.com/downloads/) >= 5.7
-   [Composer](https://getcomposer.org) >= 2.0

The deployment process should follow all the recommended [optimization processes](https://laravel.com/docs/8.x/deployment#optimization).

## Development environments

In development environments, a deployment should be followed by running a fresh migration and the development database seeder:

```bash
php artisan migrate:fresh --force
php artisan db:seed DevSeeder --force
```

**NOTE: This will overwrite all existing database tables.**

## Production environments

In production environments, a deployment should be followed by running all available migrations:

```bash
php artisan migrate
```

## Development

Local development uses either the [Laravel Sail](https://laravel.com/docs/9.x/sail) Docker environment or [Laravel Valet](https://laravel.com/docs/9.x/valet).

### Local development setup using Laravel Sail

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Add an alias to your shell [as described here](https://laravel.com/docs/9.x/sail#configuring-a-bash-alias).
3. Fork and clone the project repository (easiest with the [Github CLI](https://cli.github.com/)):

    ```bash
    gh repo fork accessibility-exchange/platform --clone
    cd platform
    ```

4. Create a `.env` file from the included example file:

    ```bash
    cp .env.example .env
    ```

5. Start the development environment by running the following command from within the project directory:

    ```bash
    sail up -d
    ```

6. Install Composer and NPM dependencies:

    ```bash
    sail composer install
    sail npm install
    ```
    
7. Generate an application key:

    ```bash
    sail artisan key:generate
    ```

8. Run the required database migrations:

    ```bash
    sail artisan migrate
    ```
For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/9.x). Here's an overview
of how some key tasks can be carried out using Sail:

- [Composer](https://getcomposer.org) commands may be executed by using `sail composer <command>`.
- [NPM](https://docs.npmjs.com/cli/v7) commands may be executed by using `sail npm <command>`.
- [Artisan](https://laravel.com/docs/8.x/artisan) commands may be executed by using `sail artisan <command>`.

### Local development setup using Laravel Valet

1. Install [Homebrew](https://brew.sh).
2. Install PHP 8.0 via Homebrew:
   
   ```bash
   brew install php@8.0
   ```
   
3. Install [Composer](https://getcomposer.org/).
4. Install Valet:

   ```bash
   composer global require laravel/valet
   valet install
   ```

5. Fork and clone the project repository (easiest with the [Github CLI](https://cli.github.com/)):

    ```bash
    gh repo fork accessibility-exchange/platform --clone
    cd platform
    ```

6. Create a `.env` file from the included example file:

    ```bash
    cp .env.example .env
    ```
   
7. Install Composer and NPM dependencies:

    ```bash
    composer install
    npm install
    ```

8. Generate an application key:

    ```bash
    php artisan key:generate
    ```
9. Create a database:

    ```bash
    mysql -uroot -e "create database accessibilityexchange;"
    ```

10. Run the required database migrations:

     ```bash
     php artisan migrate
     ``` 
   
11. Tell Valet to serve the application:

      ```bash
      valet link
      ```

For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/9.x). Here's an overview
of how some key tasks can be carried out using Valet:

- [Composer](https://getcomposer.org) commands may be executed by using `composer <command>`.
- [NPM](https://docs.npmjs.com/cli/v7) commands may be executed by using `npm <command>`.
- [Artisan](https://laravel.com/docs/8.x/artisan) commands may be executed by using `php artisan <command>`.

### Development workflow

-   This project uses [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/), enforced by [commitlint](https://commitlint.js.org/).
    All commit messages and pull request titles must follow these standards.
-   The [`dev`](https://github.com/accessibility-exchange/platform/tree/dev) branch contains refined features
    that have been prototyped and gone through one or more co-design sessions.
-   Feature refinement must take place in a feature branch forked from the `prototype` branch. Feature refinement branches
    must be named according to the format `feat/<feature>`. Once a refined feature is ready to merge into `dev`, the
    merge must be performed using a [squash commit](https://docs.github.com/en/github/collaborating-with-pull-requests/incorporating-changes-from-a-pull-request/about-pull-request-merges#squash-and-merge-your-pull-request-commits).
-   The [`main`](https://github.com/accessibility-exchange/platform/tree/main) branch contains refined features that
    are considered production-ready.
-   Prereleases must be tagged from the `dev` branch.
-   Releases must be tagged from the `main` branch.

## License

The Accessibility Exchange platform is available under the [BSD 3-Clause License](https://github.com/accessibility-exchange/platform/blob/main/LICENSE.md).
