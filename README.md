# Task Management System

PHP App to host multiple user's daily task lists.

## Description

A PHP app for multiple users to host their daily tasks. They can check off finished tasks,
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
<dt>3.) You may need to set the following permissions:</dt>
<dd>a.) 600 for the files inside Task/Application/Configuration.</dd>
<dd>b.) 755 for all directories</dd>
<dd>c.) 644 for all php files.</dd>
<dt>4.)fill out the attributes in the database.ini and mail.ini file in the Task/Application/Configuration folder
 with the connection info for your database and mail server.</dt>
</dl>


### Executing program

 <dl>
      <dt>1.) open up a browser to the task folder on your LAMP server and you should get the
	  login page.</dt>
      <dt>2.) click register to register a new user.</dt>
      <dt>3.) you will need to access the user table and set "isAdmin" to 1 on a user to set
	  it as admin.</dt>      
</dl>


## Authors

Contributors names and contact info

David Martin  
[Github](https://github.com/dmmartind )


## Acknowledgments

* [PHPMailer](https://github.com/PHPMailer/PHPMailer)
