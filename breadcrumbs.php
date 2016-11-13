<ol class="breadcrumb">
	<li><a href="/"><?php echo $_SERVER['SERVER_NAME']; ?></a></li>
	<?php 
	$breadcrumb_path = "";
	if( $current_path != ""){
		$fileFolder = new SplFileInfo($root_folder . $current_path);
		$editor_params = "";
		if( $fileFolder->isFile() ){
			if( checkEditable($fileFolder->getExtension()) ){
				$editor_params = "&editor=true";
			}
		}
		$each_folders = explode("/", $current_path);
		if( is_array($each_folders) && count($each_folders) > 0){
			$breadcrumb_index = 0;
			foreach ($each_folders as $each_folder) {
				$breadcrumb_url = $breadcrumb_path.$each_folder;
				if(++$breadcrumb_index == count($each_folders)){
					$breadcrumb_url.=$editor_params;
				}
				echo'<li><a href="/?current_path='.$breadcrumb_url.'">'.$each_folder.'</a></li>';
				$breadcrumb_path .= $each_folder."/";

			}
		}
		else{
			echo '<li></li>';
		}
	}
	   
	?>
</ol>