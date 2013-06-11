/**************************************************************
*    <VULRIS VULRIS_schema.sql.>
* 	 Complete table structure and stored procedures for VULRIS 
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

-- MySQL dump 10.13  Distrib 5.5.29, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: VULRIS
-- ------------------------------------------------------
-- Server version	5.5.29-0ubuntu0.12.10.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE IF NOT EXISTS `VULRIS`;
USE `VULRIS`;
--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `departments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `hostname_convention` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `family_selections`
--

DROP TABLE IF EXISTS `family_selections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `family_selections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1322 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `hosts`
--

DROP TABLE IF EXISTS `hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hosts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_id` int(11) DEFAULT NULL,
  `system_admin_id` int(11) DEFAULT NULL,
  `system_admin2_id` int(11) DEFAULT NULL,
  `firstseen` datetime DEFAULT NULL,
  `lastseen` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `mac` varchar(255) DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `fqdn` varchar(255) DEFAULT NULL,
  `netbios` varchar(255) DEFAULT NULL,
  `local_checks_proto` varchar(255) DEFAULT NULL,
  `smb_login_used` varchar(255) DEFAULT NULL,
  `ssh_auth_meth` varchar(255) DEFAULT NULL,
  `ssh_login_used` varchar(255) DEFAULT NULL,
  `pci_dss_compliance` varchar(255) DEFAULT NULL,
  `pci_dss_compliance_` varchar(255) DEFAULT NULL,
  `pcidss_compliance_failed` varchar(255) DEFAULT NULL,
  `pcidss_compliance_passed` varchar(255) DEFAULT NULL,
  `pcidss_deprecated_ssl` varchar(255) DEFAULT NULL,
  `pcidss_expired_ssl_certificate` varchar(255) DEFAULT NULL,
  `pcidss_obsolete_operating_system` varchar(255) DEFAULT NULL,
  `pcidss_dns_zone_transfer` varchar(255) DEFAULT NULL,
  `pcidss_high_risk_flaw` varchar(255) DEFAULT NULL,
  `pcidss_medium_risk_flaw` varchar(255) DEFAULT NULL,
  `pcidss_reachable_db` varchar(255) DEFAULT NULL,
  `pcidss_www_xss` varchar(255) DEFAULT NULL,
  `pcidss_directory_browsing` varchar(255) DEFAULT NULL,
  `pcidss_known_credentials` varchar(255) DEFAULT NULL,
  `pcidss_compromised_host_worm` varchar(255) DEFAULT NULL,
  `pcidss_unprotected_mssql_db` varchar(255) DEFAULT NULL,
  `pcidss_obsolete_software` varchar(255) DEFAULT NULL,
  `pcidss_www_sql_injection` varchar(255) DEFAULT NULL,
  `pcidss_backup_files` varchar(255) DEFAULT NULL,
  `system_type` varchar(255) DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2595 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `individual_plugin_selections`
--

DROP TABLE IF EXISTS `individual_plugin_selections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `individual_plugin_selections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` varchar(255) DEFAULT NULL,
  `plugin_id` int(11) DEFAULT NULL,
  `plugin_name` varchar(255) DEFAULT NULL,
  `family` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25906 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) DEFAULT NULL,
  `plugin_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `firstseen` datetime DEFAULT NULL,
  `lastseen` datetime DEFAULT NULL,
  `plugin_output` text,
  `port` int(11) DEFAULT NULL,
  `svc_name` varchar(255) DEFAULT NULL,
  `protocol` varchar(255) DEFAULT NULL,
  `severity` int(11) DEFAULT NULL,
  `plugin_name` varchar(255) DEFAULT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `cm_compliance_info` varchar(255) DEFAULT NULL,
  `cm_compliance_actual_value` varchar(255) DEFAULT NULL,
  `cm_compliance_check_id` varchar(255) DEFAULT NULL,
  `cm_compliance_policy_value` varchar(255) DEFAULT NULL,
  `cm_compliance_audit_file` varchar(255) DEFAULT NULL,
  `cm_compliance_check_name` varchar(255) DEFAULT NULL,
  `cm_compliance_result` varchar(255) DEFAULT NULL,
  `cm_compliance_output` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_items_on_host_id` (`host_id`),
  KEY `index_items_on_plugin_id` (`plugin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12760 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `patches`
--

DROP TABLE IF EXISTS `patches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `patches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25936 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plugins`
--

DROP TABLE IF EXISTS `plugins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(255) DEFAULT NULL,
  `family_name` varchar(255) DEFAULT NULL,
  `description` text,
  `plugin_version` varchar(255) DEFAULT NULL,
  `plugin_publication_date` datetime DEFAULT NULL,
  `plugin_modification_date` datetime DEFAULT NULL,
  `vuln_publication_date` datetime DEFAULT NULL,
  `cvss_vector` varchar(255) DEFAULT NULL,
  `cvss_base_score` varchar(255) DEFAULT NULL,
  `cvss_temporal_score` varchar(255) DEFAULT NULL,
  `cvss_temporal_vector` varchar(255) DEFAULT NULL,
  `exploitability_ease` varchar(255) DEFAULT NULL,
  `exploit_framework_core` varchar(255) DEFAULT NULL,
  `exploit_framework_metasploit` varchar(255) DEFAULT NULL,
  `metasploit_name` varchar(255) DEFAULT NULL,
  `exploit_framework_canvas` varchar(255) DEFAULT NULL,
  `canvas_package` varchar(255) DEFAULT NULL,
  `exploit_available` varchar(255) DEFAULT NULL,
  `risk_factor` varchar(255) DEFAULT NULL,
  `solution` text,
  `synopsis` text,
  `plugin_type` varchar(255) DEFAULT NULL,
  `exploit_framework_exploithub` varchar(255) DEFAULT NULL,
  `exploithub_sku` varchar(255) DEFAULT NULL,
  `stig_severity` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `always_run` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25906 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `plugins_preferences`
--

DROP TABLE IF EXISTS `plugins_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugins_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) DEFAULT NULL,
  `plugin_id` int(11) DEFAULT NULL,
  `plugin_name` varchar(255) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `preference_name` varchar(255) DEFAULT NULL,
  `preference_type` varchar(255) DEFAULT NULL,
  `preference_values` varchar(255) DEFAULT NULL,
  `selected_values` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `policies`
--

DROP TABLE IF EXISTS `policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `server_preferences`
--

DROP TABLE IF EXISTS `server_preferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `server_preferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6775 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `service_descriptions`
--

DROP TABLE IF EXISTS `service_descriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `service_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `port` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25906 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `system_admin`
--

DROP TABLE IF EXISTS `system_admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `system_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `department2_id` int(11) DEFAULT NULL,
  `department3_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `v_references`
--

DROP TABLE IF EXISTS `vreferences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vreferences` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plugin_id` int(11) DEFAULT NULL,
  `reference_name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `index_references_on_plugin_id` (`plugin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14670 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `versions`
--

DROP TABLE IF EXISTS `versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `versions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25906 DEFAULT CHARSET=latin1;

/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

DROP TABLE IF EXISTS `nmap`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nmap` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) DEFAULT NULL,
  `scan_id` int(11) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `OS` varchar(255) DEFAULT NULL,
  `uptime` int(11) DEFAULT NULL,
  `last_boot` varchar(255) DEFAULT NULL,
  `distance` int(11) DEFAULT NULL,
  `trace` varchar(255) DEFAULT NULL,
  `script_id` varchar(255) DEFAULT NULL,
  `script_output` varchar(255) DEFAULT NULL,
  `ports` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

DROP TABLE IF EXISTS `nmap_scan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `nmap_scan` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `scanner` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `hosts_up` int(11) DEFAULT NULL,
  `hosts_down` int(11) DEFAULT NULL,
  `hosts_total` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Dump completed on 2013-03-30 20:45:22
DROP PROCEDURE IF EXISTS Host_check;
DELIMITER //
CREATE PROCEDURE Host_check( nreport_id int,nsystem_admin_id int,nsystem_admin2_id int,nfirstseen datetime,nlastseen datetime, 
			     nactive int,nname text,nos text,nmac text,nstart datetime,nend datetime,nip text,nfqdn text,nnetbios text,
			     nlocal_checks_proto text,nsmb_login_used text,nssh_auth_meth text,nssh_login_used text,npci_dss_compliance text,
			     npci_dss_compliance_ text,npcidss_compliance_failed text,npcidss_compliance_passed text,
			     npcidss_deprecated_ssl text,npcidss_expired_ssl_certificate text,
			     npcidss_obsolete_operating_system text,npcidss_dns_zone_transfer text,
			     npcidss_high_risk_flaw text,npcidss_medium_risk_flaw text,npcidss_reachable_db text,
			     npcidss_www_xss text,npcidss_directory_browsing text,npcidss_known_credentials text,
			     npcidss_compromised_host_worm text,npcidss_unprotected_mssql_db text,
			     npcidss_obsolete_software text,npcidss_www_sql_injection text,npcidss_backup_files text,
			     nsystem_type text,nnotes text)
			  
	    
  BEGIN

  if nip IN (SELECT ip FROM hosts) THEN

/*DECLARE pre_id INT SELECT id INTO pre_id From hosts where ip = nip AND mac = nmac;*/

    UPDATE hosts SET
		 report_id = IFNULL(nreport_id, report_id),
		 system_admin_id = IFNULL(nsystem_admin_id, system_admin_id),
		 system_admin2_id = IFNULL(nsystem_admin2_id, system_admin2_id),
	 	 firstseen = IFNULL(nfirstseen, firstseen),
		 lastseen = IFNULL(nlastseen, lastseen),
		 active = IFNULL(nactive, active), 
		 name = IFNULL(nname, name),
		 os = IFNULL(nos, os),
		 mac = IFNULL(nmac, mac),
		 hosts.start = IFNULL(nstart, hosts.start), 
		 hosts.end = IFNULL(nend, hosts.end),
		 ip = IFNULL(nip, ip),
		 fqdn = IFNULL(nfqdn, fqdn),
		 netbios = IFNULL(nnetbios, netbios),
		 local_checks_proto = IFNULL(nlocal_checks_proto, local_checks_proto),
		 smb_login_used = IFNULL(nsmb_login_used, smb_login_used),
		 ssh_auth_meth = IFNULL(nssh_auth_meth, ssh_auth_meth),
		 ssh_login_used = IFNULL(nssh_login_used, ssh_login_used),
		 pci_dss_compliance = IFNULL(npci_dss_compliance, pci_dss_compliance),
		 pci_dss_compliance_ = IFNULL(npci_dss_compliance_, pci_dss_compliance_),
		 pcidss_compliance_failed = IFNULL(npcidss_compliance_failed, pcidss_compliance_failed),
		 pcidss_compliance_passed = IFNULL(npcidss_compliance_passed,  pcidss_compliance_passed),
		 pcidss_deprecated_ssl = IFNULL(pcidss_deprecated_ssl, pcidss_deprecated_ssl),
		 pcidss_expired_ssl_certificate = IFNULL(npcidss_expired_ssl_certificate, pcidss_expired_ssl_certificate),
		 pcidss_obsolete_operating_system = IFNULL(npcidss_obsolete_operating_system, pcidss_obsolete_operating_system),
		 pcidss_dns_zone_transfer = IFNULL(npcidss_dns_zone_transfer, pcidss_dns_zone_transfer),
		 pcidss_high_risk_flaw = IFNULL(npcidss_high_risk_flaw, pcidss_high_risk_flaw),
		 pcidss_medium_risk_flaw = IFNULL(npcidss_medium_risk_flaw, pcidss_medium_risk_flaw),
		 pcidss_reachable_db = IFNULL(npcidss_reachable_db, pcidss_reachable_db),
		 pcidss_www_xss = IFNULL( npcidss_www_xss, pcidss_www_xss),
		 pcidss_directory_browsing = IFNULL(npcidss_directory_browsing, pcidss_directory_browsing),
		 pcidss_known_credentials = IFNULL(npcidss_known_credentials, pcidss_known_credentials),
		 pcidss_compromised_host_worm = IFNULL(npcidss_compromised_host_worm, pcidss_compromised_host_worm),
		 pcidss_unprotected_mssql_db = IFNULL(npcidss_unprotected_mssql_db, pcidss_unprotected_mssql_db),
		 pcidss_obsolete_software = IFNULL(npcidss_obsolete_software, pcidss_obsolete_software),
		 pcidss_www_sql_injection = IFNULL(npcidss_www_sql_injection, pcidss_www_sql_injection),
		 pcidss_backup_files = IFNULL(npcidss_backup_files, pcidss_backup_files),
		 system_type = IFNULL(nsystem_type, system_type),
		 notes = IFNULL(nnotes, notes)
	WHERE ip=nip;
   else
        INSERT INTO hosts 
			(report_id,system_admin_id,system_admin2_id,firstseen,lastseen, 
			active,name,os,mac,start,end,ip,fqdn,netbios,local_checks_proto,
		        smb_login_used,ssh_auth_meth,ssh_login_used,pci_dss_compliance,
			pci_dss_compliance_,pcidss_compliance_failed,pcidss_compliance_passed,
		        pcidss_deprecated_ssl,pcidss_expired_ssl_certificate,
			pcidss_obsolete_operating_system,pcidss_dns_zone_transfer,
			pcidss_high_risk_flaw,pcidss_medium_risk_flaw,pcidss_reachable_db,
			pcidss_www_xss,pcidss_directory_browsing,pcidss_known_credentials,
			pcidss_compromised_host_worm,pcidss_unprotected_mssql_db,
			pcidss_obsolete_software,pcidss_www_sql_injection,pcidss_backup_files,
			system_type,notes)

		 VALUES(nreport_id, nsystem_admin_id, nsystem_admin2_id, nfirstseen, nlastseen, 
		        nactive, nname, nos, nmac, nstart, nend, nip, nfqdn, nnetbios,
		        nlocal_checks_proto, nsmb_login_used, nssh_auth_meth, nssh_login_used, npci_dss_compliance,
			npci_dss_compliance_, npcidss_compliance_failed, npcidss_compliance_passed,
  			npcidss_deprecated_ssl, npcidss_expired_ssl_certificate,
			npcidss_obsolete_operating_system, npcidss_dns_zone_transfer,
			npcidss_high_risk_flaw, npcidss_medium_risk_flaw, npcidss_reachable_db,
			npcidss_www_xss, npcidss_directory_browsing, npcidss_known_credentials,
			npcidss_compromised_host_worm, npcidss_unprotected_mssql_db,
			npcidss_obsolete_software, npcidss_www_sql_injection, npcidss_backup_files,
			nsystem_type, nnotes);

    end if;

