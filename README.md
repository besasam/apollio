# apoll.io
Art sharing platform

## How to install

**Requirements**: PHP >= 7.1.3, Composer, MySQL

Clone the directory and run `composer install` to download and install the required packages.

Create the file `/.env.local` containing your database credentials: 

`DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name"`

The database should already exist, and the database user should have full permissions for it.

Then, in the project directory, run:
```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
to create the database structure for the app.

*Optional:* Unzip the contents of `sample_images.zip` into `/public/uploads` and run `sample.sql` if you want to use the sample/testing data for the app.

If you want to run the app on the local test server, run:

```
php bin/console server:run
```

and follow the instructions. You will now be able to use the full functionality of the app.

For instructions how to run apoll.io on a production server, see [Symfony Docs](https://symfony.com/doc/current/setup/web_server_configuration.html).