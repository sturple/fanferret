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
		this.valid = function () {
			return questions.reduce(function (prev, curr) {
				if (!curr.valid()) return false;
				return prev;
			},true);
		};
		this.update = survey.update;
		this.getToken = function () {
			return survey.getToken();
		};
		survey.update();
	};
});