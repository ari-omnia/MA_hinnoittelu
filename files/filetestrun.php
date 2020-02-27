
<?php
echo "getting in";
echo "<br>";

require "db/db.php";
require "fileHandler.php";

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
