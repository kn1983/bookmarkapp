define(function(){
	var User = Backbone.Model.extend({
		defaults: {
			id: null,
			username: "",
			email: "",
			password: ""
		},
		validate: function(attributes){
			var pattern = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
				errorMessages = [];

			if(attributes.username.length < 2){
				errorMessages.push("The username is too short. It needs to be at least 2 characters!");
			}
			if(attributes.email.length < 6){
				errorMessages.push("The email is too short. It needs to be at least 6 characters!");
			}
			if(attributes.password.length < 5){
				errorMessages.push("The password is too short. It needs to be at least 5 characters!");
			}
			if(!attributes.email.match(pattern)){
				errorMessages.push("This is not a valid e-mail!");
			}

			// Return errors
			if(errorMessages.length > 0){
				return errorMessages;
			}
		}
	});
	return User; 
});