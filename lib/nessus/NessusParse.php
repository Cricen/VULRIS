<?php
/**************************************************************
*    <VULRIS NessusParse.php.>
*	 This program takes a .nesses file and goes through
*	 The Nessus parser takes a Nessus file and goes through 
*	 each section of the XML and sends the XML data to the 
*	 MySQL database. The program can be segmented into two 
*	 main portions, the plugin and preference portion which 
*	 gives information about the Nessus scan and the reported 
*	 hosts & items. Please see 
*	 http://static.tenable.com/documentation/nessus_v2_file_format.pdf 
*	 for more information.
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




/*
 *get_HostDepartment takes an ip address and an array of 
 *departments.id => hostname_conventions and returns the
 *department id of the convention that ip belongs to.
 *This is needed to determin a host's admin name.
 *
 */
function get_HostDepartment($ip, $convention)
{
$pieces = explode(".", strval($ip));

 foreach( $convention as $id => $network)
  { 
    $networkElements = explode(".", $network);
    //checks for class C address
    if( $networkElements[2] == $pieces[2] AND
	$networkElements[1] == $pieces[1] AND
        $networkElements[0] == $pieces[0] AND 
	$networkElements[3] == "0") {
	return $id;
   }
   //checks for class B address
   elseif( $networkElements[1] == $pieces[1] AND
            $networkElements[0] == $pieces[0] AND
	    $networkElements[2] == "0") {
	 return $id;
            }
   //checks for class C address
    elseif($networkElements[0] == $pieces[0] AND $networkElements[1] == "0" ){
	return $id;
    }
  }
}
/*
 *Check SQL function checks for an SQL id element using
 *two other elements a table name and a database connection.
 *returns null if empty
 */

function Check_SQL($table, $tag1,$tag2,$value1,$value2,$db){
$sql = "SELECT id from $table where $tag1=$value1 and $tag2=$value2";
//echo "<p> $sql </p>"; //Quick note: there are lots of these meant for troubleshooting. 
if(mysqli_connect_errno()){
echo "<p> Connect failed in Check_SQL()</p>";
}

if($result = $db->query($sql)){ 
	while($tuple = $result->fetch_assoc()){
		$num = $tuple["id"];
		}
	$result->free();
    }
if(!empty($num)){
    return $num;
    }
else{
 return null;
 }
}

/***********************************************
 *This first section handlses the uploaded file
 *and sets up the DB connection 
 ***********************************************/
$uploaddir = sys_get_temp_dir();
$uploadfile = tempnam(sys_get_temp_dir(), basename($_FILES['userfile']['name']));
$itemArray = array();
$allHosts = array();
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	   echo "<p>File was uploaded and successfully parsed.</p>";	
	   if(isset($_SERVER['HTTP REFERER'])){
	     echo "<a href=".$_SERVER['HTTP REFERER']."> BACK</a>";
	     }
	   } 
	else { 
		echo "<h1>Upload Error!</h1>";
		echo "An error occurred while executing this script. The file may be too large or the upload folder may have insufficient permissions.";
		echo "<p />";
		echo "Please examine the following items to see if there is an issue";
		echo "<hr><pre>";
		echo "1.  ".$uploaddir." (Temp) directory exists and has the correct permissions.<br />";
		echo "2.  The php.ini file needs to be modified to allow for larger file uploads.<br />";
	        if(isset($_SERVER['HTTP REFERER'])){
	   	  echo "<a href=".$_SERVER['HTTP REFERER']."> BACK</a>";
	  	   }
		echo "</pre><hr>";
		exit; 
}

//The $xml variable holds the uploaded nessus file struct
if(file_exists($uploadfile)) { 
	$xml = simplexml_load_file($uploadfile);
} 
else { 
	exit('Failed to open the xml file');
} 

include('config.php');
//require_once( 'DB.php' );
$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname); #DB::connect( "mysql://$dbuser:$dbpass@$dbhost/$dbname" );
if(mysqli_connect_errno()){
  echo "<p> Connect failed in Check_SQL()</p>";
exit();
}

$randValue = rand();
$startScanArray = array();
$endScanArray = array();
$newTag = array();
$convention = array();
$sql = "SELECT id, hostname_convention from departments";
$result = $db->query($sql);

    while($tuple = $result->fetch_assoc()){
       $convention[$tuple["id"]] = $tuple["hostname_convention"];   
    }
