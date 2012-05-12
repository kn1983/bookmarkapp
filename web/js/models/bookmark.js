define(function(){
	var Bookmark = Backbone.Model.extend({
		defaults: {
			id: null,
			title: "",
			url: ""
		}
	});
	return Bookmark;
});