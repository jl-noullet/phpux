<?php
$server = 'mysql5-27';
$base = 'sourcecojln';
$user = 'sourcecojln';
$pass = 'xxxxxxxx';
$conn = mysql_connect( $server, $user, $pass );
if (!$conn) { die("echec connexion serveur"); }
mysql_select_db($base) or die('erreur selection base de donnees');
// echo "connected<br/>";

// initialiser geoip
require_once '../geoip/geoip2.phar';
use GeoIp2\Database\Reader;
// This creates the Reader object, which should be reused
$reader = new Reader('../geoip/GeoLite2-City.mmdb');

$sqlrequest = "SELECT * FROM ssv1 ORDER BY date DESC;";

$result = mysql_query( $sqlrequest );
if	(!$result) echo "erreur base de donnees " . $sqlrequest;
else	{
	echo '<table border="1" cellpadding="8">';
	while	( $row = mysql_fetch_assoc($result) )
		{
		echo '<tr>';
		foreach	( $row as $elem )
			echo '<td>' . $elem . '</td>';
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

