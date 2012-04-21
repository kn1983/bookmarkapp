window.UserInfoModel = Backbone.Model.extend();
window.UserCollection = Backbone.Collection.extend({
	model: UserInfoModel,
	url: "api/auth"
});