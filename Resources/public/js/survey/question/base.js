define(['jquery'],function ($) {
	return function (name, group, document) {
		this.active = function () {	};
		this.valid = function () {
			return true;
		};
		document = $(document);
		var a = document.find('#' + name + ' .fanferret-question-done > a');
		var self = this;
		a.click(function () {
			if (!self.valid()) return;
			group.next();
		});
	};
});