end //

DROP PROCEDURE IF EXISTS Items_check;

 //
CREATE PROCEDURE Items_check( Nhost_id int, Nplugin_id int, Nstatus int, Nfirstseen datetime,
  			      Nlastseen datetime, Nplugin_output text, Nport int, Nsvc_name varchar(255), 
			      Nprotocol varchar(255), Nseverity int, Nplugin_name varchar(255), Nverified tinyint(1),
  			      Ncm_compliance_info varchar(255), Ncm_compliance_actual_value varchar(255),
  			      Ncm_compliance_check_id varchar(255), Ncm_compliance_policy_value varchar(255),
			      Ncm_compliance_audit_file varchar(255), Ncm_compliance_check_name varchar(255),
			      Ncm_compliance_result varchar(255), Ncm_compliance_output varchar(255))
			      
  BEGIN

  if (Nhost_id , Nplugin_id) IN (SELECT host_id, plugin_id FROM items) THEN

/*DECLARE pre_id INT SELECT id INTO pre_id From hosts where ip = nip AND mac = nmac;*/

    UPDATE items SET
		status = 2,
		lastseen = Nlastseen

	WHERE host_id=Nhost_id AND plugin_id=Nplugin_id;
   else
        INSERT INTO items 	
			(host_id,plugin_id,status,firstseen,lastseen,plugin_output,port,
			 svc_name,protocol,severity,plugin_name,verified,cm_compliance_info,
			 cm_compliance_actual_value,cm_compliance_check_id,cm_compliance_policy_value,
			 cm_compliance_audit_file,cm_compliance_check_name,cm_compliance_result,
			 cm_compliance_output) 
		VALUES 
			(Nhost_id,Nplugin_id,1,Nfirstseen,Nlastseen,Nplugin_output,Nport,
			 Nsvc_name,Nprotocol,Nseverity,Nplugin_name,Nverified,Ncm_compliance_info,
			 Ncm_compliance_actual_value,Ncm_compliance_check_id,Ncm_compliance_policy_value,
			 Ncm_compliance_audit_file,Ncm_compliance_check_name,Ncm_compliance_result,
			 Ncm_compliance_output) ;
    end if;

