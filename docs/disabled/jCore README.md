README
======

Introduction
------------

jCore 0.0.2 - RESTful JSON Network Bindings

Depends on, and includes, the following technologies:

* JSON 3
* jQuery 1.7.2
* Knockout 2.1.0
* Knockout Mapping

Requires:

* Redis 2.4.14
* PHP 5.3.10

Installation
------------

1. [Install Redis](http://redis.io/download).
2. Place the jCore project under your web root.

Example (0.0.2)
-------------------

You can place your custom client code in `js/script.js`. Here's an example of what a basic client could look like:

	jCore.config({
		root: '/jcore',
		xdebug: false
	});

	var viewModel = {
		resource: jCore.sync({
			// The URI of the resource to sync with.
			uri: '/hello-world',

			// All the properties you declare here will be data-bound.
			// You can set up Knockout declarative bindings to refer to them in your markup.

			// <div data-bind="resource.text"></div>
			// <div data-bind="resource.arr()[1]"></div>
			init: { text:'', arr:'' }
		}),
		test: jCore.sync({
			uri: '/test',
			init: { foo:'' }
		})
	};

	// Subscribe to changes to a specific observable property.
	viewModel.test.foo.subscribe(function(newValue) {
		console.log(newValue);
	});
	 
 	ko.applyBindings(viewModel);


Discussion
==========

Power to the Clients
--------------------

The "cloud" used to refer to a collection of vague, next-gen networking techniques. Now, it's more clearly defined, but has been defined by organizations with big data centers. They are steering the "cloud" toward being "an online place where your stuff can reside". Using this kind of cloud (now becoming difficult to avoid) provides some benefits, including ubiquitous accessibility, automatic backups, consistency across platforms, automatic software updates, etc. 

Privacy is not one of them.

jCore's cloud philosophy makes the server a simple client hub, and gives power to its clients. A user's data is meant to reside on their own machine, and is accessible to only those they specifically grant. HTML5 makes this possible.

jCore is a server/network abstraction, so that you can focus on giving your client power. A jCore server is part resource repository, part notification center. Sync a client-side model with a server-side resource, and it will refresh its state regularly. Using Knockout, you can subscribe to changes with the model, or you can simply let your data bindings flash the update through to the DOM. 


Network Performance
-------------------

jCore 0.0.2 does not make use of server-sent events (although a future version might). It uses the standard HTTP request-response model, polling the server for changes. Since this activity is common in jCore 0.0.2, making this efficient was a priority. If the resource hasn't changed, checking the server results in around 20 bytes of transferred data for each synced resource, with an average latency of about 16 ms (regardless of the size of the resource). On the other hand, if the resource has changed, the client downloads the entire JSON resource value. So, make changes to resources only when necessary, and only syncronize what you need. 


Proposal 0.0.3a - Write Access to jCore Resources
-------------------------------------------------

Configuring jCore to sync a writeable resource:

	var self = this;

	// This asks jCore for write access to the resource.
	self.resource = jCore.sync({
		uri: '/jcore/ajax/?res=/hello-world',	// The resource id, or URI.
		write: true,	// Do we want write access to this resource? Default is false.
		merge: function(serverValue, clientValue) {
			// This is called when a write conflict is detected.
			// It is optional. By default, the server-side value is retained, and the client
			//	value is discarded.

			// This resends the change we wanted.
			// Right before this is called, jCore received and handled a sync response,
			//	so this might go through this time.
			// If yet another change has been made, and the change request is rejected again,
			//	this merge function will just be called again.
			self.resource(clientValue);
		}
	});

	// On a resource with write access, this causes change requests to be scheduled.
	self.resource('New value');

Change requests contain the uri of the resource to change, the client's old resource value (or
its hash, whichever is lighter-weight), and the client's new resource value.

Similarly to how the server processes sync requests, the server compares the change request value
with the server-side value (comparing hashes if it gets that instead). 

If the request's old value (or hash) does NOT match the server's value (or hash), it responds like it 
would with a sync request: it sends back a snapshot of the current server-side value of the resource.

However, if it matches, the server sets the server-side resource value to the request's new value (and generates its hash), then sends back a lightweight "synced" code.

This means that if the client sets its local copy of the resource, it might go through or it might not. If it doesn't, its merge function is called. If it doesn't provide a merge function, the client-side value is overwritten. 






