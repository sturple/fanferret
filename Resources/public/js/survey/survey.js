var fanFerret = (function () {
	var retr = {};
	var token = $('head script[data-fanferret-token]').attr('data-fanferret-token');
	retr.getToken = function () {
		return token;
	};
	var active_key = token + '_active';
	var functions = [];
	var registered = 0;
	var submitted = 0;
	retr.register = function () {
		var id = registered++;
		return function (func) {
			functions[id] = func;
			++submitted;
			if (submitted !== registered) return;
			functions.forEach(function (func) {
				func();
			});
			$('.fanferret-spinner').fadeOut();
			$('#survey-carousel').addClass('slide');
		};
	};
	retr.wait = function (func) {
		var handle = retr.register();
		handle(func);
	};
	var next = null;
	var prev = null;
	$(function () {
		next = $('.fanferret-survey-button-next');
		next.click(function () {	retr.next();	});
		prev = $('.fanferret-survey-button-previous');
		prev.click(function () {	retr.prev();	});
	});
	var groups = [];
	var active = 0;
	var is_last = function () {
		var last = groups.length - 1;
		return active === last;
	};
	var is_first = function () {
		return active === 0;
	};
	var set_next_label = function (label) {
		var node = document.createTextNode(label);
		var e = next[0];
		while (e.firstChild !== null) e.removeChild(e.firstChild);
		e.appendChild(node);
	};
	var update_buttons = function () {
		prev.attr('disabled',active === 0);
		next.attr('disabled',(groups.length !== 0) && !groups[active].valid());
		set_next_label(is_last() ? 'Submit' : 'Next');
		//	This could more elegantly be expressed as a reduce
		//	or left fold, but jQuery doesn't have that
		var valid = true;
		$('#survey-carousel > ol.carousel-indicators > li').each(function (i, li) {
			//	Just in case this is running while we're still
			//	loading all the groups
			if (groups.length <= i) return;
			li = $(li);
			if (!valid) {
				li.addClass('fanferret-disabled');
				return;
			}
			li.removeClass('fanferret-disabled');
			var g = groups[i];
			valid = g.valid();
		});
		localStorage.setItem(active_key,active);
	};
	var old_active = localStorage.getItem(active_key);
	if (old_active !== null) old_active = parseInt(old_active);
	retr.addGroup = function (group) {
		groups.push(group);
		if (groups.length === 1) group.active();
		update_buttons();
		if (old_active === null) return;
		if (groups.length !== (old_active + 1)) return;
		active = old_active;
		
		set_active();
	};
	retr.currentGroup = function () {
		return groups[groups.length - 1];
	};
	var set_active = function () {
		$('#survey-carousel').carousel(active);
		groups[active].active();
		$('html, body').animate({scrollTop: $("#survey-carousel").offset().top}, 1000);
		update_buttons();
	};
	retr.next = function () {
		if (is_last()) {
			document.getElementsByTagName('form')[0].submit();
			return;
		}
		++active;
		set_active();
	};
	retr.prev = function () {
		if (is_first()) return;
		--active;
		set_active();
	};
	var add_to = [];
	retr.addQuestion = function (dependency) {
		var args = Array.apply(null,arguments);
		args.splice(0,1);
		var handle = retr.register();
		require([dependency],function (question) {
			handle(function () {
				var g = retr.currentGroup();
				args.push(g,localStorage,document);
				var q = new (function () {	question.apply(this,args);	})();
				if (add_to.length === 0) g.addQuestion(q);
				else add_to[add_to.length - 1](q);
			});
		});
	};
	retr.pushAddQuestion = function (func) {
		add_to.push(func);
	};
	retr.popAddQuestion = function () {
		add_to.pop();
	};
	retr.update = function () {
		update_buttons();
	};
	$(function () {
		$('#survey-carousel').on('slide.bs.carousel',function (e) {
			var curr = parseInt($(e.relatedTarget).attr('data-index'));
			var retr = groups.slice(0,curr).reduce(function (prev, curr) {
				if (!curr.valid()) return false;
				return prev;
			},true);
			if (!retr) return false;
			active = curr;
			update_buttons();
			return true;
		});
	});
	return retr;
})();
//	This just prevents anything from happening
//	until the document is completely ready
(function () {
	var handle = fanFerret.register();
	$(function () {	handle(function () {	});	});
})();