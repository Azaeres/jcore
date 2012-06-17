README
======

Introduction
------------

jCore 0.0.1 - RESTful JSON Network Bindings

Requires the following technologies:
* Redis 2.4.14
* PHP 5.3.10
* JSON 3
* jQuery 1.7.2

Optional:
* Knockout 2.1.0 / Knockout Mapping

Installation
------------

1. Place this project under your web root.

That's it. No, really. 

Example (0.0.1)
-------------------

Note: jCore is designed to "play nice" with Knockout, but is not tightly coupled to it.

	self.resource = jCore.synchronize('/jcore/ajax/?res=/hello-world', function() {
		// Create function
		return ko.observable();
	}, function(value) {
		// Update function
		self.resource(value);
	});

	// Schedules the model to sync regularly.
	setInterval(self.resource.synchronize, 1000);

Discussion
==========

Proposal 0.0.2a - The Server as a Notification Center
------------------------------------------------

The idea here is that syncing gives you an opportunity to provide a callback function
that is called when the jCore client detects a change with that resource.

	self.model = ko.observable();

	jCore.sync({
		uri: '/jcore/ajax/?res=/hello-world',
		notify: function(oldValue, newValue) {
			// This is called when the resource at the specified uri changes.
			// This design further decouples the network bindings from the client-side model.
			
			// It could provide a 'values' array of the snapshots of the resource that 
			//	show the changes that have occurred since the client's version.
			// One advantage with this is that the client shouldn't miss any changes.
			// This would need the server to keep track of every change that occurs with
			//	its resources.
			// This could also result in greatly increasing the amount of transferred data.

			// Otherwise, we could provide just the new value (and the client's old value)
			//	so that the client receives the latest version of the resource.
		},
		get: function() {
			// This gives the sync function a way of getting the client-side resource value.
			return self.model();
		},
		set: function(value) {
			// This gives the sync function a way of setting the client-side resource value.
			self.model(value);
		}
	});

Behind the scenes, the sync function schedules pull requests, checks the response, 
and decides whether to notify the client.

Proposal 0.0.2b - Network Data Bindings
----------------------------------

The idea here is that jCore keeps its own model, but allows for custom client-side bindings.
If we do this, jCore would greatly benefit from requiring Knockout (and possibly its Mapping 
plugin). The problem with this is that it is yet another tight coupling between libraries.
Another advantage with this proposal is that it has a much simpler API than 0.0.2a, and 
could even be faster to develop.

	var self = this;

	// 'sync' returns a Knockout observable and promptly schedules sync requests.
	// All scheduled sync requests are sent out in one batch HTTP request.
	// Every 5 seconds might be reasonable.
	// Note: Knockout has a throttling feature that might be useful here.
	self.resource = jCore.sync({
		uri: '/jcore/ajax/?res=/hello-world',	// The resource id, or URI.
		write: false	// Do we want write access to this resource? Default is false.
	});

	// Since the jCore resource is a Knockout observable, the client can subscribe to 
	// be notified of its changes.
	// This is what jCore does to generate hashes of the resource value.
	self.resource.subscribe(function(newValue) {
	    console.log("The person's new name is " + newValue);
	});

	// Getting the value of a resource.
	var value = self.resource();

	// Setting the value of a resource.
	// The setter for readonly resources is really only used internally by the jCore client 
	//	when it gets responses to its sync requests.
	// If you use it yourself, you'll just change it temporarily (causing your subscribers 
	//	to be notified) until the next sync overwrites your change. Readonly resources do not
	// 	send change requests to the server. 
	// At any rate, so that jCore's internal state doesn't get messed up when you do this, 
	//	jCore itself subscribes to the observable's changes in order to generate hashes when 
	//	it's set.
	self.resource('New value');

Sync requests always contain the URI of the resource to sync, and the client-side resource 
value (or its hash, whichever would be quicker to transfer). Note: that the resource value 
can be empty.

The server compares the request value with the server-side value (if the request has a hash 
instead, it compares the request hash with the server-side hash). 

Note: I expect that as long as the conditions are the same on both the server and the client (same 
hashing algorithm, same resource value), both machines will generate the same hash independently. 
This should be researched and tested, though. If this is true, we can trim the amount of data 
transferred over the network since we don't need to send a value with its hash.

If the request value matches the server value, the server responds with a lightweight "synced" code.
If it doesn't match, the server simply responds with a snapshot of the current server-side value of 
the resource. The client generates a hash of the value (if the hash string would be lighter weight 
than the JSON string of the value), and then uses the Knockout setter to store the new value 
(notifying any of its subscribers).

Proposal 0.0.3 - Write Access to jCore Resources
-------------------------------------------------

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

This means that if the client sets its local copy of the resource, it might go through, or it might 
result in a sync response that overwrites its intended change. At that point, if the client has 
provided a merge function, it is called. Otherwise, the default is to simply keep the server's value
and discard the client's. 

Proposal 0.0.4 - Key-based Security Model
-----------------------------------------







