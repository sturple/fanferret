var fanFerret = (function () {
	var retr = {};
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
	};
	retr.addGroup = function (group) {
		groups.push(group);
		if (groups.length === 1) group.active();
		update_buttons();
	};
	retr.currentGroup = function () {
		return groups[groups.length - 1];
	};
	var set_active = function () {
		$('#survey-carousel').carousel(active);
		groups[active].active();
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
	retr.addQuestion = function (dependency) {
		var args = Array.apply(null,arguments);
		args.splice(0,1);
		var handle = retr.register();
		require([dependency],function (question) {
			handle(function () {
				var g = retr.currentGroup();
				args.push(g,document);
				var q = new (function () {	question.apply(this,args);	})();
				g.addQuestion(q);
			});
		});
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