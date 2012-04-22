define(['../models/userInfo'], function(UserInfo){
	var UserInfo = Backbone.Collection.extend({
		model: UserInfo,
		url: "userinfo"
	});
	return UserInfo;
});