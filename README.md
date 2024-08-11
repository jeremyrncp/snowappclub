Snow App Club
========================

Cette application est dédiée à tous les passionnés de neige désireux d'effectuer des mesures du manteau neigeux.

Requirements
------------

* PHP 8.2.0 or higher;
* PDO-SQLite PHP extension enabled;
* and the [usual Symfony application requirements][2].

Installation
------------

Modify .env to your environment.
 
[Download Composer][6] and use the `composer` binary installed
on your computer to run these commands:

```bash
git clone https://github.com/jeremyrncp/demo.git my_project
cd my_project/
composer install
bin/console doctrine:migrations:migrate
```

Usage
-----

[Download Symfony CLI][4] and run this command:**

```bash
cd my_project/
symfony serve
```

[1]: https://symfony.com/doc/current/best_practices.html
[2]: https://symfony.com/doc/current/setup.html#technical-requirements
[3]: https://symfony.com/doc/current/setup/web_server_configuration.html
[4]: https://symfony.com/download
[5]: https://symfony.com/book
[6]: https://getcomposer.org/