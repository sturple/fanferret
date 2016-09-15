define(['jquery'],function ($) {
	return function (es) {
		var curr = null;
		var self = this;
		es = $(es);
		es.click(function () {
			var e = $(this);
			curr = e.attr('data-value');
			es.removeClass('selected');
			e.addClass('selected');
			self.change(curr,this);
		});
		this.getValue = function () {
			return curr;
		};
		this.change = function () {	};
	};
});