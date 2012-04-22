define([
	'views/mainmenu',
	'views/loginForm',
	'models/login',
	'text!tpls/header.html'
	], function(MainMenuView, LoginFormView, LoginModel, headerTpl){
	var HeaderView = Backbone.View.extend({
		tagName: "div",
		className: "header-content",
		template: _.template(headerTpl),
		render: function(){
			this.$el.html(this.template);
			this.$el.append(new MainMenuView({loggedIn: this.options.loggedIn}).render().el);

			if(!this.options.loggedIn){
				this.$el.append(new LoginFormView({model: new LoginModel()}).render().el);
			}
			return this;
		}
	});
	return HeaderView;
});