define(['router'], function(Router){
	var initialize = function(){
		var router = new Router();
		Backbone.history.start();
	}
	return {
		initialize: initialize
	}
});