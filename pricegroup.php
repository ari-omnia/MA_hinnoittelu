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
            var price_group_code = $("#price_group_code").val();
            var price_group_desc = $("#price_group_desc").val();
            var sales_price_factor = $("#sales_price_factor").val();
            var old_sales_price_factor = $("#old_sales_price_factor").val();
            var fixed_sum_to_price = $("#fixed_sum_to_price").val();
            var old_fixed_sum_to_price = $("#old_fixed_sum_to_price").val();
            //var add = $("#add").val();
            //var update = $("#update").val();
            //var del = $("#del").val();
            $(".form-message").load("pricegroup_chk.php", {
                id: id,
                price_group_code: price_group_code,
                price_group_desc: price_group_desc,
                sales_price_factor: sales_price_factor,
                old_sales_price_factor: old_sales_price_factor,
                old_fixed_sum_to_price: old_fixed_sum_to_price,
                fixed_sum_to_price: fixed_sum_to_price,
                mode: mode
            });
        })
    });
</script>


<?php

$mode = $_GET['mode'];

if(!empty($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $id = -1;
}

if ($id > 0) {
    $sql = "SELECT * from pricegroups where id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

?>

<!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
<div class="container">
    <form name="pricegroup" action="pricegroup_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add price group" : "Update"; ?></legend>
        <!-- ***************-->
        <!-- PRICE GROUP CODE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Price group code
            </div>
            <div class="col-75">
                <input id="price_group_code" type="text" name="price_group_code" value=<?php echo $value = ($id > 0) ? $row['price_group_code'].' readonly="readonly"' : ""; ?>>
                <input id='id' type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- PRICE GROUP DESCRIPTION  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Price group description
            </div>
            <div class="col-75">
                <input id="price_group_desc" type="text" name="price_group_desc" value=<?php echo $value = ($id > 0) ? $row['price_group_desc'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- PRICE GROUP ADD PERCENTAGE     -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Sales price factor
            </div>
            <div class="col-75">
                <input id="sales_price_factor" type="text" name="sales_price_factor" value=<?php echo $value = ($id > 0) ? $row['sales_price_factor'] : ""; ?>>
                <input id="old_sales_price_factor" type="hidden" name="old_sales_price_factor" value=<?php echo $row['sales_price_factor']; ?>>

            </div>
        </div>

        <!-- ***************-->
        <!-- PRICE GROUP ADD FIXED FEE     -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Fixed sum to price
            </div>
            <div class="col-75">
                <input id="fixed_sum_to_price" type="text" name="fixed_sum_to_price" value=<?php echo $value = ($id > 0) ? $row['fixed_sum_to_price'] : ""; ?>>
                <input id="old_fixed_sum_to_price" type="hidden" name="old_fixed_sum_to_price" value=<?php echo $row['fixed_sum_to_price']; ?>>

            </div>
        </div>
        <!-- ***************-->
        <!-- ***************-->
        <!--div class="row"-->
        <div class="container-btn1">
            <?php
                if($mode=="1") {
                    echo "<button class='button button--add' type='submit' onclick='onAdd()'>Add</button>";
              } else {
                    echo "<button class='button button--update' type='submit' onclick='onUpdate()'>Update</button>";
                    echo "<button class='button button--delete' type='submit' onclick='onDelete()'>Delete</button>";
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
