window.RegisterFormView = Backbone.View.extend({
	tagName: "div",
	className: "msgForm",
	template: _.template($("#register-form-tpl").html()),
	initialize: function(){

	},
	render: function(){
		// this.model;
		this.$el.html(this.template());
		return this;
	},
	events: {
		"click .submitUser": "register"
	},
	register: function(event){
		// var registerFormData = $("#register").serialize();
		// $.post("register", registerFormData, function(data){
		// 	console.debug("Success");
		// },"json");
		// return false;
	}
});