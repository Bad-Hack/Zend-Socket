Zend-Socket
===========

This is a small project to implement PHP sockets with zend framework.
The Socket class used by fancywebsocket is used with modification. For more details on FancyWebSockets please visit the github url https://github.com/Flynsarmy/PHPWebSocket-Chat.git

Lets try to make it more compatible with zend-framework structure and get the best out of it.

Special thanks to
Ajay Patel,
Dharmesh Patel,
Krunal Shah,
Jimit Shah.
For their precious help.

Requirement Specification
=========================
1) mod_rewrite should be enabled with the server (-prefered apache-).
2) socket ports should be available for usage

How To Configure?
=================
Its simple as configuring Zend-Framework.
The convention that are followed are
a) Zend Modular structure is used (preferred - Testing is done with modular structure only)
b) Library for Pws - PHP-Web-Sockets is included.
c) Folder "socket" is included in the root directory. which includes the socket handling functionality file "server.php"

Steps 1) Put the files in the default web-hosting folder "/www/" in folder name "zs"
steps 2) I have used the zs folder during development if you are using any other folder 
as root folder then don't forget to change the base url in application.ini
steps 3) Though this build is not for production if you are using it for production purpose then configure the 
.htaccess file to change the environment varible to production 


Wholaaa.. Ready to go..
**Note: Please ignore the novice programming pattern in this build.. if you find any improvement or bugs please let me know at my email : thekingofall@rocketmail.com

