<?php
	ini_set('display_errors', '1');
	require_once('../firespark/constants.php');
	header('Content-Type: application/json');

	$return = array(
		"name" => DB_NAMEPRE
	);

	echo json_encode($return);

	//var_dump($_SERVER);
	/*$conn = new PDO("mysql:host=localhost;dbname=mysql", "fsdbguy", "Kp6SS1W6imawhHKt");

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	//echo "Connected to the database.\n";

	$sql = "select * from help_category;";

	//$resutlt = $conn->query($sql);

	foreach($conn->query($sql) as $row)
	{
		print $row["name"] . "<br>";
	}*/
?>