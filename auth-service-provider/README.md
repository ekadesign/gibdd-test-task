# Laravel
Only support Laravel 9.x and 10.x

# Config
1. Publish config:
   ```bash
   php artisan vendor:publish --provider=Testtask\\AuthServiceProvider\\ServiceProvider
   ```
2. Add to **auth.php** in guards section:
    ```php
    'testtask' => [
        'driver' => 'token',
        'provider' => 'testtask',
    ]
    ```
3. Add to **auth.php** in providers section:
    ```php
    'testtask' => [
        'driver' => 'testtask',
    ]
    ```

