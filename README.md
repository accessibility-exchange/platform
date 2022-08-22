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

-   [PHP](https://www.php.net/supported-versions.php) >= 8.1 with [required extensions](https://laravel.com/docs/8.x/deployment#server-requirements)
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

6. Start the development environment by running the following command from within the project directory:

    ```bash
    sail up -d
    ```

7. Install Composer and NPM dependencies:

    ```bash
    sail composer install
    sail npm install
    ```
    
8. Generate an application key:

    ```bash
    sail artisan key:generate
    ```

9. Run the required database migrations:

    ```bash
    sail artisan migrate
    ```

10. Download the application fonts:

    ```bash
    sail artisan google-fonts:fetch
    ```

For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/9.x). Here's an overview
of how some key tasks can be carried out using Sail:

- [Composer](https://getcomposer.org) commands may be executed by using `sail composer <command>`.
- [NPM](https://docs.npmjs.com/cli/v7) commands may be executed by using `sail npm <command>`.
- [Artisan](https://laravel.com/docs/8.x/artisan) commands may be executed by using `sail artisan <command>`.

### Local development setup using Laravel Valet

1. Install [Homebrew](https://brew.sh).
2. Install PHP 8.1 via Homebrew:
   
   ```bash
   brew install php@8.1
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
   
    Then, change the `APP_ENV` value to `local`:

    ```dotenv
    APP_ENV=local
    ```

8. Generate an encryption key for [CipherSweet](https://github.com/spatie/laravel-ciphersweet):

    ```bash
    openssl rand -hex 32
    ```

    Add it to your `.env` file:
    
    ```dotenv
    CIPHERSWEET_KEY="<your key>"
    ```

9. Install Composer and NPM dependencies:

    ```bash
    composer install
    npm install
    ```

10. Generate an application key:

     ```bash
     php artisan key:generate
     ```
 
11. Create a database:

    ```bash
    mysql -uroot -e "create database accessibilityexchange;"
    ```

12. Run the required database migrations:

     ```bash
     php artisan migrate
     ``` 

13. Download the application fonts:

    ```bash
    php artisan google-fonts:fetch
    ```

14. Tell Valet to serve the application:

      ```bash
      valet link
      ```

15. Install [Mailhog](https://github.com/mailhog/MailHog) so that you can access transactional email from the platform:

    ```bash
    brew install mailhog
    brew services start mailhog
    ```
    
    Then, make sure that your `.env` file contains the following values:

    ```dotenv
    MAIL_MAILER=smtp
    MAIL_HOST=127.0.0.1
    MAIL_PORT=1025
    ```
    
    You will now be able to access mail that the platform sends by visiting http://127.0.0.1:8025 or http://localhost:8025. For more information and additional configuration options, [read this blog post](https://ryangjchandler.co.uk/posts/setup-mailhog-with-laravel-valet).

For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/9.x). Here's an overview
of how some key tasks can be carried out using Valet:

- [Composer](https://getcomposer.org) commands may be executed by using `composer <command>`.
- [NPM](https://docs.npmjs.com/cli/v7) commands may be executed by using `npm <command>`.
- [Artisan](https://laravel.com/docs/8.x/artisan) commands may be executed by using `php artisan <command>`.

### Running tests

The project uses [Pest](http://pestphp.com) for testing. Running tests locally requires the presence of a database named `accessibilityexchange_test`. For more information about testing Laravel, [read the documentation]().

### Development workflow

- This project uses [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/), enforced by [commitlint](https://commitlint.js.org/).
    All commit messages and pull request titles must follow these standards.
- The [`dev`](https://github.com/accessibility-exchange/platform/tree/dev) branch contains features
    that have been prototyped and gone through one or more co-design sessions.
- Feature development must take place in a fork, in a branch based on the `dev` branch. Feature branches
    must be named according to the format `feat/<feature>`.
- Before opening a pull request, developers should run `composer format && composer analyze && php artisan test --coverage` to ensure that their code is properly formatted, does not cause static analysis errors, and passes tests. Depending on the code coverage, more tests may need to be written to ensure that code coverage does not drop.
- Once a feature is ready to merge into `dev`, the merge must be performed using a [squash commit](https://docs.github.com/en/github/collaborating-with-pull-requests/incorporating-changes-from-a-pull-request/about-pull-request-merges#squash-and-merge-your-pull-request-commits).
- The [`production`](https://github.com/accessibility-exchange/platform/tree/production) branch contains refined features that
    are considered production-ready.
- Prereleases must be tagged from the `dev` branch.
- Releases must be tagged from the `production` branch.

## License

The Accessibility Exchange platform is available under the [BSD 3-Clause License](https://github.com/accessibility-exchange/platform/blob/main/LICENSE.md).
