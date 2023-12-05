# Transaction
<h5>Setup</h5>
<li>Install composer</li>
<li>Install mysql</li>
<li>Clone Project</li>
<li> Make command line "composer update", "cp .env.example .env", "php artisan key:generate", "php artisan migrate"</li>
<hr>
<h5>EndPoint</h5>
<li>POST: http://localhost:8000/api/import-json (Take "users" and "transactions" files in the body)</li>
<li>GET:  http://localhost:8000/api/list-users <br> (get all data users if need to filter add param (statusCode, currency, amountRange[], dateRange[] ) )</li>
