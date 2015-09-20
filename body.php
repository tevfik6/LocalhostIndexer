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
	<div class="col-xs-4 col-xs-offset-4 text-right">
		<strong>Order by:</strong>
		<div class="btn-group btn-group-sm" data-toggle="buttons">
			<label class="btn btn-default active">
				<input type="radio" name="order_by" id="option_name" value="name" checked> Name <span class="glyphicon glyphicon-sort-by-attributes" order-data="asc"></span><span class="glyphicon glyphicon-sort-by-attributes-alt hide" order-data="desc"></span>
			</label>
			<label class="btn btn-default sugar-btn">
				<input type="radio" name="order_by" id="option_sugar_versions" value="sugar_versions"> Sugar Versions <span class="glyphicon glyphicon-sort-by-attributes" order-data="asc"></span><span class="glyphicon glyphicon-sort-by-attributes-alt hide" order-data="desc"></span>
			</label>
			<label class="btn btn-default">
				<input type="radio" name="order_by" id="option_last_modified" value="last_modified"> Last Modified <span class="glyphicon glyphicon-sort-by-attributes"  order-data="asc"></span><span class="glyphicon glyphicon-sort-by-attributes-alt hide" order-data="desc"></span>
			</label>
		</div>
	</div>
</div>
<script>
	var list_group_content;
	$(function(){

		function itemsToObject($items_object){
			var result = []; 

			$.each($items_object, function (index, item) {
				$item = $(item);
				var itemObj = {
					file_name: ($item.find(".file_folder_name").html() == "Level Up"?'..':$item.find(".file_folder_name").html()),
					perm: $item.find(".file_folder_perm").html(),
					size: $item.find(".file_folder_size").attr('data'),
					mtime: $item.find(".file_folder_mtime").attr('data'),
					obj: $item,
					html: $item.html(),
				};

				if($item.find(".sugar").length > 0){
					var $sugar = $item.find(".sugar");
					itemObj.sugar_version = $sugar.find(".version").attr("data");
					itemObj.sugar_flavor = $sugar.find(".flavor").html();
					itemObj.sugar_build = $sugar.find(".build").html();
					
					itemObj.sugar = {};
					itemObj.sugar.version = $sugar.find(".version").html();
					itemObj.sugar.flavor = $sugar.find(".flavor").html();
					itemObj.sugar.build = $sugar.find(".build").html();
				}
				result.push(itemObj);
			});
			return $.extend(true, [], result);
		}
		
		var _list_group_object = itemsToObject($(".list-group").find(".list-group-item").not('.list-group-header'));
		// console.log(_list_group_object);
		

		//sorting functions
		function sort_by_date(a, b){
			var aRep = a.mtime;
			var bRep = b.mtime;
			if (aRep > bRep) return 1;
			if (aRep < bRep) return -1;
			return 0;
		}

		function sort_by_sugar_version(a, b){

			// console.log("%cDebugger:", "color:#FF6161;", a);
			// console.log("%cDebugger:", "color:#BBABE8;", b);
			var aRep,
				bRep;
			if( typeof a.sugar_version === 'undefined' ) aRep = 0;
			else aRep = parseInt(a.sugar_version);

			if( typeof b.sugar_version === 'undefined' ) bRep = 0;
			else bRep = parseInt(b.sugar_version);

			// console.log("%cDebugger:", "color:#E8B24D;", aRep, bRep);
			// console.log("\n");

			if (aRep > bRep) return 1;
			if (aRep < bRep) return -1;
			return 0;
		}

		function sort_by_found_score(a, b){
			var aRep = a.found_score;
			var bRep = b.found_score;
			if (aRep > bRep) return 1;
			if (aRep < bRep) return -1;
			return 0;
		}

		function sort_by_file_name(a, b){
			var aRep = a.file_name.toLowerCase();
			var bRep = b.file_name.toLowerCase();
			if (aRep > bRep) return 1;
			if (aRep < bRep) return -1;
			return 0;
		}

		//sorting array
		function sort_array(arr, sortFunction){
			// var sorted_array = arr.slice();
			var sorted_array = $.extend(true, [], arr);
			sortFunction = typeof sortFunction !== 'undefined' && sorted_array.sort(sortFunction);
			return sorted_array;
		}


		//Printing the objects
		function print_list_group (given_print_object, asc_desc){
			var print_object = $.extend(true, [], given_print_object);

			$(".list-group").find(".list-group-item:not(.list-group-header)").remove();
			// console.log("%cprint_list_group: "+asc_desc, "color:red");
			
			if(asc_desc == "desc") print_object.reverse();
			
			$.each(print_object, function (index, item) {
				$(".list-group .list-group-item").last().after(item.obj);
			});
		}



		//Sorting and printing the object
		function sort_and_print(list_group_objects, order_by, asc_desc){
			var sorted_list_group_object = $.extend(true, [], list_group_objects);
			// console.log("%csort_and_print: "+order_by+" "+asc_desc, "color:red");
			switch(order_by){
				case 'sugar_versions':
					sorted_list_group_object = sort_array(sorted_list_group_object, sort_by_sugar_version);
					// sort.key = 'sugar_versions';
				break;

				case 'last_modified':
					// sort.key = 'last_modified';
					sorted_list_group_object = sort_array(sorted_list_group_object, sort_by_date);
				break;

				case 'found_score':
					// sort.key = 'found_score';
					sorted_list_group_object = sort_array(sorted_list_group_object, sort_by_found_score);
				break;

				case 'name':
				default:
					// sort.key = 'name';
					sorted_list_group_object = sort_array(sorted_list_group_object, sort_by_file_name);
				break;
			}
			print_list_group(sorted_list_group_object, asc_desc);
		}


		//switchin the button states
		function swicth_btns_state(btnSelector, asc_desc_deactive){
			var $btn = $(btnSelector);
			switch(asc_desc_deactive){
				case 'asc':
					$btn.find(".glyphicon-sort-by-attributes").removeClass("hide");
					$btn.find(".glyphicon-sort-by-attributes-alt").addClass("hide");
					sort.order = "asc";
				break;
		
				case 'desc':
					$btn.find(".glyphicon-sort-by-attributes-alt").removeClass("hide");
					$btn.find(".glyphicon-sort-by-attributes").addClass("hide");
					sort.order = "desc";
				break;
		
				case 'deactive':
				default:
					$btn.removeClass("active");
				break;
			}
			// console.log("sort from swicth_btns_state:", sort);

		}


		//sorting values by default
		var sort = {
			order:"asc",
			key: $('.button-group-wrapper .btn-group .active input').val()//"name"
		};


		// order_by buttons click event
		$('.button-group-wrapper .btn').on('click', function(){
			// console.log(sort.key, $(this).find("input").val(), (sort.key == $(this).find("input").val()))
			
			if(sort.key == $(this).find("input").val()){ 
				swicth_btns_state($(this), $(this).find(".glyphicon.hide").attr("order-data"));
				// swicth_btns_state($(this), (sort.order == "asc" ? 'desc' : 'asc'));
				// sort.order = (sort.order == "asc" ? 'desc' : 'asc');
			}
			else {
				swicth_btns_state($(this), "asc");
			}
			sort.key = $(this).find("input").val();
			// console.log("sort from click:", sort);

			var active_files_folder = itemsToObject($(".list-group").find(".list-group-item").not('.list-group-header'));
			// console.log("%cDebugger:", "color:green;", active_files_folder);

			sort_and_print(active_files_folder, sort.key, sort.order);
		});


		//search input box key up event
		$("#search").on("keyup", function(event){
			var keywords = $(this).val();

			if((event.keyCode == 8 || event.keyCode == 46) && $("#option_name").parents(".btn").hasClass("active"))
				return false;

			// console.log("%c#search Keypressed:", "color:orange;", keywords);
			// console.log("%c#sort:", "color:blue;", sort);
			
			// sort.key = 'found_score';
			// sort.order = 'desc';

			if($.trim(keywords) == "" || event.keyCode == 8 || event.keyCode == 46) {
				var files_folders = $.extend(true, [], _list_group_object);
				// console.log("%c_list_group_object", "color:red;");
			}
			else{
				var files_folders = itemsToObject($(".list-group").find(".list-group-item").not('.list-group-header'));
				// console.log("%cfiles_folders", "color:orange;");
			}
			files_folders = search_keyword(files_folders, keywords);

			print_list_group(files_folders);

			if( $.trim(keywords) == ""  && (event.keyCode == 8 || event.keyCode == 46) && !$("#option_name").parents(".btn").hasClass("active")){
				swicth_btns_state($("#option_name").parents(".btn"), "desc");
				$("#option_name").parents(".btn").trigger('click');
			}
			else{
				swicth_btns_state('.button-group-wrapper .btn-group .active', 'deactive');
			}
		});


	
		//search keyword function
		function search_keyword (given_search_object, keywords) {
			var search_object = $.extend(true, [], given_search_object);
			
			var splited_keywords = keywords.split(' ');
			$.each(splited_keywords, function (i, keyword) {
				var re = new RegExp(keyword, 'gi');
				$.each(search_object, function (index, item) {
					
					// console.log("keyword", keyword, "re_file_name:", re_file_name, "re_sugar_version:", re_sugar_version, "re_sugar_flavor:", re_sugar_flavor, item);
					var re_file_name = item.file_name.match(re);
					var re_sugar_version = (typeof item.sugar !== 'undefined' ? item.sugar.version.match(re) : false);
					var re_sugar_flavor = (typeof item.sugar !== 'undefined' ? item.sugar.flavor.match(re) : false);
					
					if(typeof search_object[index].found_score === 'undefined'){
						search_object[index].found_score = 0;
					}

					if(re_file_name || re_sugar_version || re_sugar_flavor){
						// console.log("%c#FOUND:", "color:green;", "keyword", keyword, "re_file_name:", re_file_name, "re_sugar_version:", re_sugar_version, "re_sugar_flavor:", re_sugar_flavor, item);
						if(re_file_name)
							search_object[index].found_score += 5;
						if(re_sugar_version)
							search_object[index].found_score += 3;
						if(re_sugar_flavor)
							search_object[index].found_score++;
					}
				});
			});

			var result_object = [];
			$.each(search_object, function (index, item) {
				if(item.found_score != 0) { 
					result_object.push(item);
				}
			});
			// console.log("%cSearch Results","color:4584BF;", [result_object]);
			return result_object;
		}

		$(".s_link").on('click', function (event) {
			$(this).parents("a").attr('href', $(this).attr("data-href"));
		});

		//affix 
		$('.button-group-wrapper').on('affix.bs.affix', function () {
			$('.main-container').css("margin-top", '110px');
			$(".list-group-header").addClass("affix");
		}).on('affix-top.bs.affix', function () {
			$(".main-container").removeAttr("style");
			$(".list-group-header").removeClass("affix");
		});

		//if there is something in the search box remember it
		if($.trim($("#search").val()) != ""){
			$("#search").trigger("keyup");
		}

		$("#search").trigger("focus");
	});
