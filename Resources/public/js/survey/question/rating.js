define(['jquery','survey/question/base','survey/condition','survey/radio'],function ($, base, condition, radio) {
	return function (name, threshold, operator, group, storage, document) {
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
		var key = group.getToken() + '_' + name;
		var explain_key = key + '_explain';
		var update = function () {
			var val = get_value();
			hidden.val(val);
			if (val === null) storage.removeItem(key);
			else storage.setItem(key,val);
			if (explain_hidden) {
				if (is_explain()) explain_div.slideDown();
				else explain_div.slideUp();
				var ex = explain.val();
				explain_hidden.val(ex);
				storage.setItem(explain_key,ex);
			}
			group.update();
		};
		if ((threshold !== null) && (operator !== null)) {
			explain_hidden = document.find('#form_' + name + '_explain');
			explain = div.find('textarea');
			explain_div = div.find('.fanferret-rating-explain-input');
			c = new condition(threshold,operator);
		}
		this.addValid(function () {
			return get_value() !== null;
		});
		var restored = storage.getItem(key);
		if (restored !== null) {
			radios.setValue(restored);
		}
		if (explain_hidden) {
			explain.val(storage.getItem(explain_key));
		}
		update();
		var prev = radios.change;
		radios.change = function () {
			prev();
			update();
		};
		if (explain) explain.on('input change',update);
	};
});