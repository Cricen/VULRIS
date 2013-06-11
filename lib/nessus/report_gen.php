<?php

/**************************************************************
*    <VULRIS report_gen.php.>
*	 Nessus report generation.
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
 *This program creates the report file name and
 *runs the risu command. If you have used a different 
 *ruby / gems install set the HOME path accordingly
 */
//ini_set('display_errors',1);
//error_reporting(E_ALL);
$report=$_GET["q"];
$fileName = $report ."-". date('M-d-Y');
$cmd = 'export HOME=/usr/lib64/ruby/gems/1.8/gems; risu -t '.$report.' -o /var/www/html/Reports/'. $fileName.'.pdf 2>&1';
$output= shell_exec($cmd);
echo $output;
echo "$fileName";

?>


