define(['jquery','survey/question/base'],function ($, base) {
	return function (name, group, storage, document) {
		base.call(this,name,group,document);
		document = $(document);
		var div = document.find('#' + name);
		var hidden = document.find('#form_' + name);
		var response = div.find('textarea');
		//sudo form
		var $testimonial = null;
		var $testimonial_name = null;
		var $testimonial_region = null;
		// form
		var testimonial_hidden = null;
		var testimonial_name_hidden = null;
		var testimonial_region_hidden = null;
		
		var key = group.getToken() + '_' + name;
		var testimonial_key = key + '_testimonial';
		var update = function () {
			var val = response.val();
			hidden.val(val);
			storage.setItem(key,val);
			if (!$testimonial) return;
			// checkbox
			var tval = $testimonial.prop('checked') ? 'true' : '';
			testimonial_hidden.val(tval);
			storage.setItem(testimonial_key,tval);
			// name
			tval = $testimonial_name.val();		
			testimonial_name_hidden.val(tval);
			storage.setItem(testimonial_key+'_name',tval);
			//region
			tval = $testimonial_region.val();		
			testimonial_region_hidden.val(tval);			
			storage.setItem(testimonial_key+'_region',tval);
		};
		response.on('input change',update);
		if (div.hasClass('fanferret-testimonial')) {
			//checkbox
			$testimonial = div.find('input[type="checkbox"]');
			$testimonial.change(update);
			testimonial_hidden = document.find('#form_' + name + '_testimonial');
			
			//name
			$testimonial_name = div.find('.form-testimonial-name');
			$testimonial_name.change(update);
			testimonial_name_hidden = document.find('#form_' + name + '_testimonial_name');
			
			//region
			$testimonial_region = div.find('.form-testimonial-region');
			$testimonial_region.change(update);	
			testimonial_region_hidden = document.find('#form_' + name + '_testimonial_region');
		}
		response.val(storage.getItem(key));
		if ($testimonial) $testimonial.prop('checked',storage.getItem(testimonial_key) === 'true');
		update();
	};
});