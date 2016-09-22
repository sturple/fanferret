define(['jquery','survey/question/base','survey/radio'],function ($, base, radio) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var hidden = document.find('#form_' + name);
		var div = document.find('#' + name);
		var other = null;
		var other_hidden = null;
		var other_div = null;
		var radios = new radio(div.find('a'));
		var key = group.getToken() + '_' + name;
		var other_key = key + '_other';
		var update = function () {
			var selected = radios.getValue();
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
		radios.setValue(storage.getItem(key));
		if (div.hasClass('fanferret-other')) {
			other = div.find('input[type="text"]');
			other_div = div.find('.fanferret-checklist-option-other-text');
			other_hidden = document.find('#form_' + name + '_other');
			other.val(storage.getItem(other_key));
			other.on('input change',update);
		}
		update();
		var change = radios.change;
		radios.change = function () {
			change();
			update();
		};
		this.addValid(function () {
			var val = radios.getValue();
			if (!val) return false;
			if (val !== 'other') return true;
			if (other.val().trim() === '') return false;
			return true;
		});
	};
});