
<?php
echo "getting in";

require "db/db.php";
require "fileHandler.php";

var_dump($conn);
echo "kutsutaan fetchAllFiles";

$fetch = fetchAllFiles();

if(!fetchAllFiles()) {
    echo "ei onnistu";
} else {
    echo "onnistui";
}

?>
