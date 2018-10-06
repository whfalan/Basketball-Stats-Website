<?php
	include('config/setup.php'); 
?>

<!DOCTYPE html>
<html>
	<head>
		<title> <?php echo $page['title'].' | '.$site_title; ?> </title>
		
		<?php include('config/css.php'); ?>
		<?php include('config/js.php'); ?>
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<?php
				
		
		if ($asthetic == 1) {?>
			<div id = "wrap"><!-- puts the footer at the bottom -->
			
			<?php include(D_TEMPLATE.'/navigation.php'); ?>
				
			<div class = "container">
				
				<h1><?php echo $page['header'];?></h1>
				<p> <?php echo $page ['body']; ?></p>
			</div>
			
			<!-- INCLUDE CORRECT FILE CONTENT-, Home = home.php-->
			<div class = "container">
				<?php 
					$q = "SELECT * FROM pages where id = ".$pageid;
					$r = mysqli_query($dbc, $q);
				
					$nav = mysqli_fetch_assoc($r);
					include('template/'.$nav['title'].'.php');
				?> 
				</div>
			</div>
			
			<?php include (D_TEMPLATE.'/footer.php'); ?>
		<?php	
		} else {?>
			<?php 
				$q = "SELECT * FROM pages where id = ".$pageid;
				$r = mysqli_query($dbc, $q);
			
				$nav = mysqli_fetch_assoc($r);
				include('template/'.$nav['title'].'.php');
			?>
		<?php
		}?>
		
		
	</body>
	
</html> 
