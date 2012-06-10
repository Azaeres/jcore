/******************************************************************

 **/

jCore = function() {
};
jCore.synchronize = function(uri, init, set) {
	var obj = init();
	obj.synchronize = function() {
		$.ajax(uri, {
			success: function(data) {
				data = JSON.parse(data);
				set(data);
			}
		});

		return this;
	};

	return obj.synchronize();
};






$(document).ready(function() {

	// num - The number to be zero padded, this can be a string or number.
	// count - The total length of the string after it is zero padded, this should be a number.
	function ZeroPad(num, count) {
		var numZeropad = num + '';

		while(numZeropad.length < count) {
			numZeropad = '0' + numZeropad;
		}

		return numZeropad;
	}

	function JCoreViewModel() {
		var self = this;

		self.datetime = jCore.synchronize('/jcore/ajax/', function() {
			var create = function(options) {
				return ko.observable(ZeroPad(options.data, 2));
			};

			var update = function(options) {
				return ZeroPad(options.data, 2);
			};

			var propertyMapping = {
				create: create,
				update: update
			};

			var mapping = {
				'minutes': propertyMapping,
				'seconds': propertyMapping
			};

			return ko.mapping.fromJS({
				month:'',
				mday:'',
				hours:'0',
				minutes:'0',
				seconds:'0'
			}, mapping);
		}, function(data) {
			ko.mapping.fromJS(data, self.datetime);
		});

		setInterval(self.datetime.synchronize, 1000);
	}

	ko.applyBindings(new JCoreViewModel());
});




