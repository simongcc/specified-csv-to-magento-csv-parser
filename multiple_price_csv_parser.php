<?php
/*

The MIT License (MIT)

Copyright (c) 2014-2015 Ng Simon
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

The program objective:
1. skip the empty sku line
2. add group price for the remaing rows automatically with calculation based on Kinki's formula

*/

function output_browser($context, $flag = true) {
	// $flag = ($flag === undefined) ? true : $flag;

	if($flag) {
		echo $context;
	}
}

/*
	Row processing

	skip row with empty sku column
	return row data with sku

	echo is for screen preview
*/
function skip_col_and_row($data, $flag) {
	$output_row = array(); // output

	// skip_column
	$skip_column = array(1,2); // name and brand in price edit

	$num = count($data);
	// $num = 1; // display only first column
	for ($c=0; $c < $num; $c++) {

		// skip row if first column is empty
	    // if($data[0] != ""){
	    if($data[0] != ""){
	    	// var_dump($data[$c]);
	    	// var_dump(count($data[$c]));

	    	// skip all predefined columns in skip_column array
	    	if( !in_array($c, $skip_column, true ) ){
		    	// put column into array
		    	$output_row[] = $data[$c];
		    	output_browser( $data[$c], $flag );

		        // if it is not the last column, output separator
		        if( $c !== $num-1 ) {
		        	output_browser(",", $flag);
		        }
	        	else {
	        		// echo "\n";
	        		output_browser("<br>", $flag);
	        	} // else
	    	}
        } // if
    } // for
	return $output_row;
}

function remap_data($data, $row_no, $flag = true) {
	global $fp;
		
	// if($row_no > 5) return;

	$output_row = array();
	$tmp = $data;
	$num = count($data);
	$limit = 7; //(0 to $limit-1)
	// var_dump($num);
    	// var_dump($data);
    for ($c=0; $c < $num; $c++) {

		// remap 5-8 column data into separate row
	     if( $row_no > 2 ) {
	      $data_line = array(
	      	',,,'.$tmp[5].',2,all',
	      	',,,'.$tmp[6].',3,all',
	      	',,,'.$tmp[7].',4,all',
	      	',,,'.$tmp[8].',5,all'
	      	);

	      $data[6] = 1;
	  	 } else {
	  	 	// additional row header
	  	 	// used row 5 as group heading, without copy and paste again in excel
	  	 	$data[5] = "_group_price_website";
	  	 	$data[6] = "status";
	  	 }
	    
    	// echo "row= ".$row_no." c= ".$c."<br>";
    	// var_dump($data[0]);
        if($data[0] != "" && $c < $limit){
        	// individual row checking, since it is a loop, need to check for exact running $column
        	if($c === 3 && $data[$c] == "") {$data[3] = 999; } // if price is not defined, set to 999
        	if($c === 6 && $data[3] == 999) {$data[6] = 2; } // if price is not defined, set to 999
        	if($c === 5 && $row_no > 2) {$data[5] = "all"; }
        	echo $data[$c];
        	$output_row[] = $data[$c];

            // if it is not the last column, output separator
            // if( $c !== $num-1 ) {
            if( $c !== $limit - 1 ) {
            	echo ",";
            }
        	else {
        		echo "<br>";
        		fputcsv($fp, $output_row);

        		  // put the new row into an array for output
        		if( $row_no > 2 ) {
			      foreach($data_line as $line){
			     	 $line = str_replace("\"", "", $line);
			          $val = explode(",",$line);

			          // output line to browser
			          $total_col = count($val);
			          for($ac=0; $ac<$total_col; $ac++) {
			          	echo $val[$ac];

			          	if( $ac !== $total_col-1 ) {
			          		echo ",";
			          	} else {
			          		echo "<br>";
			          		fputcsv($fp, $val);
			          	}
			          }
			      	} // foreach
		    	 }
        	} // else
        } // if
    } // for
	 
	return $output_row;
}

// write
$source = "price_data";
$output = $source . "_import_prepared_test";
$fp = fopen($output.".csv", 'w');

// read
$row = 1;
if (($handle = fopen($source.".csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
        $num = count($data);

        // write the just read data to file
        // do something for the $data
        if($row > 1){ // skip first row
	        $data = skip_col_and_row($data, false);
	        $data = remap_data($data, $row);
        }
		
		$row++;
    }
    fclose($handle);
    fclose($fp);
}
?>