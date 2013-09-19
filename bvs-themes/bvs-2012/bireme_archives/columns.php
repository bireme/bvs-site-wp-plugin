<?php 
	if ($top_sidebar == true){
	?>
	<div class="top_sidebar">
			<?php dynamic_sidebar( 'top_sidebar' . $current_language ); ?>	
	</div>	
	<?php	
	}
?>
<?php 
	$colors = $settings['colors'];
	$layout = $settings['layout'];
	for($i=1; $i <= $total_columns; $i++) {
		$column_width = $layout[''.$i.''];
		if ($i==1){
			$column_name='first';			
		} elseif($i==2) {
			$column_name='second';					
		} elseif($i==3) {
			$column_name='third';					
		} elseif($i==4) {
			$column_name='fourth';					
		}		
		?>
		<style>
			.column_<?php echo $i;?> .widget {
				background: #<?php echo $colors[''.$column_name.'-background'];?>;
				color: #<?php echo $colors[''.$column_name.'-text'];?>;
			}
			.column_<?php echo $i;?> a {
				color: #<?php echo $colors[''.$column_name.'-link-active'];?>;
			}
			.column_<?php echo $i;?> a:visited {
				color: #<?php echo $colors[''.$column_name.'-link-visited'];?>;
			}
			.column_<?php echo $i;?> h3, .column_<?php echo $i;?> h3 a {
				color: #<?php echo $colors[''.$column_name.'-title-first'];?>;							
			}
			.column_<?php echo $i;?> h3 {
				border-color: #<?php echo $colors[''.$column_name.'-title-first'];?>;	
			}
		</style>
		<div class="column column_<?php echo $i;?>" style="width: <?php echo $column_width; ?>; ">
				<?php dynamic_sidebar( 'column-' . $i . $current_language ); ?>
		</div>
	<?php
	}
?>
<div class="spacer"></div>
<?php 
	if ($footer_sidebar == true){
	?>
	<div class="footer_sidebar">
			<?php dynamic_sidebar( 'footer_sidebar' . $current_language ); ?>	
	</div>	
	<div class="spacer"></div>	
	<?php	
	}
?>
