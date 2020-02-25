<script>
function goBack() {
    window.history.back();
}
</script>

<?php

require 'db/db.php';
require 'common/ypflib.php';

if (isset($_POST['mode'])) {
    $mode = $_POST['mode'];
} else {
    $mode = "errorissa";
}

if ($mode != "errorissa" && $mode != "") {
    // if delete
    if ($mode == 'delete') {
        $sql = itemDelete();

    } // Tässä voisi olla ELSE, ettei deleten jälkeen turhaan valuta kaikkia läpi

    // Reset validation error fields
    $error_item_exits = false;
    $error_supplier_code = false;
    $error_supplier_name = false;
    $error_purchase_price_factor = false;
    $error_supplier_file = false;
    $error_data_start_row = false;
    $error_file_path = false;
    $error_file_column_separator = false;
    $error_column_manufacturer = false;
    $error_column_product_code = false;
    $error_column_product_desc = false;
    $error_column_ean_code = false;
    $error_column_category = false;
    $error_column_subcat1 = false;
    $error_column_subcat2 = false;
    $error_column_purchase_price = false;
    $error_form = false;

    // Read fields from POST
    $supplier_code = $_POST['supplier_code'];
    $supplier_name = $_POST['supplier_name'];
    $purchase_price_factor = $_POST['purchase_price_factor'];
    $supplier_file = $_POST['supplier_file'];
    $data_start_row = $_POST['data_start_row'];
    $file_path = $_POST['file_path'];
    $file_column_separator = $_POST['file_column_separator'];
    $column_manufacturer = $_POST['column_manufacturer'];
    $column_product_code = $_POST['column_product_code'];
    $column_product_desc = $_POST['column_product_desc'];
    $column_ean_code = $_POST['column_ean_code'];
    $column_category = $_POST['column_category'];
    $column_subcat1 = $_POST['column_subcat1'];
    $column_subcat2 = $_POST['column_subcat2'];
    $column_purchase_price = $_POST['column_purchase_price'];

    // If ADD, we first check if item already exists
    if ($mode == 'add') {
        $sql = "SELECT * from supplierlists where supplier_file='$supplier_file' AND file_path='$file_path'";

        $result = mysqli_query($conn, $sql);

        if($result->num_rows > 0) {
            $error_item_exits = true;
            $error_form = true;
        }

    } // End exist check

    // If no errors, continue with field validations
    if (!$error_form) {
        // validate SUPPLIER CODE
        if (empty($supplier_code)) {
            $error_supplier_code = true;
            $error_form = true;
        }

        // validate SUPPLIER NAME
        if (empty($supplier_name)) {
            $error_supplier_name = true;
            $error_form = true;
        }

        // validate PURCHASE PRICE FACTOR
        if (empty($purchase_price_factor)) {
            $error_purchase_price_factor = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($purchase_price_factor)) {
                $error_purchase_price_factor = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($purchase_price_factor, '2')) {
                    $error_purchase_price_factor = true;
                    $error_form = true;
                }
            }
        }

        // validate SUPPLIER FILE
        if (empty($supplier_file)) {
            $error_supplier_file = true;
            $error_form = true;
        }

        // validate DATA START ROW
        if (empty($data_start_row)) {
            $error_data_start_row = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($data_start_row)) {
                $error_data_start_row = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($data_start_row, '0')) {
                    $error_data_start_row = true;
                    $error_form = true;
                }
            }
        }

        // validate FILE PATH
        if (empty($file_path)) {
            $error_file_path = true;
            $error_form = true;
        }

        // validate COLUMN SEPARATOR
        if (empty($file_column_separator)) {
            $error_file_column_separator = true;
            $error_form = true;
        }

        // validate COLUMN MANUFACTURER
        if (empty($column_manufacturer)) {
            $error_column_manufacturer = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_manufacturer)) {
                $error_column_manufacturer = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_manufacturer, '0')) {
                    $error_column_manufacturer = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN PRODUCT CODE
        if (empty($column_product_code)) {
            $error_column_product_code = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_product_code)) {
                $error_column_product_code = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_product_code, '0')) {
                    $error_column_product_code = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN PRODUCT DESC
        if (empty($column_product_desc)) {
            $error_column_product_desc = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_product_desc)) {
                $error_column_product_desc = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_product_desc, '0')) {
                    $error_column_product_desc = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN EAN CODE
        if (empty($column_ean_code)) {
            $error_column_ean_code = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_ean_code)) {
                $error_column_ean_code = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_ean_code, '0')) {
                    $error_column_ean_code = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN Category
        if (empty($column_category)) {
            $error_column_category = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_category)) {
                $error_column_category = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_category, '0')) {
                    $error_column_category = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN SUB Category1
        if (empty($column_subcat1)) {
            $error_column_subcat1 = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_subcat1)) {
                $error_column_subcat1 = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_subcat1, '0')) {
                    $error_column_subcat1 = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN SUB Category2
        if (empty($column_subcat2)) {
            $error_column_subcat2 = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_subcat2)) {
                $error_column_subcat2 = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_subcat2, '0')) {
                    $error_column_subcat2 = true;
                    $error_form = true;
                }
            }
        }

        // validate COLUMN PURCHASE PRICE
        if (empty($column_purchase_price)) {
            $error_column_purchase_price = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($column_purchase_price)) {
                $error_column_purchase_price = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($column_purchase_price, '0')) {
                    $error_column_purchase_price = true;
                    $error_form = true;
                }
            }
        }


    } // END field validations

    // if no errors prepare SQL statements accordin ADD or UPDATE
    if (!$error_form) {
        $kentat = array ('supplier_code',	'supplier_name', 'purchase_price_factor',	'supplier_file', 'data_start_row',	'file_path',	'file_column_separator',
                        'column_manufacturer',	'column_product_code',	'column_product_desc',	'column_ean_code',	'column_category',
                        'column_subcat1',	'column_subcat2',	'column_purchase_price');
        $kentat = implode(",",$kentat);
        $id = $_POST['id'];

        // Strip tags from text fields
        $supplier_code = strip_tags($supplier_code);
        $supplier_name = strip_tags($supplier_name);
        $supplier_file = strip_tags($supplier_file);
        $file_path = strip_tags($file_path);

        // ADD or UPDATE
        if ($mode == 'add') {
            $sql = "INSERT INTO supplierlists($kentat) VALUES  (
                '$supplier_code',
                '$supplier_name',
                $purchase_price_factor,
                '$supplier_file',
                $data_start_row,
                '$file_path',
                '$file_column_separator',
                $column_manufacturer,
                $column_product_code,
                $column_product_desc,
                $column_ean_code,
                $column_category,
                $column_subcat1,
                $column_subcat2,
                $column_purchase_price
                )";

        } elseif ($mode == 'update') {
            $sql = "UPDATE supplierlists SET
            supplier_code = '$supplier_code',
            supplier_name = '$supplier_name',
            purchase_price_factor = $purchase_price_factor,
            supplier_file = '$supplier_file',
            data_start_row = $data_start_row,
            file_path = '$file_path',
            file_column_separator	= '$file_column_separator',
            column_manufacturer = $column_manufacturer,
            column_product_code = $column_product_code,
            column_product_desc	= $column_product_desc,
            column_ean_code	= $column_ean_code,
            column_category	= $column_category,
            column_subcat1	= $column_subcat1,
            column_subcat2	= $column_subcat2,
            column_purchase_price	= $column_purchase_price
            WHERE id = $id";
        }

        // Then we EXECUTE SQL

        if (mysqli_query($conn, $sql)) {
            $status = "";
            mysqli_close($conn);
            ?>
            <script>
                goBack()
            </script>
            <?php

        }
        else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            $status = "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        mysqli_close($conn);

    }

} // End ADD || UPDATE

