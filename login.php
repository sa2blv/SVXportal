<?php
$username = $_POST['username'];
$password = $_POST['password'];
echo "<html>";
echo "<title>Welcome to the Restricted Area</title><head>";
echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=http://$username:$password@www.myserver/svxrecording/index.htm\">";
echo "</head><body>";
echo "<center><h2>Please Wait .... Login Into the Restricted Area ... </h2>";
echo "</body></html>";
?> 