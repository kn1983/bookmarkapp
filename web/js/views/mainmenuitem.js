define(['text!tpls/mainMenuItem.html'], function(MainMenuItemView){
	var MainMenuItemView = Backbone.View.extend({
		tagName: "li",
		template: _.template(MainMenuItemView),
		render: function(){
			this.$el.html(this.template(this.model.toJSON()));
			return this;
		}
	});
	return MainMenuItemView;
});