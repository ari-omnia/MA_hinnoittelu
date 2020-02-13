<?php
    /*
     * Fetch all supplier files.
     */
    function fetchAllFiles()
    {
        global $conn;
        
        $res = $conn->query("SELECT * FROM supplierlists");

        while($row = $res->fetch_assoc())
        {
            arrangeFile($row['supplier_file'], $row);
        }
    }

    /*
     * Arrange one supplier's file to the unified data table.
     * If $row is empty we have to get the single record (row) from
     * supplierlists table based on the file name. 
     */
    function arrangeFile($file, $row = '')
    {
        global $conn;
        
        if($row == '')
        {
            $res = $conn->query("SELECT * FROM supplierlists WHERE supplier_file = '".$file."';");

            $row = $res->fetch_assoc();
        }
        
        $handle = fopen($row['file_path'].'/'.$file, 'r');

        if($row['file_column_separator'] == "t" || $row['file_column_separator'] == "T")
        {
            $sep = "\t";    // Separator char is tab.
        }
        else
        {
            $sep = $row['file_column_separator'];
        }

        // Check if there is a headline row. If there is read it out of the way. We don't need it.
        if($row['data_start_row'] == 1)
        {
            fgetcsv($handle, 0, $sep);
        }
        
//            while(($data = fgetcsv($handle, 0, $sep)) !== false) // Uncomment this line to go through all lines in file.
        for($i = 0; $i < 2; $i++)   // ... and remove this line...
        {
            $data = fgetcsv($handle, 0, $sep);  // ... and this.

            // Suppress notices about NULL indexes in the next query.
            error_reporting(E_ALL & ~E_NOTICE); 

            $ins = "INSERT INTO unifiedlists
                                (
                                    supplier_file,
                                    manufacturer,
                                    supplier_code,
                                    product_code,
                                    product_desc,
                                    ean_code,
                                    category,
                                    subcat1,
                                    subcat2,
                                    supplier_purchase_price
                                )
                    VALUES 
                            (
                                '".$row["supplier_file"]."', 
                                '".$data[$row["column_manufacturer"]]."', 
                                '".$data[$row["supplier_code"]]."',
                                '".$data[$row["column_product_code"]]."',
                                '".$data[$row["column_product_desc"]]."',
                                '".$data[$row["column_ean_code"]]."',
                                '".$data[$row["column_category"]]."',
                                '".$data[$row["column_subcat1"]]."',
                                '".$data[$row["column_subcat2"]]."',
                                '".$data[$row["column_purchase_price"]]."'
                            )";

            $conn->query($ins);

            // Turn notices on again.
            error_reporting(E_ALL); 
        }
    }
