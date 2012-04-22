<<<<<<< HEAD
define(function(){
	var UserInfo = Backbone.Model.extend();
	return UserInfo;
});
=======
window.UserInfoModel = Backbone.Model.extend();
window.UserCollection = Backbone.Collection.extend({
	model: UserInfoModel,
	url: "api/auth"
});
>>>>>>> ad59ad5d0caf4a4aa7a7081afbfe7a9c9a2eb089
