define([
	'text!tpls/registerForm.html',
	'collections/users'
	], 
	function(registerFormTpl, Users){
	var RegisterFormView = Backbone.View.extend({
		tagName: "div",
		className: "msgForm",
		template: _.template(registerFormTpl),
		initialize: function(eventName){
			this.model.on("error", this.appendErrors, this);
		},
		render: function(){
			var self = this;
			this.before(function(){	
				self.$el.html(self.template());
			});
			return this;
		},
		events: {
			"click .submitUser": "register"
		},
		register: function(eventName){
			var data = {
				username: $("#register #username").val(),
				email: $("#register #email").val(),
				password: $("#register #password").val()
			};
			if(this.model.set(data)){
				var self = this;
				if(this.model.isNew()){
					console.debug(this.model);
					this.users.create(data, {
						success: function(){
							console.debug(data);
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
		},
		before: function(callback){
			if(this.users){
				console.debug(this.users);
				if(callback) callback();
			} else {
				var self = this;
				this.users = new Users();
				this.users.fetch({
					success: function(){
						if(callback) callback();
					}
				});
			}
		}
		// register: function(event){
		// 	var registerFormData = $("#register").serialize();
		// 	$.post("api/users", registerFormData, function(data){
		// 		console.debug(data);
		// 	},"json");
		// 	return false;
		// }
	});
	return RegisterFormView;
});