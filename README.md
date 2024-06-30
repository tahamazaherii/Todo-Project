## Todo-Project</br>
# 🚀 Get started here
This template guides you through CRUD operations (GET, POST, PUT, DELETE), variables, and tests.
# 🔖 How to use this template</br>
## Step 1: config your env file</br>
 you must go to https://mailtrap.io => login => Create a New Inbox => Get SMTP Credentials <br>
 I need just this part : <br>
 MAIL_MAILER=smtp <br>
 MAIL_HOST=smtp.mailtrap.io <br>
 MAIL_PORT=2525 <br>
 MAIL_USERNAME=your_mailtrap_username <br>
 MAIL_PASSWORD=your_mailtrap_password <br>
 Open your Laravel project / Navigate to the .env file in the root directory of your project <br>
## Step 2: config your database env file
First step import sql file in your mysql and cofig in laravel .env : <br>
DB_CONNECTION=mysql <br>
DB_HOST=127.0.0.1 <br>
DB_PORT=3306 <br>
DB_DATABASE=tasktodo <br>
DB_USERNAME=root <br>
DB_PASSWORD= <br>
## Step 3: Test in laravel
If you want test database , controller , model I suggest you write this code in directory project: <br>
php artisan test
## Step 4: Send requests
Read documention and send requests step by step  <br>
https://documenter.getpostman.com/view/24141572/2sA3XV9fEf

## Manage Exception
for manage exception go to bootstrap/app
## Feature
If the user creates a todo and the time(due_date) set to do it is missed due_date, site will send email to user.<br>
if you want check this feature you must make fake data in database and put past date in due_date and write in your cmd : <br>
php artisan todos:check-due  <br>


if you want test with schedule you must write :  <br>
php artisan  schedule:work  <br>
this code check due_date every hour  <br>

## Thank you for pay attention to My project :)


