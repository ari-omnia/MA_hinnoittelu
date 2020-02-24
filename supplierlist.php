<?php

require "header.php";
require "includes/logged.php";

?>

<script src="js/pricegroup.js"></script>
<script src="js/ypjslib.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
var mode = "";

function onDelete() {
    // verify delete
    if (confDelete()) {
        mode = "delete";
    } else {
        mode = "";
    }
}

function onUpdate() {
    mode = "update";
}

function onAdd() {
    mode = "add";
}
</script>

<script>
    $(document).ready(function() {
        $("form").submit(function(event) {
            event.preventDefault();
            var id = $("#id").val();
            var supplier_code = $("#supplier_code").val();
            var supplier_name = $("#supplier_name").val();
            var purchase_price_factor = $("#purchase_price_factor").val();
            var supplier_file = $("#supplier_file").val();
            var data_start_row = $("#data_start_row").val();
            var file_path = $("#file_path").val();
            var file_column_separator = $("#file_column_separator").val();
            var column_manufacturer = $("#column_manufacturer").val();
            var column_product_code = $("#column_product_code").val();
            var column_product_desc = $("#column_product_desc").val();
            var column_ean_code = $("#column_ean_code").val();
            var column_category = $("#column_category").val();
            var column_subcat1 = $("#column_subcat1").val();
            var column_subcat2 = $("#column_subcat2").val();
            var column_purchase_price = $("#column_purchase_price").val();
            $(".form-message").load("supplierlist_chk.php", {
                id: id,
                supplier_code: supplier_code,
                supplier_name: supplier_name,
                purchase_price_factor: purchase_price_factor,
                supplier_file: supplier_file,
                data_start_row: data_start_row,
                file_path: file_path,
                file_column_separator: file_column_separator,
                column_manufacturer: column_manufacturer,
                column_product_code: column_product_code,
                column_product_desc: column_product_desc,
                column_ean_code: column_ean_code,
                column_category: column_category,
                column_subcat1: column_subcat1,
                column_subcat2: column_subcat2,
                column_purchase_price: column_purchase_price,
                mode: mode
            });
        })
    });
</script>

<?php

$mode = $_GET['mode'];

if(!empty($_POST['id'])) {
    $id = $_POST['id'];
}
else {
  	$id = -1;
}

if ($id > 0) {
    $sql = "SELECT * from supplierlists where id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

?>

<!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
<div class="container">
    <form name="supplierlist" action="supplierlist_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add supplier file" : "Update"; ?></legend>
        <!-- ***************-->
        <!-- SUPLLIER       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Supplier
            </div>
            <div class="col-75">
                <input id="supplier_code" type="text"  name="supplier_code" value=<?php echo $value = ($id > 0) ? $row['supplier_code'].' readonly="readonly"' : ""; ?>>
                <input type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- SUPLLIER NAME  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Supllier name
            </div>
            <div class="col-75">
                <input id="supplier_name" type="text" name="supplier_name" value=<?php echo $value = ($id > 0) ? $row['supplier_name'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- PURCHASE PRICE FACTOR -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Price factor
            </div>
            <div class="col-75">
                <input id="purchase_price_factor" type="text" name="purchase_price_factor" value=<?php echo $value = ($id > 0) ? $row['purchase_price_factor'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- FILE NAME      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                File name
            </div>
            <div class="col-75">
                <input id="supplier_file" type="text" name="supplier_file" value=<?php echo $value = ($id > 0) ? $row['supplier_file'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- FILE DATA STARTGIN ROW      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Data start
            </div>
            <div class="col-75">
                <input id="data_start_row" type="text" name="data_start_row" value=<?php echo $value = ($id > 0) ? $row['data_start_row'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- FILE LOCATION  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                File location
            </div>
            <div class="col-75">
                <input id="file_path" type="text" name="file_path"  value=<?php echo $value = ($id > 0) ? $row['file_path'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- FILE COL SEP   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                File Column separator
            </div>
            <div class="col-75">
                <input id="file_column_separator" type="text" name="file_column_separator" maxlength="1" value=<?php echo $value = ($id > 0) ? $row['file_column_separator'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- MANUFACTURER COL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Manufacturer col.
            </div>
            <div class="col-75">
                <input id="column_manufacturer" type="text" name="column_manufacturer" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_manufacturer'] : ""; ?>>
            </div>
          <!--/fieldset-->
        </div>
        <!-- ***************-->
        <!-- PROD COL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Product col.
            </div>
            <div class="col-75">
                <input id="column_product_code" type="text" name="column_product_code"  maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_product_code'] : ""; ?>>
            </div>
          <!--/fieldset-->
        </div>
        <!-- ***************-->
        <!-- PROD DESC COL   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Product Description col.
            </div>
            <div class="col-75">
                <input id="column_product_desc" type="text" name="column_product_desc" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_product_desc'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- EAN COL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                EAN col.
            </div>
            <div class="col-75">
                <input id="column_ean_code" type="text" name="column_ean_code" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_ean_code'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- CATEGORY COL   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Category col.
            </div>
            <div class="col-75">
                <input id="column_category" type="text" name="column_category" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_category'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- SUB CATEGORY 1   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Sub Category 1 col.
            </div>
            <div class="col-75">
                <input id="column_subcat1" type="text" name="column_subcat1" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_subcat1'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- SUB CATEGORY 2   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Sub Category 2 col.
            </div>
            <div class="col-75">
                <input id="column_subcat2" type="text" name="column_subcat2" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_subcat2'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- PURCH PRICE COL   -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Purchase price col.
            </div>
            <div class="col-75">
                <input id="column_purchase_price" type="text" name="column_purchase_price" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['column_purchase_price'] : ""; ?>>
            </div>
        </div>

        <!-- ***************-->
        <!-- ***************-->
        <!--div class="row"-->
        <div class="container-btn1">
            <?php
            if($mode=="1") {
                echo "<button class='button button--add' type='submit' onclick='onAdd()'>Add</button>";
                //echo "<input class='button button--add' onclick='return validateForm()' type='submit' name='add' value='Add'>";
            } else {
                echo "<button class='button button--update' type='submit' onclick='onUpdate()'>Update</button>";
                echo "<button class='button button--delete' type='submit' onclick='onDelete()'>Delete</button>";
                //echo "<input class='button button--update' onclick='return validateForm()' type='submit' name='update' value='Update'>";
                //echo "<input class='button button--delete' onclick='return confDelete()' type='submit' name='delete' value='Delete'>";
            }
            ?>
            <input class="button button--add" onclick="window.history.go(-1); return false;" type="submit" name="back" value="Cancel">
        </div>
        <p class="form-message"></p>
    </form>
</div>

<?php

require "footer.php";

?>
