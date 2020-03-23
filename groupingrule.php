<?php

require "header.php";
require "includes/logged.php";

?>

<script src="js/pricegroup.js"></script>
<script src="js/ypjslib.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

<script>
var mode = "";

// verify delete
function onDelete() {
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
            var fields1 = $("#fields1").val();
            var comp1 = $("#comp1").val();
            var selection1 = $("#selection1").val();
            var oper1 = $("#oper1").val();
            var fields2 = $("#fields2").val();
            var comp2 = $("#comp2").val();
            var selection2 = $("#selection2").val();
            var oper2 = $("#oper2").val();
            var fields3 = $("#fields3").val();
            var comp3 = $("#comp3").val();
            var selection3 = $("#selection3").val();
            var oper3 = $("#oper3").val();
            var fields4 = $("#fields4").val();
            var comp4 = $("#comp4").val();
            var selection4 = $("#selection4").val();

            $(".form-message").load("groupingrule_chk.php", {
                id: id,
                grouping_code: grouping_code,
                grouping_desc: grouping_desc,
                price_group: price_group,
                target_category: target_category,
                grouping_SQL_selection: grouping_SQL_selection,
                fields1: fields1,
                comp1: comp1,
                selection1: selection1,
                oper1: oper1,
                fields2: fields2,
                comp2: comp2,
                selection2: selection2,
                oper2: oper2,
                fields3: fields3,
                comp3: comp3,
                selection3: selection3,
                oper3: oper3,
                fields4: fields4,
                comp4: comp4,
                selection4: selection4,
                mode: mode
            });
        })

        $("#groupupdate").submit(function(event) {
            event.preventDefault();
            var id = $("#id").val();
            var grouping_code = $("#rg_grouping_code").val();

            $(".form-groupupdate-message").load("groupupdate.php", {
                id: id,
                grouping_code: grouping_code
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
<div class="container2col">
    <form id="groupupdate" name="groupupdate" action="groupupdate.php" method="post" target="_parent">
        <input id='rg_grouping_code' type='hidden' name='rg_grouping_code' value=<?php echo $row['grouping_code'];?>>
        <input class= "button button--groupupdate" type="submit" value="Regroup!">
        <p class="form-groupupdate-message"></p>
    </form>
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
        <!-- FIELD SELECTIOSN FOR SQL  -->
        <!-- ***************-->
        <div class="row">
            <div class="col2-20">

            </div>
            <div class="col2-80">
                <table>
                    <!-- ROW ONE HEADER 1-->
                    <tr class="tr-grouping">
                        <th><label for="fields1">Choose a field 1</label></th>
                        <th><label for="comp1">Compare 1</label></th>
                        <th><label for="selection1">Selection 1</label></th>
                    </tr>
                    <!-- ROW TWO DATA 1 -->
                    <tr class="tr-grouping">
                        <td>
                            <select id="fields1" name="fields1">
                                <option value=""></option>
                                <option value="supplier_code" <?php echo $val = ($row['fields1'] == 'supplier_code') ? 'selected' : ' ';?>>Supplier</option>
                                <option value="manufacturer" <?php echo $val = ($row['fields1'] == 'manufacturer') ? 'selected' : ' ';?>>Manufacturer</option>
                                <option value="product_code" <?php echo $val = ($row['fields1'] == 'product_code') ? 'selected' : ' ';?>>Product code</option>
                                <option value="product_desc" <?php echo $val = ($row['fields1'] == 'product_desc') ? 'selected' : ' ';?>>Product description</option>
                                <option value="ean_code" <?php echo $val = ($row['fields1'] == 'ean_code') ? 'selected' : ' ';?>>EAN code</option>
                                <option value="category" <?php echo $val = ($row['fields1'] == 'category') ? 'selected' : ' ';?>>Category</option>
                                <option value="subcat1" <?php echo $val = ($row['fields1'] == 'subcat1') ? 'selected' : ' ';?>>Sub category 1</option>
                                <option value="subcat2" <?php echo $val = ($row['fields1'] == 'subcat2') ? 'selected' : ' ';?>>Sub category 2</option>
                                <option value="supplier_purchase_price <?php echo $val = ($row['fields1'] == 'supplier_purchase_price') ? 'selected' : ' ';?>">Supplier purchase price</option>
                            </select>
                        </td>
                        <td>
                            <select id="comp1" name="comp1">
                                <option value="=" <?php echo $val = ($row['comp1'] == '=') ? 'selected' : ' ';?>>equals</option>
                                <option value=">" <?php echo $val = ($row['comp1'] == '>') ? 'selected' : ' ';?>>greater</option>
                                <option value="<" <?php echo $val = ($row['comp1'] == '<') ? 'selected' : ' ';?>>less</option>
                                <option value=">=" <?php echo $val = ($row['comp1'] == '>=') ? 'selected' : ' ';?>>greater or equal</option>
                                <option value="<=" <?php echo $val = ($row['comp1'] == '<=') ? 'selected' : ' ';?>>less or equal</option>
                                <option value="<>" <?php echo $val = ($row['comp1'] == '<>') ? 'selected' : ' ';?>>not equal</option>
                                <option value="BETWEEN" <?php echo $val = ($row['comp1'] == 'BETWEEN') ? 'selected' : ' ';?>>BETWEEN</option>
                                <option value="IN" <?php echo $val = ($row['comp1'] == 'IN') ? 'selected' : ' ';?>>IN</option>
                                <option value="LIKE" <?php echo $val = ($row['comp1'] == 'LIKE') ? 'selected' : ' ';?>>LIKE</option>
                                <option value="NOT BETWEEN" <?php echo $val = ($row['comp1'] == 'NOT BETWEEN') ? 'selected' : ' ';?>>NOT BETWEEN</option>
                                <option value="NOT IN" <?php echo $val = ($row['comp1'] == 'NOT IN') ? 'selected' : ' ';?>>NOT IN</option>
                                <option value="NOT LIKE" <?php echo $val = ($row['comp1'] == 'NOT LIKE') ? 'selected' : ' ';?>>NOT LIKE</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="selection1" id="selection1" value="<?php echo $value = ($id > 0) ? $row['selection1'] : ''; ?>">
                        </td>
                    </tr>
                    <tr class="tr-grouping">
                        <td>
                        </td>
                        <td>
                            <select id="oper1" name="oper1">
                                <option value="AND" <?php echo $val = ($row['oper1'] == 'AND') ? 'selected' : ' ';?>>AND</option>
                                <option value="OR" <?php echo $val = ($row['oper1'] == 'OR') ? 'selected' : ' ';?>>OR</option>
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>

                    <!-- ROW THREE DATA 1 -->
                    <tr class="tr-grouping">
                        <td>
                            <select id="fields2" name="fields2">
                                <option value=""></option>
                                <option value="supplier_code" <?php echo $val = ($row['fields2'] == 'supplier_code') ? 'selected' : ' ';?>>Supplier</option>
                                <option value="manufacturer" <?php echo $val = ($row['fields2'] == 'manufacturer') ? 'selected' : ' ';?>>Manufacturer</option>
                                <option value="product_code" <?php echo $val = ($row['fields2'] == 'product_code') ? 'selected' : ' ';?>>Product code</option>
                                <option value="product_desc" <?php echo $val = ($row['fields2'] == 'product_desc') ? 'selected' : ' ';?>>Product description</option>
                                <option value="ean_code" <?php echo $val = ($row['fields2'] == 'ean_code') ? 'selected' : ' ';?>>EAN code</option>
                                <option value="category" <?php echo $val = ($row['fields2'] == 'category') ? 'selected' : ' ';?>>Category</option>
                                <option value="subcat1" <?php echo $val = ($row['fields2'] == 'subcat1') ? 'selected' : ' ';?>>Sub category 1</option>
                                <option value="subcat2" <?php echo $val = ($row['fields2'] == 'subcat2') ? 'selected' : ' ';?>>Sub category 2</option>
                                <option value="supplier_purchase_price <?php echo $val = ($row['fields2'] == 'supplier_purchase_price') ? 'selected' : ' ';?>">Supplier purchase price</option>

                            </select>
                        </td>
                        <td>
                            <select id="comp2" name="comp2">
                                <option value="=" <?php echo $val = ($row['comp2'] == '=') ? 'selected' : ' ';?>>equals</option>
                                <option value=">" <?php echo $val = ($row['comp2'] == '>') ? 'selected' : ' ';?>>greater</option>
                                <option value="<" <?php echo $val = ($row['comp2'] == '<') ? 'selected' : ' ';?>>less</option>
                                <option value=">=" <?php echo $val = ($row['comp2'] == '>=') ? 'selected' : ' ';?>>greater or equal</option>
                                <option value="<=" <?php echo $val = ($row['comp2'] == '<=') ? 'selected' : ' ';?>>less or equal</option>
                                <option value="<>" <?php echo $val = ($row['comp2'] == '<>') ? 'selected' : ' ';?>>not equal</option>
                                <option value="BETWEEN" <?php echo $val = ($row['comp2'] == 'BETWEEN') ? 'selected' : ' ';?>>BETWEEN</option>
                                <option value="IN" <?php echo $val = ($row['comp2'] == 'IN') ? 'selected' : ' ';?>>IN</option>
                                <option value="LIKE" <?php echo $val = ($row['comp2'] == 'LIKE') ? 'selected' : ' ';?>>LIKE</option>
                                <option value="NOT BETWEEN" <?php echo $val = ($row['comp2'] == 'NOT BETWEEN') ? 'selected' : ' ';?>>NOT BETWEEN</option>
                                <option value="NOT IN" <?php echo $val = ($row['comp2'] == 'NOT IN') ? 'selected' : ' ';?>>NOT IN</option>
                                <option value="NOT LIKE" <?php echo $val = ($row['comp2'] == 'NOT LIKE') ? 'selected' : ' ';?>>NOT LIKE</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="selection2" id="selection2" value="<?php echo $value = ($id > 0) ? $row['selection2'] : ''; ?>">
                        </td>
                    </tr>
                    <tr class="tr-grouping">
                        <td>
                        </td>
                        <td>
                            <select id="oper2" name="oper2">
                                <option value="AND" <?php echo $val = ($row['oper2'] == 'AND') ? 'selected' : ' ';?>>AND</option>
                                <option value="OR" <?php echo $val = ($row['oper2'] == 'OR') ? 'selected' : ' ';?>>OR</option>
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <!-- ROW FOUR DATA 1 -->
                    <tr class="tr-grouping">
                        <td>
                            <select id="fields3" name="fields3">
                                <option value=""></option>
                                <option value="supplier_code" <?php echo $val = ($row['fields3'] == 'supplier_code') ? 'selected' : ' ';?>>Supplier</option>
                                <option value="manufacturer" <?php echo $val = ($row['fields3'] == 'manufacturer') ? 'selected' : ' ';?>>Manufacturer</option>
                                <option value="product_code" <?php echo $val = ($row['fields3'] == 'product_code') ? 'selected' : ' ';?>>Product code</option>
                                <option value="product_desc" <?php echo $val = ($row['fields3'] == 'product_desc') ? 'selected' : ' ';?>>Product description</option>
                                <option value="ean_code" <?php echo $val = ($row['fields3'] == 'ean_code') ? 'selected' : ' ';?>>EAN code</option>
                                <option value="category" <?php echo $val = ($row['fields3'] == 'category') ? 'selected' : ' ';?>>Category</option>
                                <option value="subcat1" <?php echo $val = ($row['fields3'] == 'subcat1') ? 'selected' : ' ';?>>Sub category 1</option>
                                <option value="subcat2" <?php echo $val = ($row['fields3'] == 'subcat2') ? 'selected' : ' ';?>>Sub category 2</option>
                                <option value="supplier_purchase_price <?php echo $val = ($row['fields3'] == 'supplier_purchase_price') ? 'selected' : ' ';?>">Supplier purchase price</option>

                            </select>
                        </td>
                        <td>
                            <select id="comp3" name="comp3">
                                <option value="=" <?php echo $val = ($row['comp3'] == '=') ? 'selected' : ' ';?>>equals</option>
                                <option value=">" <?php echo $val = ($row['comp3'] == '>') ? 'selected' : ' ';?>>greater</option>
                                <option value="<" <?php echo $val = ($row['comp3'] == '<') ? 'selected' : ' ';?>>less</option>
                                <option value=">=" <?php echo $val = ($row['comp3'] == '>=') ? 'selected' : ' ';?>>greater or equal</option>
                                <option value="<=" <?php echo $val = ($row['comp3'] == '<=') ? 'selected' : ' ';?>>less or equal</option>
                                <option value="<>" <?php echo $val = ($row['comp3'] == '<>') ? 'selected' : ' ';?>>not equal</option>
                                <option value="BETWEEN" <?php echo $val = ($row['comp3'] == 'BETWEEN') ? 'selected' : ' ';?>>BETWEEN</option>
                                <option value="IN" <?php echo $val = ($row['comp3'] == 'IN') ? 'selected' : ' ';?>>IN</option>
                                <option value="LIKE" <?php echo $val = ($row['comp3'] == 'LIKE') ? 'selected' : ' ';?>>LIKE</option>
                                <option value="NOT BETWEEN" <?php echo $val = ($row['comp3'] == 'NOT BETWEEN') ? 'selected' : ' ';?>>NOT BETWEEN</option>
                                <option value="NOT IN" <?php echo $val = ($row['comp3'] == 'NOT IN') ? 'selected' : ' ';?>>NOT IN</option>
                                <option value="NOT LIKE" <?php echo $val = ($row['comp3'] == 'NOT LIKE') ? 'selected' : ' ';?>>NOT LIKE</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="selection3" id="selection3" value="<?php echo $value = ($id > 0) ? $row['selection3'] : ''; ?>">
                        </td>
                    </tr>
                    <tr class="tr-grouping">
                        <td>
                        </td>
                        <td>
                            <select id="oper3" name="oper3">
                                <option value="AND" <?php echo $val = ($row['oper3'] == 'AND') ? 'selected' : ' ';?>>AND</option>
                                <option value="OR" <?php echo $val = ($row['oper3'] == 'OR') ? 'selected' : ' ';?>>OR</option>
                            </select>
                        </td>
                        <td>
                        </td>
                    </tr>
                    <!-- ROW FIVE DATA 1 -->
                    <tr class="tr-grouping">
                        <td>
                            <select id="fields4" name="fields4">
                                <option value=""></option>
                                <option value="supplier_code" <?php echo $val = ($row['fields4'] == 'supplier_code') ? 'selected' : ' ';?>>Supplier</option>
                                <option value="manufacturer" <?php echo $val = ($row['fields4'] == 'manufacturer') ? 'selected' : ' ';?>>Manufacturer</option>
                                <option value="product_code" <?php echo $val = ($row['fields4'] == 'product_code') ? 'selected' : ' ';?>>Product code</option>
                                <option value="product_desc" <?php echo $val = ($row['fields4'] == 'product_desc') ? 'selected' : ' ';?>>Product description</option>
                                <option value="ean_code" <?php echo $val = ($row['fields4'] == 'ean_code') ? 'selected' : ' ';?>>EAN code</option>
                                <option value="category" <?php echo $val = ($row['fields4'] == 'category') ? 'selected' : ' ';?>>Category</option>
                                <option value="subcat1" <?php echo $val = ($row['fields4'] == 'subcat1') ? 'selected' : ' ';?>>Sub category 1</option>
                                <option value="subcat2" <?php echo $val = ($row['fields4'] == 'subcat2') ? 'selected' : ' ';?>>Sub category 2</option>
                                <option value="supplier_purchase_price <?php echo $val = ($row['fields4'] == 'supplier_purchase_price') ? 'selected' : ' ';?>">Supplier purchase price</option>

                            </select>
                        </td>
                        <td>
                            <select id="comp4" name="comp4">
                                <option value="=" <?php echo $val = ($row['comp4'] == '=') ? 'selected' : ' ';?>>equals</option>
                                <option value=">" <?php echo $val = ($row['comp4'] == '>') ? 'selected' : ' ';?>>greater</option>
                                <option value="<" <?php echo $val = ($row['comp4'] == '<') ? 'selected' : ' ';?>>less</option>
                                <option value=">=" <?php echo $val = ($row['comp4'] == '>=') ? 'selected' : ' ';?>>greater or equal</option>
                                <option value="<=" <?php echo $val = ($row['comp4'] == '<=') ? 'selected' : ' ';?>>less or equal</option>
                                <option value="<>" <?php echo $val = ($row['comp4'] == '<>') ? 'selected' : ' ';?>>not equal</option>
                                <option value="BETWEEN" <?php echo $val = ($row['comp4'] == 'BETWEEN') ? 'selected' : ' ';?>>BETWEEN</option>
                                <option value="IN" <?php echo $val = ($row['comp4'] == 'IN') ? 'selected' : ' ';?>>IN</option>
                                <option value="LIKE" <?php echo $val = ($row['comp4'] == 'LIKE') ? 'selected' : ' ';?>>LIKE</option>
                                <option value="NOT BETWEEN" <?php echo $val = ($row['comp4'] == 'NOT BETWEEN') ? 'selected' : ' ';?>>NOT BETWEEN</option>
                                <option value="NOT IN" <?php echo $val = ($row['comp4'] == 'NOT IN') ? 'selected' : ' ';?>>NOT IN</option>
                                <option value="NOT LIKE" <?php echo $val = ($row['comp4'] == 'NOT LIKE') ? 'selected' : ' ';?>>NOT LIKE</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="selection4" id="selection4" value="<?php echo $value = ($id > 0) ? $row['selection4'] : ''; ?>">
                        </td>
                    </tr>

                </table>
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
                    $_SESSION['id']=$id;
                }
                ?>
                <input class="button button--add" onclick="window.history.go(-1); return false;" type="submit" name="back" value="Cancel">
                <?php
                    echo "<a href='groupsimulation.php' target='_blank' class='button button--update'>Simulate</a>";
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
    // Fetch Pricegroups to options
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
