<?php 
$current_path = "";
$root_folder = getcwd().'/../';

if(isset($_GET['current_path'])){
	$current_path = $_GET['current_path'];

	$current_path = preg_replace("/\\/{2,}/", "/", $current_path );
	$current_path = str_replace(array("../", "..", "./"), array("","",""), $current_path );
	$current_path = preg_replace("/\\/{2,}/", "/", $current_path );
	$current_path = trim($current_path, '/');
	if(!is_dir($root_folder.$current_path)){
		$current_path = "";
	}
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

function checkSugarVersionEdition($path){
	$sugar_version_path = $path."/sugar_version.php";
	$returnValues = false;
	if(file_exists($sugar_version_path)){
		$sugar_version_content = file_get_contents($sugar_version_path);
		$re = "/\\\$sugar_version\\s*=\\s*'(.*)';\\n\\\$sugar_db_version\\s*=\\s*'(.*)';\\n\\\$sugar_flavor\\s*=\\s*'(.*)';\\n\\\$sugar_build\\s*=\\s*'(.*)';\\n\\\$sugar_timestamp\\s*=\\s*'(.*)';/m"; 
		preg_match($re, $sugar_version_content, $sugar_version_matches);
		
		if(is_array($sugar_version_matches) && count($sugar_version_matches) == 6){
			$returnValues = array(
				'version'	 => $sugar_version_matches[1], // sugar_version
				'db_version' => $sugar_version_matches[2], // sugar_db_version
				'flavor'	 => $sugar_version_matches[3], // sugar_flavor
				'build'		 => is_numeric($sugar_version_matches[4])?$sugar_version_matches[4]:'NaN', // sugar_build
				'timestamp'	 => $sugar_version_matches[5], // sugar_timestamp
			);
		}
	}
	return $returnValues;
}

function getFilesFolders()
{
	global $root_folder, $current_path;
	$files_folders_iterator = new DirectoryIterator($root_folder.$current_path);
	$files_folders_container = array();
	$date_format = "d-m-Y H:i:s";

	foreach ($files_folders_iterator as $file_folder) {
		if($file_folder->getFilename() != "." && ($current_path != "" || !$file_folder->isDot() ) ){
			$current_file_folder = array(
				'is_dir' 			=> $file_folder->isDir(),
				'is_dot' 			=> $file_folder->isDot(),
				'name' 				=> $file_folder->getFilename(),
				'relative_path' 	=> ($current_path != "" ? $current_path."/" : '').$file_folder->getFilename(),
				'full_path' 		=> $file_folder->getPathname(),
				'perm' 				=> array(
					'plain' 			=> substr(sprintf('%o', $file_folder->getPerms()), -4),
					'formated' 			=> substr(sprintf('%o', $file_folder->getPerms()), -4).' ',
				),
				'size' 				=> array(
					'plain' 			=> $file_folder->isDir() ? 0 : $file_folder->getSize(),
					'formated' 			=> $file_folder->isDir() ? false : formatBytes($file_folder->getSize()),
				),
				'mtime' 			=> array(
					'plain' 			=> $file_folder->getMTime(),
					'formated' 			=> date($date_format, $file_folder->getMTime()),
				), //modified time
				'atime' 			=> array(
					'plain' 			=> $file_folder->getMTime(),
					'formated' 			=> date($date_format, $file_folder->getATime()), 
				), //access time
				'ctime' 			=> array(
					'plain' 			=> $file_folder->getMTime(),
					'formated' 			=> date($date_format, $file_folder->getCTime()), 
				),//inode change time
				'sugar'				=> checkSugarVersionEdition($file_folder->getPathname()),
				'has_index_php'		=> file_exists($file_folder->getPathname()."/index.php"),
			);
			array_push($files_folders_container, $current_file_folder);
		}
	}
	return $files_folders_container;
}
