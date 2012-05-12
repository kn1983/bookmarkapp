define(['text!tpls/bookmarkDialog.html'],function(bookmarkDialogTpl){
	var BookmarkDialogView = Backbone.View.extend({
		tagName: "div",
		className: "bookmark-dialog",
		template: _.template(bookmarkDialogTpl),
		initialize: function(){
			console.debug(this.model);
		},
		render: function(){
			this.$el.html(this.template);
			return this;
		},
		events: {
			"keydown #bookmarkurl": "getPageTitle"
		},
		getPageTitle: function(){
			var url = $("#bookmarkurl").val();
			if(url.substring(0, 3) === 'www'){
				url = 'http://' + url;
			}
			if(url === ''){
				$("#bookmarktitle").val("");
			} else {
				$.ajax({
					type: "POST",
					url: "web/getUrlTitle.php",
					data: {url: url},
					success: function(data){
						$("#bookmarktitle").val(data);
					}
				});
			}
		},
		validateUrl: function(url){
			var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
			return urlregex.test(url);
		}
	});
	return BookmarkDialogView;
});