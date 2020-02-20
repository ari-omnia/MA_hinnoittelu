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
            var grouping_code = $("#grouping_code").val();
            var grouping_desc = $("#grouping_desc").val();
            var price_group = $("#price_group").val();
            var target_category = $("#target_category").val();
            var grouping_SQL_selection = $("#grouping_SQL_selection").val();
            var grouping_rule_manufacturer = $("#grouping_rule_manufacturer").val();
            var grouping_rule_product_code = $("#grouping_rule_product_code").val();
            var grouping_rule_product_desc = $("#grouping_rule_product_desc").val();
            var grouping_rule_ean_code = $("#grouping_rule_ean_code").val();
            var grouping_rule_category = $("#grouping_rule_category").val();
            var grouping_rule_subcat1 = $("#grouping_rule_subcat1").val();
            var grouping_rule_subcat2 = $("#grouping_rule_subcat2").val();
            $(".form-message").load("groupingrule_chk.php", {
                id: id,
                grouping_code: grouping_code,
                grouping_desc: grouping_desc,
                price_group: price_group,
                target_category: target_category,
                grouping_SQL_selection: grouping_SQL_selection,
                grouping_rule_manufacturer: grouping_rule_manufacturer,
                grouping_rule_product_code: grouping_rule_product_code,
                grouping_rule_product_desc: grouping_rule_product_desc,
                grouping_rule_ean_code: grouping_rule_ean_code,
                grouping_rule_category: grouping_rule_category,
                grouping_rule_subcat1: grouping_rule_subcat1,
                grouping_rule_subcat2: grouping_rule_subcat2,
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
    $sql = "SELECT * from groupingrules where id = $id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}

?>

<!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
<div class="container">
    <form name="groupingrule" action="groupingrule_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add grouping rule" : "Update"; ?></legend>
        <!-- ***************-->
        <!-- GROUP CODE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Group code
            </div>
            <div class="col-75">
                <input id="grouping_code" type="text"  name="grouping_code" value=<?php echo $value = ($id > 0) ? $row['grouping_code'].' readonly="readonly"' : ""; ?>>
                <input id='id' type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUP DESCRIPTION  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Group description
            </div>
            <div class="col-75">
                <input id="grouping_desc" type="text" name="grouping_desc" value=<?php echo $value = ($id > 0) ? $row['grouping_desc'] : ""; ?>>

            </div>
        </div>
        <!-- ***************-->
        <!-- PRICE GROUP      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Price group
            </div>
            <div class="col-75">
                <input id="price_group" type="text" name="price_group" value=<?php echo $value = ($id > 0) ? $row['price_group'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- TARGET CATEGORY      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Target category
            </div>
            <div class="col-75">
                <input id="target_category" type="text" maxlength="6" name="target_category" value=<?php echo $value = ($id > 0) ? $row['target_category'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE SELECTION SQL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Grouping rule selection
            </div>
            <div class="col-75">
                <textarea id="grouping_SQL_selection" name="grouping_SQL_selection"><?php echo $value = ($id > 0) ? $row['grouping_SQL_selection'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR MANUFACTURER  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Manufacturer
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_manufacturer" name="grouping_rule_manufacturer"><?php echo $value = ($id > 0) ? $row['grouping_rule_manufacturer'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR PRODUCT CODE  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Supplier Product code
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_product_code" name="grouping_rule_product_code"><?php echo $value = ($id > 0) ? $row['grouping_rule_product_code'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR PRODUCT DESC  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Product desc
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_product_desc" name="grouping_rule_product_desc"><?php echo $value = ($id > 0) ? $row['grouping_rule_product_desc'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR EAN CODE -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule EAN code
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_ean_code" name="grouping_rule_ean_code"><?php echo $value = ($id > 0) ? $row['grouping_rule_ean_code'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE CATEGORY  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Category
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_category" name="grouping_rule_category"><?php echo $value = ($id > 0) ? $row['grouping_rule_category'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE SUB CATEGORY 1 -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Sub category 1
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_subcat1" name="grouping_rule_subcat1"><?php echo $value = ($id > 0) ? $row['grouping_rule_subcat1'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE SUB CATEGORY 2 -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
                Rule Sub category 2
            </div>
            <div class="col-75">
                <textarea id="grouping_rule_subcat2" name="grouping_rule_subcat2"><?php echo $value = ($id > 0) ? $row['grouping_rule_subcat2'] : ""; ?>
                </textarea>
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
        </div>
    </form>
</div>

<?php

    require "footer.php";

?>
