/*
 * 
 */
 
(function(w, undefined) {
	w.jCore = function() {
	};
	w.jCore.synchronize = function(uri, create, update) {
		var obj = create();
		obj.synchronize = function() {
			$.ajax(uri, {
				success: function(data) {
					data = JSON.parse(data);
					update(data);
				}
			});

			return this;
		};

		return obj.synchronize();
	};
})(window);
