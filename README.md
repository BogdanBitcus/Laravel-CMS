# Laravel-CMS
CMS based on Laravel framework


# How it works (Princip)

Step 1: <br>
We have link, for example /about, in webbrowser.

Step 2: <br>
Find link in table "pages" (field "addr") and we geting page ID.

Step 3: <br>
By this record we get ID type of page.

Step 4: <br>
By ID type we getting view_template from table 'types' and show it for browser. 


# Setup

Step 1: (initialize migrations for DB)<br>
php artisan migrate:refresh --seed

Step 2:<br>
Login by url <b>/cms</b><br>
Login : <b>test@example.com</b><br>
Password : <b>password</b>


