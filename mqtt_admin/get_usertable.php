<?php
include '../config.php';
include '../function.php';
include "../Mqtt_driver.php";
include 'genhash.php';
define_settings();
set_laguage();

// Create connection
$conn = new mysqli($mosquito_auth_db_host, $mosquito_auth_db_user, $mosquito_auth_db_password, $mosquito_auth_db_db);

mysqli_set_charset($conn, "utf8");

if ( $_SESSION['loginid'] > 0) 
{

    $password = $_POST['password'];
    $node = $_REQUEST['node'];

    ?>

<table class="table">
	<thead class="thead-dark">
		<tr>
			<th scope="col-7"><?php echo _('Topic')?></th>
			<th scope="col-3"><?php echo _('RO / RW')?></th>
			<th scope="col"></th>
		</tr>
	</thead>
	<tbody>
  
  
  
    
    <?php

    $sql = "SELECT * FROM `acls` where username = '$node' ";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "    <tr>

      <td>" . $row["topic"] . "</td>";
            if ($row["rw"] == 1) {
                echo "<td>" . _("Rw") . "</td>";
            } else {
                echo "<td>" . _("Ro") . "</td>";
            }

            if ($_SESSION['is_admin'] > 0) {
                echo "
                  <td>
                    " . '<i class="fas fa-trash" onclick="Delete_topic_mqtt(' . $row['id'] . ')"></i>' . "
                </td>";
            } else {
                echo "<td></td>";
            }
            echo "</tr>";
        }
    } else {}

    ?>

</tbody>
</table>

<?php
}

$conn->close();
