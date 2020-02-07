  <?php
    require "header.php";
  ?>

  <script src="js/pricegroup.js"></script>
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
        $sql = "SELECT * from pricegroups where prcgrp_id = $id";
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
              <input type="text"  name="prcgrp_code" value=<?php echo $value = ($id > 0) ? $row['prcgrp_code'] : ""; ?>>
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
              <input type="text" name="prcgrp_name" value=<?php echo $value = ($id > 0) ? $row['prcgrp_name'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
            </div>
        </div>
        <!-- ***************-->
        <!-- PRICE GROUP FORMULA     -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Price group formula
            </div>
            <div class="col-75">
              <input type="text" name="prcgrp_formula" value=<?php echo $value = ($id > 0) ? $row['prcgrp_formula'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
