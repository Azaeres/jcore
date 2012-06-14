
// num - The number to be zero padded, this can be a string or number.
// count - The total length of the string after it is zero padded, this should be a number.
function ZeroPad(num, count) {
	var numZeropad = num + '';

	while(numZeropad.length < count) {
		numZeropad = '0' + numZeropad;
	}

	return numZeropad;
}


$(document).ready(function() {

	function JCoreViewModel() {
		var self = this;

		var resourceUri = '/jcore/ajax/?res=/hello-world';
		self.resource = jCore.synchronize(resourceUri, function() {
			// Create function
			return ko.observable();
		}, function(value) {
			// Update function
			self.resource(value.arr[1]);
		});

	//	setInterval(self.resource.synchronize, 1000);
	}

	ko.applyBindings(new JCoreViewModel());
});




