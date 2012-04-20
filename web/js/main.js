var AppRouter = Backbone.Router.extend({
	routes: {
		"": "content",
		"!/bookmarks": "bookmarks"
	},
	loggedIn: false,

	initialize: function(){
		this.before(function(){
			app.loggedIn = app.userInfo.first().get("logged_in");
			$("#header").html(new HeaderView().render().el);
				
		});
	},
	bookmarks: function(){
		console.debug("bookmarks");
	},
	before: function(callback){
		if(this.userInfo){
			if(callback) callback();
		} else {
			this.userInfo = new UserCollection();
			this.userInfo.fetch({
				success: function(){
					if(callback) callback();
				}
			});
		}
	}
});

var app = new AppRouter();
Backbone.history.start();