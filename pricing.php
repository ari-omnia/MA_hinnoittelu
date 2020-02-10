  <?php
    require "header.php";
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
        $sql = "SELECT * from pricing where prcupd_id = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
  	}

    ?>

    <!-- <div style="width:450px; height:350px; border-radius: 25px; padding:20px; background-color:grey ; margin-top: 15px; margin-bottom: 15px;margin-left: 20%; margin-right: auto;"-->
    <div class="container">
      <form name="pricing" action="pricing_chk.php" method="post">
        <legend><?php echo $legend = ($mode == "1") ? "Add pricing" : "Update"; ?></legend>

        <!-- ***************-->
        <!-- PRICE GROUP FILE      -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Pricing file
            </div>
            <div class="col-75">
              <input type="text"  name="prcupd_file" value=<?php echo $value = ($id > 0) ? $row['prcupd_file'] : ""; ?>>
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
              <input type="text" name="prcupd_manufacturer" value=<?php echo $value = ($id > 0) ? $row['prcupd_manufacturer'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- PRODUCT        -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Product
            </div>
            <div class="col-75">
              <input type="text" name="prcupd_prod" value=<?php echo $value = ($id > 0) ? $row['prcupd_prod'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
              <input type="text" name="prcupd_EAN" value=<?php echo $value = ($id > 0) ? $row['prcupd_EAN'] : ""; ?>>
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
              <input type="text" name="prcupd_category" value=<?php echo $value = ($id > 0) ? $row['prcupd_category'] : ""; ?>>
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
              <input type="text" name="prcupd_subcat1" value=<?php echo $value = ($id > 0) ? $row['prcupd_subcat1'] : ""; ?>>
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
              <input type="text" name="prcupd_subcat2" value=<?php echo $value = ($id > 0) ? $row['prcupd_subcat2'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- PURCH PRICE       -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Purchase price
            </div>
            <div class="col-75">
              <input type="text" name="prcupd_purch_price" value=<?php echo $value = ($id > 0) ? $row['prcupd_purch_price'] : ""; ?>>
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
