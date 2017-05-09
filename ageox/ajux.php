<?php
$server = 'mysql5-27';
$base = 'sourcecojln';
$user = 'sourcecojln';
$pass = 'xxxxxxxx';
$conn = mysql_connect( $server, $user, $pass );
if (!$conn) { die("echec connexion serveur"); }
mysql_select_db($base) or die('erreur selection base de donnees');
// echo "connected<br/>";

if	( isset( $_REQUEST['boss'] ) )
	{
	if	( $_REQUEST['boss'] == 'b3' )
		{
		echo "<h1>Creation Table</h1>";

		$sqlrequest = "DROP TABLE ssv1";
		$result = mysql_query( $sqlrequest );
		
		$sqlrequest = "CREATE TABLE ssv1 ( ";
		$sqlrequest .= "date TIMESTAMP, ip text, v text, agent text";
		// timestamp format : YYYY-MM-DD HH:MI:SS ex 1970-01-01 00:00:01
		// In an INSERT or UPDATE query, the TIMESTAMP automatically set itself to the current time !!
		$sqlrequest .= ", PRIMARY KEY (`date`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		echo "<p>$sqlrequest</p>\n";
		$result = mysql_query( $sqlrequest );
		if (!$result)
		   echo "<h1>erreur creation table SQL</h1>";
		}
	}

else if	( ( isset( $_REQUEST['v'] ) ) )
	{
	$val = $_REQUEST['v'];
	$ip = $_SERVER['REMOTE_ADDR'];
	$agent = $_SERVER['HTTP_USER_AGENT'];
	if	( !get_magic_quotes_gpc() )
		$val = addslashes( $val );
	if	( preg_match('/([^)]+[)])/', $agent, $matches ) )
		$agent = $matches[1];
	$sqlrequest  = "INSERT INTO ssv1 ( ip, v, agent ) VALUES ( '";
	$sqlrequest .= $ip . "', '" . $val . "', '" . $agent . "' )";
	echo "<p>$sqlrequest</p>";
	$result = mysql_query( $sqlrequest );
	if (!$result)
	   echo "<h1>erreur insert SQL</h1>";
	}
else	{
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
			echo "</tr>\n";
			}
		echo '</table>';
		}
	}

?>

