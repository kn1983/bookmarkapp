window.HeaderView = Backbone.View.extend({
	tagName: "div",
	className: "header-content",
	template: _.template($("#header-tpl").html()),
	initialize: function(){

	},
	render: function(eventName){
		this.$el.html(this.template());
		if(app.loggedIn){
			this.$el.append(new MainMenuView().render().el);
		} else {
			this.$el.append(new LoginFormView().render().el);
			this.$el.append(new MainMenuView().render().el);
		}
		return this;
	}
});