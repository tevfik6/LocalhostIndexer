

<div class="button-group-wrapper" data-spy="affix" data-offset-top="66">
	<div class="col-xs-6">
		<div class="input-group">
			<div class="input-group-addon"><span class="glyphicon glyphicon-search" style="margin-right:0px"></span></div>
			<input type="text" class="form-control" id="search" v-model="searchKeyword" placeholder="Keyword" tabindex="1">
		</div>
	</div>
</div>
<div class="list-group main-container">
	<li class="list-group-item list-group-header">
		<span class="file_folder_name">
			<a href="#" @click="sortBy('name', $event)" :tabindex="filesfolders.length+2">File/Folder Name <i v-if="sortKeyActive('name')" :class="{'fa-sort-alpha-asc': !reverse, 'fa-sort-alpha-desc': reverse}" class="fa"></i></a>
		</span>
		<div class="file_folder_info pull-right">
			<span class="sugar" v-if="anySugarInstances">
				<span class="version">
					<a href="#" @click="sortBy('sugar_sort_version', $event)" tabindex="{{ filesfolders.length+3 }}">Sugar Version <i v-if="sortKeyActive('sugar_sort_version')" :class="{'fa-sort-numeric-asc': !reverse, 'fa-sort-numeric-desc': reverse}" class="fa"></i></a>
				</span>
				<span class="flavor">Sugar Edition</span>
				<span class="build">Sugar Build</span>
			</span>
			<span class="file_folder_size">
				<a href="#" @click="sortBy('size.plain', $event)" tabindex="{{ filesfolders.length+4 }}">File Size <i v-if="sortKeyActive('size.plain')" :class="{'fa-sort-numeric-asc': !reverse, 'fa-sort-numeric-desc': reverse}" class="fa"></i></a>
			</span>
			<span class="file_folder_perm">
				<a href="#" @click="sortBy('perm.plain', $event)" tabindex="{{ filesfolders.length+5 }}">Perm  <i v-if="sortKeyActive('perm.plain')" :class="{'fa-sort-numeric-asc': !reverse, 'fa-sort-numeric-desc': reverse}" class="fa"></i></a>
			</span>
			<span class="file_folder_mtime">
				<a href="#" @click="sortBy('mtime.plain', $event)" tabindex="{{ filesfolders.length+6 }}">Last Modified  <i v-if="sortKeyActive('mtime.plain')" :class="{'fa-sort-amount-asc': !reverse, 'fa-sort-amount-desc': reverse}" class="fa"></i></a>
			</span>
		</div>
	</li>
	<a v-if="levelUps.length > 0" v-for="levelUp in levelUps" :href="getLink('/?current_path='+levelUp.relative_path)" class="list-group-item" v-cloak :tabindex="filesfolders.length+1">
		<span class="glyphicon fa fa-level-up fa-lg" aria-hidden="true" style="margin-left:5px;"></span>
		<span class="file_folder_name">Level Up</span>
		<div class="file_folder_info pull-right">
			<span class="file_folder_size">&nbsp;</span>
			<span class="file_folder_perm">&nbsp;</span>
			<span class="file_folder_mtime">&nbsp;</span>
		</div>
	</a>
	<!-- <a v-for="(filefolder, index) in filesfolders | filterBy searchKeyword in 'name' 'perm.formated' 'mtime.formated' 'sugar.version' 'sugar.flavor' | orderBy sortKey reverse" href="{{ is_dir && !has_index_php || name == '..' ? '/?current_path='+relative_path : relative_path | getLink  }}" class="list-group-item hide" v-class="{'hide': false}" tabindex="{{ index+1 }}"> -->
	<a v-for="(filefolder, index) in filteredFilesFolders" :href=" filefolder.is_dir && !filefolder.has_index_php || filefolder.name == '..' ? '/?current_path='+filefolder.relative_path : filefolder.relative_path" class="list-group-item" v-cloak :tabindex="index+1">
		<span v-if="filefolder.name == '..'" class="glyphicon fa fa-level-up fa-lg" aria-hidden="true" style="margin-left:5px;"></span>
		<img  v-if="filefolder.sugar && filefolder.name != '..'" class="sugar_logo" src="assets/img/sugar_logo.svg">
		<span v-if="filefolder.is_dir && filefolder.name != '..' && !filefolder.sugar" class="glyphicon fa fa-folder fa-lg" aria-hidden="true"></span>
		<span v-if="!filefolder.is_dir && !filefolder.sugar" class="glyphicon fa fa-file-o fa-lg" aria-hidden="true"></span>
		<span class="file_folder_name">{{ filefolder.name == '..' ? 'Level Up' : filefolder.name }}</span>
		<span v-if="filefolder.name != '..' && filefolder.has_index_php" class="glyphicon fa fa-search s_link browse_folder" title="Browse the folder" aria-hidden="true"></span>
		<span v-if="filefolder.editable && !filefolder.is_dir" class="glyphicon fa fa-pencil-square-o s_link go_to_editor" title="Open in Editor" aria-hidden="true"></span>
		<div class="file_folder_info pull-right">
			<span class="sugar" v-if="filefolder.sugar">
				<span class="version">{{ filefolder.sugar.version }}</span>
				<span class="flavor">{{ filefolder.sugar.flavor }}</span>
				<span class="build">{{ filefolder.sugar.build }}</span>
			</span>
			<span class="file_folder_size" data="{{ filefolder.size.plain }}">{{ filefolder.is_dir ? '&nbsp;' : filefolder.size.formated }}</span>
			<span class="file_folder_perm" data="{{ filefolder.perm.plain }}">{{ filefolder.perm.formated }}</span>
			<span class="file_folder_mtime" data="{{ filefolder.mtime.plain }}">{{ filefolder.mtime.formated }}</span>
		</div>
	</a>
</div>
