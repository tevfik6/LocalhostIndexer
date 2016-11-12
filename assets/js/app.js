$(function(){
	$(".browse_folder").on('click', function (event) {
		var $a = $(this).parents("a");
		$a.attr('href', '/?current_path='+$a.attr("href"));
	});

	$(".go_to_editor").on('click', function (event) {
		var $a = $(this).parents("a");
		$a.attr('href', '/?current_path='+$a.attr("href")+'&editor=true');
	});

	//affix
	$('.button-group-wrapper').on('affix.bs.affix', function () {
		$('.main-container').css("margin-top", '110px');
		$(".list-group-header").addClass("affix");
	}).on('affix-top.bs.affix', function () {
		$(".main-container").removeAttr("style");
		$(".list-group-header").removeClass("affix");
	});

	if (typeof editor !== 'undefined' ){
		if (localWorker.get("editor-theme")){
			ace_editor_theme = localWorker.get("editor-theme");
		}
		if (localWorker.get("editor-softwrap")){
			var soft_wrap = localWorker.get("editor-softwrap") == 'true' ? true : false;
			editor.getSession().setUseWrapMode(soft_wrap);
			if ( soft_wrap ) $(".soft_wrap").addClass('active');
			else $(".soft_wrap").removeClass('active');
		}
		else{
			localWorker.set("editor-softwrap", false);
		}

		if ( typeof ace_editor_theme !== 'undefined' ) {
			var found = false;
			$("select#editor-theme").find('option').removeAttr("selected").each(function () {
				var $this = $(this);
				if($this.val() == ace_editor_theme ) {
					$this.attr("selected","selected");
					editor.setTheme(ace_editor_theme);
					found = true;
				}
			});
			if ( !found ) {
				ace_editor_theme = false;
			}
		}
		if( typeof ace_editor_theme === 'undefined' || !ace_editor_theme) {
			var $selected_option = $("select#editor-theme").find('option:selected');
			ace_editor_theme = $selected_option.val();
			localWorker.set("editor-theme", ace_editor_theme);
			editor.setTheme(ace_editor_theme);
		}

		$("select#editor-theme").on('change', function () {
			var $this = $(this);
			ace_editor_theme = $this.val();
			localWorker.set("editor-theme", ace_editor_theme);
			editor.setTheme(ace_editor_theme);
		});

		$(".soft_wrap").on("click", function (event) {
			var $this = $(this);
			var ace_soft_wrap = !$this.hasClass("active");
			localWorker.set("editor-softwrap", ace_soft_wrap);
			editor.getSession().setUseWrapMode(ace_soft_wrap);
			$this.toggleClass("active");
		});

		editor.on('input', function (event) {
		    if (editor.getSession().getUndoManager().isClean())
				$('.file_save').addClass("disabled");
		    else
				$('.file_save').removeClass("disabled");
		});

		$('.file_save').on("click", function() {
			$.ajax({
				method: "POST",
				url: "?save=true",
				data: {file_path: current_file_path, content: editor.getValue()},
				dataType: 'json',
				success: function (result) {
					if (result.success){
						editor.getSession().getUndoManager().markClean();
						$('.file_save').addClass("disabled");
					}
				}
			});
		});

		$("#editor").bind('keydown', function (event) {
			if ((event.metaKey || event.ctrlKey) && event.keyCode == 83) { /*ctrl+s or command+s*/
				event.preventDefault();
				event.stopPropagation();
				$(".file_save:not(.disabled)").trigger("click");
			}
		})
	}

	$("#search").trigger("focus");
});

