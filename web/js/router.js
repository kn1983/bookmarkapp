define([
	'models/user',
	'views/header',
	'views/registerForm', 
	'collections/userInfo',
	'collections/users'
	], function(User, HeaderView, RegisterFormView, UserInfo, Users){
	var AppRouter = Backbone.Router.extend({
		routes: {
			"": "content",
			"!/bookmarks": "bookmarks",
			"!/register": "register"
		},
		initialize: function(){
			var self = this;
			this.before(function(){
				self.before2(function(){
					$("#header").html(new HeaderView({loggedIn: self.userInfo.first().get("logged_in")}).render().el);
				});
			});
		},
		bookmarks: function(){
			this.before(function(){
				console.debug("bookmarks");
			});
		},
		register: function(){
			var self = this;
			this.before(function(){
				self.before2(function(){
					$("#content").html(new RegisterFormView({model: new User(), collection: self.users}).render().el);
				});
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
		},
		before2: function(callback){
			if(this.users){
				if(callback) callback();
			} else {
				var self = this;
				this.users = new Users();
				this.users.fetch({
					success: function(){
						if(callback) callback();
					}
				});
			}
		}
	});
	return AppRouter;
});