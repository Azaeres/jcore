README
======

Introduction
------------

The jCore resource syncronization system.

Makes use of the following technologies:
* Redis 2.4.14
* PHP 5.3.10
* JSON 3
* jQuery 1.7.2
* Knockout 2.1.0 / Knockout Mapping

Installation
------------

1. Place this project in your web root.

That's it. No, really. 

Example (prototype)
-------------------

	var model = jCore.synchronize('/ajax/?res=10', function() {
		// Create function
	}, function() {
		// Update function
	});

	// Schedules the model to sync regularly.
	setInterval(model.synchronize, 1000);

