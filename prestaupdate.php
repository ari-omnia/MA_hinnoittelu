<?php
require "db/db_presta.php";
require "db/db.php";
require "presta.php";

echo "<br>";
echo "kutsutaan startTransfer";
echo "<br>";

startTransfer();

echo "Siirto valmis";

?>
