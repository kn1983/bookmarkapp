define(['text!tpls/bookmarkDialog.html'],function(bookmarkDialogTpl){
	var BookmarkDialogView = Backbone.View.extend({
		tagName: "div",
		className: "bookmark-dialog",
		template: _.template(bookmarkDialogTpl),
		initialize: function(){
			this.overlay = $('<div class="dialogOverlay"></div>');
			$("body").append(this.overlay);
		},
		render: function(){
			this.$el.html(this.template);
			return this;
		},
		save: function(eventName){
			var self = this;
			var formData = {
				url: $("#bookmarkurl").val(),
				title: $("#bookmarktitle").val()
			};
			this.model.set(formData);
			if(this.model.isNew()){
				this.collection.create(this.model, {
					success: function(){
						console.debug("success");
						self.closeDialog();
					}
				});
			}
			return false;
		},
		cancel: function(){
			this.closeDialog();
		},
		closeDialog: function(){
			this.remove();
			this.unbind();
			this.overlay.remove();
			history.back();
		},
		events: {
			// "keydown #bookmarkurl": "getPageTitle",
			"click .saveBookmark": "save",
			"click .cancel": "cancel"
		},
		// getPageTitle: function(){
		// 	var url = $("#bookmarkurl").val();
		// 	if(url.substring(0, 3) === 'www'){
		// 		url = 'http://' + url;
		// 	}
		// 	if(url === ''){
		// 		$("#bookmarktitle").val("");
		// 	} else {
		// 		$.ajax({
		// 			type: "POST",
		// 			url: "web/getUrlTitle.php",
		// 			data: {url: url},
		// 			success: function(data){
		// 				$("#bookmarktitle").val(data);
		// 			}
		// 		});
		// 	}
		// },
		validateUrl: function(url){
			var urlregex = new RegExp("^(http:\/\/www.|https:\/\/www.|ftp:\/\/www.|www.){1}([0-9A-Za-z]+\.)");
			return urlregex.test(url);
		}
	});
	return BookmarkDialogView;
});