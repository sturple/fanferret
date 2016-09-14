define(function () {
	return function (threshold, condition) {
		//	TODO: Check condition
		this.check = function (value) {
			if (condition === '>') return value > threshold;
			if (condition === '<') return value < threshold;
			if (condition === '<=') return value <= threshold;
			if (condition === '>=') return value >= threshold;
			if (condition === '=') return value === threshold;
			if (condition === '<>') return value !== threshold;
			//	This shouldn't happen
			return false;
		};
		this.getCondition = function () {
			return condition;
		};
		this.getThreshold = function () {
			return threshold;
		};
	};
});