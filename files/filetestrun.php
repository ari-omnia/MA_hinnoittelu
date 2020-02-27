
<?php
echo "getting in";
echo "<br>";
echo "require db<br>";
require "../db/db.php";
echo "db ok<br>";
require "../fileHandler.php";

var_dump($conn);
echo "<br>";
echo "kutsutaan fetchAllFiles";
echo "<br>";

arrangeFile("test.csv");

/*$fetch = fetchAllFiles();

if(!fetchAllFiles()) {
    echo "ei onnistu";
    echo "<br>";
} else {
    echo "onnistui";
    echo "<br>";
}*/

?>
