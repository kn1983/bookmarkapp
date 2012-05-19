define([
	'models/user',
	'models/bookmark',
	'views/header',
	'views/registerform',
	'views/bookmarkdialog', 
	'collections/userInfo',
	'collections/users',
	'collections/bookmarks'
	], function(User, Bookmark, HeaderView, RegisterFormView, BookmarkDialogView, UserInfo, Users, Bookmarks){
	var AppRouter = Backbone.Router.extend({
		routes: {
			"": "content",
			"!/bookmarks": "bookmarks",
			"!/register": "register",
			"!/addbookmark": "addBookmark",
			"!/mybookmarks": "mybookmarks"
		},
		initialize: function(){
			var self = this;
			this.before(function(){
				self.before2(function(){
					$("#header").html(new HeaderView({loggedIn: self.loggedIn}).render().el);
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
					$("#content").empty();
					$("#content").html(new RegisterFormView({model: new User(), collection: self.users}).render().el);
				});
			});
		},
		addBookmark: function(){
			var self = this;
			// self.currentDialog = new BookmarkDialogView({model: new Bookmark()});
			// $("body").append(self.currentDialog.render().el);

			this.before(function(){
				self.before2(function(){
					if(self.loggedIn){
						if(self.bookmarks){
							self.bookmarks = new Bookmarks();
						}
						// if(!self.currentDialog){
							self.currentDialog = new BookmarkDialogView({model: new Bookmark(), collection: self.bookmarks});
						// } else {
						// 	console.debug(self);
						// 	console.debug("not exist");
						// }
						$("body").append(self.currentDialog.render().el);
					}
				});
			});
		},
		mybookmarks: function(){
			if(this.currentDialog){
				console.debug("true");
			} else {
				console.debug("false");
			}
			console.debug("mybookmarks");
		},
		before: function(callback){
			var self = this;
			if(this.userInfo){
				if(callback) callback();
			} else {
				this.userInfo = new UserInfo();
				this.userInfo.fetch({
					success: function(){
						self.loggedIn = self.userInfo.first().get("logged_in");
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