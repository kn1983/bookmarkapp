window.LoginFormView = Backbone.View.extend({
	tagName: "div",
	className: "msgForm",
	template:_.template($("#login-form-tpl").html()), 
	initialize: function(){
		this.render();
	},
	render: function(eventName){
		this.$el.html(this.template());
		return this;
	},
	events: {
		"click .loginBtn": "loginF"
	},
	loginF: function(event){
		var url = "login";
		var loginFormData = $("#login").serialize();
		// console.debug(loginFormData);
		$.post(url, loginFormData, function(data){
			console.debug(data);
		});
		return false;
	}
});


var AppRouter = Backbone.Router.extend({
	routes: {
		"": "content"
	},
	initialize: function(){
		$("#content").html(new LoginFormView().render().el);
		// $.getJSON(url, function(data){

		// });
	},
	content: function(){
		console.debug("test");
		// $("#header").html(new LoginFormView.render().el);
	}
});

var app = new AppRouter();

// loginFormView = new LoginFormView();