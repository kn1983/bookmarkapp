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
				$("#header").html(new HeaderView({loggedIn: self.userInfo.first().get("logged_in")}).render().el);
			});
			// this.before2(function(){
			// 	console.debug("success");
			// });
		},
		bookmarks: function(){
			this.before(function(){
				console.debug("bookmarks");
			});
		},
		register: function(){
			this.before(function(){
				$("#content").html(new RegisterFormView({model: new User()}).render().el);
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
		// before2: function(callback){
		// 	if(this.users) {
		// 		if(callback) callback();
		// 	} else {
		// 		this.users = new Users();
		// 		var self = this;
		// 		this.users.fetch({
		// 			success: function(){
		// 				// console.debug(self.users);
		// 			}
		// 		})
		// 	}
		// }
	});
	return AppRouter;
});