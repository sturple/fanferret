define(['jquery'],function ($) {
	return function (name, document) {
		document = $(document);
		var hidden = document.find('#form_' + name);
		var div = document.find('#' + name);
		var other = null;
		var other_hidden = null;
		var other_div = null;
		var update = function () {
			var selected = div.find('input[name="' + name + '_group"]:checked').val();
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
	};
});