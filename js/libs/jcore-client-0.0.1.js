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
        var json = JSON.stringify(sample);
        var valueWeight = json.length;
        console.log(json+' '+valueWeight);


        var hash = hex_sha256(json);
        var hashWeight = hash.length;
        console.log(hash+' '+hashWeight);
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
            }
            else {
                // If a URI is specified, we'll just sync its resource.
                pullRequest[uri] = ko.mapping.toJS(syncedResources[uri]);
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

                                var hash = hex_sha256(response.value);
                                var hashWeight = hash.length;
                                console.log(response.value+' '+response.value.length);
                                console.log(hash+' '+hashWeight);

                                var value = JSON.parse(response.value);
                                ko.mapping.fromJS(value, syncedResources[uri].value);
                            //    console.log(ko.mapping.toJS(syncedResources));
                            //    ko.mapping.fromJS(value, resource);
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