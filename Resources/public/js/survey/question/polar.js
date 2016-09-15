define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, document) {
		base.call(this,name,group,document);
		document = $(document);
		var root = document.find('#' + name);
		var check = root.find('input[type="checkbox"]');
		var hidden = document.find('#form_' + name);
		var negative = root.hasClass('fanferret-polar-negative');
		var explain_div = null;
		var explain = null;
		var explain_hidden = null;
		var explain_negative = root.hasClass('fanferret-polar-explain-negative');
		var is_explain = function () {
			var is_negative = check[0].checked === negative;
			return is_negative === explain_negative;
		};
		if (root.hasClass('fanferret-polar-explain')) {
			explain_div = root.find('.fanferret-polar-explain-input');
			explain = root.find('textarea');
			explain_hidden = document.find('#form_' + name + '_explain');
			var update = function () {
				explain_hidden.val(explain.val());
				group.update();
			};
			update();
			explain.change(update);
			var valid = this.valid;
			this.valid = function () {
				if (!is_explain()) return valid();
				if (explain.val().trim() === '') return false;
				return valid();
			};
		}
		var set = function (value) {
			hidden.val(value ? 'true' : '');
			check.prop('checked',value);
			if (explain) {
				if (is_explain()) explain_div.show();
				else explain_div.hide();
			}
			group.update();
		};
		set(check.prop('checked'));
		check.change(function () {
			set(this.checked);
		});
	};
});