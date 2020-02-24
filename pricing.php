  <?php
    require "header.php";
    require "includes/logged.php";
  ?>

  <script src="js/pricing.js"></script>
  <script src="js/ypjslib.js"></script>

  <?php
  	if(!empty($_POST['add'])) {
  		$mode = $_POST['add'];
  	} elseif (!empty($_POST['update'])) {
        $mode = $_POST['update'];
  	} elseif (!empty($_POST['delete'])) {
        $mode = $_POST['delete'];
  	}

    $mode = $_GET['mode'];

  	if(!empty($_POST['id'])) {
        $id = $_POST['id'];
  	}
  	else {
  	  $id = -1;
  	}

  	if ($id > 0) {
        $sql = "SELECT * from pricing where id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
  	}

    ?>

    <!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
    <div class="container">
      <form name="pricing" action="pricing_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add pricing" : "Update"; ?></legend>

        <!-- ***************-->
        <!-- SUPPLIER FILE      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Pricing file
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['supplier_file'] : ""; ?>
                <!--input type="text"  name="supplier_file" value=<?//php echo $value = ($id > 0) ? $row['supplier_file'] : ""; ?>-->
                <input type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- PRODUCT MANUFACTURER -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Manufacturer
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['manufacturer'] : ""; ?>
                <!--input type="text" name="manufacturer" value=<?//php echo $value = ($id > 0) ? $row['manufacturer'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- SUPLLIER      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Supplier
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['supplier_code'] : ""; ?>
                <!--input type="text" name="supplier_code" value=<?//php echo $value = ($id > 0) ? $row['supplier_code'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- PRODUCT CODE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Product
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['product_code'] : ""; ?>
                <!--input type="text" name="product_code" value=<?//php echo $value = ($id > 0) ? $row['product_code'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- PRODUCT DESCRIPTION     -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Description
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['product_desc'] : ""; ?>
                <!--input type="text" name="product_desc" value=<?//php echo $value = ($id > 0) ? $row['product_desc'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- EAN CODE        -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              EAN code
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['ean_code'] : ""; ?>
                <!--input type="text" name="ean_code" value=<?//php echo $value = ($id > 0) ? $row['ean_code'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- CATEGORY       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Category
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['category'] : ""; ?>
                <!--input type="text" name="category" value=<?//php echo $value = ($id > 0) ? $row['category'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- SUB CATEGORY 1       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Sub cat. 1
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['subcat1'] : ""; ?>
                <!--input type="text" name="subcat1" value=<?//php echo $value = ($id > 0) ? $row['subcat1'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- SUB CATEGORY 2       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Sub cat. 2
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['subcat2'] : ""; ?>
                <!--input type="text" name="subcat2" value=<?//php echo $value = ($id > 0) ? $row['subcat2'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- ORIGINAL PURCH PRICE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Supplier price
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['supplier_purchase_price'] : ""; ?>
                <!--input type="text" name="supplier_purchase_price" value=<?//php echo $value = ($id > 0) ? $row['supplier_purchase_price'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- NEW PURCH PRICE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              New price
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['new_purchase_price'] : ""; ?>
                <!--input type="text" name="new_purchase_price" value=<?//php echo $value = ($id > 0) ? $row['new_purchase_price'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- SALES PRICE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Sales price
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['sales_price'] : ""; ?>
                <!--input type="text" name="sales_price" value=<?//php echo $value = ($id > 0) ? $row['sales_price'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING CODE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Grouping code
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['grouping_code'] : ""; ?>
                <!--input type="text" name="grouping_code" value=<?//php echo $value = ($id > 0) ? $row['grouping_code'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- PRICE GROUP CODE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Price group
            </div>
            <div class="col-75">
                <?php echo $value = ($id > 0) ? $row['price_group_code'] : ""; ?>
                <!--input type="text" name="price_group_code" value=<?//php echo $value = ($id > 0) ? $row['price_group_code'] : ""; ?>-->
            </div>
        </div>
        <!-- ***************-->
        <!-- ***************-->
        <!--div class="row"-->
          <div class="container-btn1">
                <?php
                //if($mode=="1") {
                //    echo "<input class='button button--add' onclick='return validateForm()' type='submit' name='add' value='Add'>";
                //} else {
                //    echo "<input class='button button--update' onclick='return validateForm()' type='submit' name='update' value='Update'>";
                //    echo "<input class='button button--delete' onclick='return confDelete()' type='submit' name='delete' value='Delete'>";
                //}
                ?>
              <input class="button button--add" onclick="window.history.go(-1); return false;" type="submit" name="back" value="Cancel">
          </div>
        </div>
      </form>

    </div>
  <?php
    require "footer.php";
  ?>
