<?php
    /**************************************************************
     *    <VULRIS Interface page.>
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
    
    
    session_start();
if (!$_SESSION["loggedIn"]) header( 'Location: login.php' );
if ($_SESSION["loggedIn"]) echo '<div align="right"><font face="Verdana" color="#FFFFFF" size="2"> You are logged in as '.$_SESSION['username'].' <a href=lib/Database/login/logout.php> Click Here </a> To Logout </div>';
?>
<?php
include('lib/Database/db.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="HTTP://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>VULRIS</title>
<script type="text/javascript" src="lib/js/MM.js"></script>
<!--<script type="text/javascript" src="lib/js/jquery.min.js"></script>-->
<!--Alternately you can get this script here -->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>-->
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
<script type="text/javascript" src="lib/js/tabs.js"></script>
<script type="text/javascript" src="lib/js/reportHandler.js"></script>
<!--<script type="text/javascript" src="lib/js/db.js"></script>-->
<script type="text/javascript">
$(document).ready(function()
{

 $(".edit_tr").click(function()
   {
var ID=$(this).attr('id');
	$("#admin_"+ID).hide();
	$("#email_"+ID).hide();
	$("#phone_"+ID).hide();
	$("#department_"+ID).hide();
	$("#location_"+ID).hide();
	$("#convention_"+ID).hide();
	$("#admin_input_"+ID).show();
	$("#email_input_"+ID).show();
	$("#phone_input_"+ID).show();
	$("#department_input_"+ID).show();
	$("#location_input_"+ID).show();
	$("#convention_input_"+ID).show();
	}).change(function()
		{
		var ID=$(this).attr('id');
		var admin=$("#admin_input_"+ID).val();
		var email=$("#email_input_"+ID).val();
		var phone=$("#phone_input_"+ID).val();
		var department=$("#department_input_"+ID).val();
		var location=$("#location_input_"+ID).val();
		var convention=$("#convention_input_"+ID).val();

		var dataString = 'id='+ ID +'&admin='+admin+'&email='+email+'&phone_number='+phone+'&department_name='+department+'&location='+location+'&convention='+convention;

		$("#admin_"+ID).html('<img src="images/load.gif" />'); 
	if(admin.length>0 && department.length > 0)
	{
	alert("The database has been updated.");
		$.ajax({
		type: "POST",
		url: "lib/Database/table_edit_ajax.php",
		data: dataString,
		cache: false,
		success: function(html) 
		{

			$("#admin_"+ID).html(admin);
			$("#email_"+ID).html(email);
			$("#phone_"+ID).html(phone);
			$("#department_"+ID).html(department);
			$("#location_"+ID).html(location);
			$("#convention_"+ID).html(convention);
		}
	});
  }
else
  {
   
  }

});

$(".editbox").mouseup(function() 
  {
	return false
   });

$(document).mouseup(function()
  {
	$(".editbox").hide();
	$(".text").show();
   });

});
</script> 
<link href="style.css" rel="stylesheet" type="text/css" />

<div id = "apDiv32">
	<img src="images/Vluris-Logo.png" width = "500" height = "188" />
</div>
</head>

<body>

<div id="apDiv44">	

  <div id="container">
  <div id="tabs">
    <ul>
      <li><a href="#tab-1">Nessus Control</a></li>
      <li><a href="#tab-2">Database Control</a></li>
      <li><a href="#tab-3">Reports Control</a></li>
      <li><a href="#tab-4">Nmap Input</a></li>
    </ul>
    
    
  <div id="tab-1">
	<h3>Launch a Nessus session</h3>
	<form action="https://192.168.100.220:8834">
	    <input type="submit" value="Launch Nessus">
	    </form>
	<h3>Load a Nessus file</h3> 
   <table width="80%"><tr>
	<td width="150px" valign="center">
	</td>
	<td valign="center">
	<br><br>
	<table cellspacing="5" cellpadding="5" width="600">
		<tr>
			<td colspan="4">
				<form enctype="multipart/form-data" action="lib/nessus/parse.php" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="2000000000" />
				<img src="images/nessus_logo.png"></img>
				<p>The Nessus file will be uploaded, parsed, and added to the backend MySQL database.</p>
			</td>
		</tr>
		<tr>
			<td><p>Select .nessus file: </p></td><td><input name="userfile" type="file" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Process File" />
				</form>
			</td>
		</tr>
	</table>
	</td>
</tr></table>

  </div>
  
  <div id="tab-2">

<table width="100%">
<tr class="head">
<th>Admin Name</th><th>Phone Number</th><th>Email</th><th>Department</th><th>Location</th><th>Convention</th>
</tr>
<?php
$newID=-1;
$newAdmin=NULL;
$newPhone=NULL;
$newEmail=NULL;
$newDname=NULL;
$newLocation=NULL;
$newConvention=NULL;
$sql="select departments.id, system_admins.admin, system_admins.email, system_admins.phone_number,departments.department_name, departments.location,
		 departments.hostname_convention 
		 from system_admins, departments 
		WHERE 
			departments.id=system_admins.department_id OR departments.id=system_admins.department2_id 
			OR departments.id=system_admins.department_id";
$result = $mysqli->query($sql);
$insert_check =0;
$i=1;
while($row = $result->fetch_assoc())
{
$id=$row['id'];
$admin=$row['admin'];
$email=$row['email'];
$dname=$row['department_name'];
$location=$row['location'];
$phone=$row['phone_number'];
$convention=$row['hostname_convention'];
?>

<?php
//this next block is for inserting a new record.
if($i%2){
	?>
	<tr id="<?php echo $newID; ?>" class="edit_tr" bgcolor="#181818">
	<?php } else { ?>
	<tr id="<?php echo $newID; ?>" class="edit_tr" bgcolor="#666666">
	<?php } ?>
	<?php if($insert_check ==0){
	$insert_check =1;?>

	<td width="50%" class="edit_td">
	<span id="admin_<?php echo $newID; ?>" class="text"><?php echo $newAdmin; ?></span>
	<input type="text" size="30" value="<?php echo $newAdmin; ?>" class="editbox" id="admin_input_<?php echo $newID; ?>" />
	</td>
	<td width="50%" class="edit_td">
	<span id="phone_<?php echo $newID; ?>" class="text"><?php echo $newPhone; ?></span> 
	<input type="text" size="15" value="<?php echo $newPhone; ?>"  class="editbox" id="phone_input_<?php echo $newID; ?>"/>
	</td>
	<td width="50%" class="edit_td">
	<span id="email_<?php echo $newID; ?>" class="text"><?php echo $newEmail; ?></span> 
	<input type="text"  size="40" value="<?php echo $newEmail; ?>"  class="editbox" id="email_input_<?php echo $newID; ?>"/>
	</td>
	<td width="50%" class="edit_td">
	<span id="department_<?php echo $newID; ?>" class="text"><?php echo $newDname; ?></span> 
	<input type="text"  size="40" value="<?php echo $newDname; ?>"  class="editbox" id="department_input_<?php echo $newID; ?>"/>
	</td>
	<td width="50%" class="edit_td">
	<span id="location_<?php echo $newID; ?>" class="text"><?php echo $newLocation; ?></span> 
	<input type="text" size="40" value="<?php echo $newLocation; ?>"  class="editbox" id="location_input_<?php echo $newID; ?>"/>
	</td>
	<td width="50%" class="edit_td">
	<span id="convention_<?php echo $newID; ?>" class="text"><?php echo $newConvention; ?></span> 
	<input type="text"  size="15" value="<?php echo $newConvention; ?>"  class="editbox" id="convention_input_<?php echo $newID; ?>"/>
	</td>
	</tr>
	<?php }?>

<?php
if($i%2)
//this is where the database is displayed. 
{
?>
<tr id="<?php echo $id; ?>" class="edit_tr" >
<?php } else { ?>
<tr id="<?php echo $id; ?>" bgcolor="#3D3D4C" class="edit_tr">
<?php }  ?>
<td width="50%" class="edit_td">
<span id="admin_<?php echo $id; ?>" class="text"><?php echo $admin; ?></span>
<input type="text" size="30" value="<?php echo $admin; ?>" class="editbox" id="admin_input_<?php echo $id; ?>" />
</td>
<td width="50%" class="edit_td">
<span id="phone_<?php echo $id; ?>" class="text"><?php echo $phone; ?></span> 
<input type="text" size="15" value="<?php echo $phone; ?>"  class="editbox" id="phone_input_<?php echo $id; ?>"/>
</td>
<td width="50%" class="edit_td">
<span id="email_<?php echo $id; ?>" class="text"><?php echo $email; ?></span> 
<input type="text" size="40" value="<?php echo $email; ?>"  class="editbox" id="email_input_<?php echo $id; ?>"/>
</td>
<td width="50%" class="edit_td">
<span id="department_<?php echo $id; ?>" class="text"><?php echo $dname; ?></span> 
<input type="text" size="40" value="<?php echo $dname; ?>"  class="editbox" id="department_input_<?php echo $id; ?>"/>
</td>
<td width="50%" class="edit_td">
<span id="location_<?php echo $id; ?>" class="text"><?php echo $location; ?></span> 
<input type="text" size="40" value="<?php echo $location; ?>"  class="editbox" id="location_input_<?php echo $id; ?>"/>
</td>
<td width="50%" class="edit_td">
<span id="convention_<?php echo $id; ?>" class="text"><?php echo $convention; ?></span> 
<input type="text" size="15" value="<?php echo $convention; ?>"  class="editbox" id="convention_input_<?php echo $id; ?>"/>
</td>
</tr>
<?php
$i++;
}
?>

</table>

</div>  

    <div id="tab-3">
      <h3>Report generation</h3>
	<form>
		Please select a report:<br />
		<select id="mySelect">
		<option>assets</option>
		<option>cover_sheet</option>
		<option>exec_summary</option>
		<option>exec_summary_detailed</option>
		<option>stig_findings_summary</option>
		<option>finding_statistics</option>
		<option>findings_host</option>
		<option>findings_summary</option>
		<option>findings_summary_with_pluginid</option>
		<option>graphs</option>
		<option>host_summary</option>
		<option>historical</option>
		<option>ms_patch_summary</option>
		<option>ms_update_summary</option>
		<option>Ms_wsus_findings</option>
		<option>notable</option>
		<option>notable_detailed</option>
		<option>pci_compliance</option>
		<option>technical_findings</option>
		</select>
		<br/> <br/>
		<input type="button" value="report" onClick ="gen()">
	</form>
  	
    </div>
    
    
    <div id="tab-4">

	<h3>Load a Nmap file</h3> 
   <table width="80%"><tr>
	<td width="150px" valign="center">
	</td>
	<td valign="center">
	<br><br>
	<table cellspacing="5" cellpadding="5" width="600">
		<tr>
			<td colspan="4">
				<form enctype="multipart/form-data" action="lib/nmap/parse.php" method="POST">
				<input type="hidden" name="MAX_FILE_SIZE" value="2000000000" />
				<img src="images/nessus_logo.png"></img>
				<p>The Nmap file will be uploaded, parsed, and added to the backend MySQL database.</p>
			</td>
		</tr>
		<tr>
			<td><p>Select an Nmap (.XML) file: </p></td><td><input name="userfile" type="file" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Process File" />
				</form>
			</td>
		</tr>
	</table>
	</td>
</tr></table>

    </div>
  </div>
</div>

</div>
</div>


</body>
</html>
