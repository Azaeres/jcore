/******************************************************************

 **/
 
(function(a) {
	a.jCore = function() {
	};
	a.jCore.synchronize = function(uri, init, set) {
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
})(window);
