/*
 * 
 */
 
(function(w, undefined) {

//	var hashes = [];

	w.jCore = function() {
	};
	w.jCore.synchronize = function(uri, create, update) {
		var obj = {};
		if (typeof create === 'function') {
			obj = create();
		}

		obj.synchronize = function() {
			$.ajax(uri, {
				success: function(data) {
					data = JSON.parse(data);

					if (data.error === 0) {
						var value = JSON.parse(data.value);

						if (typeof update === 'function') {
							update(value);
						}
						else {
							obj = value;
						}
					}
					else {
						throw('Error '+data.error+': '+data.desc);
					}
				}
			});

			return this;
		};

		return obj.synchronize();
	};

//	var hash = hex_sha256("string");
//	var hmac = hex_hmac_sha256("key", "data");

//	console.log(hash);
//	console.log(hmac);

	
})(window);
