window.LoginFormView = Backbone.View.extend({
	tagName: "div",
	className: "msgForm",
	template:_.template($("#login-form-tpl").html()),

	initialize: function(){
		_.bindAll(this, "render");
		this.model.on('change', this.render);
		this.render();
	},
	render: function(eventName){
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	},
	events: {
		"click .loginBtn": "login"
	},
	login: function(event){
		var loginFormData = $("#login").serialize();
		var self = this;
		$.post("api/auth", loginFormData, function(data){
			if(data.login){
				window.location.reload();
			} else {
				self.model.set({errorMessage: data.error});
			}
		},"json");
		return false;
	}
});