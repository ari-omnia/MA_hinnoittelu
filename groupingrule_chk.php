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
    $error_grouping_code = false;
    $error_grouping_desc = false;
    $error_price_group = false;
    $error_target_category = false;
    $error_grouping_SQL_selection = false;
    $error_grouping_rule_manufacturer = false;
    $error_grouping_rule_product_code = false;
    $error_grouping_rule_product_desc = false;
    $error_grouping_rule_ean_code = false;
    $error_grouping_rule_category = false;
    $error_grouping_rule_subcat1 = false;
    $error_grouping_rule_subcat2 = false;
    $error_form = false;

    // Read fields from POST
    $grouping_code = $_POST['grouping_code'];
    $grouping_desc = $_POST['grouping_desc'];
    $price_group = $_POST['price_group'];
    $target_category = $_POST['target_category'];
    $grouping_SQL_selection = $_POST['grouping_SQL_selection'];
    $grouping_rule_manufacturer = $_POST['grouping_rule_manufacturer'];
    $grouping_rule_product_code = $_POST['grouping_rule_product_code'];
    $grouping_rule_product_desc = $_POST['grouping_rule_product_desc'];
    $grouping_rule_ean_code = $_POST['grouping_rule_ean_code'];
    $grouping_rule_category = $_POST['grouping_rule_category'];
    $grouping_rule_subcat1 = $_POST['grouping_rule_subcat1'];
    $grouping_rule_subcat2 = $_POST['grouping_rule_subcat2'];

    // If ADD, we first check if item already exists
    if ($mode == 'add') {
        $sql = "SELECT * FROM groupingrules WHERE grouping_code='$grouping_code'";

        $result = mysqli_query($conn, $sql);

        if($result->num_rows > 0) {
            $error_item_exits = true;
            $error_form = true;
        }

    } // End exist check

    // If no errors, continue with field validations
    if (!$error_form) {
        // validate GROUP CODE
        if (empty($grouping_code)) {
            $error_grouping_code = true;
            $error_form = true;
        }

        // validate GROUP DESCRIPTION
        if (empty($grouping_desc)) {
            $error_grouping_desc = true;
            $error_form = true;
        }

        // validate PRICE GROUP DESCRIPTION
        if (empty($price_group)) {
            $error_price_group = true;
            $error_form = true;
        }

        // validate TARGET CATEGORY DESCRIPTION
        if (empty($target_category)) {
            $error_target_category = true;
            $error_form = true;
        }

        // !!!HERE WE NEED TO ADD SOME CHECKINGS FOR SQL-clause and FIELD SQL-clauses
        // !!
        // !!

    } // END field validations

    // if no errors prepare SQL statements accordin ADD or UPDATE
    if (!$error_form) {
        $kentat = array ('grouping_code', 'grouping_desc', 'price_group', 'target_category', 'grouping_SQL_selection',
            'grouping_rule_manufacturer', 'grouping_rule_product_code',
            'grouping_rule_product_desc', 'grouping_rule_ean_code', 'grouping_rule_category', 'grouping_rule_subcat1',
            'grouping_rule_subcat2');
        $kentat = implode(",",$kentat);
        $id = $_POST['id'];

        // Strip tags from text fields
        $group_code = strip_tags($group_code);
        $group_desc = strip_tags($group_desc);
        $price_group = strip_tags($price_group);
        $target_category = strip_tags($target_category);
        $grouping_SQL_selection = strip_tags($grouping_SQL_selection);
        $grouping_rule_manufacturer = strip_tags($grouping_rule_manufacturer);
        $grouping_rule_product_code = strip_tags($grouping_rule_product_code);
        $grouping_rule_product_desc = strip_tags($grouping_rule_product_desc);
        $grouping_rule_ean_code = strip_tags($grouping_rule_ean_code);
        $grouping_rule_category = strip_tags($grouping_rule_category);
        $grouping_rule_subcat1 = strip_tags($grouping_rule_subcat1);
        $grouping_rule_subcat2 = strip_tags($grouping_rule_subcat2);

        // ADD or UPDATE
        if ($mode == 'add') {
            $sql = "INSERT INTO groupingrules($kentat) VALUES (
                '$grouping_code',
                '$grouping_desc',
                '$price_group',
                $target_category,
                '$grouping_SQL_selection',
                '$grouping_rule_manufacturer',
                '$grouping_rule_product_code',
                '$grouping_rule_product_desc',
                '$grouping_rule_ean_code',
                '$grouping_rule_category',
                '$grouping_rule_subcat1',
                '$grouping_rule_subcat2'
                )";

        } elseif ($mode == 'update') {
            $sql = "UPDATE groupingrules SET
            grouping_code = '$grouping_code',
            grouping_desc = '$grouping_desc',
            price_group = '$price_group',
            target_category = $target_category,
            grouping_SQL_selection = '$grouping_SQL_selection',
            grouping_rule_manufacturer = '$grouping_rule_manufacturer',
            grouping_rule_product_code = '$grouping_rule_product_code',
            grouping_rule_product_desc = '$grouping_rule_product_desc',
            grouping_rule_ean_code = '$grouping_rule_ean_code',
            grouping_rule_category = '$grouping_rule_category',
            grouping_rule_subcat1 = '$grouping_rule_subcat1',
            grouping_rule_subcat2 = '$grouping_rule_subcat2'
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
    $sql = "DELETE FROM groupingrules WHERE id = $id";
    return $sql;
}

