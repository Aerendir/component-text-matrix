Agents instructions
===================

## General rules

- Always use English for all names and comments.
- Always use descriptive names for variables, methods, function and classes. Ex.: `getUserById` is a good name for a method that retrieves a user by its ID, while `getData` is not descriptive at all. `$rolesValidator` is a good name for a variable that holds an instance of a class that validates user roles, while `$validator` is not descriptive at all and `$v` is even worse.
- All names MUST be in camelCase; array keys MUST be in snake_case.
- Use English for all descriptions in `AGENTS.md` and `README.md`.


## Dockerized Development

The library is completely dockerized. All commands are run inside the containers through `make`.

### Start a specific PHP version

To start the containers with a specific version of PHP, use the `start` command followed by the version:

```bash
make start 8.3
```

### Install a specific Symfony version

To install a specific version of Symfony, use the `sf` command followed by the version:

```bash
make sf 7.0
```

This command uses Symfony Flex to configure the required version and updates the `symfony/*` packages.

When using Flex, to the `composer.json` file is added the key `extra.symfony.require` with the version of Symfony to install, e.g. `"^7.0"`. This is used by Flex to determine which version of Symfony to install and to update the `symfony/*` packages accordingly.

IMPORTANT: NEVER commit the Flex configuration in the `composer.json`.
If you have to commit `composer.json`, please, first remove the `extra.symfony.require` configuration of Flex from it.


## Managing versions

### Managing the versions of libraries in `composer.json`

This is a Symfony bundle: this means it will be installed in apps with a wide range of Symfony versions.

To ensure it works with all supported Symfony versions, we need to set the version constraints of the libraries in `composer.json` to all the supported version.

For example, if we support Symfony 5.4 and 6.4, we need to set the version constraints of the libraries to all the versions supported by both Symfony 5.4 and 6.4: `"symfony/*": "^5.4 || ^6.4"`.

This applies to all other relvant libraries used in the bundle: read the GitHub Actions workflow `.github/workflows/phpunit.yml` to understand the matrix of supported versions and to know which are the libraries that have explicit support in multiple versions.


### Changing the supported PHP versions

When changing the supported PHP versions, you need to update those files:

- Supported Docker versions (`.docker/php`): for each version of PHP there is a dedicated folder with the same name, e.g. `8.2`. To add a new version, simply copy an existing folder, rename it after the new supported version and update the `Dockerfile` accordingly.
- GitHub Actions workflows (`.github/workflows/*.yml`): all but `phpunit.yml` workflows have a matrix with only the lowest supported PHP version
- GitHub Actions `phpunit.yml` workflow: update the SonarCloud step: always send the report for lowest supported PHP versions and the highest supported composer versions. The matrix has to support all supported PHP versions
- `composer.json` (`require.php`): always ensure it requires the lowest supported version of PHP, e.g. `"php": "^8.2"`.
- `Makefile`: update `PHP_VERSIONS` variable with the new supported version. Set the variable `PHP_V` to the lowest supported version, e.g. `8.2`.
- `README.md`: update the "Supported PHP versions" section with the updated supported versions.

### Changing the supported Symfony versions

When changing the supported Symfony versions, you need to update those files:

- GitHub Actions workflows (`.github/workflows/*.yml`): update `jobs.*.strategy.matrix.symfony`
- `composer.json` (`require.symfony/*`): always ensure it requires the lowest supported version of Symfony, e.g. `"symfony/*": "^6.4"`.
  For the current stable release, support it from the lowest minor version `0`, e.g. `"symfony/*": "^8.0"`.
  For the previous stable releases, support it from the highest minor version `4`, e.g. `"symfony/*": "^5.4"`.
- `Makefile`: Set the variable `SF_V` to the lowest supported version, e.g. `6.4`.
