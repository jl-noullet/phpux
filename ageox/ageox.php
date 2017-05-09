<?php
// $server = 'mysql5-27';
$server = 'sourcecojln.mysql.db';
$base = 'sourcecojln';
$user = 'sourcecojln';
$pass = 'xxxxxxxx';
$conn = mysqli_connect( $server, $user, $pass, $base );
if (!$conn) { die("echec connexion serveur et base"); }

// initialiser geoip
require_once '../geoip/geoip2.phar';
use GeoIp2\Database\Reader;
// This creates the Reader object, which should be reused
$reader = new Reader('../geoip/GeoLite2-City.mmdb');

$sqlrequest = "SELECT * FROM ssv1 ORDER BY date DESC;";

$result = $conn->query( $sqlrequest );
if	(!$result) echo "erreur base de donnees " . $sqlrequest;
else	{
	echo '<table border="1" cellpadding="8">';
	while	( $row = mysqli_fetch_assoc($result) )
		{
		echo '<tr>';
		// foreach	( $row as $elem )
		//		echo '<td>' . $elem . '</td>';
		echo '<td>' . $row[date] . '</td>';
		echo '<td>' . $row[ip] . '</td>';
		echo '<td>' . $row[v] . '</td>';
		echo '<td>' . $row[agent] . '</td>';
		// on a l'essentiel, reste a faire la geoloc
		$ip = $row[ip];
		if	( !isset( $geoloc[$ip] ) )
			{
			$record = $reader->city($ip);
			$geoloc[$ip]  = '<td>' . $record->country->isoCode . '</td>';
			$geoloc[$ip] .= '<td>' . $record->postal->code . ' ' . $record->city->name . '</td>';
			$geoloc[$ip] .= '<td>' . $record->location->latitude . ',' . $record->location->longitude . '</td>';
			$geoloc[$ip] .= '<td>' . $record->location->accuracyRadius . '</td>';
			}
		echo $geoloc[$ip];
		echo "</tr>\n";
		}
	echo '</table>';
	}
?>