</script>
<div class="list-group main-container">


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
		$icon = '<span class="glyphicon fa fa-folder fa-lg" aria-hidden="true"></span> ';
		$s_icon = '<span class="glyphicon fa fa-search" aria-hidden="true" style="top: -1px;margin-left: 5px;"></span> ';
		
		if(!$file_folder->isDir()){
			$icon = '<span class="glyphicon fa fa-file-o fa-lg" aria-hidden="true"></span> ';
		}

		//checking if index.php or index.html exist in the following path
		$link = $relative_path;
		$s_link = false;
		if($file_folder_name == ".."){
			$exp_cp = explode("/", $relative_path);
			array_pop($exp_cp);
			array_pop($exp_cp);
			$link = "/?current_path=".implode("/", $exp_cp);
			$icon = '<span class="glyphicon fa fa-level-up fa-lg" aria-hidden="true" style="margin-left:5px;"></span> ';
		}	
		else if(!$file_folder->isDot() && $is_dir && !file_exists($full_path."/index.php") ){
			$link = "/?current_path=".$link;
		}
		else if(!$file_folder->isDot() && $file_folder->isDir()){
			$s_icon = '<span class="glyphicon fa fa-search" aria-hidden="true" style="top: -1px;margin-left: 5px;"></span> ';
			$s_link = '<span class="s_link" style="display:inline-block;" data-href="/?current_path='.$link.'">'.$s_icon.'</span>';
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
			
			if($file_folder_name != "..")
				$icon = '<img class="sugar_logo" src="assets/img/sugar_logo.svg"> ';
		}

		$file_folder_info .= '<span class="file_folder_size" data="'.$file_folder->getSize().'">'.$file_folder_size.'</span>';
		$file_folder_info .= '<span class="file_folder_perm">'.$file_folder_perm.'</span>';
		$file_folder_info .= '<span class="file_folder_mtime" data="'.$file_folder->getMTime().'">'.date("d-m-Y H:i:s",$file_folder_mtime).'</span>';
		// $file_folder_info .= '<span class="file_folder_atime">'.date("d-m-Y H:i:s",$file_folder_atime).'</span>';
		// $file_folder_info .= '<span class="file_folder_ctime">'.date("d-m-Y H:i:s",$file_folder_ctime).'</span>';

		$file_folder_info_wrapper = '<div class="file_folder_info pull-right">'.$sugar_version_edition.$file_folder_info.'</div>';
		if($file_folder_name != "." && ($current_path != "" || !$file_folder->isDot() ) ){
			$file_folder_container .= '
			<a href="'.$link.'" class="list-group-item">'.$icon.'<span class="file_folder_name">'.($file_folder_name == ".."?'Level Up':$file_folder_name).($s_link?:'').'</span>'.$file_folder_info_wrapper.'</a>';
		}
	}

	$header = '
	<li class="list-group-item list-group-header">File/Folder Name<div class="file_folder_info pull-right">'.($any_sugar_folder?'<span class="sugar"> <span class="version">Sugar Version</span><span class="flavor">Sugar Edition</span><span class="build">Sugar Build</span> </span>':'').'<span class="file_folder_size">File Size</span><span class="file_folder_perm">Perm </span><span class="file_folder_mtime">Last Modified</span> </div></li>';

	echo $header.$file_folder_container;

	if(!$any_sugar_folder)
		echo '<script> $(function(){ $(".sugar-btn").addClass("hide"); });</script>';
	
?>
</div>
