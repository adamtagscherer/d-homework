# d-homework

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

The following softwares needs to be installed locally to make the project run.

```
* [Apache](https://httpd.apache.org/) - The web server used.
* [MySQL](https://www.mysql.com/) - The database.
* [PHP 7+](http://php.net/) - Coding language.
* [Composer](https://getcomposer.org/) - Dependency manager.
```

### Installing

Clone the directory and serve it with Apache.

```
git clone https://github.com/adamtagscherer/d-homework.git
```

Make a MySQL database and import the d-homework.sql database scheme found in the root directory.

```
CREATE DATABASE d-homework;
mysql -u <username> -p <databasename> < <d-homework.sql>
```

Install the dependencies with composer.

```
composer install
```

Rename the config.sample.php to config.php and fill out with the proper settings.
