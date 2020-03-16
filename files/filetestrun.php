
<?php
echo "getting in";
echo "<br>";
echo "require db<br>";
require "../db/db.php";
require "../db/db_presta.php";
echo "db ok<br>";
require "../fileHandler.php";
require "../priceGenerator.php";
require "../presta.php";


//var_dump($conn);
echo "<br>";
echo "kutsutaan fetchAllFiles";
echo "<br>";


//arrangeFile("test.csv");

fetchAllFiles();
//groupProducts();
//startTransfer();

/*$fetch = fetchAllFiles();

if(!fetchAllFiles()) {
    echo "ei onnistu";
    echo "<br>";
} else {
    echo "onnistui";
    echo "<br>";
}*/

?>
