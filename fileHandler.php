<?php
    set_error_handler('handleError');
    set_exception_handler('handleException');

    /*
     * Fetch all supplier files.
     */
    function fetchAllFiles()
    {
        global $conn;
        
        try
        {
            $res = mysqli_query($conn, "SELECT * FROM supplierlists");
            
            if(mysqli_num_rows($res) == 0)
            {
                throw new Exception('Getting data from supplierlists table failed. <br>', 1);
            }
            else
            {
                throw new Exception('Getting data from supplierlists table successful.<br>');
            }
        } 
        catch(Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
            
            if($ex->getCode() > 0)
                return;
        }

        while($row = mysqli_fetch_assoc($res))
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

        $fileRowNum = 0;
        
        if($row == '')
        {
            try
            {
                $res = mysqli_query($conn, "SELECT * FROM supplierlists WHERE supplier_file = '".$file."';");

                if(mysqli_num_rows($res) == 0)
                {
                    throw new Exception('Getting data from supplierlists table with file '.$file.' failed.<br>', 1);
                }
                else
                {
                    throw new Exception('Getting data from supplierlists table successful.<br>');
                }
            } 
            catch(Exception $ex) 
            {
                echo $ex->getMessage();

                writeLog($ex->getMessage());
    
                if($ex->getCode() > 0)
                    return;
            }

            $row = mysqli_fetch_assoc($res);
        }

        try
        {
            if(!file_exists($row['file_path'].'/'.$file))
            {
                throw new Exception('File '.$file.' not found.<br>', 1);
            }
            else
            {
                throw new Exception('File '.$file.' found.<br>');
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex->getMessage();
            
            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }
        
        fixColumns($row);

        try
        {
            $handle = fopen($row['file_path'].'/'.$file, 'r');
            
            if(!$handle)
            {
                throw new Exception('Could not open file '.$row['file_path'].'/'.$file.'<br>', 1);
            }
            else
            {
                throw new Exception('File open successful.<br>');
            }
        } 
        catch (Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());

            if($ex->getCode() > 0)
                return;
        }
        
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
            $fileRowNum++;
            
            $res = findEAN('unifiedlists', $data[$row['column_ean_code']]);

            $product = collectData($data, $row);

            // Check if the product is already in unifiedlists. If not insert to the table, if found then update the table.
            if(mysqli_num_rows($res) == 0)
            {
                insertIntoUnifiedlistsTable($product, $file, $fileRowNum);
            }
            else
            {
                updateUnifiedlistsTable($product, $file, $fileRowNum);
            }
        }
        
        fclose($handle);
    }

    function findEAN($table, $ean)
    {
        global $conn;

        $sql = "SELECT ean_code FROM ".$table." WHERE ean_code = '".$ean."';";
        $res = mysqli_query($conn, $sql);
        
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
                    transferData($data, $row, 'manufacturer', $v, $k, $prod);

                    break;

                case 'column_product_code':
                    transferData($data, $row, 'product_code', $v, $k, $prod);

                    break;

                case 'column_product_desc':
                    transferData($data, $row, 'product_desc', $v, $k, $prod);

                    break;

                case 'column_ean_code':
                    transferData($data, $row, 'ean_code', $v, $k, $prod);

                    break;

                case 'column_category':
                    transferData($data, $row, 'category', $v, $k, $prod);

                    break;

                case 'column_subcat1':
                    transferData($data, $row, 'subcat1', $v, $k, $prod);

                    break;

                case 'column_subcat2':
                    transferData($data, $row, 'subcat2', $v, $k, $prod);

                    break;

                case 'column_purchase_price':
                    transferData($data, $row, 'purchase_price', $v, $k, $prod);
                    
                    break;
                }
            }
        
        return $prod;
    }
    
    function transferData($data, $row, $columnName, $value, $k, &$prod)
    {
        if($value < 0)
        {
            $prod[$columnName] = NULL;
        }
        else
        {
            $prod[$columnName] = $data[$row[$k]];
        }
    }
    
    function insertIntoUnifiedlistsTable($product, $file, $fileRowNum)
    {
        global $conn;

        $sql = "INSERT INTO unifiedlists
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

        try
        {
            $res = mysqli_query($conn, $sql);
            
            if($res === FALSE)
            {
                throw new Exception('Row '.$fileRowNum.': inserting data into unifiedlists table failed in file '.$file.'.<br> '. mysqli_error($conn).' '.$sql.'<br>', 1);
            }
            else
            {
                throw new Exception('Row '.$fileRowNum.': inserting data into unifiedlists table successful.<br>');
            }
        } 
        catch(Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }

    function updateUnifiedlistsTable($product, $file, $fileRowNum)
    {
        global $conn;

        $sql = "UPDATE unifiedlists
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

        try
        {
            $res = mysqli_query($conn, $sql);
            
            if($res === FALSE)
            {
                throw new Exception('Row '.$fileRowNum.': updating data to unifiedlists table failed in file '.$file.'.<br> '. mysqli_error($conn).' '.$sql.'<br>', 1);
            }
            else
            {
                throw new Exception('Row '.$fileRowNum.': updating data to unifiedlists table successful.<br>');
            }
        } 
        catch(Exception $ex) 
        {
            echo $ex->getMessage();

            writeLog($ex->getMessage());
        }
    }

    /*
     * Handle uncaught errors.
     */
    function handleError($errno, $errstr)
    {
        $txt = "<b>Error:</b> ".$errstr."<br>";
        
        echo $txt;
        
        writeLog($txt);
    }
    
    /*
     * Handle uncaught exceptions.
     */
    function handleException($ex)
    {
        $txt = "<b>Exception:</b> ".$ex."<br>";
        
        echo $txt;

        writeLog($txt);
    }
    
    function writeLog($msg)
    {
        date_default_timezone_set('Europe/Helsinki');

        $h = fopen('errorlog.txt', 'a');

        $time = date("Y-m-d H:i:s");

        $txt = "$time $msg \n";
        
        fwrite($h, $txt);
        
        fclose($h);
    }