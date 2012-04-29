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

			if(!attributes.email.match(pattern)){
				errorMessages.push("Du har inte angett en korrekt e-postaddress!");
			}
			// Return errors
			if(errorMessages.length > 0){
				return errorMessages;
			}
		}
	});
	return User; 
});
// The username is too short. It needs to be at least 2 characters