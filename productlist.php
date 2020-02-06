  <?php
    require "header.php";
  ?>

  <script src="js/productlist.js"></script>
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
        $sql = "SELECT * from productlists where productlist_id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
  	}

    ?>

    <!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
    <div class="container">
      <form name="productlist" action="productlist_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add supplier file" : "Update"; ?></legend>

        <!-- ***************-->
        <!-- SUPLLIER       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Supplier
            </div>
            <div class="col-75">
              <input type="text"  name="file_supplier" value=<?php echo $value = ($id > 0) ? $row['file_supplier'] : ""; ?>>
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
              <input type="text" name="file_supp_name" value=<?php echo $value = ($id > 0) ? $row['file_supp_name'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
              <input type="text" name="file_name" value=<?php echo $value = ($id > 0) ? $row['file_name'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
              <input type="text" name="file_loc"  value=<?php echo $value = ($id > 0) ? $row['file_loc'] : ""; ?>>
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
              <input type="text" name="file_col_sep" maxlength="1" value=<?php echo $value = ($id > 0) ? $row['file_col_sep'] : ""; ?>>
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
              <input type="text" name="file_mfg_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_mfg_col'] : ""; ?>>
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
              <input type="text" name="file_prod_col"  maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_prod_col'] : ""; ?>>
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
              <input type="text" name="file_prod_desc_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_prod_desc_col'] : ""; ?>>
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
              <input type="text" name="file_EAN_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_EAN_col'] : ""; ?>>
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
              <input type="text" name="file_category_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_category_col'] : ""; ?>>
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
              <input type="text" name="file_subcat1_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_subcat1_col'] : ""; ?>>
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
              <input type="text" name="file_subcat2_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_subcat2_col'] : ""; ?>>
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
              <input type="text" name="file_purch_price_col" maxlength="2" value=<?php echo $value = ($id > 0) ? $row['file_purch_price_col'] : ""; ?>>
            </div>
        </div>

        <!-- ***************-->
        <!-- ***************-->
        <!--div class="row"-->
          <div class="container-btn1">
                <?php
                if($mode=="1") {
                echo "<input class='button button--add' onclick='return validateForm()' type='submit' name='add' value='Add'>";
              } else {
                echo "<input class='button button--update' onclick='return validateForm()' type='submit' name='update' value='Update'>";
                echo "<input class='button button--delete' onclick='return confDelete()' type='submit' name='delete' value='Delete'>";
              }
              ?>
              <input class="button button--add" onclick="window.history.go(-1); return false;" type="submit" name="back" value="Cancel">
          </div>
        </div>
      </form>

    </div>
  <?php
    require "footer.php";
  ?>
