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
    $error_price_group_code = false;
    $error_price_group_desc = false;
    $error_sales_price_factor = false;
    $error_fixed_sum_to_price = false;
    $error_form = false;
    // Reset validation notification fields
    $notify_sales_price_factor_change = false;
    $notify_fixed_sum_to_price_change = false;

    // Read fields from POST
    $price_group_code = $_POST['price_group_code'];
    $price_group_desc = $_POST['price_group_desc'];
    $sales_price_factor = $_POST['sales_price_factor'];
    $old_sales_price_factor = $_POST['old_sales_price_factor'];
    $fixed_sum_to_price = $_POST['fixed_sum_to_price'];
    $old_fixed_sum_to_price = $_POST['old_fixed_sum_to_price'];

    // If ADD, we first check if item already exists
    if ($mode == 'add') {
        $sql = "SELECT * FROM pricegroups WHERE price_group_code='$price_group_code'";

        $result = mysqli_query($conn, $sql);

        if($result->num_rows > 0) {
            $error_item_exits = true;
            $error_form = true;
        }

    } // End exist check

    // If no errors, continue with field validations
    if (!$error_form) {
        // validate PRICE GROUP CODE
        if (empty($price_group_code)) {
            $error_price_group_code = true;
            $error_form = true;
        }

        // validate PRICE GROUP DESCRIPTION
        if (empty($price_group_desc)) {
            $error_price_group_desc = true;
            $error_form = true;
        }

        // validate SALES PRICE FACTOR
        if (empty($sales_price_factor)) {
            //$sales_price_factor = 0.00;
            $error_sales_price_factor = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($sales_price_factor)) {
                $error_sales_price_factor = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($sales_price_factor, '2')) {
                    $error_sales_price_factor = true;
                    $error_form = true;
                }
            }
        }

        // validate FIXED SUM TO PRICE
        if (empty($fixed_sum_to_price)) {
            //$fixed_sum_to_price = 0.00;
            $error_fixed_sum_to_price = true;
            $error_form = true;
        } else {
            if (!isValueNumeric($fixed_sum_to_price)) {
                $error_fixed_sum_to_price = true;
                $error_form = true;
            } else {
                if (!isRightDecimal($fixed_sum_to_price, '2')) {
                    $error_fixed_sum_to_price = true;
                    $error_form = true;
                }
            }
        }

        if (priceGroupUsed($price_group_code)) {
            // CHECK IF SALES PRICE FACTOR CHANGE AND USE IN OTHER GROUP RULES. NOTIFY USER
            if ($sales_price_factor != $old_sales_price_factor) {
                $notify_sales_price_factor_change = true;
            }
            // CHECK IF FIXED SUM TO PRICE CHANGE USE IN OTHER GROUP RULES. NOTIFY USER
            if ($fixed_sum_to_price != $old_fixed_sum_to_price) {
                $notify_fixed_sum_to_price_change = true;
            }
        }

    } // END field validations

    // if no errors prepare SQL statements accordin ADD or UPDATE
    if (!$error_form) {
        $kentat = array ('price_group_code', 'price_group_desc', 'sales_price_factor', 'fixed_sum_to_price');
        $kentat = implode(",",$kentat);
        $id = $_POST['id'];

        // Strip tags from text fields
        $price_group_code = strip_tags($price_group_code);
        $price_group_desc = strip_tags($price_group_desc);

        // ADD or UPDATE
        if ($mode == 'add') {
            $sql = "INSERT INTO pricegroups($kentat) VALUES (
                '$price_group_code',
                '$price_group_desc',
                $sales_price_factor,
                $fixed_sum_to_price
                )";

        } elseif ($mode == 'update') {
            $sql = "UPDATE pricegroups SET
            price_group_code = '$price_group_code',
            price_group_desc = '$price_group_desc',
            sales_price_factor = $sales_price_factor,
            fixed_sum_to_price = $fixed_sum_to_price
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
    $sql = "DELETE FROM pricegroups WHERE id = $id";
    return $sql;
}

function priceGroupUsed($price_group_code) {
    $sql = "SELECT * FROM groupingrules WHERE price_group='$price_group_code'";
    global $conn;
    $result = mysqli_query($conn, $sql);

    if($result->num_rows > 1) {
        return true;
    }
    return false;
}

?>

<script>
$("#price_group_code").removeClass("input-error");
$("#price_group_desc").removeClass("input-error");
$("#sales_price_factor").removeClass("input-error");
$("#fixed_sum_to_price").removeClass("input-error");
var alertText = "";
var confirmText = "";

// Handle record exists error
var error_item_exits = "<?php echo $error_item_exits; ?>";
if (error_item_exits == true) {
    $("#price_group_code").addClass("input-error");
    alertText = alertText + "Price group code already exits\n";
}
// Handle price group code error
var error_price_group_code = "<?php echo $error_price_group_code; ?>";
if (error_price_group_code == true) {
    $("#price_group_code").addClass("input-error");
    alertText = alertText + "Price group code is mandatory\n";
}

// Handle price group description error
var error_price_group_desc = "<?php echo $error_price_group_desc; ?>";
if (error_price_group_desc == true) {
    $("#price_group_desc").addClass("input-error");
    alertText = alertText + "Price group description is mandatory\n";
}

// Handle sales_price_factor error
var error_sales_price_factor = "<?php echo $error_sales_price_factor; ?>";
if (error_sales_price_factor == true) {
    $("#sales_price_factor").addClass("input-error");
    alertText = alertText + "Check Sales price factor is a number\n";
}

// Handle sales_price_factor error
var error_fixed_sum_to_price = "<?php echo $error_fixed_sum_to_price; ?>";
if (error_fixed_sum_to_price == true) {
    $("#fixed_sum_to_price").addClass("input-error");
    alertText = alertText + "Check Fixed sum to price is a number\n";
}

// ALERT MAIN MESSAGE WITH ALL ERRORS
if (alertText != "") {
    alert(alertText);
}
// OTHER NOTIFICATIONS
// Handle sales_price_factor notification about change
var notify_sales_price_factor_change = "<?php echo $notify_sales_price_factor_change; ?>";
if (notify_sales_price_factor_change == true) {
    confirmText = confirmText + "Please notify, Sales Price change affects to several Grouping rules.\n";
}

// Handle fixed_sum_to_price notification about change
var notify_fixed_sum_to_price_change = "<?php echo $notify_fixed_sum_to_price_change; ?>";
if (notify_fixed_sum_to_price_change == true) {
    confirmText = confirmText + "Please notify, Fixed sum to price affects to several Grouping rules.\n";
}

if (confirmText != "") {
    alert(confirmText);
}

</script>
