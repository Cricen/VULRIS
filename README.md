VULRIS V1.02
======
Leon Corrales M.
Scott Greenwald



SYSTEM REQUIREMENTS
---------------------------------------------
*Software*

 * CentOS (tested on CentOS 6.3, 6.4)

*Hardware*

 * CentOS supported hardware (list: http://wiki.centos.org/AdditionalResources/HardwareList)

-OR-

 * VMware vSphere supported hardware (list: http://www.vmware.com/resources/compatibility)

SETUP
-----------------------------------------------
*CentOS*
* Download and install CentOS (http://www.centos.org/)

*Risu*
Enter the following commands in a terminal (as superuser/root) to install Risu and its dependencies:

      yum install mysql-devel gcc
      yum install ruby
      yum install apr-devel autoconf automake httpd-devel libcurl-devel
      yum install ruby-rdoc ruby-devel
      yum install rubygems
      gem update --system
      yum install ImageMagick ImageMagick-devel libxml2-devel sqlite-devel libxslt-devel
      gem install rmagick libxml-ruby sqlite3 mysql
      gem install risu -v 1.5.3

Please the the @Risu githug page for more information. 
   `*note you we will be making some changes to the risu file*`
   
*Nessus*
Nessus is a scanner that is used to scan an entire network for vulnerabilities.
Install Nessus: http://www.tenable.com/products/nessus/select-your-operating-system

Choose the version compatible with Red Hat ES 6 (64 bits) / CentOS 6 / Oracle Linux 6
The filename should look like this:

`Nessus-5.2.0-es6.x86_64.rpm`

Once downloaded, open the directory containing Nessus in a Linux terminal and type:

     rpm -ivh Nessus-5.0.2-es6.x86_64.rpm

Setup user credentials by entering the command:

     /opt/nessus/sbin/nessus-adduser

Register for the Nessus HomeFeeed or configure your professional feed: http://www.tenable.com/products/nessus/nessus-homefeed

Check your email for the activation key and enter it after this command:

     /opt/nessus/bin/nessus-fetch --register <your key # here>

Enter the command:

    #/etc/init.d/nessusd start

Refer to the Nessus 5.0 Instllation and Configuration Guide for more details: http://static.tenable.com/documentation/nessus_5.0_installation_guide.pdf

*Apache*

Enter the following commands to install the Apache web server:

      yum install httpd
      
      service httpd start

*MySQL*

Enter the following commands to install the MySQL database:

    yum install mysql-server
    service mysqld start

Setup MySQL with the following command:

    /usr/bin/mysql_secure_installation

Follow the steps to set a root password, disable anonymous users, disable remote login, disable test database, and reload privilege tables.

*PHP*
Enter the command:

    yum install php php-mysql
    
Next, enter the commands below to start HTTP and MySQL during startup:

    chkconfig httpd on 
    chkconfig mysqld on

*Apache/RISU Configuration* 

The general idea here is to get the passenger / mod_passenger installed. The methods for accomplishing this may change. The first thing to do is to add the stealthymonkey rpm.

     rpm --import http://passenger.stealthymonkeys.com/RPM-GPG-KEY-stealthymonkeys.asc
     yum install http://passenger.stealthymonkeys.com/rhel/6/passenger-release.noarch.rpm
     yum install mod_passenger
If libev fails then download the rpm here:
http://pkgs.org/centos-6-rhel-6/epel-x86_64/libev-4.03-3.el6.x86_64.rpm.html
If fastthread is required then enter:

     gem install fastthread

If rack is required then enter:

    gem install rack
VULRIS
to be continued... 

