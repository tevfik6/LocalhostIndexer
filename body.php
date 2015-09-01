<?php 
require_once "breadcrumbs.php";
?>

<div class="list-group">


<?php


	$file_folders = new DirectoryIterator($root_folder.$current_path);
	$file_folder_container = '';
	$any_sugar_folder = false;
	foreach ($file_folders as $file_folder) {

		$is_dir = $file_folder->isDir();
		$file_folder_name = $file_folder->getFilename();

		$relative_path = ($current_path != "" ? $current_path."/" : '').$file_folder_name;
		
		$full_path = $file_folder->getPathname();

		//checking is it folder or not based on that pick the icons
		$icon = '<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ';
		
		if(is_file($file_folder_name)){
			$icon = '<span class="glyphicon glyphicon-file" aria-hidden="true"></span> ';
		}

		//checking if index.php or index.html exist in the following path
		$link = $relative_path;

		if(!$file_folder->isDot() && $is_dir && !file_exists($full_path."/index.php") ){
			$link = "/?current_path=".$link;
		}

		$file_folder_perm = substr(sprintf('%o', $file_folder->getPerms()), -4).' ';
		$file_folder_size = $file_folder->isDir() ? '&nbsp;' : formatBytes($file_folder->getSize());
		$file_folder_mtime = $file_folder->getMTime(); //modified time
		// $file_folder_atime = $file_folder->getATime(); //access time
		// $file_folder_ctime = $file_folder->getCTime(); //inode change time 
		


		$file_folder_info = '';

		$sugar_version_edition = '';
		if($sugar = checkSugarVersionEdition($full_path)){
			//format version to sortable form
			$version_sort_values = explode(".",$sugar['version']);
			foreach ($version_sort_values as $key => $value) {
				if($value <= 9 and $key != 0) $version_sort_values[$key] = "0".$value;
			}
			$version_sort_value = implode("", $version_sort_values);

			$sugar_version_edition = '<span class="sugar"><span class="version" data="'.$version_sort_value.'">'.$sugar['version'].'</span><span class="flavor">'.$sugar['flavor'].'</span><span class="build">'.$sugar['build'].'</span></span>';
			$any_sugar_folder = true;
		}

		$file_folder_info .= '<span class="file_folder_size" data="'.$file_folder->getSize().'">'.$file_folder_size.'</span>';
		$file_folder_info .= '<span class="file_folder_perm">'.$file_folder_perm.'</span>';
		$file_folder_info .= '<span class="file_folder_mtime" data="'.$file_folder->getMTime().'">'.date("d-m-Y H:i:s",$file_folder_mtime).'</span>';
		// $file_folder_info .= '<span class="file_folder_atime">'.date("d-m-Y H:i:s",$file_folder_atime).'</span>';
		// $file_folder_info .= '<span class="file_folder_ctime">'.date("d-m-Y H:i:s",$file_folder_ctime).'</span>';

		$file_folder_info_wrapper = '<div class="file_folder_info pull-right">'.$sugar_version_edition.$file_folder_info.'</div>';
		if($file_folder_name != "." && ($current_path != "" || !$file_folder->isDot() ) ){
			$file_folder_container .= '
			<a href="'.$link.'" class="list-group-item">'.$icon.$file_folder_name.$file_folder_info_wrapper.'</a>';
		}
	}

	$header = '
	<li class="list-group-item list-group-header">File/Folder Name<div class="file_folder_info pull-right">'.($any_sugar_folder?'<span class="sugar"> <span class="version">Sugar Version</span><span class="flavor">Sugar Edition</span><span class="build">Sugar Build</span> </span>':'').'<span class="file_folder_size">File Size</span><span class="file_folder_perm">Perm </span><span class="file_folder_mtime">Last Modified</span> </div></li>';

	echo $header.$file_folder_container;
?>
</div>
