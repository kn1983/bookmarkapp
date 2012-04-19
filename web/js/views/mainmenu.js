window.MainMenuView = Backbone.View.extend({
	tagName: "ul",
	className: "main-menu",
	loggedInItems: [
		{name: "Bookmarks", url: "#!/bookmarks"},
		{name: "My bookmarks", url: "#!/mybookmarks"},
		{name: "Logout", url: "logout"}
	],
	loggedOutItems: [
		{name: "Bookmarks", url: "#!/bookmarks"}
	],
	initialize: function(){
		if(app.loggedIn){
			this.collection = new MainMenuCollection(this.loggedInItems);
			console.debug(app.loggedIn);
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

window.MainMenuItemView = Backbone.View.extend({
	tagName: "li",
	template: _.template($("#main-menu-item").html()),
	render: function(){
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	}
})