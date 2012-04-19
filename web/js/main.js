window.UserInfoModel = Backbone.Model.extend();
window.UserCollection = Backbone.Collection.extend({
	model: UserInfoModel,
	url: "userinfo"
});
window.LoginFormView = Backbone.View.extend({
	tagName: "div",
	className: "msgForm",
	template:_.template($("#login-form-tpl").html()), 
	initialize: function(){
		this.userinfo
		this.render();
	},
	render: function(eventName){
		this.$el.html(this.template());
		return this;
	},
	events: {
		"click .loginBtn": "loginF"
	},
	loginF: function(event){
		var url = "login";
		var loginFormData = $("#login").serialize();
		$.post(url, loginFormData, function(data){
			console.debug(data);
			window.location.reload();
		});
		return false;
	}
});


var AppRouter = Backbone.Router.extend({
	routes: {
		"": "content"
	},
	loggedIn: false,
	initialize: function(){
		this.before(function(){
			this.loggedIn = app.userInfo.first().get("logged_in");
			if(this.loggedIn){
				console.debug("Logged in");
			} else {
				$("#content").html(new LoginFormView().render().el);
			}
				
		});
	},
	content: function(){
		// $("#header").html(new LoginFormView.render().el);
	},
	before: function(callback){
		if(this.userInfo){
			if(callback) callback();
		} else {
			this.userInfo = new UserCollection();
			this.userInfo.fetch({
				success: function(){
					if(callback) callback();
				}
			});
		}
	}
});

var app = new AppRouter();

// loginFormView = new LoginFormView();