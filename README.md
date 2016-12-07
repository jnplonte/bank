# BANK API

laravel (https://www.laravel.com/)
php (https://www.php.net/)
mysql (https://www.mysql.com/)

php artisan migrate:refresh --seed


withdraw
[PUT] /withdraw/{account_id}
required params: "amount"

deposit
[PUT] /deposit/{account_id}
required params: "amount"

transfer
[PUT] /transfer/{account_id}
required params: "amount", "transfer_id"


add user
[post] /user
required params: "email"
optional params: "first_name", "last_name", "balance"

get users
[GET] /users
optional get params: "sort", "page", "q"

get one user
[GET] /user/{user_id}

update user
[PUT] /user/{user_id}
optional params: "email", "first_name", "last_name", "balance"

delete user
[DELETE] /user/{user_id}
