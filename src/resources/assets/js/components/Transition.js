'use strict';

class Transition {

	constructor(element, name) {
		this.prefix = 'transition__';
		this.state = false;
		this.timeout = false;

		this.element = element;
		this.name = name;

		this.setState('transition');
		this.addState('leave');
		
		this.defaultClasses = element.className;
	}

	enter() {
		let transition = this;

		this.resetState();

		clearTimeout(this.timeout);

		transition.addState('active', function(){
			transition.addState('enter');
		});
	}

	leave() {
		let transition = this;
		let duration = parseFloat(getComputedStyle(this.element)['transitionDuration']) * 1000;

		this.timeout = setTimeout(function(){
			transition.removeState('active');
		}, duration);

		transition.removeState('enter');
	}

	resetState(callback = false) {
		this.element.className = this.defaultClasses;

		if (callback !== false)
			callback();
	}

	removeState(state, callback = false) {
		this.element.classList.remove(this.prefix + this.name + '--' + state);

		if (callback !== false)
			callback();
	}

	setState(state, callback = false) {
		this.removeState(this.state);
		this.addState(state, callback);
	}

	addState(state, callback = false) {
		this.element.classList.add(this.prefix + this.name + '--' + state);
		this.state = state;

		setTimeout(function(){
			if (callback !== false)
				callback();
		}, 10);
	}
}

window.Transition = Transition;




// 'use strict';

// class Transition {

// 	constructor(element, name) {
// 		this.prefix = 'transition__';
// 		this.state = false;
// 		this.defaultClasses = element.className;

// 		this.element = element;
// 		this.name = name;
// 	}

// 	enter() {
// 		let transition = this;

// 		this.resetState();

// 		transition.setState('leave', function(){
// 			transition.addState('enter-active', function(){
// 				transition.addState('enter');
// 			});
// 		});
// 	}

// 	leave() {
// 		let transition = this;
// 		let duration = parseFloat(getComputedStyle(this.element)['transitionDuration']) * 1000;

// 		transition.removeState('enter-active');
// 		transition.setState('leave-active', function(){
// 			transition.removeState('enter');
// 		});

// 		setTimeout(function(){
// 			transition.removeState('leave-active');
// 			transition.removeState('leave');
// 		}, duration);
// 	}

// 	resetState(callback = false) {
// 		this.element.className = this.defaultClasses;

// 		if (callback !== false)
// 			callback();
// 	}

// 	removeState(state, callback = false) {
// 		this.element.classList.remove(this.prefix + this.name + '--' + state);

// 		if (callback !== false)
// 			callback();
// 	}

// 	setState(state, callback = false) {
// 		this.removeState(this.state);
// 		this.addState(state, callback);
// 	}

// 	addState(state, callback = false) {
// 		this.element.classList.add(this.prefix + this.name + '--' + state);
// 		this.state = state;

// 		setTimeout(function(){
// 			if (callback !== false)
// 				callback();
// 		}, 10);
// 	}
// }

// window.Transition = Transition;