var localWorker = {
	localWorkerToken: "LocalhostIndexer",
	defaults: {
		sortKey: 'name',
		reverse: false,
	},
	support: function () {
		return (typeof(Storage) !== "undefined");
	},
	get: function (itemKey) {
		var itemValue = this.defaults[itemKey];
		if( this.check(itemKey) && this.support() ) {
			itemKey = this.localWorkerToken+"_"+itemKey;
			itemValue = localStorage.getItem(itemKey);
		}
		return itemValue;
	},
	set: function (itemKey, itemValue) {
		itemKey = this.localWorkerToken+"_"+itemKey;
		localStorage.setItem(itemKey, itemValue);
	},
	del: function (itemKey) {
		this.delete(itemKey);
	},
	delete: function (itemKey) {
		itemKey = this.localWorkerToken+"_"+itemKey;
		localStorage.removeItem(itemKey);
	},
	check: function (itemKey) {
		itemKey = this.localWorkerToken+"_"+itemKey;
		return localStorage.getItem(itemKey) !== null;
	}
};
if ( fileFoldersData != '' ){
	Vue.config.devtools = true;
	var vueObj = new Vue({
		el: '#vue-container',
		data: {
			sortKey: localWorker.get('sortKey'), //'name'
			reverse: localWorker.get('reverse') == "false" ? false : true, //false
			searchKeyword: '',
			levelUps: [],
			filesfolders: parsedData,
		},
		created: function () {
			var self = this;
			this.filesfolders.forEach(function (filefolder, index) {
				if(!filefolder.sugar)
					filefolder.sugar_sort_version = 0;
				else
					filefolder.sugar_sort_version = self.getSugarVersionSortNum(filefolder.sugar.version);
				
				if(filefolder.name == ".."){
					self.levelUps.push(filefolder);
				    self.filesfolders.splice(index, 1);
				}
			});
		},
		filters: {
			getLink: function (relativePath) {
				var sliced = relativePath.split("/");
				var last = sliced[sliced.length-1];
				if(last == '..'){
					sliced.pop();
					sliced.pop();
					relativePath = sliced.join("/");
					relativePath = relativePath == "" ? '/' : relativePath;
				}
				return relativePath;
			}
		},
		computed: {
			anySugarInstances: function(){
				var returnValue = false;
				for (var i = 0; i <= this.filesfolders.length - 1; i++) {
					if ( this.filesfolders[i].sugar ){
						returnValue = true;
						break;
					}
				};
				if(localWorker.get("sortKey") == 'sugar_sort_version'){
					this.sortBy('name');
				}
				return returnValue;
			},

			filteredFilesFolders: function(){
				var self = this;
				var filteredFilesFolders = self.filesfolders.filter(function (filefolder) {
					return (
						filefolder.name.indexOf(self.searchKeyword) !== -1 ||
						filefolder.perm.formated.indexOf(self.searchKeyword) !== -1 ||
						filefolder.mtime.formated.indexOf(self.searchKeyword) !== -1 ||
						(
							filefolder.sugar != false && 
							(
								filefolder.sugar.version.indexOf(self.searchKeyword) !== -1 ||
								filefolder.sugar.flavor.indexOf(self.searchKeyword) !== -1
							)
						)
					);
				});
				return _.orderBy(filteredFilesFolders, self.sortKey, self.reverse ? 'desc' : 'asc');
			},
		},
		methods: {
			getLink: function (relativePath) {
				var sliced = relativePath.split("/");
				var last = sliced[sliced.length-1];
				if(last == '..'){
					sliced.pop();
					sliced.pop();
					relativePath = sliced.join("/");
					relativePath = relativePath == "" ? '/' : relativePath;
				}
				return relativePath;
			},

			sortKeyActive: function (sortKey) {
				return sortKey == this.sortKey;
			},

			sortBy: function (sortKey, e) {
				if (arguments.length == 2)
					e.preventDefault();
				this.reverse = (this.sortKey == sortKey) ? ! this.reverse : false;
				this.sortKey = sortKey;
				localWorker.set("reverse", this.reverse);
				localWorker.set("sortKey", this.sortKey);
			},

			getSugarVersionSortNum: function (sugar_version) {
				var splited = sugar_version.split(".");
				for (var i = splited.length - 1; i >= 0; i--) {
					if ( splited[i] <= 9 && i != 0 ) splited[i] = "0"+splited[i];
				};
				return splited.join('');
			}
		}
	});
}
