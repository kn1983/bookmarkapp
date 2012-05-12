define([
	'views/mainmenuitem',
	'collections/mainmenu'
	], function(MainMenuItemView, MainMenuCollection){
	var MainMenuView = Backbone.View.extend({
		tagName: "ul",
		className: "main-menu",
		loggedInItems: [
			{name: "Bookmarks", url: "#!/bookmarks"},
			{name: "My bookmarks", url: "#!/mybookmarks"},
			{name: "Add bookmark", url: "#!/addbookmark"},
			{name: "Logout", url: "logout"}
		],
		loggedOutItems: [
			{name: "Bookmarks", url: "#!/bookmarks"},
			{name: "Register", url: "#!/register"}
		],
		initialize: function(){
			 if(this.options.loggedIn){
				this.collection = new MainMenuCollection(this.loggedInItems);
			} else {
				this.collection = new MainMenuCollection(this.loggedOutItems);
			}
		},
		render: function(){
			_.each(this.collection.models, function(item){
				this.$el.append(new MainMenuItemView({model:item}).render().el);
			},this);
			return this;
		}
	});
	return MainMenuView;
});