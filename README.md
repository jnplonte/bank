# BANK API


## Dependencies
* laravel: [https://laravel.com/](https://laravel.com/)
* php: [https://php.net/](https://php.net/)
* mysql: [https://mysql.com/](https://mysql.com/)
* composer: [https://getcomposer.org/](https://getcomposer.org/)
* phpunit: [https://phpunit.de/](https://phpunit.de/)


## Installation
* clone the repository by running `git clone git@github.com:jnplonte/bank.git`
* change permission of {root}\bootstrap and {root}\storage by running `chmod 777 -R bootstrap storage`
* install dependencies by running `composer install`
* rename `.env-sample` to `.env` and change db configurations
* get database and mock data by running `php artisan migrate:refresh --seed`


## How to Use
#### withdraw
- **[PUT]**  `/withdraw/{account_id}`
- required params: "amount"

#### deposit
- **[PUT]** `/deposit/{account_id}`
- required params: "amount"

#### transfer
- **[PUT]** `/transfer/{account_id}`
- required params: "amount", "transfer_id"
- *note: transfer config is on .env named "TRASFER_LIMIT", "TRASFER_CHARGE", "TRANSFER_API"*

#### add account
- **[POST]** `/account/{user_id}`
- required params: "balance"

#### delete account
- **[DELETE]** `/account/{user_id}`
- required params: "account_id"

#### add user
- **[POST]** `/user`
- required params: "email"
- optional params: "first_name", "last_name", "balance"

#### get users
- **[GET]** `/users`
- optional get params: "sort", "page", "q"

#### get one user
- **[GET]** `/user/{user_id}`

#### update user
- **[PUT]** `/user/{user_id}`
- optional params: "email", "first_name", "last_name", "balance"

#### delete user
- **[DELETE]** `/user/{user_id}`


## Testing
* install phpunit by following this instruction [https://phpunit.de/getting-started.html](https://phpunit.de/getting-started.html)
* go to root directory
* run unit test by running `phpunit`
* *note: test is inside {root}/tests/*
