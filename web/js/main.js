require (
	{
		paths: {
			'jquery': 'libs/jquery-1.7.1.min',
			'underscore': 'libs/underscore-min',
			'backbone': 'libs/backbone-min'
		}
	},
	['main'],
	function(){
		require(['require', 'order!jquery', 'order!underscore', 'order!backbone'], function(){
			require(['app'], function(App){
				// console.debug(App);
				App.initialize();
				// console.debug(App);
			});
		});
	}
);