# Accessibility in Action

[![Project license](https://badgen.net/github/license/accessibility-in-action/platform)](https://github.com/accessibility-in-action/platform/releases/latest)
[![Latest release](https://badgen.net/github/release/accessibility-in-action/platform)](https://github.com/accessibility-in-action/platform/releases/latest)
[![Check status](https://badgen.net/github/checks/accessibility-in-action/platform/dev)](https://github.com/accessibility-in-action/platform/releases/latest)
[![Localization status](https://badges.crowdin.net/accessibility-in-action/localized.svg)](https://crowdin.com/project/accessibility-in-action)

Accessibility in Action is a two-year initiative managed by the
[Institute for Research and Development on Inclusion and Society (IRIS)](https://irisinstitute.ca/) that sets out to
create an online platform which will support processes where people with disabilities have the power to make sure that policies,
programs, and services by federally regulated entities are accessible to them and respect their human rights. Current
consultation processes are built on a foundation of systemic ableism—they lack accountability, follow-through, and don't
honour the expertise of people with disabilities.

The Accessibility in Action platform is co-designed and developed by the [Inclusive Design Research Centre](https://idrc.ocadu.ca/)
at [OCAD University](https://ocadu.ca).

## Technical Details

The platform is built as a progressive web application using the [Laravel 8](https://laravel.com/docs/8.x) framework.

## Installation

For general deployment information, please see the Laravel 8.x [deployment documentation](https://laravel.com/docs/8.x/deployment).

The platform requires the following:

- [PHP](https://www.php.net/supported-versions.php) >= 7.4 (PHP 8.0 recommended) with [required extensions](https://laravel.com/docs/8.x/deployment#server-requirements)
- [MySQL](https://dev.mysql.com/downloads/) >= 5.7
- [Composer](https://getcomposer.org) >= 2.0

The deployment process should follow all of the recommended [optimization processes](https://laravel.com/docs/8.x/deployment#optimization).

## Development and prototyping environments

In development and prototyping environments, a deployment should be followed by dropping the database tables, running all
migrations and seeding the database:

```bash
php artisan migrate:fresh --seed
```

**NOTE: This will drop all existing database tables. [See the documentation](https://laravel.com/docs/8.x/migrations#drop-all-tables-migrate)
for details.**

## Production environments

In production environments, a deployment should be followed by running all available migrations:

```bash
php artisan migrate
```

## Development

Local development uses the [Laravel Sail](https://laravel.com/docs/8.x/sail) Docker environment.

### Local development setup

1. Install [Docker Desktop](https://www.docker.com/products/docker-desktop).
2. Add an alias to your shell [as described here](https://laravel.com/docs/8.x/sail#configuring-a-bash-alias).
3. Fork and clone the project repository (easiest with the [Github CLI](https://cli.github.com/)):

   ```bash
   gh repo fork accessibility-in-action/platform --clone
   ```

4. Create a `.env` file from the included example file:

   ```bash
   cp .env.example .env
   ```

5. Start the development environment by running the following command from within the project directory:

   ```bash
   sail up -d
   ```

6. Generate an application key:

   ```bash
   sail artisan key:generate
   ```

7. Run the required database migrations:

    ```bash
   sail artisan migrate
   ```

### Working on the platform

For comprehensive instructions, consult the [Laravel documentation](https://laravel.com/docs/8.x). Here's an overview
of how some key tasks can be carried out using Sail:

- [Composer](https://getcomposer.org) commands may be executed by using `sail composer <command>`.
- [NPM](https://docs.npmjs.com/cli/v7) commands may be executed by using `sail npm <command>`.
- [Artisan](https://laravel.com/docs/8.x/artisan) commands may be executed by using `sail artisan <command>`.

### Development workflow

- This project uses [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/), enforced by [commitlint](https://commitlint.js.org/).
All commit messages and pull request titles must follow these standards.
- The [`prototype`](https://github.com/accessibility-in-action/platform/tree/prototype) branch contains
  prototyped features for use in co-design sessions. It must be regularly updated with changes from the [`dev`](https://github.com/accessibility-in-action/platform/tree/dev)
  branch.
- Feature prototyping must take place in a feature branch forked from the `prototype` branch. Feature prototype branches
  must be named according to the format `prototype/<feature>`. Once a feature prototype is ready to merge into
  `prototype`, the merge must be performed using a [merge commit](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/about-pull-request-merges).
- The [`dev`](https://github.com/accessibility-in-action/platform/tree/dev) branch contains refined features
  that have been prototyped and gone through one or more co-design sessions.
- Feature refinement must take place in a feature branch forked from the `prototype` branch. Feature refinement branches
  must be named according to the format `feature/<feature>`. Once a refined feature is ready to merge into `dev`, the
  merge must be performed using a [merge commit](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/about-pull-request-merges).
- The [`main`](https://github.com/accessibility-in-action/platform/tree/main) branch contains refined features that
  are considered production-ready.
- Prereleases must be tagged from the `dev` branch.
- Releases must be tagged from the `main` branch.

## License

The Accessibility in Action platform is available under the [BSD 3-Clause License](https://github.com/accessibility-in-action/platform/blob/main/LICENSE.md).
