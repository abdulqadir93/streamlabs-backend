##### Dependencies
- PHP >= 7.1
- Composer
- Apache 2.4
- MongoDB 3.4

##### Running locally
Copy `.env.example` file and rename it to `.env`. Change the values as required.

Either copy the backend folder into the apache's default document root (htdocs folder), or, edit `httpd.conf` and change the path to document_root to where the `<project-root>/public/` folder residers. Look into `alias` and  `virtual directory` if changing the document_root isn't feasible.

Make sure to add the relevant configuration inside Apache (`conf/httpd.conf`) so that it is capable of handling php files:
```
AddHandler application/x-httpd-php .php
AddType application/x-httpd-php .php .html
LoadModule php7_module "<path-to-php-unzipped-folder>/php7apache2_4.dll"
PHPIniDir "<path-to-php-unzipped-folder>"
```

cd into the root directory and run `composer install` to install all the dependencies. Make sure mongo's instance (`mongod`) is running.

##### Frameworks/Libraries
- Used Lumen PHP micro framework developed by Laravel's team and Google's official PHP client library.
- I have only worked in PHP in my early university days (6 years) back. Used to have a couple of pet projects which I created using cakePHP MVC framework.
- My personal preference would've been .NET Core if not NodeJS (since JavaScript was not allowed). But the learning curve might've pushed the submission date even further. PHP is easy to start with.

##### Deployment
Deployed at: https://streamlabs-be.herokuapp.com