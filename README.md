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
**CentOS**
* Download and install CentOS (http://www.centos.org/)

**Risu**
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
   
**Nessus**
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

**Apache**

Enter the following commands to install the Apache web server:

      yum install httpd
      
      service httpd start

**MySQL**

Enter the following commands to install the MySQL database:

    yum install mysql-server
    service mysqld start

Setup MySQL with the following command:

    /usr/bin/mysql_secure_installation

Follow the steps to set a root password, disable anonymous users, disable remote login, disable test database, and reload privilege tables.

**PHP**
Enter the command:

    yum install php php-mysql
    
Next, enter the commands below to start HTTP and MySQL during startup:

    chkconfig httpd on 
    chkconfig mysqld on

**Apache/RISU Configuration** 

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

 **VULRIS**

Copy the contents of VULRIS to `/var/www/html`.
Modify `db.php` at `/var/www/html/lib/Database/` with your database username and password. Modify `config.php` at `/var/www/html/lib/nmap/` with your database username and password.
Modify `config.php` at `/var/www/html/lib/nessus/` with your database username and password.
Modify `config.php` at `/var/www/html/lib/Databases/login/` with your database username and password.

**SSL Setup** *optional*

Follow the directions below to set up VULRIS over https. For these instructions, refer to http://wiki.centos.org/HowTos/Https. To save some time I've included them here. 
For an SSL encrypted web server you will need a few things. Depending on your install you may or may not have OpenSSL and mod_ssl, Apache's interface to OpenSSL. Use yum to get them if you need them.

     yum install mod_ssl openssl

Yum will either tell you they are installed or will install them for you.
Using OpenSSL we will generate a self-signed certificate. If you are using this on a production server you are probably likely to want a key from Trusted Certificate Authority, but if you are
just using this on a personal site or for testing purposes a self-signed certificate is fine. To create the key you will need to be root so you can either su to root or use sudo in front of the commands.
Generate private key:

    openssl genrsa -out ca.key 1024

Generate CSR:

    openssl req -new -key ca.key -out ca.csr

Generate Self Signed Key:

    openssl x509 -req -days 365 -in ca.csr -signkey ca.key -out ca.crt

Copy the files to the correct locations:

    cp ca.crt /etc/pki/tls/certs

    cp ca.key /etc/pki/tls/private/ca.key

    cp ca.csr /etc/pki/tls/private/ca.csr

WARNING: Make sure that you copy the files and do not move them if you use SELinux. Apache will complain about missing certificate files otherwise, as it cannot read them because the
certificate files do not have the right SELinux context.


If you have moved the files and not copied them, you can use the following command to correct the SELinux contexts on those files, as the correct context definitions for /etc/pki/* come with the bundled SELinux policy.

    restorecon -RvF /etc/pki

Then we need to update the Apache SSL configuration file:

    vi +/SSLCertificateFile /etc/httpd/conf.d/ssl.conf

Change the paths to match where the Key file is stored. If you've used the method above it will be:

*SSLCertificateFile /etc/pki/tls/certs/ca.crt*

Then set the correct path for the Certificate Key File a few lines below. If you've followed the instructions above it is:

*SSLCertificateKeyFile /etc/pki/tls/private/ca.key*

Quit and save the file and then restart Apache:

    /etc/init.d/httpd restart

All being well you should now be able to connect over https to your server and see a default Centos page. As the certificate is self signed browsers will generally ask you whether you want to accept the certificate. Firefox 3 won't let you connect at all but you can override this.
Just as you set VirtualHosts for http on port 80 so you do for https on port 443. A typical
VirtualHost for a site on port 80 looks like this:

    <VirtualHost *:80>
    <Directory /var/www/vhosts/yoursite.com/httpdocs> AllowOverride All
    </Directory>
    DocumentRoot /var/www/vhosts/yoursite.com/httpdocs
    ServerName yoursite.com
    </VirtualHost>

To add a sister site on port 443 you need to add the following at the top of your file:

    NameVirtualHost *:443

and then a VirtualHost record something like this:

    <VirtualHost *:443> SSLEngine on
    SSLCertificateFile /etc/pki/tls/certs/ca.crt
    SSLCertificateKeyFile /etc/pki/tls/private/ca.key
    <Directory /var/www/vhosts/yoursite.com/httpsdocs> AllowOverride All
    </Directory>
    DocumentRoot /var/www/vhosts/yoursite.com/httpsdocs
    ServerName yoursite.com
    </VirtualHost>

Restart Apache again using:

    /etc/init.d/httpd restart

**Risu Modification**

Copy the contents of Models and Templates folders to the Risu directory and overwrite all. Risu folder: /usr/lib64/ruby/gems/1.8/gems/risu-1.5.3/lib/risu/

---more information to come soon in this section... 


**Risu Configuration**

Enter the following commands in a terminal (as superuser/root) to create a Risu configuration file:

    risu --create-config

This will create a configuration file, however one should already exist in the`lib/nessus` directory  Fill in your database information and other information you want to appear on generated reports.

    report:
    author: [your name] title: [your title] company: [your company]
    classification: [classification type]
    database:
    adapter: mysql2
    database: [database name]
    port: 3306
    username: [database username]
    password: [database password]

**Database creation**

Locate the directory with the VULRIS_schema.sql file. Enter the following commands in a terminal to set up a database with the proper tables:

# mysql -u root -p < VULRIS_schema.sql

# mysql -u root -p < users.sql
Setting Permissions

Navigate to the root of your web server in a Terminal:

# cd /var/www/html

Change the Reports folder’s group to apache:

# chgrp apache Reports
Firewall Setup

From the CentOS desktop, choose the System menu from the top bar. Select Administration, then Firewall. In the Other Ports section, modify the firewall settings as shown below to allow secure (https, port 443) traffic and open the :
Run a Nessus scan

Using VULRIS
-------------------------------------


Navigate to VULRIS by entering your server's IP address in your web browser. If you are viewing this guide on the server you may enter https://localhost
Log in and choose the Launch Nessus option.
Run a scan of your network. Refer to Nessus documentation for details:
http://www.tenable.com/products/nessus/documentation
Add a Nessus scan to VULRIS

Click the Choose File button and pick an exported .nessus scan file. After clicking Process File, the Nessus file will be uploaded, parsed, and added to the MySQL database.
Report Generation

Choose the Reports Control menu option in VULRIS. Choose a report to generate and click the Report button. Your report will be generated and a PDF will appear shortly. Certain reports may take longer to generate (e.g. graphs, historical, executive summary) depending on the size of
your network scan.
