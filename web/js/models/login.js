define(function(){
	var LoginModel = Backbone.Model.extend({
		defaults: {
			errorMessage: "",
	        username: "",
	       	password: "",
	        autologin: false,
	        login: true
		}
	});
	return LoginModel;
});