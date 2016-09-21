define(['jquery'],function ($) {
	return function (es) {
		var curr = null;
		var self = this;
		es = $(es);
		var select = function (e) {
			curr = e.attr('data-value');
			es.removeClass('selected');
			e.addClass('selected');
			self.change(curr,this);
		};
		es.click(function () {
			select($(this));
		});
		this.getValue = function () {
			return curr;
		};
		this.setValue = function (val) {
			for (var i = 0; i < es.length; ++i) {
				var e = $(es[i]);
				if (e.attr('data-value') !== val) continue;
				select(e);
				break;
			}
		};
		this.change = function () {	};
	};
});