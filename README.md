# OnCallTool
A tool for managing On Call Rotation for multiple teams. I wrote this as a replacement for a legacy on call tool that resided on really old hardware. I am a Linux admin and wanted to play with PHP, with some free time I had at that time. Before we were about to implement it, the company decided to not support applications in PHP. So it just sat. It works fairly well and does what is needed. I would not host this on an external facing website as my code is vulernable to SQL injection. I started to fix that, but after the company had no interest in supporting PHP, I stopped development. I used LDAP and a .htaccess file to allow for admin login via AD. Example .htaccess included in the admin/ folder.

#Requirements:
Software: MariaDB/MySQL, Apache HTTPD Server, PHP,
OS: Developed on Red Hat Linux 7, not tested with another OS

#Instructions
1) Setup a Linux Server, such as CentOS 7.
2) yum -y install httpd httpd-tools mod_ldap php-common php-ldap php-pdo php php-mysqlnd php-cli
3) Copy files to /var/www/html/ or use the git command to obtain them.
4) Copy the oncall-admin.conf in httpd/conf.d to /etc/httpd/conf.d
5) 
