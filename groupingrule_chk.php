<?php

  require 'db/db.php';
  require 'common/ypflib.php';
  if(!empty($_POST['add'])) {
    $mode = $_POST['add'];
  } elseif (!empty($_POST['update'])) {
      $mode = $_POST['update'];
  } elseif (!empty($_POST['delete'])) {
      $mode = $_POST['delete'];
  }

// Mode = ADD
  if ($mode == 'Add') {
		if (checkifItemExist($conn)=="OK") {
      $sql = itemAddUpdate();
		  $returnCode = 'return=addSuccess';
	  } else {
    $returnCode = 'return=alreadyExists';
    }

  }
// Mode = UPDATE
  if ($mode == 'Update') {
    $sql = itemAddUpdate();
    $returnCode = 'return=updateSuccess';
  }
// Mode = DELETE
  if ($mode == 'Delete') {
    $sql = itemDelete();
    $returnCode = 'return=deleteSuccess';
  }

    //Execute
    if (mysqli_query($conn, $sql)) {
	  $status = "";
      mysqli_close($conn);
	  header("Location: groupingrules.php?$returnCode" );
	  exit;
	}
    else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      $status = "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
    mysqli_close($conn);

 function checkifItemExist($conn) {
   //$jasenlaji = strip_tags($_POST["jasenlaji"]);
   //$sql = "SELECT jasenlaji from jasenlaji where jasenlaji like ".'"'."%".$_POST['jasenlaji']."%".'"';
   $sql = "SELECT * from groupingrules where grouping_code = $POST[grouping_code]";


   $result = mysqli_query($conn, $sql);
   if($result->num_rows > 0) {
	  return "Exist";
   } else {
	  return "OK";
   }

   mysqli_close($conn);
  }


  function itemAddUpdate() {
    global $kentat;
    global $mode;
    global $conn;

    $kentat = array ('grouping_code',	'grouping_desc',	'price_group',	'grouping_SQL_selection',	'grouping_selection_other');

    $grouping_code = $_POST["grouping_code"];
    $grouping_desc = $_POST["grouping_desc"];
    $price_group = $_POST["price_group"];
    $grouping_SQL_selection = $_POST["grouping_SQL_selection"];
    $grouping_selection_other	= $_POST["grouping_selection_other"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO groupingrules($kentat) VALUES ('$grouping_code',	'$grouping_desc',	'$price_group',
            '$grouping_SQL_selection',	'$grouping_selection_other')";
    } elseif ($mode == 'Update') {
  	  $id = $_POST["id"];
      $sql = "UPDATE groupingrules SET
      grouping_code = '$grouping_code',
      grouping_desc = '$grouping_desc',
      price_group = '$price_group',
      grouping_SQL_selection = '$grouping_SQL_selection',
      grouping_selection_other	= '$grouping_selection_other'
      WHERE id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $id = $_POST['id'];
    $sql = "DELETE FROM groupingrules WHERE id = '$id'";
    return $sql;
  }

 ?>
