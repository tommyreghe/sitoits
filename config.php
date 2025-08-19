<?php
$connectionInfo = array("UID" => "sqladmin", "pwd" => "droneITS25", "Database" => "DB_Drone", "LoginTimeout" => 30, "Encrypt" => 0, "TrustServerCertificate" => 0);
$serverName = "tcp:databasedrone.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?>
