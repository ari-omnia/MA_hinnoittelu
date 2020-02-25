<?php
require 'common/ypflib.php';


$purchase_price_factor = "17.32";

// validate PURCHASE PRICE FACTOR
if (empty($purchase_price_factor)) {
    $error_purchase_price_factor = true;
    $error_form = true;
    echo "empty";
} else {
    if (!isValueNumeric($purchase_price_factor)) {
        $error_purchase_price_factor = true;
        $error_form = true;
        echo "not numeric";
    } else {
        if (!isRightDecimalA($purchase_price_factor, 2)) {
            $error_purchase_price_factor = true;
            $error_form = true;
            echo "wrong decimals ";
        } else {
            echo "right decimals ";
        }

    }
}


function isRightDecimalA ($nbr, $nbrOfDec) {
    if (strpos($nbr, ".", 0) > 0) {
        $decimals = substr($nbr, strpos($nbr, ".", 0)+1);
        echo "parm number / ".$nbr."<br>";
        echo "parm number of dec / ".$nbrOfDec."<br>";
        echo "strpos / ".(strpos($nbr, ".", 0) + 1)."<br>";
        echo "decimals / ".$decimals."<br>";
        echo "length / ".strlen((string)$decimals)."<br>";


        if (strlen((string)$decimals) > $nbrOfDec) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }

}


?>
