define(['models/bookmark'], function(Bookmark){
	var Bookmarks = Backbone.Collection.extend({
		model: Bookmark,
		url: "api/bookmarks"
	});
	return Bookmarks;
});