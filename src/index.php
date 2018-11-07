<?php

/** 
 * index.php
 *
 * Pàgina principal.
 */

require_once('Config.php');
require_once('LibDB.php');
require_once('LibHTML.php');

$conn = new mysqli($CFG->Host, $CFG->Usuari, $CFG->Password, $CFG->BaseDades);
if ($conn->connect_error) {
  die("ERROR: Unable to connect: " . $conn->connect_error);
} 

CreaIniciHTML('inGest');

$SQL = ' SELECT * FROM CICLE_FORMATIU ORDER BY grau';

$ResultSet = $conn->query($SQL);

if ($ResultSet->num_rows > 0) {
	echo "<TABLE>";
	echo "<TH>Grau</TH>";
	echo "<TH>Codi</TH>";
	echo "<TH>Codi XTEC</TH>";
	echo "<TH>Nom</TH>";
	
	$row = $ResultSet->fetch_assoc();
	while($row) {
		echo "<TR>";
		echo "<TD>".$row["grau"]."</TD>";
		echo "<TD>".$row["codi"]."</TD>";
		echo "<TD>".$row["codi_xtec"]."</TD>";
		echo "<TD>".utf8_encode($row["nom"])."</TD>";
		echo "<TD><A HREF=AlumnesCicle.php?CicleId=".$row["cicle_formatiu_id"].">Alumnes</A></TD>";
		$row = $ResultSet->fetch_assoc();
	}
	echo "</TABLE>";
};	

echo "<A HREF=FormMatricula.php>Matriculació alumnes</A>";

echo "<DIV id=debug></DIV>";

$ResultSet->close();

$conn->close();

?>