end //
DROP PROCEDURE IF EXISTS department_check;

 //
CREATE PROCEDURE department_check(newID int, newName varchar(255), newLocation varchar(255), newHostname_convention varchar(255))
			      
  BEGIN

  if (newID , newName) IN (SELECT id, department_name FROM departments) THEN


    UPDATE departments SET
		department_name = IFNULL(newName,department_name),
		location = IFNULL(newLocation,location),
		hostname_convention=IFNULL(newHostname_convention,hostname_convention)

	WHERE id=newID;
   else
        INSERT INTO departments 	
			(department_name,location,hostname_convention) 
		VALUES 
			(newName,newLocation,newHostname_convention);
    end if;

end //



ALTER TABLE versions
   ADD UNIQUE (version);
   
ALTER TABLE vreferences
   ADD UNIQUE (plugin_id ,reference_name ,value);

ALTER TABLE departments
	ADD UNIQUE (department_name);

ALTER TABLE family_selections 
	ADD UNIQUE (policy_id, family_name);

ALTER TABLE individual_plugin_selections
	ADD UNIQUE (policy_id ,plugin_id);

ALTER TABLE patches 
	ADD UNIQUE (host_id,name);

ALTER TABLE plugins 
	ADD UNIQUE (id,plugin_name,family_name);

ALTER TABLE plugins_preferences
	ADD UNIQUE (policy_id,plugin_id);

ALTER TABLE policies
	ADD UNIQUE (name, comments);
         
ALTER TABLE reports
	ADD UNIQUE (policy_id,name);

ALTER TABLE server_preferences
	ADD UNIQUE (policy_id,name);

ALTER TABLE service_descriptions
	ADD UNIQUE (name,port);

ALTER TABLE system_admins
	ADD UNIQUE (admin);

ALTER TABLE items
	ADD UNIQUE (host_id, plugin_id);

ALTER TABLE hosts
	ADD UNIQUE (ip, mac);
	
ALTER TABLE nmap 
	ADD UNIQUE (host_id, scan_id);

INSERT INTO departments (department_name,location,hostname_convention) 
	VALUES 
	('Department','Nowhere','0.0.0.0');
	
	
INSERT INTO system_admins (admin, phone_number, email, department_id)
	VALUES
	('Admin','867-5309','csumb@csumb.edu',1);

