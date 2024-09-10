# Expense Tracker in Laravel

**Laravel Version** -> 11.6.0
**PHP Version** -> 8.2.23
**Database** -> MySQL
**Frontend** -> Vue.js
**Backend** -> Laravel

## How to run
1. Clone the repository
2. Download laravel. refer here https://laravel.com/docs/11.x/installation
    - in downloading laravel, you need to download [Composer](https://getcomposer.org/download/)
3. Install node js. refer here https://nodejs.org/en/download/package-manager 
4. Install php version "> 8.2.23"
    - add environment variable that contans the php.exe file. Example. "C:\php"
    - edit php.ini file. uncomment line ";extension=mysqli" and ";extension=pdo_mysql". You can uncomment by removing the semi-colon ';'
6. Install XAMPP
7. Open xampp and start Apache and MySQL
8. go to the project directory and open it in your VS code
9. Open terminal (cmd or powershell) then run "php artisan migrate". This builds the database schema
10. Run "php artisan db:seed" to populate database with default data.
11. Run "npm run dev". NOTE: do not terminate this command. This is needed for the front end.
12. Open another terminal and run "php artisan serve". Go to the link provided by this command.
13. You can now interact with the expense tracker.
