<?php
    /*
     * Fetch all supplier files.
     */
    function fetchAllFiles()
    {
        global $conn;
        
        $res = $conn->query("SELECT * FROM productlists");

        while($row = $res->fetch_assoc())
        {
            arrangeFile($row['file_name'], $row);
        }
    }

    /*
     * Arrange one supplier's file to the unified data table.
     * If $row is empty we have to get the single record (row) from
     * productlists table based on the file name. 
     */
    function arrangeFile($file, $row = '')
    {
        global $conn;
        
        if($row == '')
        {
            $res = $conn->query("SELECT * FROM productlists WHERE file_name = '".$file."';");

            $row = $res->fetch_assoc();
        }
        
        $handle = fopen($row['file_loc'].'/'.$file, 'r');

        if($row['file_col_sep'] == "t" || $row['file_col_sep'] == "T")
        {
            $sep = "\t";    // Separator char is tab.
        }
        else
        {
            $sep = $row['file_col_sep'];
        }

//            while(($data = fgetcsv($handle, 0, $sep)) !== false) // Uncomment this line to go through all lines in file.
        for($i = 0; $i < 2; $i++)   // ... and remove this line...
        {
            $data = fgetcsv($handle, 0, $sep);  // ... and this.

            // Suppress notices about NULL indexes in the next query.
            error_reporting(E_ALL & ~E_NOTICE); 

            $ins = "INSERT INTO pricing 
                                (prcupd_category, 
                                 prcupd_EAN, 
                                 prcupd_file, 
                                 prcupd_manufacturer, 
                                 prcupd_prod, 
                                 prcupd_purch_price, 
                                 prcupd_subcat1, 
                                 prcupd_subcat2, 
                                 prcupd_supplier
                                )
                    VALUES ('".$data[$row["file_category_col"]]."', 
                            '".$data[$row["file_EAN_col"]]."', 
                            '".$row["file_name"]."',
                            '".$data[$row["file_mfg_col"]]."',
                            '".$data[$row["file_prod_col"]]."',
                            '".$data[$row["file_purch_price_col"]]."',
                            '".$data[$row["file_subcat1_col"]]."',
                            '".$data[$row["file_subcat2_col"]]."',
                            '".$data[$row["file_supplier"]]."'
                           )";

            $conn->query($ins);

            // Turn notices on again.
            error_reporting(E_ALL); 
        }
    }
    
//    arrangeFile('gandalf.txt');
//    arrangeFile('ingram.txt');
//    fetchAllFiles();
    
