var AppRouter = Backbone.Router.extend({
	routes: {
		"": "content",
		"!/bookmarks": "bookmarks",
		"!/register": "register"
	},
	loggedIn: false,

	initialize: function(){
		this.before(function(){
			$("#header").html(new HeaderView().render().el);	
		});
	},
	bookmarks: function(){
		this.before(function(){
			console.debug("bookmarks");
		});
	},
	register: function(){
		this.before(function(){
			$("#content").html(new RegisterFormView({model: new RegisterModel()}).render().el);
		});
	},
	before: function(callback){
		$("#content").empty();
		if(this.userInfo){
			if(callback) callback();
		} else {
			this.userInfo = new UserCollection();
			this.userInfo.fetch({
				success: function(){
					app.loggedIn = app.userInfo.first().get("logged_in");
					if(callback) callback();
				}
			});
		}
	}
});

var app = new AppRouter();
Backbone.history.start();