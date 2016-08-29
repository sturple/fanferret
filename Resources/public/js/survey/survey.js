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
	var groups = [];
	var active = 0;
	retr.addGroup = function (group) {
		groups.push(group);
		if (groups.length === 1) group.active();
	};
	retr.currentGroup = function () {
		return groups[groups.length - 1];
	};
	retr.next = function () {
		var last = groups.length - 1;
		if (active === last) {
			document.getElementsByTagName('form')[0].submit();
			return;
		}
		++active;
		$('#survey_carousel').carousel(active);
		groups[active].active();
	};
	return retr;
})();
//	This just prevents anything from happening
//	until the document is completely ready
(function () {
	var handle = fanFerret.register();
	$(function () {	handle(function () {	});	});
})();