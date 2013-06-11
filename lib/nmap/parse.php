<?php
/**************************************************************
*    <VULRIS parse.php.>
*	 NMAP parser.
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

$filename = $_FILES['userfile']['name'];
$uploaddir = sys_get_temp_dir();
$uploadfile = tempnam(sys_get_temp_dir(), basename($_FILES['userfile']['name']));
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	echo "<hr><p align=\"center\"><b>File is valid, and was successfully uploaded.</b></p><hr>";
	} else { 
		echo "<h1>Upload Error!</h1>";
		echo "An error occurred while executing this script. The file may be too large or the upload folder may have insufficient permissions.";
		echo "<p />";
		echo "Please examine the following items to see if there is an issue";
		echo "<hr><pre>";
		echo "1.  ".$uploaddir." (Temp) directory exists and has the correct permissions.<br />";
		echo "2.  The php.ini file needs to be modified to allow for larger file uploads.<br />";
		echo "</pre><hr>";
		exit; 
}

if(file_exists($uploadfile)) {
	$xml = simplexml_load_file($uploadfile); 
}
else { 
	exit('Failed to process the xml file.');
} 

include('../main/config.php'); 
require_once( 'DB.php' );
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if(mysqli_connect_errno()){
  echo "<p> Connect failed in Check_SQL()</p>";
exit();
}
$nmaprun = $xml;
$scaninfo = $xml->scaninfo;
$runstats_finished = $xml->runstats->finished;
$runstats_hosts = $xml->runstats->hosts;
$sql = "INSERT INTO nmap_scan ( filename, scanner, type, time, summary, hosts_up, hosts_down, 
				hosts_total) 
		VALUES ( '$filename','$nmaprun[scanner]', '$scaninfo[type]', '$runstats_finished[timestr]',  
			'$runstats_finished[summary]', '$runstats_hosts[up]', '$runstats_hosts[down]', 
			'$runstats_hosts[total]')";
$results = $db->query($sql);
$sql = "SELECT LAST_INSERT_ID()";
$scan_id = $db->insert_id;
echo "<p> $sql </p>";


$validHosts = array();
$sql = "SELECT id, ip from hosts";
$result = $db->query($sql);

    while($tuple = $result->fetch_assoc()){
       $validHosts[$tuple["id"]] = $tuple["ip"];   
    }
echo "<p> $sql </p> ";

foreach($xml->host as $host){
	$status_state = $host->status["state"];
	foreach($host->address as $address){
		if($address["addrtype"] == "ipv4"){
			$ip_address = $address["addr"];
		}

	}
if(array_search($ip_address, $validHosts) ){
$vulrisHostID = array_search($ip_address, $validHosts);

	$hostname = $host->hostnames->hostname[name];
	$OS = $host->os->osmatch[name];
	$uptime = $host->uptime[seconds];
	$uptime_lastboot = $host->uptime[lastboot];
	$distance = $host->distance[value];
	$trace = $host->trace;

	if(isset($host->hostscript)){
		foreach($host->hostscript->script as $hS){
			$script_id = $host->hostscript->script[id];
			$script_output = $hS["output"];
		}
	}
	$script_output = addslashes($script_output);
	foreach($host->ports->port as $port){
		if($port->state[state] != 'closed'){
		$ports .= $port[protocol]." ".$port[portid]." ".$port->service[name]." ".$port->service[product]." - ";
		}

	$script_output = str_replace(',',' ', $script_output);


 $sql = "INSERT INTO nmap ( host_id, scan_id, hostname, OS, uptime, last_boot, distance, trace, script_id, script_output, ports ) 
		VALUES( $vulrisHostID, $scan_id, '$hostname', '$OS', '$uptime', '$uptime_lastboot', '$distance', '$trace', '$script_id', 
			'$script_output', '$ports') ";
		$results = $db->query($sql);

	echo "<p> $sql </p>";	
        
		
	}//end port foreach
   }//end host foreach
}//end if

?>

