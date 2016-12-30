define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var div = document.find('#' + name);
		var other = null;
		var other_hidden = null;
		var other_div = null;
		var radios = div.find('input[name="' + name + '_group"]');
		var key = group.getToken() + '_' + name;
		var other_key = key + '_other';
		var update = function () {
			var selected = radios.filter(':checked').val();
			storage.setItem(key,selected);
			if (selected === 'other') {
				hidden.val(null);
				var o = other.val();
				other_hidden.val(o);
				storage.setItem(other_key,o);
				other_div.slideDown();
			} else {
				hidden.val(selected);
				if (other) {
					other_hidden.val(null);
					other_div.slideUp();
				}
			}
			group.update();
		};
		radios.prop('checked',false);
		var old = storage.getItem(key);
		for (var i = 0; i < radios.length; ++i) {
			var e = $(radios[i]);
			if (e.attr('value') !== old) continue;
			e.prop('checked',true);
			break;
		}
		if (div.hasClass('fanferret-other')) {
			other = div.find('input[type="text"]');
			other_div = div.find('.fanferret-radio-option-other-text');
			other_hidden = document.find('#form_' + name + '_other');
			other.val(storage.getItem(other_key));
			other.on('input change',update);
		}
		update();
		div.find('input[type="radio"]').change(update);
		this.addValid(function () {
			var val = radios.filter(':checked').val();
			if (!val) return false;
			if (val !== 'other') return true;
			if (other.val().trim() === '') return false;
			return true;
		});
	};
});