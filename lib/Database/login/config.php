<?php
/**************************************************************
*    <VULRIS config.php.>
*	 VULRIS user database connection. 
*    code is derived from papabear at codecall.net
*    <http://forum.codecall.net/topic/69771-creating-a-simple-yet-secured-loginregistration-with-php5/>
*    Copyright (C) 2013  Leon Corrales M. 
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
**************************************************************/



error_reporting(E_ALL);
ini_set('display_errors', True);
    //set off all error for security purposes
	//error_reporting(E_ALL);
	

	//define some contstant
    define( "DB_DSN", "mysql:host=localhost;dbname=VULRIS_users" );
    define( "DB_USERNAME", "root" );
    define( "DB_PASSWORD", "CISCO1" );
    define( "CLS_PATH", "class" );
	
	//include the classes
	include_once( CLS_PATH . "/user.php" );
	

?>
