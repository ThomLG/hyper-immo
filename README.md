# Hyper -Immo

==================

## Installation

### Symfony Installation

`symfony new project-name --version="6.1.*" --webapp`

### Install Composer dependencies:

`composer install`

### Get project on GitHub

`git clone : https://github.com/ThomLG/hyper-immo`

### Database update

```shell
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migration:migrate # or if not migration file : php bin/console doctrine:schema:update --force)
php bin/console doctrine:fixtures:load
```

Write in env.local (create this file if not existing)
`DATABASE_URL=mysql://root:root@127.0.0.1:8889/hyper-immo?serverVersion=5.7`

### Install Database

`php bin\console doctrine:database:create`

Use `symfony console` instead of `php bin/console` if using the Symfony CLI.


## Run Symfony Server :

`symfony serve`
