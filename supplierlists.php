<?php

require "header.php";
require "includes/logged.php";

//!! define how many results you want per page
$results_per_page = 10;
// find out the number of results stored in database
$sql='SELECT * FROM supplierlists';
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
        <input class="button button--add" type="submit" value="Add" onclick="window.location.href='supplierlist.php?mode=1'">
      </div>
    </section>


<section class="listcontainer">
    <!--form action="lista_chk.php" method="post"-->
    <div class="row">
        <div class="col-25">

        <?php
        $sql = "SELECT id, supplier_code, supplier_name, purchase_price_factor, supplier_file, file_path, new_products_totalsum
                    from supplierlists LIMIT " . $this_page_first_result . "," .  $results_per_page;
        $result = mysqli_query($conn, $sql);
        if(mysqli_num_rows($result) > 0) {
            // output data columns
    		echo "<table class='listtable'>
            <thead><tr>
				<th>Val</th>
                <th>Id</th>
                <th>Supplier</th>
                <th>Sup. name</th>
                <th>Price factor</th>
                <th>File name</th>
                <th>File location</th>
                <th>New Products amount</th>
            </tr></thead>";
            // output data of each row
        		while($row = mysqli_fetch_assoc($result)) {
					$id = $row["id"];
        			echo "<tr>
                        <form name='supplierlistrow' action='supplierlist.php?mode=0' method='post'>
                            <td><input class='button button--rowselect' type='submit' name='update' value=''>
                            <input type='hidden' name='id' value=$id></td>
                            <td>".$row["id"]."</td>
                            <td>".$row["supplier_code"]."</td>
                            <td>".$row["supplier_name"]."</td>
                            <td>".$row["purchase_price_factor"]."</td>
                            <td>".$row["supplier_file"]."</td>
                            <td>".$row["file_path"]."</td>
                            <td style='text-align:center'> ".$row["new_products_totalsum"]."</td>
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
          //echo "<button class='pagination-button' type='button'>$page</button>";
          //echo '<a href="kalustoluetteloList.php?page=' . $page . '">' . $page . '</a> ';
          echo '<a class="pagination-button" href="supplierlists.php?page=' . $page . '">' . $page . '</a> ';
        }
        //!!
        ?>
      </div>
      <div>
      </div>
    </section>

<?php

require "footer.php";

?>
