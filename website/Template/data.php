

<?php
		$target_table = "Player";
		
		if(isset($_POST['submitted']) == 1){
			
			// insert data into table
			$target_table = $_POST['table'];
			
			
			$total = "(";
			$val = 0;
			$full = 0;
			while(isset($_POST['values'.$val]) == 1) {
				$l = $_POST['values'.$val];
				$total = $total.'"'.$l.'", ';
				$val = $val + 1;
				
				if (strlen($l) > 0) {
					$full = $full + 1;	
				}
			}
			
			$q = "SHOW columns FROM $target_table";
			$result = mysqli_query($bbal_dbc, $q);
			$ins_value = "(";
			global $auto;
			
			//print the first row with the names for each column
			if ($result ) {
				while($row = mysqli_fetch_assoc($result)) {
					if ($row['Extra'] != "auto_increment") {
						$ins_value = $ins_value.$row['Field'].", ";
					}
				}
				$ins_value = substr($ins_value, 0, strlen($ins_value) - 2);
				$ins_value = $ins_value.") ";
			}
			
			
			$total = substr($total, 0, strlen($total) - 2);
			$total = $total.");";
			if ($full > 0) {
				$q = "INSERT INTO $target_table $ins_value VALUES $total;";
				$r = mysqli_query($bbal_dbc, $q);
				
				//printing status
				if($r) {
					echo "<p>Item added successfully.</p>";
				} else {
					echo "<p>Item could not be added - ".mysqli_error($bbal_dbc);
					// echo '<p>'.$q.'</p>';
				}	
			}
			
			
			
		}

		if(isset($_POST['foo']) == 1) {
			$q = $_POST['foo'];
			$r = mysqli_query($bbal_dbc, $q);
		}
		echo "<h2>$target_table:</h2>";
		
	?>

<table id = "main" class="table-sort table-sort-search">
	<thead>
		<tr>
	<?php 
		
	// configure the table column names
		$q = "SHOW columns FROM $target_table";
		$result = mysqli_query($bbal_dbc, $q);
		//print the first row with the names for each column
		if ($result ) {
			
			while($row = mysqli_fetch_assoc($result)) {
			    // do stuff
			    $total = "";
				$key = $row['Field'];
				$key = mb_strtoupper($key[0]).mb_substr($key, 1, strlen($key));// make first character capital
				$cap = 1;// First is always caps 1 if last was cap.s
			    for ($i = 0; $i < strlen($key); $i++){
				    $chr = $key[$i];
					
					// capital found
					if ($cap == 0 && mb_strtolower($chr, "UTF-8") != $chr) {
						$total = $total." ";
						$cap = 1;
					} 
					else {
						$cap =0;
					}
					$total = $total.$chr;
					
				}
			    echo '<th class="table-sort">'.$total.'</th>';
			}
		} else {
			echo "<h3>No such table!</h3>";
		}
	?>
	</tr>
		</thead>
	<tbody>

	<?php
		// get primary key for deletion
		$q = 'SHOW COLUMNS FROM '.$target_table.' where `Key` = "PRI"';
		$result = mysqli_query($bbal_dbc, $q);
		$primary_key = "";
		if ($result) { // if there exists a primary key..
			$row = mysqli_fetch_assoc($result);
			$primary_key = $row['Field'];
		}
		
		// inser the data into the table
		$q = "SELECT * FROM $target_table";
		$result = mysqli_query($bbal_dbc, $q);
		if ($result) {
			while ($row = mysqli_fetch_assoc($result)) {
				// param will contain the where clause for the deletion
				$param = "'".$target_table."', "."'".$primary_key."=".$row[$primary_key]."'";
				
				echo '<tr onmousedown="longhold('.$param.', this)">';
				// echo "<script></script>";
				foreach ($row as $stat) {
					echo "<td>".$stat."</td>";
				}
				echo '</tr>';
			}
		}
	?>
	</tbody>
</table>
<script type="text/javascript">
	$(function () {
		$('#main').tablesort();
	});
</script>

<script>



	function longhold(tbl, wc, ob) {
	    var t;
	    var s_start = 100;
	    
		var start = s_start;
		var speedup = 1;
		var delta = -8;
		var original_color = window.getComputedStyle( ob ,null).getPropertyValue('color');
		var color_array = getColorWithAlpha(original_color);
		// if (getRed(original_color) === NaN) {
			// ob.style.backgroundColor = 'rgb(255,255,255)';
		// }
		
		// alert(color_array + " " + getColorCSS(color_array));
		// ob.style.backgroundColor = setRed(original_color, 230);
	    var repeat = function () {
	    	//
	    	if (color_array[3] <= 0) {
	    		var bar = 'DELETE FROM ' + tbl + " WHERE " + wc;
	    		console.log(bar);
	    		ob.innerHTML = "";
	    		$.ajax({
				    type: 'POST',
				    url: 'index.php?page=3',
				    data: { 
				        'foo': bar
				    },
				    success: function(){
				    	console.log(bar);
				    }
				});
	    		ob.onmouseup();
	    		
	    		
	    	} else {
	    	
		        t = setTimeout(repeat, start);
		        start = (start + delta) / speedup;
		        color_array[3] = start / 100; // update alpha
		        ob.style.color = getColorCSS(color_array);
	        }
	        
	        // ob.style.backgroundColor += delta;
	    };
	    
	    ob.onmousedown = function() {
	        repeat();
	    };
	
	    ob.onmouseup = function () {
	        clearTimeout(t);
	        start = s_start;
	        color_array[3] = start / 100;
	        ob.style.color = getColorCSS(color_array);
	    };
	    
	    ob.onmouseout = ob.onmouseup;
	    ob.onmousedown();
	    
	};
	
		// asumes current color alpha  = 1
	function getColorWithAlpha(color) {
		var arr = color.split("(")[1].split(")")[0].split	(",");
		for (var i = 0; i < 3; i++) {
			arr[i] = parseInt(arr[i]);
		}
		arr[3] = 1;//alpha
		return arr;
	};
	
	function getColorCSS(arr) {
		return"rgba(" + arr[0] + ", " + arr[1] + ", " + arr[2] + ", " + arr[3] + ")";
	};	
</script>