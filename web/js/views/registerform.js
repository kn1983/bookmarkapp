define(['text!tpls/registerForm.html'], function(registerFormTpl){
	var RegisterFormView = Backbone.View.extend({
		tagName: "div",
		className: "msgForm",
		template: _.template(registerFormTpl),
		render: function(){
			// this.model;
			this.$el.html(this.template());
			return this;
		},
		events: {
			"click .submitUser": "register"
		},
		register: function(event){
			var registerFormData = $("#register").serialize();
			$.post("api/user", registerFormData, function(data){
				console.debug(data);
			},"json");
			return false;
		}
	});
	return RegisterFormView;
});