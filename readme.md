# Symfony projet back
### Installation locale
S'assurer que la base de données est bien lancée et que le connection string est à jour.
```bash
composer install 
php bin/console doctrine:database:create 
php bin/console doctrine:migrations:migrate  
php bin/console doctrine:fixtures:load
```


### Login
__user:__ user@gmail.com  
__password:__ password