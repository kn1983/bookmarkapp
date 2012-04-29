define(['../models/user'], function(User){
	var Users = Backbone.Collection.extend({
		model: User,
		url: "api/users"
	});
	return Users;
});