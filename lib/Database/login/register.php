<?php
/**************************************************************
*    <VULRIS register.php.>
*	 VULRIS (hidden) user registration page. 
*	 BIG TODO: properly link and configure this page 
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
if (!$_SESSION["loggedIn"]) header( 'Location: login.php' );
if ($_SESSION["loggedIn"]) echo '<div align="right"><font face="Verdana" color="#FFFFFF" size="2"> You are logged in as '.$_SESSION['username'].' <a href=lib/Database/login/logout.php> Click Here </a> To Logout </div>';
?>

<?php 
	include_once("config.php");
?>

<?php if( !(isset( $_POST['register'] ) ) ) { ?>


<!DOCTYPE html>
<html>
    <head>
        <title>Codecall Tutorials - Secured Login with php5</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    
    <body>
        <header id="head" >
        	<p>Codecall tutorials User Registration</p>
        	<p><a href="register.php"><span id="register">Register</span></a></p>
        </header>
        
        <div id="main-wrapper">
        	<div id="register-wrapper">
            	<form method="post">
                	<ul>
                    	<li>
                        	<label for="usn">Username : </label>
                        	<input type="text" id="usn" maxlength="30" required autofocus name="username" />
                    	</li>
                    
                    	<li>
                        	<label for="passwd">Password : </label>
                        	<input type="password" id="passwd" maxlength="30" required name="password" />
                    	</li>
                        
                        <li>
                        	<label for="conpasswd">Confirm Password : </label>
                        	<input type="password" id="conpasswd" maxlength="30" required name="conpassword" />
                    	</li>
                    	<li class="buttons">
                        	<input type="submit" name="register" value="Register" />
                            <input type="button" name="cancel" value="Cancel" onclick="location.href='index.php'" />
                    	</li>
                    
                	</ul>
            	</form>
            </div>
        </div>
    
    </body>
</html>

<?php 
} else {
	$usr = new Users;
	$usr->storeFormValues( $_POST );
	
	if( $_POST['password'] == $_POST['conpassword'] ) {
		echo $usr->register($_POST);	
	} else {
		echo "Password and Confirm password do not match";	
	}
}
?>
