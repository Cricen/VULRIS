<?php
/**************************************************************
*    <VULRIS table_edit_ajax.php.>
*	 updates/inserts department information into the VULRIS 
*	 database. 
*    Copyright (C) 2013  Leon Corrales M 
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

include("db.php");

error_reporting(E_ALL);
ini_set('display_errors', True);


$DeptID=$mysqli->real_escape_String($_POST['id']);//department.id
$admin=$mysqli->real_escape_String($_POST['admin']);//admin.name
$email=$mysqli->real_escape_String($_POST['email']);//admin.email
$phone=$mysqli->real_escape_String($_POST['phone_number']);//admin.phone
$department=$mysqli->real_escape_String($_POST['department_name']);//department.name
$location=$mysqli->real_escape_String($_POST['location']);//department.location
$convention=$mysqli->real_escape_String($_POST['convention']);//department.convention


echo $department;
$sql = "CALL department_check( $DeptID, '$department', '$location', '$convention' )";
echo "$sql";
$result = $mysqli->query($sql);
$sql = "select id from departments where department_name='$department'";
echo "$sql";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$newDeptID = $row['id'];


$sql = "select id from system_admins where admin='$admin'";
echo "$sql";
$result = $mysqli->query($sql);
$row = $result->fetch_assoc();
$adminID = $row['id'];
echo $adminID;

if(!empty($adminID)){

	$sql = "select department_id, department2_id, department3_id 
		from system_admins 
		where id='$adminID'";	
	$result = $mysqli->query($sql);
	$row = $result->fetch_assoc();
        echo "$sql";
	if ($row['department_id'] == $DeptID AND !empty($row['department3_id']))
		{
		 $sql = "update system_admins set 
			   admin = '$admin', email = '$email', phone_number = '$phone',
			   department_id = $newDeptID
			  where id=$adminID";
		$result = $mysqli->query($sql);
		echo $sql;
		}
	if ($row['department2_id'] == $DeptID OR empty($row['department2_id']))
		{
		 $sql = "update system_admins set 
			   admin = '$admin', email = '$email', phone_number = '$phone',
			   department2_id = $newDeptID
			 where id=$adminID";
		$result = $mysqli->query($sql);
		echo $sql;
		}
	 if ($row['department3_id'] == $DeptID AND !empty($row['department_id']))
		{
		 $sql ="update system_admins set 
			   admin = '$admin', email = '$email', phone_number = '$phone',
			   department3_id = $newDeptID
			 where id=$adminID";
		$result = $mysqli->query($sql);
		echo $sql;
		}
	   //else echo "could not update the database, you may have to correct the database manually";
	}
else{
	$sql = "insert into system_admins (admin, email, phone_number, department_id)
		VALUE('$admin', '$email', '$phone', '$newDeptID')";
	$result = $mysqli->query($sql);
	echo $sql;
	   }

?>


