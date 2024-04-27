# Task Management System

PHP App to host multiple user's daily task lists.

## Description

a PHP app for multiple users to host their daily tasks. They can check off finished tasks,
change the contents of their tasks, set the order of each task by priority, and delete a task. 

There is also an administration page, only for admin users, where you can view all the users.

Each user has a profile page where they can change their name, email, and password.


## Getting Started

### Dependencies

PHP 8, LAMP server

### Installing


<dl>
<dt>1.) Import SQL to MYSQL server.</dt>
<dt>2.) Place the task folder in the www folder of your server.</dt>
<dt>3.) You need to set the following permissions:</dt>
<dd>a.) 600 for the files inside Task/Application/Configuration.</dd>
<dd>b.) all other directories need 755 to be applied</dd>
<dd>c.) all php files need to be set to 644.</dd>
<dt>4.)fill out the attributes in the database.ini and mail.ini file in the Task/Application/Configuration folder
 with the connection info for your database and mail server.</dt>
</dl>


### Executing program

*open up a browser to the task folder on your LAMP server and you should get the login page.
*click register to register a new user.
*you will need to access the user table and set "isAdmin" to 1 on a user to set it as admin.
Note: the admin user doesn't have access to their own tasks. They can only view other user's tasks.


## Authors

Contributors names and contact info

ex. David Martin  
ex. [Github](https://github.com/dmmartind )


## Acknowledgments

* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
