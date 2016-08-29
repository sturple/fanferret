define(['jquery'],function ($) {
	return function (name, document) {
		document = $(document);
		var div = document.find('#' + name);
		var hidden = document.find('#form_' + name);
		var response = div.find('textarea');
		var testimonial = null;
		var testimonial_hidden = null;
		var update = function () {
			hidden.val(response.val());
			if (!testimonial) return;
			testimonial_hidden.val(testimonial.prop('checked') ? 'true' : '');
		};
		response.change(update);
		if (div.hasClass('fanferret-testimonial')) {
			testimonial = div.find('input[type="checkbox"]');
			testimonial.change(update);
			testimonial_hidden = document.find('#form_' + name + '_testimonial');
		}
		update();
	};
});