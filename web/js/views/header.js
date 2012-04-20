window.HeaderView = Backbone.View.extend({
	tagName: "div",
	className: "header-content",
	template: _.template($("#header-tpl").html()),

	render: function(eventName){
		this.$el.html(this.template());
		if(app.loggedIn){
			this.$el.append(new MainMenuView().render().el);
		} else {
			this.$el.append(
				new LoginFormView({model: new LoginModel()}).render().el, 
				new MainMenuView().render().el
			);
		}
		return this;
	}
});