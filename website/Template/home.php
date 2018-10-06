<h3>BBALL Facts:</h3>

<ul>
	<li><h4>Find out the roster for:</h4>
		
		<form method = "post" action = "index.php">
			<div class = "form-group">
				<label for = "table">Team Name:</label>
				<input class = "form-control" type="text" name="team_name" placeholder="Emeralds">
			</div>
			<button type="submit" class="btn btn-default">Find Out</button>
			<input type="hidden" name="submitted" value="1">
		</form>
	</li>
	<?php
	
		if(isset($_POST['submitted']) == 1) {
			if(isset($_POST['team_name']) == 1) {
				$tn = $_POST['team_name'];
				// call procedure with this
				$q = "CALL get_roster('$tn');";
				$result = mysqli_query($bbal_dbc, $q);
				$times = 0;
				$total = '<p>';
				if ($result) {
					while($row = mysqli_fetch_assoc($result)) {
						$total = $total.$row['firstName']." ".$row['lastName'].", ";
						$times++;
					}
					$total = substr($total, 0, strlen($total) - 2).'.</p>';
				}
				//print the first row with the names for each column
				if ($times == 0) {
					echo "<p>No players on this team.</p>";
				} else {
					echo $total;
				}
				
			}
		}
	?>
	
	
	
	
	<li><h4>Find out how many points were scored by the away team for game#:</h4>
		<form method = "post" action = "index.php">
			<div class = "form-group">
				<label for = "table">Game Number:</label>
				<input class = "form-control" type="text" name="game_number" placeholder="1 (Game ID)">
			</div>
			<button type="submit" class="btn btn-default">Find Out</button>
			<input type="hidden" name="submitted" value="1">
		</form>
	</li>
	<?php
	
		if(isset($_POST['submitted']) == 1) {
			
			if(isset($_POST['game_number']) == 1) {
				$tn = $_POST['game_number'];
				$tn2 = $_POST['game_number'];
				
				$q2 = "CALL sum_points_team('$tn', 'home');";
				$result2 = mysqli_query($bbal_dbc, $q2);
				$total = "";
				//print the first row with the names for each column
				if ($result2) {
					while($row2 = mysqli_fetch_assoc($result2)) {
						$total = 'Away had a total score of - '.$row2['Points'];
					}
				}
				
				
				echo $total;
			}
		}
	?>
	
	
	
	<li><h4>Find out which players are free:</h4>
		
		<form method = "post" action = "index.php">
			<div style = "display:none" class = "form-group">
				<input class = "form-control" type="text" name="free_agent" placeholder="n/a">
			</div>
			<button type="submit" class="btn btn-default">Find Out</button>
			<input type="hidden" name="submitted" value="1">
		</form>
	</li>
	<?php
	
		if(isset($_POST['submitted']) == 1) {
			if(isset($_POST['free_agent']) == 1) {
				// call procedure with this
				$q = "CALL get_free_agents();";
				$result = mysqli_query($bbal_dbc, $q);
				//print the first row with the names for each column
				$total = '<p>';
				$times = 0;
				while($row = mysqli_fetch_assoc($result)) {
					$total = $total.$row['firstName']." ".$row['lastName'].", ";
					$times = $times + 1;
				}
				$total = substr($total, 0, strlen($total) - 2).'.</p>';
				if ($times == 0) {
					echo "<p>None are free at the moment.</p>";
				} else {
					echo $total;
				}
			}
		}
	?>
	
	
	<li><h4>Find the game statistics:</h4>
		
		<form method = "post" action = "index.php">
			<div class = "form-group">
				<label for = "table">Game Number:</label>
				<input class = "form-control" type="text" name="game_num_gamestats" placeholder="1 (game id)">
			</div>
			<button type="submit" class="btn btn-default">Find Out</button>
			<input type="hidden" name="submitted" value="1">
		</form>
	</li>
	<?php
	
		if(isset($_POST['submitted']) == 1) {
			if(isset($_POST['game_num_gamestats']) == 1) {
				$tn = $_POST['game_num_gamestats'];
				// call procedure with this
				$q = "CALL get_per_game_stats($tn);";
				$result = mysqli_query($bbal_dbc, $q);
				//print the first row with the names for each column
				$times = 0;
				$total = "";
				if ($result) {
					while($row = mysqli_fetch_assoc($result)) {
						$total = $total."<p>Name: ".$row['firstName']." ".$row['lastName'].", Time: ".$row['time'].", Score: ".$row['score'].
						", fg: ".$row['fg'].", fga: ".$row['fga'].", rebounds: ".$row['rebounds'].", assists: ".$row['assists'].", steals: ".$row['steals'].
						", blocks: ".$row['blocks'].", games_played: ".$row['games_played']."</p>";
						$times = $times + 1;
					}
				}
				
				if ($times == 0) {
					echo "<p>No game stats for that game.</p>";
				} else {
					echo $total;
				}
			}
		}
	?>
	
	
	
</ul>