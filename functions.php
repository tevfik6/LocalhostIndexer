<?php 
$current_path = "";
$root_folder = getcwd().'/../';

if(isset($_GET['current_path'])){
	$current_path = $_GET['current_path'];

	if(substr($current_path, -2) == ".."){
		$exp_cp = explode("/", $current_path);
		$exp_cp = array_pop($exp_cp);
		$exp_cp = array_pop($exp_cp);
		$current_path = implode("/", $exp_cp);
	}
	elseif( substr($current_path, -1) == "." ){
		$current_path = substr($current_path, 0, -2);
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
		
		$returnValues = array(
			'version'	 => $sugar_version_matches[1], // sugar_version
			'db_version' => $sugar_version_matches[2], // sugar_db_version
			'flavor'	 => $sugar_version_matches[3], // sugar_flavor
			'build'		 => is_numeric($sugar_version_matches[4])?$sugar_version_matches[4]:'NaN', // sugar_build
			'timestamp'	 => $sugar_version_matches[5], // sugar_timestamp
		);
	}
	return $returnValues;
}