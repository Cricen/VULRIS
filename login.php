<?php 
/**************************************************************
*    <VULRIS login page.>
*    Copyright (C) 2013  Leon F Corrales M 
*
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

	include_once("lib/Database/login/config.php");
?>

<?php if( !(isset( $_POST['login'] ) ) ) {
} 
else {
session_start();
$usr = new Users;
$usr->storeFormValues( $_POST );
if( $usr->userLogin() ) {
	header( 'Location: index.php' ) ;
	$_SESSION["loggedIn"] = true;
	$_SESSION['username'] = $_POST['username'];
	} 
else {
	echo '<center>'."<font color='red'>Sorry, Incorrect Username or Password <br> Please Try Again </br> </font>".'</center>';
	}
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>VULRIS Login</title>
        <link rel="stylesheet" type="text/css" href="style.css" />
    </head>
    
    <body>
    <div id="Cent_Column">
        <header id="head" >
        	<p id="inputHeader">VULRIS Login</p><br />
        </header>
        
        <div id="main-wrapper">
        	<div id="login-wrapper">
            	<form method="post" action="">
                	<ul>
                    	<li>
                        	<label for="usn">Username : </label>
                        	<input type="text" maxlength="30" required autofocus name="username" />
                        	<br />
                        	<br />
                    	</li>
                    
                    	<li>
                        	<label for="passwd">Password : </label>
                        	<input type="password" maxlength="30" required name="password" />
         </li>
             <li class="buttons">
             <input type="submit" name="login" value="Log in" />

           </li>
                    
                	</ul>
            	</form>
                
            </div>
        </div>
    </div>
    </body>
</html>


