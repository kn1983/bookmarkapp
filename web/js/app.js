define(['router'], function(Router){
	// 	var router = new Router();
	// 	Backbone.history.start();
	// return router;
	var initialize = function(){
		var router = new Router();
		Backbone.history.start();
	}
	return {
		initialize: initialize
	}
});