if ($error_form) {
    echo "<span class = 'form-error'>Correct errors!</span>";
}

// END MAIN

//
// PROGRAM PHP FUNCTIONS
//


//  DELETE
function itemDelete() {
    $id = $_POST['id'];
    $sql = "DELETE FROM supplierlists WHERE id = $id";
    return $sql;
}

?>

<script>
$("#supplier_code").removeClass("input-error");
$("#supplier_name").removeClass("input-error");
$("#purchase_price_factor").removeClass("input-error");
$("#supplier_file").removeClass("input-error");
$("#data_start_row").removeClass("input-error");
$("#file_path").removeClass("input-error");
$("#file_column_separator").removeClass("input-error");
$("#column_manufacturer").removeClass("input-error");
$("#column_product_code").removeClass("input-error");
$("#column_product_desc").removeClass("input-error");
$("#column_ean_code").removeClass("input-error");
$("#column_category").removeClass("input-error");
$("#column_subcat1").removeClass("input-error");
$("#column_subcat2").removeClass("input-error");
$("#column_purchase_price").removeClass("input-error");
var alertText = "";

// Handle record exists error
var error_item_exits = "<?php echo $error_item_exits; ?>";
if (error_item_exits == true) {
    $("#supplier_file").addClass("input-error");
    $("#file_path").addClass("input-error");
    alertText = alertText + "File and Path already exits\n";
}
// Handle supplier code
var error_supplier_code = "<?php echo $error_supplier_code; ?>";
if (error_supplier_code == true) {
    $("#supplier_code").addClass("input-error");
    alertText = alertText + "Supplier code is mandatory\n";
}

