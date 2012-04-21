window.RegisterFormView = Backbone.View.extend({
	tagName: "div",
	className: "msgForm",
	template:_.template($("#register-form-tpl").html()),
	
	initialize: function(){
		_.bindAll(this, "render");
		this.model.on('change', this.render);
		this.render();
	},
	render: function(){
		// this.model;
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	},
	events: {
		"click .submitUser": "register"
	},
	register: function(event){
		var registerFormData = $("#register").serialize();
		var self = this;
		$.post("api/user", registerFormData, function(data){
			if(data.registration){
				window.location.reload();
			} else {
				self.model.set({errorMessage: data.error});
			}
		},"json");
		return false;
	}
});