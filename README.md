# GDALab practice API
Here's a little documentation to be able to run this Restful API.
</br>
Used technologies:
 - **PHP** (v 7.1.1)
 - **Slim** framework (v 3)
 - **MySQL**
 - **Apache** web server

#### 1) Configure a local environment for run the API (Linux OS distributions)
 - Download XAMPP for Linux, with PHP version 7.1.1 from this link: </br>
https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/7.1.1/

 - Set the path and variables to access XAMPP bundle globally: </br>
`gedit ~/.bashrc or nano ~/.bashrc`

 - Edit the .bashrc file and add this line: </br>
 → export PATH="$PATH:/opt/lampp/bin"

 - After installation, link globally PHP of XAMPP to run it from anywhere: </br>
`sudo ln -s /opt/lampp/bin/php /usr/local/bin/php`


 - Install Composer with XAMPP already installed: </br>
`sudo su` </br>
`php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"` </br>
`php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt';` </br>
`unlink('composer-setup.php'); } echo PHP_EOL;"` </br>
`php composer-setup.php` </br>
`php -r "unlink('composer-setup.php');"` </br>

 - After installed, make composer can run globally: </br>
`sudo mv composer.phar /usr/local/bin/composer`

 - Change htdocs folder permission: </br>
`sudo chmod 777 -R /opt/lampp/htdocs`

#### 2) Preparing Slim PHP API to be executed
 - Clone the repo into yout *htdocs* XAMPP server directory: </br>
`cd /opt/lampp/htdocs/` </br>
`git clone https://github.com/AlexisAndresHR/GDALab-Practice-API.git`

 - Install the API dependencies using composer: </br>
`cd GDALab-Practice-API/` </br>
`composer install`

...
#### API URLS
The following list contains the API actions URLs and one example of the JSON values that can be sent, everyone working with POST request method.

 - **Register Customer:** </br>
http://localhost/GDALab-Practice-API/public/customers/register </br>
Request example: </br>
*{
    "dni": "INE-1234567890",
    "id_reg": 1,
    "id_com": 1,
    "email": "alexis@mail.com",
    "name": "Alexis",
    "last_name": "Hernandez",
    "address": "Azteca 313 Col Centro CP 43600",
    "date": "2021-11-17 11:59:00"
}*

 - **Search Customer:** </br>
http://localhost/GDALab-Practice-API/public/customers/search </br>
Request example: </br>
*{
    "dni": "INE-1234567890",
    "email": "alexis@mail.com"
}*

NOTE: The search function can work with just one parameter also (dni or email).

 - **Delete Customer:** </br>
http://localhost/GDALab-Practice-API/public/customers/delete </br>
Request example: </br>
*{
    "dni": "INE-1234567890",
    "email": "alexis@mail.com"
}*

NOTE: Like search function, this can work with just one parameter also (dni or email).


We can try and use those functions through URLs using [Postman](https://www.postman.com/) API platform or some other API client (software).

</br>

#### How to see error logs ?
API errors like database exceptions are catched and stored in a text file, the error_log file of PHP.
We can see that detailed errors going in our server route: </br>
`/opt/lampp/logs/php_error_log`