//$Policy = $xml->Policy;
//print_r($Policy);

/**************************************************
*Policies Table
*
***************************************************/
$policy_name = $xml->Policy->policyName;
$policy_comments = $xml->Policy->policyComments;

if(!$policy_ID){
        $sql = "INSERT INTO policies 
                VALUES {name, comments}
                    ('$policy_name','$policy_comments')";
 //echo "<p> $sql </p>";       
	$result = $db->query($sql);//ifDBError($result);
        $policy_ID = $db->insert_id; //= $db->getRow($sql);ifDBError($result);
}

//echo "<p> policy_ID $policy_ID</p><br/>";

/**************************************************
*
*server_preferences table
***************************************************/
$ServerPreferences = $Policy->Preferences->ServerPreferences;
//$p = $ServerPreferences->preference;
//print_r($p);
foreach ($ServerPreferences->preference as $preference){
$preference_name = $preference->name;
$preference_value = $preference->value;

$sql = "INSERT INTO server_preferences
                     (policy_id,name,value)
                 VALUES 
                     ($policy_ID,'$preference_name','$preference_value')";
//	echo "<p> $sql </p>";
        $result = $db->query($sql);//ifDBError($result);
        $sql = "SELECT LAST_INSERT_ID()";
//for these select last inserts try fetch
//	$server_preferences_ID = mysql_insert_id();
      $server_preferences_ID = $db->insert_id;
//	$server_preferences_ID = $db->getRow($sql);ifDBError($result);
//echo "<p>server_preference_ID $server_preferences_ID </p>";
}

/***********************************************
*plugins_preferences table
*
************************************************/
$PluginsPreferences = $Policy->Preferences->PluginsPreferences;
//print_r($PluginsPreferences->PluginsPreferences);
foreach ($PluginsPreferences->item as $tag){
	foreach($tag as $key => $value){
		switch($key){
			case "pluginName":
				$PluginsPreferences_pluginName = $value;
				break;
			case "pluginId":
				$PluginsPreferences_pluginID = $value;
				break;
			case "fullName":
				$PluginsPreferences_fullName = $value;
				break;
			case "preferenceName":
				$PluginsPreferences_name = $value;
				break;
			case "preferenceType":
				$PluginsPreferences_preference_type = $value;
				break;
			case "preferenceValues":
				$PluginsPreferences_preference_values = $value;
				break;
			case "selectedValue":
				$PluginsPreferences_selected_values = $value;
				break;
			default:
				echo "";break; 
		}
	}
$sql = "INSERT INTO plugins_preferences
                   (policy_id,plugin_id,plugin_name,fullname,preference_name,preference_type,preference_values,selected_values)
                 VALUES 
                     ($policy_ID,'$PluginsPreferences_pluginID','$PluginsPreferences_pluginName','$PluginsPreferences_fullName',
                      '$PluginsPreferences_preference_type','$PluginsPreferences_preference_values')";

//echo "<p> $sql </p>";
        $result = $db->query($sql);//ifDBError($result);
        $sql = "SELECT LAST_INSERT_ID()";
	$server_preferences_ID = $db->insert_id; //= $db->getRow($sql);ifDBError($result);

//echo "<p>server_preference_ID: $server_preferences_ID </p>";

} 

/**************************************************
*family_selections table
*
***************************************************/

$familySelect = $Policy->FamilySelection->FamilyItem;
#print_r($familySelect);
foreach ($familySelect as $tag)
{
                                $familySelect_family_name = $tag->FamilyName;  //= $value;
                                $familySelect_Status = $tag->Status;# = $value;
// took out '' from var names, they were not loading for some reason in that syntax
$sql = "INSERT INTO family_selections
                     (policy_id,family_name,status)
                 VALUES 
                     ($policy_ID,'$familySelect_family_name','$familySelect_Status')";

//echo "<p> $sql </p>";
$result = $db->query($sql);//ifDBError($result);
}

/**************************************************
*reports table
*
***************************************************/
$report_name = $xml->Report[name];
#print_r($report);

