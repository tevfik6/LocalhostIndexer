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

	// //if there is something in the search box remember it
	// if($.trim($("#search").val()) != ""){
	// 	$("#search").trigger("keyup");
	// }

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

var vueObj = new Vue({
	el: 'body',
	data: {
		sortKey: localWorker.get('sortKey'), //'name'
		reverse: localWorker.get('reverse') == "false" ? false : true, //false
		searchKeyword: '',
		levelUp: false,
		filesfolders: parsedData,
	},
	created: function () {
		var self = this;
		this.filesfolders.forEach(function (filefolder) {
			if(!filefolder.sugar)
				filefolder.sugar_sort_version = 0;
			else
				filefolder.sugar_sort_version = self.getSugarVersionSortNum(filefolder.sugar.version);
			if(filefolder.name == ".."){
				self.levelUp = [];
				self.levelUp.push(filefolder);
				self.filesfolders.$remove(filefolder);
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
	methods: {
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
		anySugarInstances: function () {
			for (var i = 0; i <= this.filesfolders.length - 1; i++) {
				if ( this.filesfolders[i].sugar )
					return true;
			};
			if(localWorker.get("sortKey") == 'sugar_sort_version'){
				this.sortBy('name');
			}
			return false;
		},
		getSugarVersionSortNum: function (sugar_version) {
			var splited = sugar_version.split(".");
			for (var i = splited.length - 1; i >= 0; i--) {
				if ( splited[i] <= 9 && i != 0 ) splited[i] = "0"+splited[i];
			};
			return splited.join('');
		}
	}
})
