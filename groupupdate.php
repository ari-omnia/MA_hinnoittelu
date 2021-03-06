<?php
require 'db/db.php';
require 'fileHandler.php';
require 'priceGenerator.php';

$error_form = false;
$error_delete = false;
$error_subtract_total = false;

$grouping_code = $_POST['grouping_code'];

//echo "groupcode /".$grouping_code;

//Update New products according the result of deleted lines
//Delete records from Pricing
//deleteGroupFromPricing($grouping_code);
$res = mysqli_query($conn, "SELECT * from pricing WHERE grouping_code = '$grouping_code'");

if(mysqli_num_rows($res) == 0)
{
    $error_form = true;
}
else
{
    //Reads pricing with selected grouping code
    while($row = mysqli_fetch_assoc($res))
    {

        // UPdate new product sum insupplier file_exists
        if ($row['new_product']) {

            if (!subtrackNewProductsTotal($row['supplier_file'])) {
                $error_subtract_total = true;
            }

        }
        // Delete row from Pricing
        if (!deleteGroupFromPricing($row['id'])) {
            $error_delete = true;
        }
    }

    // Rerun GROUPING
    groupProducts($grouping_code);
}

if ($error_form) {
    echo "<span class = 'form-groupupdate-error'>There are no records to be updated!</span>";
} else {
    echo "<span class = 'form-groupupdate-error'>Grouping updated succesfully.</span>";
}

if ($error_delete) {
    echo "<span class = 'form-groupupdate-error'>Something wrong in delete!</span>";
} else {
    echo "<span class = 'form-groupupdate-error'>Old grouping deleted succesfully.</span>";
}

if ($error_subtract_total) {
    echo "<span class = 'form-groupupdate-error'>Something wrong when subtracking!</span>";
}


function deleteGroupFromPricing($id) {
    global $conn;
    $sql = "DELETE FROM pricing WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
    echo "deleteGroupFromPricing";
}

function subtrackNewProductsTotal($supplier_file) {
    global $conn;
    $sql = "SELECT FROM supplierlists WHERE supplier_file = $supplier_file";
    $result = mysqli_query($conn, $sql);

    $new_total = $result['new_products_totalsum'] - 1;
    if ($new_total < 0) {
        $new_total = 0;
    }

    $sql = "UPDATE supplierlists
        SET new_products_totalsum = $new_total
        WHERE supplier_file = '$supplier_file'";

    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        return false;
    }
}
?>
