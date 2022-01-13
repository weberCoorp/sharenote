desk
Проверяем соответствие версии сервера php, 
должна соответствовать в composer.json
`
php -v
`

desk

`
composer install
`

copy env.example to .env
Устанавливаем базу данных прописываем коннект в .env и подключаем в .env

`
php artisan key:generate
`

`
php artisan storage:link
`
##
Выполняем миграцию и засеиваем тестовыми данными

`
php artisan migrate:fresh --seed
`




## Testing emails
The application use Mailtrap( https://mailtrap.io/ ), so you need just to type in your credentials. 

You will find them in the SMTP Settings tab of your Inbox. 
Also, you can use Integrations data from the same tab. 
Choose Laravel from the list, copy the following details, and paste them to your .env file:

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=1a2b3c4d5e6f7g //your username
    MAIL_PASSWORD=1a2b3c4d5e6f7g // your password
    MAIL_FROM_ADDRESS=from@example.com
    MAIL_FROM_NAME=Example

##  QUEUE    
    
    QUEUE_CONNECTION=database
    php artisan queue:work 
## social login
   
    to .env
    FACEBOOK_CLIENT_ID=202052323728814
    FACEBOOK_CLIENT_SECRET=f214a3180a8156b1595509c008d8557d

    to /config/service.php
    'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => 'https://yoursite.com/login/facebook/callback',
    ],
