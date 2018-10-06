<nav class = "navbar navbar-default" role = "navigation">
	<div class = "container">
		<ul class ="nav navbar-nav">	
			<?php
				
				$q = "SELECT * FROM pages";
				$r = mysqli_query($dbc, $q);
				
				$id = 1;
				while($nav = mysqli_fetch_assoc($r)) {
					if ($id == $pageid) {
						echo '<li class = "active"><a href="?page='.$nav['id'].'">'.$nav['title'].'</a></li>';
					} else {
						echo '<li><a href="?page='.$nav['id'].'">'.$nav['title'].'</a></li>';
					}
					
					$id = $id + 1;
				}
			?>
		
		</ul>
	</div>
</nav><!-- NAV END-->
			
