
<?php
echo "getting in";
echo "<br>";
echo "require db<br>";
require "db/db.php";
echo "db ok<br>";
require "fileHandler.php";
require "priceGenerator.php";

//var_dump($conn);
echo "<br>";
echo "Kutsutaan groupProducts";
echo "<br>";

//arrangeFile("test.csv");
groupProducts();
echo "Valmis groupProducts";
//groupProducts();
/*$fetch = fetchAllFiles();

if(!fetchAllFiles()) {
    echo "ei onnistu";
    echo "<br>";
} else {
    echo "onnistui";
    echo "<br>";
}*/

?>
