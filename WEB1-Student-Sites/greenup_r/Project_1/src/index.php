<!DOCTYPE html>
<html>
	<head>
		<title>Biblical Names</title>
		<!--Sorry Ben, this is cheating a bit.-->
		<style>
			body {
				font-size: 1.25vw;
			}
			
			table, th, td {
				border: 1px solid black;
				border-collapse: collapse;
				padding: 5px;
			}
			
			th, td {
				width: 11.45vw;
			}
			
			.search_option {
				border: 1px solid black;
				padding: 5px;
				display: flex;
				float: left;
				width: 11.3vw;
				height: 3vw;
				align-items: center;
			}
		</style>
		
		<!--This is cheating a lot-->
		<script>
			
			// returns the index of the table to search
			// if none of the options are selected, return -1
			function searchType(){
				// grab the search type elements
				var search_type_buttons = document.getElementsByName("search_type");
				
				// run through all of them, finding out which one is checked
				for (var i = 0; i < search_type_buttons.length; i++){
					if (search_type_buttons[i].checked == true){
						return(i);
					}
				}
			}
			
			function sortTable() {
				console.log("sorting");
				
				//borrowed code from https://www.w3schools.com/howto/howto_js_sort_table.asp
				// only change is where it says [here]. There, I changed from searching the first column, to seaching whichever column the user wants
				var table, rows, switching, i, x, y, shouldSwitch;
				var column_index = searchType();
				//if nothing is selected, search english name
				if (column_index == -1){
					column_index = 0;
				}
				
				table = document.getElementById("table");
				switching = true;
			  	/* Make a loop that will continue until
			  	no switching has been done: */
			  	while (switching) {
					// Start by saying: no switching is done:
					switching = false;
					rows = table.getElementsByTagName("tr");
					/* Loop through all table rows (except the
					first, which contains table headers): */
					for (i = 1; i < (rows.length - 1); i++) {
				  		// Start by saying there should be no switching:
				  		shouldSwitch = false;
				  		/* Get the two elements you want to compare,
				  		one from current row and one from the next: */
						x = rows[i].getElementsByTagName("TD")[column_index];		//[here]
						y = rows[i + 1].getElementsByTagName("TD")[column_index];	//[here]
						// Check if the two rows should switch place:
						if (x.innerHTML == "N/A" && y.innerHTML != "N/A") {
							shouldSwitch = true;
							break;
						}
						
						if ((x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) && y.innerHTML != "N/A") {
							// If so, mark as a switch and break the loop:
							shouldSwitch = true;
							
							break;
				  		}
						
					}
				  	if (shouldSwitch) {
						/* If a switch has been marked, make the switch
			  			and mark that a switch has been done: */
						rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
						switching = true;
					}
				}
			}
			
			// Searches the table and hides any non-matching results
			function search() {
				console.log("start");
				// Declare reused vars
				var td, txtValue;
				
				//find search term
				var input = document.getElementById("search_term");
				// make case insensitive
				var filter = input.value.toUpperCase();
				// grab table of names
				var table = document.getElementById("table");
				// and the rows
				var tr = table.getElementsByTagName("tr");
				// holds the column that we will be searching
				var search_column_index = searchType();

				// Loop through all table rows, and hide those who don't match the search query
				for (var i = 0; i < tr.length; i++) {
					
					td = tr[i].getElementsByTagName("td")[search_column_index];
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
	</head>
	<body>

		<h1>Biblical Names</h1>
		<h3>Currently has all names up to Genesis 10</h3>
		
		<hr>
		
		<div id="search_type">
			<p>What would you like to search?</p>
			<div class="search_option">
				<input type="radio" name="search_type" id="english" value="english" onclick="sortTable()">
				<label for="english">English Name</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="hebrew" value="hebrew" onclick="sortTable()">
				<label for="english">Hebrew Name</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="hebrew_trans" value="hebrew_trans" onclick="sortTable()">
				<label for="english">Hebrew Transliterated Name</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="greek" value="greek" onclick="sortTable()">
				<label for="english">Greek Name</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="greek_trans" value="greek_trans" onclick="sortTable()">
				<label for="english">Greek Transliterated Name</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="other" value="other" onclick="sortTable()">
				<label for="english">Other Language?</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="other_name" value="other_name" onclick="sortTable()">
				<label for="english">Name In Other Language</label>
			</div>
			<div class="search_option">
				<input type="radio" name="search_type" id="other_trans" value="other_trans" onclick="sortTable()">
				<label for="english">Name In Other Language Transliterated</label>
			</div>
		</div>
		
		<br>
		<br>
		<br>
		
		<input type="text" id="search_term" onkeyup="search()" placeholder="Seach for a name">
		
		<hr>
		
		<div id=table>
			<table>
				<tr>
					<th>English Name</th>
					<th>Hebrew Name</th>
					<th>(Transliterated)</th>
					<th>Greek Name</th>
					<th>(Transliterated)</th>
					<th>Other Language?</th>
					<th>Name In Other Language</th>
					<th>(Transliterated)</th>
				</tr>
				<?php
					$names = fopen("names.csv", "r") or die("Unable to open file!");
					//skips the first row (header row)
					fgets($names);
				

					while(!feof($names)) {
						// initialize the row
						$tr = "<tr>";

						// get the next line
						$singleLine = fgets($names);
						// explode into an array
						$data = explode(",", $singleLine);
						// loop through array, adding each element to row as data
						foreach($data as $i){
							$tr = $tr . "<td>" . $i . "</td>";
						}

						// place row into html
						echo $tr . "</tr>";
					}
					fclose($names);
				?>
			</table>	
		</div>

	</body>
</html>
