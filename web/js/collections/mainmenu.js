define(['models/mainmenu'], function(MainMenuItem){
	var MainMenuCollection = Backbone.Collection.extend({
		model: MainMenuItem,
	});
	return MainMenuCollection;
});