// Handle supplier name error
var error_supplier_name = "<?php echo $error_supplier_name; ?>";
if (error_supplier_name == true) {
    $("#supplier_name").addClass("input-error");
    alertText = alertText + "Supplier name is mandatory\n";
}

// Handle purcahse_price_factor error
var error_purchase_price_factor = "<?php echo $error_purchase_price_factor; ?>";
if (error_purchase_price_factor == true) {
    $("#purchase_price_factor").addClass("input-error");
    alertText = alertText + "Check Purchase price factor is a number\n";
}

// Handle supplier_file error
var error_supplier_file = "<?php echo $error_supplier_file; ?>";
if (error_supplier_file == true) {
    $("#supplier_file").addClass("input-error");
    alertText = alertText + "Supplier file is mandatory\n";
}

// Handle data_start_row error
var error_data_start_row = "<?php echo $error_data_start_row; ?>";
if (error_data_start_row == true) {
    $("#data_start_row").addClass("input-error");
    alertText = alertText + "Check Start row is a number\n";
}

// Handle file_path error
var error_file_path = "<?php echo $error_file_path; ?>";
if (error_file_path == true) {
    $("#file_path").addClass("input-error");
    alertText = alertText + "File path is mandatory\n";
}

// Handle file_column_separator error
var error_file_column_separator = "<?php echo $error_file_column_separator; ?>";
if (error_file_column_separator == true) {
    $("#file_column_separator").addClass("input-error");
    alertText = alertText + "File column separator is mandatory\n";
}

// Handle column_manufacturer errors
var error_column_manufacturer = "<?php echo $error_column_manufacturer; ?>";
if (error_column_manufacturer == true) {
    $("#column_manufacturer").addClass("input-error");
    alertText = alertText + "Check Manufacturer column\n";
}

// Handle column_product_code errors
var error_column_product_code = "<?php echo $error_column_product_code; ?>";
if (error_column_product_code == true) {
    $("#column_product_code").addClass("input-error");
    alertText = alertText + "Check Product code column\n";
}

// Handle column_product_desc errors
var error_column_product_desc = "<?php echo $error_column_product_desc; ?>";
if (error_column_product_desc == true) {
    $("#column_product_desc").addClass("input-error");
    alertText = alertText + "Check Product description column\n";
}

// Handle column_ean_code errors
var error_column_ean_code = "<?php echo $error_column_ean_code; ?>";
if (error_column_ean_code == true) {
    $("#column_ean_code").addClass("input-error");
    alertText = alertText + "Check EAN column\n";
}

// Handle column_category errors
var error_column_category = "<?php echo $error_column_category; ?>";
if (error_column_category == true) {
    $("#column_category").addClass("input-error");
    alertText = alertText + "Check Category column\n";
}

// Handle column_subcat1 errors
var error_column_subcat1 = "<?php echo $error_column_subcat1; ?>";
if (error_column_subcat1 == true) {
    $("#column_subcat1").addClass("input-error");
    alertText = alertText + "Check Sub-category 1 column\n";
}

// Handle column_subcat2 errors
var error_column_subcat2 = "<?php echo $error_column_subcat2; ?>";
if (error_column_subcat2 == true) {
    $("#column_subcat2").addClass("input-error");
    alertText = alertText + "Check Sub-category 2 column\n";
}

// Handle column_purchase_price errors
var error_column_purchase_price = "<?php echo $error_column_purchase_price; ?>";
if (error_column_purchase_price == true) {
    $("#column_purchase_price").addClass("input-error");
    alertText = alertText + "Check Purchase price column\n";
}


// ALERT MAIN MESSAGE WITH ALL ERRORS
if (alertText != "") {
    alert(alertText);
}

</script>
