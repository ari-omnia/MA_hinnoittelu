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
        $("#groupingrule").submit(function(event) {
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
echo "mode / ".$mode;
?>

<!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
<div class="container2col">
    <form id="groupingrule" name="groupingrule" action="groupingrule_chk.php" method="post">
        <legend><?//php echo $legend = ($mode == "1") ? "Add grouping rule" : "Update"; ?></legend>
        <!-- ************************************-->
        <!-- GROUP CODE & GROUP DESCRIPTION      -->
        <!-- ************************************-->
        <div class="row">
            <div class="col2-20">
                Group code
            </div>
            <div class="col2-30">
                <input id="grouping_code" type="text"  name="grouping_code" value=<?php echo $value = ($id > 0) ? $row['grouping_code'].' readonly="readonly"' : ""; ?>>
                <input id='id' type='hidden' name='id' value=<?php echo $id?>></td>
            </div>

            <div class="col2-20">
                Group description
            </div>
            <div class="col2-30">
                <input id="grouping_desc" type="text" name="grouping_desc" value=<?php echo $value = ($id > 0) ? $row['grouping_desc'] : ""; ?>>

            </div>
        </div>
        <!-- ************************************-->
        <!-- PRICE GROUP & TARGET CATEGORY       -->
        <!-- ************************************-->
        <div class="row">
            <div class="col2-20">
                Price group
            </div>
            <div class="col2-30">
                <!--input id="price_group" type="text" name="price_group" value=<?php //echo $value = ($id > 0) ? $row['price_group'] : ""; ?>-->
                <select id="price_group" name='price_group'>

                    <?php fillPricegroupOptions(); ?>

                </select>
            </div>

            <div class="col2-20">
                Target category
            </div>
            <div class="col2-30">
                <input id="target_category" type="text" maxlength="6" name="target_category" value=<?php echo $value = ($id > 0) ? $row['target_category'] : ""; ?>>
            </div>
        </div>
</div>
<div class="container2col">
        <!-- ***************-->
        <!-- GROUPING RULE SELECTION SQL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Grouping rule selection
            </div>
            <div class="col2-80">
                <textarea id="grouping_SQL_selection" name="grouping_SQL_selection"><?php echo $value = ($id > 0) ? $row['grouping_SQL_selection'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR MANUFACTURER  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Manufacturer
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_manufacturer" name="grouping_rule_manufacturer"><?php echo $value = ($id > 0) ? $row['grouping_rule_manufacturer'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR PRODUCT CODE  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Supplier Product code
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_product_code" name="grouping_rule_product_code"><?php echo $value = ($id > 0) ? $row['grouping_rule_product_code'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR PRODUCT DESC  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Product desc
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_product_desc" name="grouping_rule_product_desc"><?php echo $value = ($id > 0) ? $row['grouping_rule_product_desc'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE FOR EAN CODE -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule EAN code
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_ean_code" name="grouping_rule_ean_code"><?php echo $value = ($id > 0) ? $row['grouping_rule_ean_code'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE CATEGORY  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Category
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_category" name="grouping_rule_category"><?php echo $value = ($id > 0) ? $row['grouping_rule_category'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE SUB CATEGORY 1 -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Sub category 1
            </div>
            <div class="col2-80">
                <textarea id="grouping_rule_subcat1" name="grouping_rule_subcat1"><?php echo $value = ($id > 0) ? $row['grouping_rule_subcat1'] : ""; ?>
                </textarea>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE SUB CATEGORY 2 -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">
                Rule Sub category 2
            </div>
            <div class="col2-80">
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
                <?php
                    //$get_parameters="action=view&file=filename.pdf&sql=".$row['grouping_SQL_selection']."&id=".$id;
                    $get_parameters="id=$id";
                    echo "<a href='groupsimulation.php?$get_parameters' target='_blank' class='button button--update'>Simulate</a>";
                ?>

            </div>
            <p class="form-message"></p>
        </div>
    </form>
</div>

<?php

require "footer.php";

function fillPricegroupOptions() {
    global $conn;
    global $id;
    // haettais ensin toiseen tauluun talletettu arvo
        $sql = "SELECT * FROM groupingrules WHERE id = $id";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)) {
            if (mysqli_num_rows($result) > 0) {
                $savedselection = $row['price_group'];
            } else {
                echo "0 results";
            }
        }

        echo "<option selected value='$savedselection'>".$savedselection."</option>";
    //

    $sql = "SELECT * FROM pricegroups";
    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        if (mysqli_num_rows($result) > 0) {
            echo "<option value='".$row['price_group_code']."'>".$row['price_group_code'].' '.$row['price_group_desc']."</option>";
        } else {
            echo "0 results";
        }
    }
}
?>
