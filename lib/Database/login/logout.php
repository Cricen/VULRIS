<?php	
/**************************************************************
*    <VULRIS logout.php.>
*	 VULRIS logout page. 
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


session_start();
if (!$_SESSION["loggedIn"]) echo "<font color=white size=1pt> You Are Not Logged In </font>";
if ($_SESSION["loggedIn"]) echo '<font color=white size=1pt> You are logged in as '.$_SESSION['username'].' <a href=logout.php> Click Here </a> To Logout </font>';
session_destroy();

//change this to your IP or however you'd like to make this connection. 
header( 'Location: https://192.168.100.220/login.php' );
?>
