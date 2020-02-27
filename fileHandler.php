<?php
    /*
     * Fetch all supplier files.
     */
    function fetchAllFiles()
    {
        global $conn;
        echo "<br>";
        var_dump($conn);

        $res = $conn->query("SELECT * FROM supplierlists");
        echo "<br>";
        var_dump($res);
        while($row = $res->fetch_assoc())
        {
            arrangeFile($row['supplier_file'], $row);
        }
    }

    function fixColumns(&$row)
    {
        foreach($row as $k => $v)
        {
            switch($k)
            {
                case 'column_manufacturer':
                case 'column_product_code':
                case 'column_product_desc':
                case 'column_ean_code':
                case 'column_category':
                case 'column_subcat1':
                case 'column_subcat2':
                case 'column_purchase_price':
                    $row[$k] -= 1;
            }
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
        echo "<br>.arrangeFile /";
        var_dump($row);

        if($row == '')
        {
            $res = $conn->query("SELECT * FROM supplierlists WHERE supplier_file = '".$file."';");

            $row = $res->fetch_assoc();
        }

        fixColumns($row);

        $handle = fopen($row['file_path'].'/'.$file, 'r');

        if($row['file_column_separator'] == "t" || $row['file_column_separator'] == "T")
        {
            $sep = "\t";    // Separator char is tab.
        }
        else
        {
            $sep = $row['file_column_separator'];
        }

        // If start row > 1 then read the rows not needed out of the way.
        for($i = 1; $i < $row['data_start_row']; $i++)
        {
            fgetcsv($handle, 0, $sep);
        }

        while(($data = fgetcsv($handle, 0, $sep)) !== false)
        {
            $res = findEAN('unifiedlists', $data[$row['column_ean_code']]);

            $product = collectData($data, $row);

          // Check if the product is already in unifiedlists. If not insert to the table, if found then update the table.
            if($res->num_rows == 0)
            {
                insertIntoUnifiedlistsTable($product);
            }
            else
            {
                updateUnifiedlistsTable($product);
            }
        }
    }

    function findEAN($table, $ean)
    {
        global $conn;

        $eanQuery = "SELECT ean_code FROM ".$table." WHERE ean_code = '".$ean."';";
        $res = $conn->query($eanQuery);

        return $res;
    }

    function collectData($data, $row)
    {
        $prod["supplier_file"] = $row["supplier_file"];
        $prod["supplier_code"] = $row["supplier_code"];

        foreach($row as $k => $v)
        {
            switch($k)
            {
                case 'column_manufacturer':
                    if($v < 0)
                    {
                        $prod['manufacturer'] = NULL;
                    }
                    else
                    {
                        $prod['manufacturer'] = $data[$row[$k]];
                    }

                    break;

                case 'column_product_code':
                    if($v < 0)
                    {
                        $prod['product_code'] = NULL;
                    }
                    else
                    {
                        $prod['product_code'] = $data[$row[$k]];
                    }

                    break;

                case 'column_product_desc':
                    if($v < 0)
                    {
                        $prod['product_desc'] = NULL;
                    }
                    else
                    {
                        $prod['product_desc'] = $data[$row[$k]];
                    }

                    break;

                case 'column_ean_code':
                    if($v < 0)
                    {
                        $prod['ean_code'] = NULL;
                    }
                    else
                    {
                        $prod['ean_code'] = $data[$row[$k]];
                    }

                    break;

                case 'column_category':
                    if($v < 0)
                    {
                        $prod['category'] = NULL;
                    }
                    else
                    {
                        $prod['category'] = $data[$row[$k]];
                    }

                    break;

                case 'column_subcat1':
                    if($v < 0)
                    {
                        $prod['subcat1'] = NULL;
                    }
                    else
                    {
                        $prod['subcat1'] = $data[$row[$k]];
                    }

                    break;

                case 'column_subcat2':
                    if($v < 0)
                    {
                        $prod['subcat2'] = NULL;
                    }
                    else
                    {
                        $prod['subcat2'] = $data[$row[$k]];
                    }

                    break;

                case 'column_purchase_price':
                    if($v < 0)
                    {
                        $prod['purchase_price'] = NULL;
                    }
                    else
                    {
                        $prod['purchase_price'] = $data[$row[$k]];
                    }

                    break;
            }
        }

        return $prod;
    }

    function insertIntoUnifiedlistsTable($product)
    {
        global $conn;

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
                                '".$product["supplier_file"]."',
                                '".$product["manufacturer"]."',
                                '".$product["supplier_code"]."',
                                '".$product["product_code"]."',
                                '".$product["product_desc"]."',
                                '".$product["ean_code"]."',
                                '".$product["category"]."',
                                '".$product["subcat1"]."',
                                '".$product["subcat2"]."',
                                '".$product["purchase_price"]."'
                            )";

        $conn->query($ins);
    }

    function updateUnifiedlistsTable($product)
    {
        global $conn;

        $upd = "UPDATE unifiedlists
                SET
                    supplier_file = '".$product["supplier_file"]."',
                    manufacturer = '".$product["manufacturer"]."',
                    supplier_code = '".$product["supplier_code"]."',
                    product_code = '".$product["product_code"]."',
                    product_desc = '".$product["product_desc"]."',
                    category = '".$product["category"]."',
                    subcat1 = '".$product["subcat1"]."',
                    subcat2 = '".$product["subcat2"]."',
                    supplier_purchase_price = '".$product["purchase_price"]."'
                WHERE ean_code = '".$product["ean_code"]."';";

        $conn->query($upd);
    }
