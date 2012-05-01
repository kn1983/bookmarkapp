define([
	'text!tpls/registerForm.html'
	], 
	function(registerFormTpl){
	var RegisterFormView = Backbone.View.extend({
		tagName: "div",
		className: "msgForm",
		template: _.template(registerFormTpl),
		initialize: function(eventName){
			this.model.on("error", this.appendErrors, this);
		},
		render: function(){
			var self = this;
			self.$el.html(self.template());
			return this;
		},
		events: {
			"click .submitUser": "register"
		},
		register: function(eventName){
			var formData = {
				username: $("#register #username").val(),
				email: $("#register #email").val(),
				password: $("#register #password").val()
			};
			if(this.model.set(formData)){
				var self = this;
				if(this.model.isNew()){
					this.collection.create(this.model, {
						success: function(){
							console.debug("success");
						}
					});
				} else {
					console.debug("not new");
				}
			}
			return false;
		},
		appendErrors: function(model, errors){	
			var errorDiv = this.$el.find(".error");
			errorDiv.empty();
			$.each(errors, function(index, error){
				errorDiv.append('<p>' + error + '</p>');
			});
		}
	});
	return RegisterFormView;
});