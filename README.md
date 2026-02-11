# Pimcore Wiki Bundle

This bundle provides a wiki/documentation interface for Pimcore, allowing you to display markdown files in the admin interface.

## Installation

Install the bundle via composer:

```bash
composer require torqit/pimcore-wiki-bundle
```

## Configuration

Configure the location of your markdown files in your config/packages/wiki_bundle.yaml:

```yaml
wiki_bundle:
    documentation_path: '%kernel.project_dir%/docs'
```

## Usage

After installation and configuration, access your documentation at:

```
/admin/documentation
```

The bundle will automatically read and display markdown files from the configured documentation path.


# License

This bundle is licensed under the Pimcore Open Core License (POCL)
and is intended for use with Pimcore Platform 2025.1 and newer.

See LICENSE.md for full license text.
