define(['jquery','survey/question/base','survey/condition'],function ($, base, condition) {
	return function (name, threshold, operator, group, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var explain_hidden = null;
		var explain = null;
		var explain_div = null;
		var c = null;
		var div = document.find('#' + name);
		var radios = div.find('input[name="' + name + '_group"]');
		var get_value = function () {
			var val = radios.filter(':checked').val();
			if (!val) return null;
			return parseInt(val);
		};
		var is_explain = function () {
			if (!c) return false;
			var v = get_value();
			if (v === null) return false;
			return c.check(v);
		};
		var get_explain = function () {
			if (!is_explain()) return null;
			return explain.val();
		};
		var update = function () {
			hidden.val(get_value());
			if (explain_hidden) {
				if (is_explain()) explain_div.show();
				else explain_div.hide();
				explain_hidden.val(get_explain());
			}
			group.update();
		};
		if ((threshold !== null) && (operator !== null)) {
			explain_hidden = document.find('#form_' + name + '_explain');
			explain = div.find('input[type="text"]');
			explain_div = div.find('.fanferret-rating-explain-input');
			c = new condition(threshold,operator);
		}
		update();
		div.find('input[type="radio"]').change(update);
		if (explain) explain.change(update);
		var valid = this.valid;
		this.valid = function () {
			if (get_value() === null) return false;
			if (is_explain() && !get_explain()) return false;
			return valid();
		};
	};
});