define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var div = document.find('#' + name);
		var radios = div.find('input[name="' + name + '_group"]');
		var get_value = function () {
			var val = radios.filter(':checked').val();
			if (!val) return null;
			return val;
		};
		var update = function () {
			hidden.val(get_value());
		};
		update();
		div.find('input[type="radio"]').change(update);
		var valid = this.valid;
		this.valid = function () {
			if (!get_value()) return false;
			return valid();
		};
	};
});