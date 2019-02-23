# data-manager

#### Configure Server
```bash
- Change .env to connect with your Database
```

#### Installation
```bash
- git clone 
- composer install
- curl install
- PHP >= 7.1
```

#### Run this project
```bash
- open a new terminal tab
    $ php bin/console server:run
```

#### Create & update of the BDD
```bash
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
```

#### If you need add fixtures
```bash
- run this command
    $ php bin/console doctrine:fixtures:load
```