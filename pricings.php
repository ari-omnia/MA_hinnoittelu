<?php
require "header.php";
require "includes/logged.php";

if (isset($_POST['searchgroup']) || isset($_POST['searchnewrow'])) {
    if ($_POST['searchgroup'] != $_SESSION['searchgroup']) {
        $_SESSION['searchgroup'] = $_POST['searchgroup'];
        //$sqlWhere1 = $sqlWhere1." grouping_code ="."'".$_SESSION['searchgroup']."'";
        //echo "sqlWhere1= ".$sqlWhere1;
    }
    if ($_POST['searchnewrow'] != $_SESSION['searchnewrow']) {
        $_SESSION['searchnewrow'] = $_POST['searchnewrow'];
    }
}
$sqlsearchgroup = "";
$sqlsearchnewrow = "";

if ($_SESSION['searchgroup'] != "") {
    $sqlsearchgroup = " grouping_code ="."'".$_SESSION['searchgroup']."'";
}
if ($_SESSION['searchnewrow'] != "") {
    if ($sqlsearchgroup != "") {
        $sqlsearchgroup = $sqlsearchgroup." AND ";
    }
    $sqlsearchnewrow = " new_product ="."1";
}

$sqlsearch = $sqlsearchgroup.$sqlsearchnewrow;

if ($sqlsearch != "") {
    $sqlsearch = "WHERE ".$sqlsearch;
}

  //!! define how many results you want per page
  $results_per_page = 100;
  // find out the number of results stored in database
  $sql='SELECT * FROM pricing '.''.$sqlsearch;

  $result = mysqli_query($conn, $sql);
  $number_of_results = mysqli_num_rows($result);
  // determine number of total pages available
  $number_of_pages = ceil($number_of_results/$results_per_page);
  // determine which page number visitor is currently on
  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }

  // determine the sql LIMIT starting number for the results on the displaying page
  $this_page_first_result = ($page-1)*$results_per_page;
  //!!
?>

    <!-- Actual Page DIV -->
    <section class="function-container">
      <div>
        <!--input class="button button--add" type="submit" value="Add" onclick="window.location.href='pricing.php?mode=1'"-->
        <!--input type="text" id="searchGroupRule" onkeyup="searchGroupRule()" placeholder="Search for group rules..." title="Type in a name"-->
        <form method="post">
            <input type='text' id='searchgroup' name='searchgroup'>
            <input type="checkbox" id="searchnewrow" name="searchnewrow" value="searchnewrow">
            <label for="vehicle1"> New Products</label>
            <input type="submit" name="button1" class="button button--add" value="Src/Res">
            <a href='pricings.php' class='button button--reset' style="text-decoration: none;">Start </a>
        </form>
    </div>
    <div style="flex-basis:820px">
    </div>
    <div>
        <form id="pricingpresta" name="pricingpresta" action="prestaupdate.php" method="post">
            <input class="button button--presta" type="submit" value="Presta update">
        </form>
        <?php
		if(isset($_GET['return']))
        {
          if($_GET['return'] == 'addSuccess') {
           echo "Tietue lisätty onnistuneesti";
          }if($_GET['return'] == 'updateSuccess') {
           echo "Tietue päivitetty onnistuneesti";
          }if($_GET['return'] == 'deleteSuccess') {
           echo "Tietue poistettu onnistuneesti";
          }
          if($_GET['return'] == 'alreadyExists') {
           echo "Pricing specs löytyy jo kannasta";
          }
	    	}
         ?>
      </div>
    </section>


    <section class="listcontainer">
      <!--form action="lista_chk.php" method="post"-->
        <div class="row">
          <div class="col-25">

            <?php
              $sql = "SELECT * from pricing ".$sqlsearch." LIMIT " . $this_page_first_result . "," .  $results_per_page;
              $result = mysqli_query($conn, $sql);
              if(mysqli_num_rows($result) > 0) {
                // output data columns
            		echo "<table class='listtable' id='myTable'>
                    <thead><tr>
    					<th>Val</th>
                        <th>Id</th>
                        <th>Price upd. file</th>
                        <th>Prod. Manufacturer</th>
                        <th>Product</th>
                        <th>EAN code</th>
                        <th>Category</th>
                        <th>Sub cat. 1</th>
                        <th>Sub cat. 2</th>
                        <th>Sales pr.</th>
                        <th>Target cat</th>
                        <th>Grouping rule</th>
                        <th>New product</th>
                  </tr></thead>";
                // output data of each row
            		while($row = mysqli_fetch_assoc($result)) {
						$id = $row["id"];
            			echo "<tr>
          				  <form name='pricingrow' action='pricing.php?mode=0' method='post'>
                            <td><input class='button button--rowselect' type='submit' name='update' value=''>
                            <input type='hidden' name='id' value=$id></td>
                            <td>".$row["id"]."</td>
                            <td>".$row["supplier_file"]."</td>
          				    <td>".$row["manufacturer"]."</td>
                            <td>".$row["product_code"]."</td>
                            <td>".$row["ean_code"]."</td>
                            <td>".$row["category"]."</td>
                            <td>".$row["subcat1"]."</td>
                            <td>".$row["subcat2"]."</td>
                            <td>".$row["sales_price"]."</td>
                            <td>".$row["target_category"]."</td>
                            <td>".$row["grouping_code"]."</td>
                            <td>".$row["new_product"]."</td>
          				  </form>
                  </tr>";
            		}
            		echo "</table>";
            	} else {
            		echo "0 results";
            	}
              mysqli_close($conn);
             ?>

          </div>
        </div>
    </section>
    <section class='pagination-container'>
      <div>
      </div>
      <div>
        <?php
        // !!display the links to the pages
        for ($page=1;$page<=$number_of_pages;$page++) {
            if ($page == $_GET['page']) {
                echo '<a class="pagination-button pagination-button--current" href="pricings.php?page=' . $page . '">' . $page . '</a> ';
            } else {
              //echo "<button class='pagination-button' type='button'>$page</button>";
              //echo '<a href="kalustoluetteloList.php?page=' . $page . '">' . $page . '</a> ';
                if (printPaginatioButton($page, $number_of_pages)) {
                    echo '<a class="pagination-button" href="pricings.php?page=' . $page . '">' . $page . '</a> ';
                }
            }
        }
        //!!
        ?>
      </div>
      <div>
      </div>
    </section>



<?php
function printPaginatioButton($page, $number_of_pages) {
    if (!isset($_GET['page'])) {
        switch ($page) {
            case ($page < 4):
                return true;
                break;

            case ($page % 10 == 0):
                return true;
                break;

            case ($page > ($number_of_pages - 4)):
                return true;
                break;

            default:
                return false;
                break;
        }

    } else {
        switch ($page) {
            case ($page < 4):
                return true;
                break;

            case ($_GET['page'] - $page < 3 && $_GET['page'] - $page > 0):
                return true;
                break;
            case ($page - $_GET['page'] < 3 && $page - $_GET['page'] > 0):
                return true;
                break;

            case ($page % 10 == 0):
                return true;
                break;
            case ($page > ($number_of_pages - 3)):
                return true;
                break;

            default:
                return false;
                break;
        }
    }

}

require "footer.php";
?>

<script>
function searchGroupRule() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("searchGroupRule");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[11];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}
</script>
