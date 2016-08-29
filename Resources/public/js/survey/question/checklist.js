define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var div = document.find('#' + name);
		var other = null;
		var other_hidden = null;
		var other_div = null;
		var radios = div.find('input[name="' + name + '_group"]');
		var update = function () {
			var selected = radios.filter(':checked').val();
			if (selected === 'other') {
				hidden.val(null);
				other_hidden.val(other.val());
				other_div.show();
				return;
			}
			if (other) {
				other_hidden.val(null);
				other_div.hide();
			}
			hidden.val(selected);
		};
		if (div.hasClass('fanferret-other')) {
			other = div.find('input[type="text"]');
			other_div = div.find('.fanferret-checklist-option-other-text');
			other_hidden = document.find('#form_' + name + '_other');
		}
		update();
		div.find('input').change(update);
		var valid = this.valid;
		this.valid = function () {
			var val = radios.filter(':checked').val();
			if (!val) return false;
			if (val !== 'other') return valid();
			if (other.val().trim() === '') return false;
			return valid();
		};
	};
});