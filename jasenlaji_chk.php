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
  if ($mode == 'Lisää') {
		if (checkifItemExist($conn)=="OK")
	    {
           $sql = itemAddUpdate();
		   $returnCode = 'return=addSuccess';
	    }
      else
      { $returnCode = 'return=alreadyExists';}
        
  }
// Mode = UPDATE
  if ($mode == 'Päivitä') {
    $sql = itemAddUpdate();
    $returnCode = 'return=updateSuccess';
  }
// Mode = DELETE
  if ($mode == 'Poista') {
    $sql = itemDelete();
    $returnCode = 'return=deleteSuccess';
  }

    //Execute
    if (mysqli_query($conn, $sql)) {
	  $status = "";
      mysqli_close($conn);
	  header("Location: jasenlaji_selaus.php?$returnCode" );
	  exit;
	}
    else {
	  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      $status = "Error: " . $sql . "<br>" . mysqli_error($conn);
	}
    mysqli_close($conn);

 function checkifItemExist($conn) {
   $jasenlaji = strip_tags($_POST["jasenlaji"]);
   $sql = "SELECT jasenlaji from jasenlaji where jasenlaji like ".'"'."%".$_POST['jasenlaji']."%".'"';
   $result = mysqli_query($conn, $sql);
   if($result->num_rows > 0)
   {
	  return "Exist";
   }
   else
   {
	 return "OK";
   }

   mysqli_close($conn);
 }

  function checkJasenmaksuvuodessa() {
    if ( isset($_POST["jasenmaksu_vuodessa"])&&($_POST["jasenmaksu_vuodessa"]!="") )
	{
		if (!(isValueNumeric(ypCnvNbrFormDb($_POST["jasenmaksu_vuodessa"],2))))
		{
		  echo "Jäsenmaksu ei ole numeerinen";
		  return false;
		}
		else
        {
		 if (!isRightDecimal($_POST["jasenmaksu_vuodessa"],2))
		 {
			 echo "Luvussa pitää olla kaksi desimaalia";
		 }
		}
	}
  }

  function itemAddUpdate() {
    global $kentat;
    global $mode;
	global $conn;
	 $kentat = array ('jasenlaji', 'jasenlaji_kuvaus', 'jasenmaksu', 'jasenmaksu_vuodessa');
	 $jasenlaji = mysqli_real_escape_string($conn,strip_tags($_POST["jasenlaji"]));
	 $kuvaus = mysqli_real_escape_string($conn,strip_tags($_POST["jasenlaji_kuvaus"]));
	 $jasenmaksu = $_POST["jasenmaksu"];
	 $jasenmaksu_vuodessa=ypCnvNbrFormDb($_POST["jasenmaksu_vuodessa"], 2);
    // prepare
    $kentat = implode(",",$kentat);
	$id = $_POST['id'];
	if ($mode == 'Lisää') {
    $sql = "INSERT INTO jasenlaji($kentat) VALUES ('$jasenlaji', '$kuvaus', $jasenmaksu, $jasenmaksu_vuodessa)";
    }

    elseif ($mode == 'Päivitä') {
	 $jasenlaji_id = $_POST["jasenlaji_id"];
     $sql = "UPDATE jasenlaji SET
     jasenlaji_kuvaus = '$kuvaus',
     jasenlaji = '$jasenlaji',
     jasenmaksu = $jasenmaksu,
     jasenmaksu_vuodessa = $jasenmaksu_vuodessa
     WHERE jasenlaji_id = $id";
   }
   return $sql;
  }


  //  DELETE
  function itemDelete() {
    $jasenlaji_id = $_POST['jasenlaji_id'];
    $sql = "DELETE FROM jasenlaji WHERE jasenlaji_id = '$jasenlaji_id'";
    return $sql;
  }

 ?>
