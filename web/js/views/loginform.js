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
		"click .loginBtn": "login"
	},
	login: function(event){
		var url = "login";
		var loginFormData = $("#login").serialize();
		$.post(url, loginFormData, function(data){
			console.debug(data);
			window.location.reload();
		});
		return false;
	}
});