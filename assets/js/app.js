$(function(){
	$(".s_link").on('click', function (event) {
		var $a = $(this).parents("a");
		$a.attr('href', '/?current_path='+$a.attr("href"));
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
var vueObj = new Vue({
	el: '#files-folders',
	props: ['model'],
	data:{
		filesfolders:[],
	},
	ready: function () {
		this.$set('filesfolders', parsedData);
	},
	filters:{
		getLink: function (relativePath) {
			var sliced = relativePath.split("/");
			console.log("sliced", sliced, "relativePath", relativePath);
			var last = sliced[sliced.length-1];
			console.log("sliced", sliced, "relativePath", relativePath, "last", last);
			if(last == '..'){
				sliced.pop();
				sliced.pop();
				console.log("sliced", sliced, "relativePath", relativePath, "last", last);
				relativePath = sliced.join("/");
				relativePath = relativePath == "" ? '/' : relativePath;
			}
			console.log("sliced", sliced, "relativePath", relativePath, "last", last);
			console.log("\n\n");
			return relativePath;
		}
	},
	methods:{
		anySugarInstances: function () {
			for (var i = 0; i <= this.filesfolders.length - 1; i++) {
				if ( this.filesfolders[i].sugar ) 
					return true;
			};
			return false;
		},
	}
})