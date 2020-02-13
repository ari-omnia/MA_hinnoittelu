  <?php
    require "header.php";
  ?>

  <script src="js/groupingrule.js"></script>
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
              <input type="text"  name="grouping_code" value=<?php echo $value = ($id > 0) ? $row['grouping_code'] : ""; ?>>
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
              <input type="text" name="grouping_desc" value=<?php echo $value = ($id > 0) ? $row['grouping_desc'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
              <input type="text" name="price_group" value=<?php echo $value = ($id > 0) ? $row['price_group'] : ""; ?>>
			  <input type='hidden' name='id' value=<?php echo $id?>></td>
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
              <input type="text" name="grouping_SQL_selection"  value=<?php echo $value = ($id > 0) ? $row['grouping_SQL_selection'] : ""; ?>>
            </div>
        </div>
        <!-- ***************-->
        <!-- GROUPING RULE OTHER  -->
        <!-- ***************-->
        <div class="row">
            <div class="col-25">
              Group selection other
            </div>
            <div class="col-75">
              <input type="text" name="grouping_selection_other" maxlength="1" value=<?php echo $value = ($id > 0) ? $row['grouping_selection_other'] : ""; ?>>
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
