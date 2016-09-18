define(['jquery'],function ($) {
	return function (name, group, document) {
		var e = $(document).find('#' + name);
		this.active = function () {	};
		var checkers = [];
		this.valid = function () {
			var self = this;
			var retr = checkers.reduce(function (prev, f) {
				if (!f.apply(self)) return false;
				return prev;
			},true);
			if (retr) e.addClass('fanferret-valid');
			else e.removeClass('fanferret-valid');
			return retr;
		};
		this.addValid = function (f) {
			checkers.push(f);
		};
	};
});