$sql = "INSERT INTO reports
                     (policy_id,name)
                  VALUES 
                     ($policy_ID,'$report_name')";
//echo "<p> $sql </p>";
$result = $db->query($sql);//ifDBError($result);
$reports_ID = $db->insert_id; //= $db->getRow($sql);ifDBError($result);

/**************************************************
*hosts table
*
***************************************************/
foreach($xml->Report->ReportHost as $ReportHost){
	$scan_start = $randValue;
	$scan_end = $randValue;

	$host_name = $ReportHost[name];
	foreach($ReportHost->HostProperties->tag as $tag){
		switch ($tag[name]) {
			case "HOST_END":
				$host_end = date("Y-m-d h:i:s");
				break;
			case "operating-system":
				$operating_system = $tag;
				break;
			case "mac-address":
				$mac_addr = $tag;
				break;
			case "host-ip":
				$ip_addr = $tag;
				break;
			case "notes";
				$notes = $tag;
				break;
			case "host-fqdn":
				$fqdn = $tag;
				break;
			case "netbios-name":
				$netbios = $tag;
				break;
			case "HOST_START":
				$host_start = date("Y-m-d h:i:s");
				break;
			case "system-type":
				$system_type = $tag;
				break;
			case "ssh-auth-meth":
				$ssh_auth_meth = $tag;
				break;
			case "ssh-login-used":
				$ssh_login_used = $tag;
				break;
			case "smb-login-used":
				$smb_login_used = $tag;
				break;
			case "local-checks-proto":
				$local_checks_proto = $tag;
				break;

/*----------------------PCI DSS COMPLIANCE -----------------------------------------------*/

			case "pcidss:compliance:failed":
				$pcidss_compliance_failed = $tag;
				break;
			case "pcidss:compliance:passed":
				$pcidss_compliance_passed = $tag;
				break;
			case "pci-dss-compliance":
				$pcidss_compliance = $tag;
				break;
			case "pci-dss-compliance_":
				$pcidss_compliance_ = $tag;
				break;
			case "pcidss:low_risk_flaw":
				$pcidss_low_risk_flaw = $tag;
				break;
			case "pcidss:medium_risk_flaw":
				$pcidss_medium_risk_flaw = $tag;
				break;
			case "pcidss:high_risk_flaw":
				$pcidss_high_risk_flaw = $tag;
				break;
			case "pcidss:www:xss":
				$pcidss_www_xss = $tag;
				break;
			case "pcidss:known_credentials":
				$pcidss_known_credentials = $tag;
				break;
			case "pcidss:www:header_injection":
				$pcidss_www_header_injection = $tag;
				break;
			case "pcidss:directory_browsing":
				$pcidss_directory_browsing = $tag;
				break;
			case "pcidss:directory_dns":
				$pcidss_directory_dns = $tag;
				break;
			case "pcidss:obsolete_operatingdns_system":
				$pcidss_obsolete_operating_system = $tag;
				break;
			case "pcidss:deprecated_ssl":
				$pcidss_deprecated_ssl = $tag;
				break;
			case "pcidss:reachable_db":
				$pcidss_reachable_db = $tag;
				break;
			case "pcidss:expired_ssl_certificate":
				$pcidss_expired_ssl_certificate = $tag;
				break;
			case "pcidss:compromised_host_worm";
				$pcidss_compromised_host_worm = $tag;
				break;
			case "pcidss::unprotected_mssql_db";
				$pcidss_unprotected_mssql_db = $tag;
				break;
			case "pcidss:obsolete_software";
				$pcidss_obsolete_software = $tag;
				break;
			case "pcidss:backup_files";
				$pcidss_backup_files = $tag;
				break;

			default:  //who knows all the wonderful tags nessus has created.  I specifically ignore MSxx-xxx tags.
					if(!preg_match("/MS\d+-\d+/i", $tag[name])){
						$newTag[] = (string)$tag[name];
					}
			}

$host_Department = get_HostDepartment($ip_addr, $convention);

//this gets all admin id's for an ip's deparmtnet
$sql = "select id from system_admins 
	where department_id = $host_Department or
	      department2_id = $host_Department or
	      department3_id = $host_Department";
//echo "<p>$sql</p>"; 
$result = $db->query($sql);
if($result){
$row = $result->fetch_row();
$admin = $row[0];
 if($row[1]){$admin2=$row[1];}
}

$sql = "CALL Host_check($reports_ID,'$admin','$admin2','$host_start','$host_end',1,'$host_name',' $operating_system','$mac_addr',
			'$host_start','$host_end','$ip_addr','$fqdn','$netbios','$local_checks_proto','$smb_login_used',
			'$ssh_auth_meth','$ssh_login_used','$pcidss_compliance','$pcidss_compliance_',
			'$pcidss_compliance_failed','$pcidss_compliance_passed','$pcidss_deprecated_ssl',
			'$pcidss_expired_ssl_certificate','$pcidss_obsolete_operating_system','$pcidss_directory_dns',
			'$pcidss_high_risk_flaw','$pcidss_medium_risk_flaw','$pcidss_reachable_db',
			'$pcidss_www_xss','$pcidss_directory_browsing','$pcidss_known_credentials',
			'$pcidss_compromised_host_worm','$pcidss_unprotected_mssql_db','$pcidss_obsolete_software',
			'$pcidss_www_header_injection','$pcidss_backup_files','$system_type','$notes')";
}

