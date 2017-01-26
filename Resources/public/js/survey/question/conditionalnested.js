define(['jquery','survey/question/base','survey/radio'],function ($, base, radio) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var root = document.find('#' + name);
		var radios = new radio(root.find('a'));
		var nested = document.find('#' + name + '_nested');
		nested.hide();
		var hidden = true;
		var negative = root.hasClass('fanferret-conditional-nested-negative');
		var questions = [];
		var key = group.getToken() + '_' + name;
		var impl = document.find('#form_' + name);
		var update = function () {
			var val = radios.getValue();
			storage.setItem(key,val);
			if (val === null) return;
			val = val === 'true';
			impl.val(val ? 'true' : '');
			hidden = val === negative;
			if (hidden) nested.slideUp();
			else {
				nested.slideDown();
				$('html,body').animate({scrollTop: nested.offset().top},1000);
			}
			group.update();
		};
		var change = radios.change;
		radios.change = function () {
			change();
			update();
		};
		radios.setValue(storage.getItem(key));
		update();
		var active = this.active;
		this.active = function () {
			active();
			questions.forEach(function (q) {	q.active();	});
		};
		var self = this;
		this.addQuestion = function (q) {	questions.push(q);	};
		var valid = this.valid;
		this.valid = function () {
			var retr = radios.getValue() !== null;
			if (!valid()) retr = false;
			if (retr) root.addClass('fanferret-valid');
			else root.removeClass('fanferret-valid');
			if (hidden) return retr;
			return questions.reduce(function (prev, curr) {
				if (!curr.valid()) return false;
				return prev;
			},retr);
		};
	};
});