?>

<script>
$("#grouping_code").removeClass("input-error");
$("#grouping_desc").removeClass("input-error");
$("#price_group").removeClass("input-error");
$("#$target_category").removeClass("input-error");
$("#grouping_SQL_selection").removeClass("input-error");
$("#grouping_rule_manufacturer").removeClass("input-error");
$("#grouping_rule_product_code").removeClass("input-error");
$("#grouping_rule_product_desc").removeClass("input-error");
$("#grouping_rule_ean_code").removeClass("input-error");
$("#grouping_rule_category").removeClass("input-error");
$("#grouping_rule_subcat1").removeClass("input-error");
$("#grouping_rule_subcat2").removeClass("input-error");
var alertText = "";

// Handle record exists error
var error_item_exits = "<?php echo $error_item_exits; ?>";
if (error_item_exits == true) {
    $("#grouping_code").addClass("input-error");
    alertText = alertText + "Group code already exits\n";
}
// Handle price group code error
var error_grouping_code = "<?php echo $error_grouping_code; ?>";
if (error_grouping_code == true) {
    $("#grouping_code").addClass("input-error");
    alertText = alertText + "Group code is mandatory\n";
}

// Handle price group description error
var error_grouping_desc = "<?php echo $error_grouping_desc; ?>";
if (error_grouping_desc == true) {
    $("#grouping_desc").addClass("input-error");
    alertText = alertText + "Group description is mandatory\n";
}

// Handle target category error
var $error_target_category = "<?php echo $error_target_category; ?>";
if ($error_target_category == true) {
    $("#target_category").addClass("input-error");
    alertText = alertText + "Target category is mandatory\n";
}

// Handle SQL selection error
var error_grouping_SQL_selection = "<?php echo $error_grouping_SQL_selection; ?>";
if (error_grouping_SQL_selection == true) {
    $("#grouping_SQL_selection").addClass("input-error");
    alertText = alertText + "SQL selection is mandatory\n";
}

// Handle Group rule Manufacturer error
var error_grouping_rule_manufacturer = "<?php echo $error_grouping_rule_manufacturer; ?>";
if (error_grouping_rule_manufacturer == true) {
    $("#grouping_rule_manufacturer").addClass("input-error");
    alertText = alertText + "Error in Manufacturer rule\n";
}

// Handle Group rule Product codr error
var error_grouping_rule_product_code = "<?php echo $error_grouping_rule_product_code; ?>";
if (error_grouping_rule_product_code == true) {
    $("#grouping_rule_product_code").addClass("input-error");
    alertText = alertText + "Error in Product code rule\n";
}

// Handle Group rule Product desc error
var error_grouping_rule_product_desc = "<?php echo $error_grouping_rule_product_desc; ?>";
if (error_grouping_rule_product_desc == true) {
    $("#grouping_rule_product_desc").addClass("input-error");
    alertText = alertText + "Error in Product desc rule\n";
}

// Handle Group rule ean code error
var error_grouping_rule_ean_code = "<?php echo $error_grouping_rule_ean_code; ?>";
if (error_grouping_rule_ean_code == true) {
    $("#grouping_rule_ean_code").addClass("input-error");
    alertText = alertText + "Error in EAN rule\n";
}

// Handle Group rule ean code error
var error_grouping_rule_category = "<?php echo $error_grouping_rule_category; ?>";
if (error_grouping_rule_category == true) {
    $("#grouping_rule_category").addClass("input-error");
    alertText = alertText + "Error in Category rule\n";
}

// Handle Group rule ean code error
var error_grouping_rule_subcat1 = "<?php echo $error_grouping_rule_subcat1; ?>";
if (error_grouping_rule_subcat1 == true) {
    $("#grouping_rule_subcat1").addClass("input-error");
    alertText = alertText + "Error in Sub category 1 rule\n";
}
// Handle Group rule ean code error
var error_grouping_rule_subcat2 = "<?php echo $error_grouping_rule_subcat2; ?>";
if (error_grouping_rule_subcat2 == true) {
    $("#grouping_rule_subcat2").addClass("input-error");
    alertText = alertText + "Error in Sub category 2 rule\n";
}


// ALERT MAIN MESSAGE WITH ALL ERRORS
if (alertText != "") {
    alert(alertText);
}

</script>
