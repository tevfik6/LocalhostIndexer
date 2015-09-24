<?php
require_once "breadcrumbs.php";
?>
<div class="button-group-wrapper" data-spy="affix" data-offset-top="66">
	<div class="col-xs-4">
		<div class="input-group">
			<div class="input-group-addon"><span class="glyphicon glyphicon-search" style="margin-right:0px"></span></div>
			<input type="text" class="form-control" id="search" placeholder="Keyword">
		</div>
	</div>
</div>
<div class="list-group main-container" id="files-folders">
	<li class="list-group-item list-group-header">
		<span class="file_folder_name">File/Folder Name</span>
		<div class="file_folder_info pull-right">
			<span class="sugar" v-if="anySugarInstances()">
				<span class="version">Sugar Version</span>
				<span class="flavor">Sugar Edition</span>
				<span class="build">Sugar Build</span>
			</span>
			<span class="file_folder_size">File Size</span>
			<span class="file_folder_perm">Perm </span>
			<span class="file_folder_mtime">Last Modified</span>
		</div>
	</li>
	<a v-repeat="filesfolders" href="{{ is_dir && !has_index_php || name == '..' ? '/?current_path='+relative_path : relative_path | getLink  }}" class="list-group-item">
		<span v-if="name == '..'" class="glyphicon fa fa-level-up fa-lg" aria-hidden="true" style="margin-left:5px;"></span>
		<img  v-if="sugar && name != '..'" class="sugar_logo" src="assets/img/sugar_logo.svg">
		<span v-if="is_dir && name != '..' && !sugar" class="glyphicon fa fa-folder fa-lg" aria-hidden="true"></span>
		<span v-if="!is_dir && !sugar"class="glyphicon fa fa-file-o fa-lg" aria-hidden="true"></span>
		<span class="file_folder_name">{{ name == '..' ? 'Level Up' : name }}</span>
		<span v-if="name != '..' && has_index_php" class="glyphicon fa fa-search s_link" aria-hidden="true" style="top: -1px;margin-left: 5px;"></span> 
		<div class="file_folder_info pull-right">
			<span class="sugar" v-if="sugar">
				<span class="version" data="7060200">7.6.2.0</span>
				<span class="flavor">ENT</span>
				<span class="build">1002</span>
			</span>
			<span class="file_folder_size" data="{{ size.plain }}">{{ is_dir ? '&nbsp;' : size.formated }}</span>
			<span class="file_folder_perm" data="{{ perm.plain }}">{{perm.formated }}</span>
			<span class="file_folder_mtime" data="{{ mtime.plain }}">{{ mtime.formated }}</span>
		</div>
	</a>
</div>
