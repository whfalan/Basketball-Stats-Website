
<script>
      // $(function () {
// 
        // $('#insertform').on('submit', function (e) {
// 
          // e.preventDefault();
// 
          // $.ajax({
            // type: 'post',
            // url: 'index.php?page=3',
            // data: $('#insertform').serialize(),
            // success: function () {
              	// var iframe = document.getElementById('data_window');
				// // iframe.src += '';
            // }
          // });
// 
        // });
// 
      // });
      
   
$(document).ready(function(){
	var table_names = [
		['Team', 'Team Name', 'Team Location'],
		['Coach', "First Name", "Last Name", "Number of Wins", "Number of Loses", "Team"],
		['Player', 'First Name', 'Last Name', 'Height', 'Age', 'Team'],
		['Game', "Type", "Year", "Home Team", "Away Team"],
		['Contract', 'Season Signed', 'Contract Length', 'Salary', 'Team', 'PlayerID', 'Active?'],
		['Stadium', 'Season Built', 'Capacity', 'City', 'Team'],
		['GameStats', 'Game Number', 'Player ID', 'Minutes', 'Points', 'fg', 'fga', 'Rebounds', 'Assists', 'Steals', 'Blocks', 'Teams'],
		['Season', 'mvp', 'Champion']];
	
	
	$( "#target" ).keypress(function( e ) {
		
		
		if(window.event) { // IE                    
	      keynum = e.keyCode;
	    } else if(e.which){ // Netscape/Firefox/Opera                   
	      keynum = e.which;
	    }
	    
	    curVal = String.fromCharCode(keynum);
			
    	var val = this.value + curVal;
			
    	console.log("You pressed a key inside the input field"+val);
    	for (var i = 0; i < table_names.length; i++) {
    		if (table_names[i][0] === val) {
    			console.log("match!");
    			var d = document.getElementById('edit');
    			var total = "<hr><h3>Insert:</h3>";
    			for (var j = 1; j < table_names[i].length;j++) {
    				var val = "'" + "values" + (j-1) + "'";
    				total += '<label for = ' + val + '>' + table_names[i][j] + '</label>'
    				total += '<input class = "form-control" type="text" name=' + val + 'id=' + val + '>'; 	
    			}
    			d.innerHTML = total;
    			break;
    		}
    	}
	});
});
// $("#options").load(function(){
	// var table_names = [
		// ['Team', 'Team Name', 'Team Location'],
		// ['Coach', "First Name", "Last Name", "Number of Wins", "Number of Loses", "Team"],
		// ['Player', 'First Name', 'Last Name', 'Height', 'Age', 'Player ID', 'Team'],
		// ['Game', "Type", "Game Number", "Year", "Home Team", "Away Team"],
		// ['Contract', 'Season', 'Length', 'Salary', 'Team', 'PlayerID', 'Active?'],
		// ['Stadium', 'Season Built', 'Capacity', 'City', 'Team'],
		// ['GameStats', 'Game Number', 'Player ID', 'Minutes', 'Points', 'fg', 'fga', 'Rebounds', 'Assists', 'Steals', 'Blocks', 'Teams'],
		// ['Season', 'mvp', 'Champion']];
	// var options = "";
	// for (var i = 0; i < table_names.length; i++) {
		// if (i == table_names.length - 1) {
			// options += table_names[i][0] + ".";
		// }
		// else {
			// options += table_names[i][0] + ", ";
		// }
	// }
// 	
	// document.getElementById("#options").innerHTML = options;
// });

</script>


<h4 id="options"></h4>

<form id="insertform" action = "index.php?page=3" method="post">
	<div class = "form-group">
		<label for = "table">Table</label>
		<input id="target" class = "form-control" type="text" name="table" id="table" placeholder="Player/Team/Coach/Game/GameStats/Season/Stadium/Contract">
	</div>
	<div class = "form-group">
		
		<div id = "edit">
			
		</div>
	</div>
	<button id="submit" type="submit" class="btn btn-default">Submit</button>
	<input type="hidden" name="submitted" value="1">
</form>

<!-- <iframe id="data_window" src="index.php?page=3&asthetic=0" height="200" width="1000" frameBorder="0"></iframe> -->