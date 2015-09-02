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
	<div class="col-xs-4 col-xs-offset-4">
		<strong>Order by:</strong>
		<div class="btn-group btn-group-sm" data-toggle="buttons">
			<label class="btn btn-default active">
				<input type="radio" name="order_by" id="option_name" value="name" checked> Name <span class="glyphicon glyphicon-sort-by-attributes"></span><span class="glyphicon glyphicon-sort-by-attributes-alt hide"></span>
			</label>
			<label class="btn btn-default sugar-btn">
				<input type="radio" name="order_by" id="option_sugar_versions" value="sugar_versions"> Sugar Versions <span class="glyphicon glyphicon-sort-by-attributes"></span><span class="glyphicon glyphicon-sort-by-attributes-alt hide"></span>
			</label>
			<label class="btn btn-default">
				<input type="radio" name="order_by" id="option_last_modified" value="last_modified"> Last Modified <span class="glyphicon glyphicon-sort-by-attributes hide"></span><span class="glyphicon glyphicon-sort-by-attributes-alt"></span>
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
					file_name: $item.find(".file_folder_name").html(),
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
			return result;
		}
		
		var list_group_object = itemsToObject($(".list-group").find(".list-group-item").not('.list-group-header'));
		console.log(list_group_object);
		
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

		function sort_array(arr, sortFunction){
			// var sorted_array = arr.slice();
			var sorted_array = $.extend(true, [], arr);
			sortFunction = typeof sortFunction !== 'undefined' && sorted_array.sort(sortFunction);
			return sorted_array;
		}

		function reverseArrows(btnObj, reset){
			if(typeof reset !== 'undefined'){
				$(btnObj).find(".glyphicon-sort-by-attributes-alt").removeClass("hide");
				$(btnObj).find(".glyphicon-sort-by-attributes").addClass("hide");
			}
			else{
				$(btnObj).find(".glyphicon").toggleClass('hide');
			}
		}

		var sortCheckedValue = $(this).find("input").val();
		var reversed = false;
		var sorted_list_group_object = sort_array(list_group_object);
		$('.button-group-wrapper .btn').on('click', function(){
			sorted_list_group_object = [];

			if(sortCheckedValue == $(this).find("input").val()){ 
				reversed = !reversed;
				reverseArrows($(this));
			}
			else {
				reversed = true;
				reverseArrows($(this), true);
			}
			
			sortCheckedValue = $(this).find("input").val();
			// console.log("%cDebugger:", "color:green;", sortCheckedValue);
			// console.log("%cReversed:", "color:#999;", reversed);
			switch(sortCheckedValue){
				case 'sugar_versions':
					sorted_list_group_object = sort_array(list_group_object, sort_by_sugar_version);
				break;

				case 'last_modified':
					sorted_list_group_object = sort_array(list_group_object, sort_by_date);
				break;

				case 'name':
				default:
					sorted_list_group_object = sort_array(list_group_object);
				break;
			}

			if(reversed) sorted_list_group_object.reverse();
			var print_list_group_object = search_keyword($("#search").val());
			
			$(".list-group").find(".list-group-item:not(.list-group-header)").remove();
			$.each(print_list_group_object, function (index, item) {
				$(".list-group .list-group-item").last().after(item.obj);
			});
		});

		$('.button-group-wrapper').on('affix.bs.affix', function () {
			$('.main-container').css("margin-top", '110px');
			$(".list-group-header").addClass("affix");
		}).on('affix-top.bs.affix', function () {
			$(".main-container").removeAttr("style");
			$(".list-group-header").removeClass("affix");
		});


		function search_keyword (keywords) {
			var search_list_group_object = sort_array(sorted_list_group_object);
			
			var splited_keywords = keywords.split(' ');
			$.each(splited_keywords, function (i, keyword) {
				var re = new RegExp(keyword, 'gi');
				$.each(search_list_group_object, function (index, item) {
					// console.log("keyword", keyword, "re_file_name:", re_file_name, "re_sugar_version:", re_sugar_version, "re_sugar_flavor:", re_sugar_flavor, item);
					var re_file_name = item.file_name.match(re);
					var re_sugar_version = (typeof item.sugar !== 'undefined' ? item.sugar.version.match(re) : false);
					var re_sugar_flavor = (typeof item.sugar !== 'undefined' ? item.sugar.flavor.match(re) : false);
					
					if(typeof search_list_group_object[index].found_score === 'undefined'){
						search_list_group_object[index].found_score = 0;
					}

					if(re_file_name || re_sugar_version || re_sugar_flavor){
						// console.log("%c#FOUND:", "color:green;", "keyword", keyword, "re_file_name:", re_file_name, "re_sugar_version:", re_sugar_version, "re_sugar_flavor:", re_sugar_flavor, item);
						if(re_file_name)
							search_list_group_object[index].found_score += 5;
						if(re_sugar_version)
							search_list_group_object[index].found_score += 3;
						if(re_sugar_flavor)
							search_list_group_object[index].found_score++;
					}
				});
			});


			var clear_search_list_group_object = [];
			$.each(search_list_group_object, function (index, item) {
				if(item.found_score != 0) { 
					clear_search_list_group_object.push(item);
				}
			});

			clear_search_list_group_object = sort_array(clear_search_list_group_object, sort_by_found_score);
			clear_search_list_group_object.reverse();
			// console.log(clear_search_list_group_object);
			return clear_search_list_group_object;
		}

		$("#search").on("keyup", function(event){
			console.log("%c#search Keypressed:", "color:orange;", $(this).val());
			var print_list_group_object = search_keyword($(this).val());
			
			$(".list-group").find(".list-group-item:not(.list-group-header)").remove();
			$.each(print_list_group_object, function (index, item) {
				$(".list-group .list-group-item").last().after(item.obj);
			});
		});
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
		$icon = '<span class="glyphicon glyphicon-folder-close" aria-hidden="true"></span> ';
		
		if(!$file_folder->isDir()){
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
			<a href="'.$link.'" class="list-group-item">'.$icon.'<span class="file_folder_name">'.$file_folder_name.'</span>'.$file_folder_info_wrapper.'</a>';
		}
	}

	$header = '
	<li class="list-group-item list-group-header">File/Folder Name<div class="file_folder_info pull-right">'.($any_sugar_folder?'<span class="sugar"> <span class="version">Sugar Version</span><span class="flavor">Sugar Edition</span><span class="build">Sugar Build</span> </span>':'').'<span class="file_folder_size">File Size</span><span class="file_folder_perm">Perm </span><span class="file_folder_mtime">Last Modified</span> </div></li>';

	echo $header.$file_folder_container;

	if(!$any_sugar_folder)
		echo '<script> $(function(){ $(".sugar-btn").addClass("hide"); });</script>';
	
?>
</div>
