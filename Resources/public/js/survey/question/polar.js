define(['jquery','survey/question/base','survey/radio'],function ($, base, radio) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var root = document.find('#' + name);
		var radios = new radio(root.find('a'));
		var check = root.find('input[type="checkbox"]');
		var hidden = document.find('#form_' + name);
		var negative = root.hasClass('fanferret-polar-negative');
		var explain_div = null;
		var explain = null;
		var explain_hidden = null;
		var explain_negative = root.hasClass('fanferret-polar-explain-negative');
		var get_value = function () {
			var val = radios.getValue();
			if (val === null) return null;
			return val === 'true';
		};
		var is_explain = function () {
			if (!explain) return false;
			var val = get_value();
			if (val === null) return false;
			var is_negative = val === negative;
			return is_negative === explain_negative;
		};
		var key = group.getToken() + '_' + name;
		var explain_key = key + '_explain';
		var update = function () {
			if (explain) {
				if (is_explain()) explain_div.slideDown();
				else explain_div.slideUp();
				var ex = explain.val().trim();
				explain_hidden.val(ex);
				storage.setItem(explain_key,ex);
			}
			var val = radios.getValue();
			hidden.val((val === 'true') ? 'true' : '');
			storage.setItem(key,val);
			group.update();
		};
		this.addValid(function () {
			return get_value() !== null;
		});
		radios.setValue(storage.getItem(key));
		if (root.hasClass('fanferret-polar-explain')) {
			explain_div = root.find('.fanferret-polar-explain-input');
			explain = root.find('textarea');
			explain_hidden = document.find('#form_' + name + '_explain');
			explain.val(storage.getItem(explain_key));
			explain.on('input change',update);
		}
		var change = radios.change;
		radios.change = function () {
			change();
			update();
		};
		update();
	};
});