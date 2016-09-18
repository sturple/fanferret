define(['jquery','survey/question/base','survey/condition','survey/radio'],function ($, base, condition, radio) {
	return function (name, threshold, operator, group, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var explain_hidden = null;
		var explain = null;
		var explain_div = null;
		var c = null;
		var div = document.find('#' + name);
		var radios = new radio(div.find('a'));
		var get_value = function () {
			var val = radios.getValue();
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
				if (is_explain()) explain_div.slideDown();
				else explain_div.slideUp();
				explain_hidden.val(get_explain());
			}
			group.update();
		};
		if ((threshold !== null) && (operator !== null)) {
			explain_hidden = document.find('#form_' + name + '_explain');
			explain = div.find('textarea');
			explain_div = div.find('.fanferret-rating-explain-input');
			c = new condition(threshold,operator);
		}
		update();
		var prev = radios.change;
		radios.change = function () {
			prev();
			update();
		};
		if (explain) explain.on('input change',update);
		this.addValid(function () {
			if (get_value() === null) return false;
			if (is_explain() && !get_explain()) return false;
			return true;
		});
	};
});