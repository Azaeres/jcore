
// num - The number to be zero padded, this can be a string or number.
// count - The total length of the string after it is zero padded, this should be a number.
/*
function ZeroPad(num, count) {
	var numZeropad = num + '';

	while(numZeropad.length < count) {
		numZeropad = '0' + numZeropad;
	}

	return numZeropad;
}
 */


jCore.config({
	root: '/jcore'
});

var viewModel = {
	resource: jCore.sync({
		// The URI of the resource to sync with.
		uri: '/hello-world',

		// All the properties you declare here will be bound.
		// That means you can set up your declarative bindings to refer to them.
		// data-bind="resource.text" or data-bind="resource.arr()[1]"
		init: { text:'', arr:'' }
	}),
	test: jCore.sync({
		uri: '/test',
		init: { foo:'' }
	})
};

ko.applyBindings(viewModel);




