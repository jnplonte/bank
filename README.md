# BANK API


## Dependencies
* laravel: [https://www.laravel.com/](https://www.laravel.com/)
* php: [https://www.php.net/](https://www.php.net/)
* mysql: [https://www.mysql.com/](https://www.mysql.com/)


## Installation
* run `git clone https://github.com/jnplonte/bank.git`
* run `composer install`
* rename `.env-sample` to `.env` and change db configurations
* run `php artisan migrate:refresh --seed`


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
- *note: transfer config is on .env named "TRASFER_LIMIT", "TRASFER_CHARGE"*

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
