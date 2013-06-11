//Javascript Document 
/**************************************************************
*    <VULRIS reportHandler.js.>
*	 This script takes the mySelect form element from 
*	 index.html and sends the selection to report_gen.php
*	 using the ajax XMLHttpRequest object. when report_gen
*	 returns the file name a new window is opended and the
* 	 file is downloaded. 
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


function gen(){

var x=document.getElementById("mySelect").selectedIndex;
var y=document.getElementById("mySelect").options;
var report = y[x].text; //set the selected text
xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    var name = xmlhttp.responseText;
    alert(xmlhttp.responseText);
    var url='Reports/'+name+'.pdf';
    window.open(url,'Download');
    }
  }
xmlhttp.open("GET","lib/nessus/report_gen.php?q="+report,true);
xmlhttp.send();

}
