
jCore.config({
	root: '/jcore',
	xdebug: false
});

var viewModel = {
	resource: jCore.sync({
		// The URI of the resource to sync with.
		uri: '/hello-world',

		// Declare the properties you will use here.
		// If you don't, then Knockout won't apply bindings to them.
		// data-bind="resource.text" or data-bind="resource.arr()[1]"
		init: { text:'', arr:'' }
	}),
	test: jCore.sync({
		uri: '/test',
		init: { foo:'' }
	})
};

viewModel.test.foo.subscribe(function(newValue) {
	console.log(newValue);
});
 
ko.applyBindings(viewModel);




