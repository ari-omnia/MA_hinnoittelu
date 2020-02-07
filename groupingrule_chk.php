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
   $sql = "SELECT * from groupingrules where group_code = $POST[group_code]";


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

    $kentat = array ('group_code',	'group_desc',	'group_price_grp',	'group_selection_SQL',	'group_selection_other');

    $group_code = $_POST["group_code"];
    $group_desc = $_POST["group_desc"];
    $group_price_grp = $_POST["group_price_grp"];
    $group_selection_SQL = $_POST["group_selection_SQL"];
    $group_selection_other	= $_POST["group_selection_other"];

    // prepare
    $kentat = implode(",",$kentat);
  	$id = $_POST['id'];

  	if ($mode == 'Add') {
      $sql = "INSERT INTO groupingrules($kentat) VALUES ('$group_code',	'$group_desc',	'$group_price_grp',
            '$group_selection_SQL',	'$group_selection_other')";
    } elseif ($mode == 'Update') {
  	  $group_id = $_POST["group_id"];
      $sql = "UPDATE groupingrules SET
      group_code = '$group_code',
      group_desc = '$group_desc',
      group_price_grp = '$group_price_grp',
      group_selection_SQL = '$group_selection_SQL',
      group_selection_other	= '$group_selection_other'
      WHERE group_id = $id";
      }
      echo $sql;
  return $sql;
  }


  //  DELETE
  function itemDelete() {
    $group_id = $_POST['id'];
    $sql = "DELETE FROM groupingrules WHERE group_id = '$group_id'";
    return $sql;
  }

 ?>
