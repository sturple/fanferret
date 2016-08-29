define(function () {
	return function (name, survey, document) {
		var questions = [];
		var active = 0;
		var is_active = false;
		var carousel = $('#' + name + '_carousel');
		this.addQuestion = function (question) {
			questions.push(question);
			if (questions.length === 1) question.active();
		};
		this.currentQuestion = function () {
			return questions[questions.length - 1];
		};
		this.active = function () {
			if (is_active) return;
			is_active = true;
			if (questions.length === 0) return;
			questions[0].active();
		};
		this.next = function () {
			var last = questions.length - 1;
			if (active === last) {
				survey.next();
				return;
			}
			++active;
			carousel.carousel(active);
			questions[active].active();
		};
	};
});