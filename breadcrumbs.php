<ol class="breadcrumb">
	<li><a href="/"><?php echo $_SERVER['SERVER_NAME']; ?></a></li>
	<?php 
	$breadcrumb_path = "";
	if( $current_path != ""){
		$each_folders = explode("/", $current_path);
		if( is_array($each_folders) && count($each_folders) > 0){
			foreach ($each_folders as $each_folder) {
				echo'<li><a href="/?current_path='.$breadcrumb_path.$each_folder.'">'.$each_folder.'</a></li>';
				$breadcrumb_path .= $each_folder."/";
			}
		}
		else{
			echo '<li></li>';
		}
	}
	   
	?>
</ol>