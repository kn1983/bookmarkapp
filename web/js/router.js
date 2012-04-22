define([
	'views/header',
	'views/registerForm', 
	'collections/userInfo'
	], function(HeaderView, RegisterFormView, UserInfo){
	var AppRouter = Backbone.Router.extend({
		routes: {
			"": "content",
			"!/bookmarks": "bookmarks",
			"!/register": "register"
		},
		initialize: function(){
			var self = this;
			this.before(function(){
				$("#header").html(new HeaderView({loggedIn: self.userInfo.first().get("logged_in")}).render().el);
			});
		},
		bookmarks: function(){
			this.before(function(){
				console.debug("bookmarks");
			});
		},
		register: function(){
			this.before(function(){
				$("#content").html(new RegisterFormView().render().el);
			});
		},
		before: function(callback){
			$("#content").empty();
			if(this.userInfo){
				if(callback) callback();
			} else {
				this.userInfo = new UserInfo();
				this.userInfo.fetch({
					success: function(){
						if(callback) callback();
					}
				});
			}

		}
	});
	return AppRouter;
});