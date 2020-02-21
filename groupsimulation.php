<?php

//require "header.php";
//require "includes/logged.php";
require 'db/db.php';
?>
<style>
p.a {
  font-family: "Courier New", Courier, monospace;
}
</style>
<?php
//echo "id ".$_GET['id'];
$id = $_GET['id'];

//$sql = $_GET['sql'];
//echo $sql;

// Haetaan taulusta SQL lause
if ($id > 0) {
    $sql = "SELECT * from groupingrules where id = $id";
    $result = mysqli_query($conn, $sql);
    $grouprule = mysqli_fetch_assoc($result);
}

$sql = $grouprule['grouping_SQL_selection'];


$result = mysqli_query($conn, $sql);
if(mysqli_num_rows($result) > 0) {
    // output data columns

    // output data of each row
    ?>
    <div style="margin-left: 80px">
        <table style="width:50%">
            <?php
            while($row = mysqli_fetch_assoc($result)) {
                $printrow = "";
                ?>
            <tr>
                <?php
                foreach($row as $field => $value) {
                    echo "<td>$value</td>";
                }
            }
            ?>
            </tr>
        </table>
    </div>

<?php

}

?>