//echo "<p> $sql </p>";

$result = $db->query($sql);//ifDBError($result);
$host_ID = $db->insert_id; //= $db->getRow($sql);ifDBError($result);
$sql = "SELECT id from hosts where ip = '$ip_addr' AND mac = '$mac_addr'";
//echo "<p> $sql </p>";
$result = $db->query($sql);
$row = $result->fetch_row();
$host_ID = $row[0];
$allHosts[] = $host_ID;
/*****************************************************************
 *                       Vulnerability tables Start
 *	Here we pick up and populate the remaining tables
 *	including the very important items table 
 *****************************************************************/
	foreach ($ReportHost->ReportItem as $ReportItem){
		$port = htmlspecialchars($ReportItem[port], ENT_QUOTES);
		$service = htmlspecialchars($ReportItem[svc_name], ENT_QUOTES);
		$protocol = htmlspecialchars($ReportItem[protocol], ENT_QUOTES);
		$severity = htmlspecialchars($ReportItem[severity], ENT_QUOTES);
		$cpe = htmlspecialchars($ReportItem[cpe], ENT_QUOTES);
		$pluginID = htmlspecialchars($ReportItem[pluginID], ENT_QUOTES);
		$pluginName = htmlspecialchars($ReportItem[pluginName], ENT_QUOTES);
		$pluginFamily = htmlspecialchars($ReportItem[pluginFamily], ENT_QUOTES);
		$Family = htmlspecialchars($ReportItem[Family], ENT_QUOTES);
		$status	= htmlspecialchars($ReportItem[Status], ENT_QUOTES);

		$exploit_framework_canvas = htmlspecialchars($ReportItem->exploit_framework_canvas, ENT_QUOTES); 
		$canvas_package = htmlspecialchars($ReportItem->canvas_package, ENT_QUOTES); 
		$exploitability_ease = htmlspecialchars($ReportItem->exploitability_ease, ENT_QUOTES);
		$exploit_framework_core = htmlspecialchars($ReportItem->exploit_framework_core, ENT_QUOTES); 
		$exploit_framework_metasploit = htmlspecialchars($ReportItem->exploit_framework_metasploit, ENT_QUOTES);
		$metasploit_name = htmlspecialchars($ReportItem->metasploit_name, ENT_QUOTES);
		$exploit_available = htmlspecialchars($ReportItem->exploit_available, ENT_QUOTES);
		$exploit_framework_exploithub = htmlspecialchars($ReportItem->exploit_framework_exploithub, ENT_QUOTES);
		$exploithub_sku = htmlspecialchars($ReportItem->exploithub_sku, ENT_QUOTES);
		$stig_severity = htmlspecialchars($ReportItem->stig_severity, ENT_QUOTES);

		$cvss_vector = htmlspecialchars($ReportItem->cvss_vector, ENT_QUOTES);
		$cvss_T_vector = htmlspecialchars($ReportItem->cvss_temporal_vector, ENT_QUOTES);
		$cvss_B_vector = htmlspecialchars($ReportItem->cvss_base_vector, ENT_QUOTES);
		$solution = htmlspecialchars($ReportItem->solution, ENT_QUOTES);
		$cvss_T_score = htmlspecialchars($ReportItem->cvss_temporal_score, ENT_QUOTES);
		$risk_factor = htmlspecialchars($ReportItem->risk_factor, ENT_QUOTES);
		$description = htmlspecialchars($ReportItem->description, ENT_QUOTES);
		$synopsis = htmlspecialchars($ReportItem->synopsis, ENT_QUOTES);

		$vuln_publication_date = htmlspecialchars($ReportItem->vuln_publication_date, ENT_QUOTES);
		$plugin_publication_date = htmlspecialchars($ReportItem->plugin_publication_date, ENT_QUOTES);
		$patch_publication_date = htmlspecialchars($ReportItem->patch_publication_date, ENT_QUOTES);
		$plugin_modification_date = htmlspecialchars($ReportItem->plugin_modification_date, ENT_QUOTES);
		$plugin_type = htmlspecialchars($ReportItem->plugin_type, ENT_QUOTES);
		$plugin_fname = htmlspecialchars($ReportItem->fname, ENT_QUOTES);
		$always_run = htmlspecialchars($ReportItem->always_run, ENT_QUOTES);
 
		$vuln_publication_date = ($vuln_publication_date == "") ? "Not known": htmlspecialchars($vuln_publication_date, ENT_QUOTES);
		$plugin_publication_date = ($plugin_publication_date == "") ? "Not known": htmlspecialchars($plugin_publication_date, ENT_QUOTES);
		$patch_publication_date = ($patch_publication_date == "") ? "Not known": htmlspecialchars($patch_publication_date, ENT_QUOTES);
		$plugin_modification_date = ($plugin_modification_date == "") ? "Not known": htmlspecialchars($plugin_modification_date, ENT_QUOTES);

		$plugin_output = htmlspecialchars($ReportItem->plugin_output, ENT_QUOTES);
		$plugin_version = htmlspecialchars($ReportItem->plugin_version, ENT_QUOTES);
		$cveList = $bidList = $osvdbList = $certList = $iavaList = $cweList = $msftList = $secuniaList = $edbList = $see_alsoList = "";


		$cm_compliance_info = htmlspecialchars($ReportItem->{'cm:compliance-info'}, ENT_QUOTES);
		$cm_compliance_actual_value = htmlspecialchars($ReportItem->{'cm:compliance-actual-value'}, ENT_QUOTES);
		$cm_compliance_check_id = htmlspecialchars($ReportItem->{'cm:compliance-check-id>'}, ENT_QUOTES);
		$cm_compliance_policy_value = htmlspecialchars($ReportItem->{'cm:compliance-actual-value'}, ENT_QUOTES);
		$cm_compliance_audit_file = htmlspecialchars($ReportItem->{'cm:compliance-audit-file'}, ENT_QUOTES);
		$cm_compliance_check_name = htmlspecialchars($ReportItem->{'cm:compliance-check-name'}, ENT_QUOTES);
		$cm_compliance_result = htmlspecialchars($ReportItem->{'cm:compliance-result'}, ENT_QUOTES);
		$cm_compliance_output = htmlspecialchars($ReportItem->{'cm:compliance-output'}, ENT_QUOTES);

		foreach($ReportItem->see_also as $see_also){
			$see_also = htmlspecialchars($see_also);
			$sql = "INSERT INTO vreferences
				(plugin_id,reference_name,value)
			VALUES
				($pluginID,'see_also','$see_also')";
			//echo "<p> $sql </p>";
        		$result = $db->query($sql);//ifDBError($result);


		}

                foreach($ReportItem->owasp as $owasp){
                        $owasp = htmlspecialchars($owasp);
                        $sql = "INSERT INTO vreferences
                                (plugin_id,reference_name,value)
                        VALUES
                                ($pluginID,'owasp','$owasp')";
                        //echo "<p> $sql </p>";
                        $result = $db->query($sql);//ifDBError($result);


                }
		foreach ($ReportItem->cve as $cve) {
			$cve = htmlspecialchars($cve);
			$sql = "INSERT INTO vreferences
				(plugin_id,reference_name,value)
			VALUES
				($pluginID,'cve','$cve')";
			//echo "<p> $sql </p>";
        		$result = $db->query($sql);//ifDBError($result);
        		$sql = "SELECT LAST_INSERT_ID()";

		}
                foreach ($ReportItem->iavb as $iavb) {
                        $iavb = htmlspecialchars($iavb);
                        $sql = "INSERT INTO vreferences
                                (plugin_id,reference_name,value)
                        VALUES
                                ($pluginID,'iavb','$iavb')";
                        //echo "<p> $sql </p>";
                        $result = $db->query($sql);//ifDBError($result);
                        $sql = "SELECT LAST_INSERT_ID()";

                }

                foreach ($ReportItem->iavt as $iavt) {
                        $iavt = htmlspecialchars($iavt);
                        $sql = "INSERT INTO vreferences
                                (plugin_id,reference_name,value)
                        VALUES
                                ($pluginID,'iavt','$iavt')";
                        //echo "<p> $sql </p>";
                        $result = $db->query($sql);//ifDBError($result);
                        $sql = "SELECT LAST_INSERT_ID()";

                }

                foreach ($ReportItem->cpe as $cpe) {
                        $cve = htmlspecialchars($cve);
                        $sql = "INSERT INTO vreferences
                                (plugin_id,reference_name,value)
                        VALUES
                                ($pluginID,'cpe','$cpe')";
                        //echo "<p> $sql </p>";
                        $result = $db->query($sql);//ifDBError($result);
                        $sql = "SELECT LAST_INSERT_ID()";

                }

		foreach ($ReportItem->bid as $bid) {
			$sql = "INSERT INTO vreferences
				(plugin_id,reference_name,value)
			VALUES
				($pluginID,'bid','$bid')";
        		$result = $db->query($sql);//ifDBError($result);
			//echo "<p> $sql </p>";
		}
		foreach ($ReportItem->xref as $xref) {
			$x = explode(":", $xref);
			switch ($x[0]) {
				case "osvdb":
					$osvdb = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'osvdb','$osvdb')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "cert":
					$cert = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'cert','$cert')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "iava":
					$iava = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'iava','$iava')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "cwe":
					$cwe = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'cwe','$cwe')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "msft":
					$msft = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'msft','$msft')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "secunia":
					$secunia = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'secunia','$secunia')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				case "edb-id":
					$ebd = htmlspecialchars($x[1]);
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'edb-id','$ebd')";
					//echo "<p> $sql </p>";
        				$result = $db->query($sql);//ifDBError($result);
					break;
				default: 
				$sql = "INSERT INTO vreferences
					(plugin_id,reference_name,value)
					VALUES
					($pluginID,'xref','$xref')";
					$result = $db->query($sql);//ifDBError($result);
					break;	
				}
			}
		
		//echo "<p> hostID $host_ID pluginID: $pluginID </p>";
		$items_ID_ = Check_SQL( 'items', 'host_id','plugin_id',$host_ID,$pluginID,$db);
		if(empty($items_ID_)){
			$sql = "CALL Items_check
					($host_ID,$pluginID,1,'$host_start','$host_end','$plugin_output','$port',
					 '$service','$protocol',$severity,'$pluginName',1,'$cm_compliance_info',
					 '$cm_compliance_actual_value','$cm_compliance_check_id','$cm_compliance_policy_value',
					 '$cm_compliance_audit_file','$cm_compliance_check_name','$cm_compliance_result',
					 '$cm_compliance_output')";	
			//echo "<p> $sql </p>";
			$result = $db->query($sql);//ifDBError($result);
			$items_ID = Check_SQL( 'items', 'host_id','plugin_id',$host_ID,$pluginID,$db);
			$itemArray[] = $items_ID;
			//echo "<p>if $items_ID </p>";
			}
		else{			
			$sql = "CALL Items_check
					($host_ID,$pluginID,1,'$host_start','$host_end','$plugin_output','$port',
					 '$service','$protocol',$severity,'$pluginName',1,'$cm_compliance_info',
					 '$cm_compliance_actual_value','$cm_compliance_check_id','$cm_compliance_policy_value',
					 '$cm_compliance_audit_file','$cm_compliance_check_name','$cm_compliance_result',
					 '$cm_compliance_output')";	
			//echo "<p> $sql </p>";
			$result = $db->query($sql);
			$items_ID = $items_ID_;
			$itemArray[] = $items_ID_;
			//echo "<p>else $items_ID_ </p>";
			}
		$sql = "INSERT INTO plugins
					( id,plugin_name,family_name,description,plugin_version,plugin_publication_date,
					 plugin_modification_date,vuln_publication_date,cvss_vector,cvss_base_score,
					 cvss_temporal_score,cvss_temporal_vector,exploitability_ease,exploit_framework_core,
					 exploit_framework_metasploit,metasploit_name,exploit_framework_canvas,
					 canvas_package,exploit_available,risk_factor,solution,synopsis,plugin_type,
					 exploit_framework_exploithub,exploithub_sku,stig_severity,fname,always_run) 

				VALUES 
					($pluginID,'$pluginName','$pluginFamily','$description','$plugin_version',
					 '$plugin_publication_date','$plugin_modification_date','$vuln_publication_date',
					 '$cvss_vector','$cvss_B_score','$cvss_T_vector','$cvss_T_score',
					 '$exploitability_ease','$exploit_framework_core','$exploit_framework_metasploit',
					 '$metasploit_name','$exploit_framework_canvas','$canvas_package','$exploit_available',
					 '$risk_factor','$solution','$synopsis','$plugin_type','$exploit_framework_exploithub',
					 '$exploithub_sku','$stig_severity','$plugin_fname','$always_run')";
			//	echo "<p> $sql </p>";
				$result = $db->query($sql);//ifDBError($result);
				

		//This looks like a look up table in which case family may be off? it could be it should be fam id?
		$sql = "INSERT INTO individual_plugin_selections
				(policy_id,plugin_id,plugin_name,family,status)
			VALUES
				($policy_ID,$pluginID,'$pluginName','$Family','$status')";
		//echo "<p> $sql </p>";
		$result = $db->query($sql);//ifDBError($result);


		if(empty($plugin_version)){
		$sql = "INSERT INTO versions
				(version)
			VALUES
				('$plugin_version')";
		$result = $db->query($sql);//ifDBError($result);
		}
