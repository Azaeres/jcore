/*
 *
 */

(function(w, undefined) {

    //	var hashes = [];
    w.jCore = {};
    var config = {
        root: ''
    };

    w.jCore.config = function(options) {
        config = $.extend({}, config, options);
    };



    var syncedResources = {};

    var updateHash = function(uri) {
        var sample = ko.mapping.toJS(syncedResources[uri]);
        var json = JSON.stringify(sample.value);
        var valueWeight = json.length;

        // For sha256, the size of the hash should always be 64 (bytes).
        var hashWeight = 64;
        if (hashWeight < valueWeight) {
            // The hash of the resource value would be lighter than the actual value, so we'll cache it.
            var hash = hex_sha256(json);
            syncedResources[uri].hash = hash;
        //    console.log('JSON: '+json);
        //    console.log('Generated hash: '+hash);
        }
    };

    w.jCore.sync = function(options) {
        var defaults = {
            write: false,
            init: {}
        };

        options = $.extend({}, defaults, options);

        var init = options.init;
        if (typeof init !== 'object')
            init = defaults.init;

        var sendPullRequest = function(uri) {
            var pullRequest = {};
            if (typeof uri === 'undefined') {
                // If a URI is not specified, we'll sync all resources.
                pullRequest = ko.mapping.toJS(syncedResources);

                $.each(pullRequest, function(i, val) {
                    if (typeof val.hash !== 'undefined') {
                        delete val.value;
                    }
                });

            //    console.log(pullRequest);
            }
            else {
                // If a URI is specified, we'll just sync its resource.
                pullRequest[uri] = ko.mapping.toJS(syncedResources[uri]);
                if (typeof pullRequest[uri].hash !== 'undefined') {
                    delete pullRequest[uri].value;
                }
            }

            var json = JSON.stringify(pullRequest);

            // The URI is required.
            // This should be scheduled, or throttled, or something.
            $.ajax({
                url: config.root+'/ajax/',
                type: 'POST',
                data: {
                    pull: json
                },
            //    datatype: "json",
            //    contentType: "application/json charset=utf-8",
                success: function(batchResponse) {
                    batchResponse = JSON.parse(batchResponse);
                //    console.log(batchResponse);

                    if (typeof batchResponse.error === 'undefined') {
                        $.each(batchResponse, function(uri, response) {
                            if (typeof response.error === 'undefined') {
                                var value = JSON.parse(response.value);
                                ko.mapping.fromJS(value, syncedResources[uri].value);

                                updateHash(uri);

                            //    console.log(ko.mapping.toJS(syncedResources));
                            }
                            else if (response.error === 0) {
                                // Server is saying we're in sync.
                                // Nothing needs to be done.
                            //    console.log(ko.mapping.toJS(syncedResources));
                            }
                            else {
                                var err = new Error();
                                err.name = 'Error ' + response.error;
                                err.message = batchResponse.desc;
                                throw (err);
                            }
                        });
                    }
                    else {
                        var err = new Error();
                        err.name = 'Error ' + batchResponse.error;
                        err.message = batchResponse.desc;
                        throw (err);
                    }
                }
            });
        };

        var resource = ko.mapping.fromJS(options.init);
        if (typeof options.uri !== 'undefined') {

            syncedResources[options.uri] = {
                value: resource
            //  hash: ''
            };

            updateHash(options.uri);
            sendPullRequest(options.uri);

            if (Object.keys(syncedResources).length === 1) {
                // We've added the first resource to the sync list, so
                // we should start syncing it.
                setInterval(sendPullRequest, 5000);
            }
        }
        else {
			throw('URI not specified');
        }

        return resource;
    };

    //	var hash = hex_sha256("string");
    //	var hmac = hex_hmac_sha256("key", "data");
    //	console.log(hash);
    //	console.log(hmac);

})(window);