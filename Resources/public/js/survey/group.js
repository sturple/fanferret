define(['jquery'],function ($) {
	return function (name, survey, document) {
		document = $(document);
		var questions = [];
		var active = 0;
		var is_active = false;
		var carousel = $('#' + name + '_carousel');
		var self = this;
		this.addQuestion = function (question) {
			questions.push(question);
			if (questions.length === 1) question.active();
			self.update();
		};
		this.currentQuestion = function () {
			return questions[questions.length - 1];
		};
		this.active = function () {
			if (is_active) return;
			is_active = true;
			questions.forEach(function (q) {	q.active();	});
		};
		var is_valid = function () {
			return questions.reduce(function (prev, curr) {
				if (!curr.valid()) return false;
				return prev;
			},true);
		};
		var button = document.find('#' + name + ' .fanferret-group-done > button');
		this.update = function () {
			var enabled = is_valid();
			button.attr('disabled',!enabled);
		};
		button.click(function () {
			if (is_valid()) survey.next();
		});
		this.update();
	};
});