//		echo "<p> $sql </p>";

		$sql = "INSERT INTO service_descriptions
				(name,port,description)
			VALUES
				('$service','$port','$description')";
		$result = $db->query($sql);//ifDBError($result);
		//echo "<p> $sql </p>";
		//This next one may be off I need to test this table in risu w/lots of .nesses files
		$sql = "INSERT INTO patches
				(host_id,name,value)
			VALUES
				($host_ID,'$service','$port')";
		$result = $db->query($sql);//ifDBError($result);
		//echo "<p> $sql </p>";
	}

}

/*************************************************
*The Items table logic section takes the unique
*host and items values that were seen in the file  
*uses the hosts to find all of the items in the 
*database and stores them. The difference between 
*these array are items that didn't show for the 
*hosts in the file(if they were in the Database) 
*we then change these items to status 3; meaning
*they are implicitly fixed.
*BUG:-MySQL by default only allows 150 conditions 
*in a WHERE Clause. So files with more than 150
*hosts may not be correctly processed. A suggested
*temporary fix is to change this MySQL setting
*or parse the same file more than once.  
**************************************************/
$uniqueArray = array_unique($itemArray);

$uallHosts = array_unique($allHosts);

$allItems = array();
$status3 = array();

$sql = "select id from items
	where host_id = '$uallHosts[0]'";

for ($i = 1; $i < count($uallHosts); ++$i) {
    if(!empty($uallHosts[$i])){
        $sql .= " OR host_id = '$uallHosts[$i]'";
      }
    }
//echo $sql;
$result = $db->query($sql);

    while ($row = mysqli_fetch_assoc($result)) {
      $allItems[] = $row["id"];
    }
$status3 = array_values(array_diff($allItems, $uniqueArray));

$sql = "UPDATE items
	SET status=3
	WHERE id = '$status3[0]'";
$x = count($status3);

for ($i = 1; $i < count($status3); ++$i) {
    if(!empty($status3[$i])){
        $sql .= " OR id = '$status3[$i]'";
      }
    }
//echo "<p>$sql</p>";
$result = $db->query($sql);
?>


