VULRIS V1.02
======
Scott Greenwald
Leon Corrales Morales


SYSTEM REQUIREMENTS
---------------------------------------------
Software
 *CentOS (tested on CentOS 6.3, 6.4)
Hardware
 *CentOS supported hardware (list: http://wiki.centos.org/AdditionalResources/HardwareList)
-OR-
*VMware vSphere supported hardware (list: http://www.vmware.com/resources/compatibility)
SETUP
-----------------------------------------------
CentOS
Download and install CentOS (http://www.centos.org/)
Risu
Enter the following commands in a terminal (as superuser/root) to install Risu and its dependencies:
     # yum install mysql-devel gcc
     # yum install ruby
     # yum install apr-devel autoconf automake httpd-devel libcurl-devel
     # yum install ruby-rdoc ruby-devel
     # yum install rubygems
     # gem update --system
     # yum install ImageMagick ImageMagick-devel libxml2-devel sqlite-devel libxslt-devel
     # gem install rmagick libxml-ruby sqlite3 mysql
     # gem install risu --v 1.5.3
