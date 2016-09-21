define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var div = document.find('#' + name);
		var hidden = document.find('#form_' + name);
		var response = div.find('textarea');
		var testimonial = null;
		var testimonial_hidden = null;
		var key = group.getToken() + '_' + name;
		var testimonial_key = key + '_testimonial';
		var update = function () {
			var val = response.val();
			hidden.val(val);
			storage.setItem(key,val);
			if (!testimonial) return;
			var tval = testimonial.prop('checked') ? 'true' : '';
			testimonial_hidden.val(tval);
			storage.setItem(testimonial_key,tval);
		};
		response.on('input change',update);
		if (div.hasClass('fanferret-testimonial')) {
			testimonial = div.find('input[type="checkbox"]');
			testimonial.change(update);
			testimonial_hidden = document.find('#form_' + name + '_testimonial');
		}
		response.val(storage.getItem(key));
		if (testimonial) testimonial.prop('checked',storage.getItem(testimonial_key) === 'true');
		update();
	};
});