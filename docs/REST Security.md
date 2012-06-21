REST Security
===============================

[Reference](http://www.csoonline.com/article/554614/why-rest-security-doesn-t-exist-and-what-to-do-about-it-)

So, what can you do to secure your RESTful APIs? There are many rules to follow, but we'll call out a few:

* Do employ the same security mechanisms for your APIs as any web application your organization deploys. For example, if you are filtering for XSS on the web front-end, you must do it for your APIs, preferably with the same tools.

* Don't roll your own security. Use a framework or existing library that has been peer-reviewed and tested. Developers not familiar with designing secure systems often produce flawed security implementations when they try to do it themselves, and they leave their APIs vulnerable to attack.

* Unless your API is a free, read-only public API, don't use single key-based authentication. It's not enough. Add a password requirement.

* Don't pass unencrypted static keys. If you're using HTTP Basic and sending it across the wire, encrypt it.

* Ideally, use hash-based message authentication code (HMAC) because it's the most secure. (Use SHA-2 and up, avoid SHA & MD5 because of vulnerabilities.)

[Client-side encryption](http://pajhome.org.uk/crypt/md5/instructions.html)

Making Use of HTTP Standards
----------------------------------------------

[A Guide to Designing and Building RESTful Web Services](http://msdn.microsoft.com/en-us/library/dd203052.aspx)

Some common HTTP methods:

	GET		Requests a specific representation of a resource
	PUT		Create or update a resource with the supplied representation
	DELETE	Deletes the specified resource
	POST	Submits data to be processed by the identified resource
	HEAD 	Similar to GET but only retrieves headers and not the body
	OPTIONS	Returns the methods supported by the identified resource

Responding to a HTTP HEAD request in PHP:

	if (stripos($_SERVER['REQUEST_METHOD'], 'HEAD') !== FALSE) {
    	exit();
	}


HMAC Authorization
------------------

Another approach is to avoid both basic and digest authentication and implement a custom authentication scheme around the “Authorization” header. Many of these schemes use a custom Hash Message Authentication Code (HMAC) approach, where the server provides the client with a user id and a secret key through some out-of-band technique (e.g., the service sends the client an e-mail containing the user id and secret key). The client will use the supplied secret key to sign all requests.

For this approach to work, the service must define an algorithm for the client to follow when signing the requests. For example, it must outline how to canonicalize the message and which parts should be included in the HMAC signature along with the secret key. This is important because the client and service must follow the same algorithm for this to work. Once the client has generated the HMAC hash, it can include it in the “Authorization” header along with the user id:

	Authorization: skonnard:uCMfSzkjue+HSDygYB5aEg==

When the service receives this request, it will read the “Authorization” header and split out the user id and hash value. It can find the secret for the supplied user id and perform the same HMAC algorithm on the message. If the computed hash matches the one in the message, we know the client has possession of the shared secret and is a valid user. We also know that no one has tampered with whatever parts of the message were used to compute the HMAC hash (and that could be the entire message). In order to mitigate replay attacks, we can include a timestamp in the message and include it in the hash algorithm. Then the service can reject out-of-date messages or recently seen timestamp values.

The HMAC approach is superior to both basic and digest authentication, especially if the generated secrets are sufficiently long and random, because it doesn’t subject the password to dictionary or brute force attacks. As a result, this technique is quote common in today’s public facing RESTful services.


Setting a Request's Authorization Header with jQuery
----------------------------------------------------

	jQuery.ajax({
		url: ‘/path/to/locked-resource’,
		'beforeSend': function(xhr) {
			xhr.setRequestHeader("Authorization", "{userId}:{hmac}")
		}
	})

Proposal 0.0.3a - jCore Request Signing
=======================================

**Note: SSL is a must-have.**

Example usage:

	jCore.sync({
		// The URI of the resource to sync with.
		uri: '/hello-world',

		// Declare the properties you will use here.
		// If you don't, then Knockout won't apply bindings to them.
		// data-bind="resource.text" or data-bind="resource.arr()[1]"
		init: { text:'', arr:'' }
	}

1. The server gives a userId and a secret passkey to the user (through another service, like email).
2. The user enters these two pieces of information into their client (drag and drop?), which is encrypted with a user-supplied password.
3. The first time the client syncs a locked resource, it prompts the user for their password.
4. The client uses their password to decrypt their userId and secret passkey.
5. Then, for each subsequent request for a locked resource, the client generates an HMAC, using the secret passkey and the body of the request.
6. The client signs the request with their userId and the generated HMAC, and sends it.
7. The server looks up the secret passkey for the specified userId.
8. The server generates the request HMAC and compares it with the one the client provided.
9. If they're the same, the server treats the user, their client, and its request as valid. Otherwise, the server treats them as invalid.

Points of vulnerability:

* The raw passkeys are located at '/jc/passkeys/{userId}'. A database break-in will compromise them.
* An attacker can intercept the userId and secret passkey as it is given to the user. (Email might not be the best way of transferring them, but it's convenient).
* Without SSL, an attacker could capture a request and send it at a later date (a replay attack).
* The user's password can be obtained with a keystroke logger, or by fooling the user into typing it into a spoofed jCore authenticator (users really need to know what site they're at) (also, SSL could help with this by flagging strange sites). Once an attacker has their password, they would need to break in to the client's browser's local storage to get the encrypted userId and secret passkey (from what I've heard, this part wouldn't be difficult). 

Redis Security
--------------

[Reference](https://codeinsecurity.wordpress.com/2012/01/26/redis-security/)

Keep your Redis server secure:

* Add a passphrase to your configuration.
* Use a firewall to block all connections to port 6379, except from trusted IPs.
* If you must operate in an untrusted zone, enforce IP-layer security (e.g. ipsec).
Patch Redis to only bind to a single IP address (or support the votes for this to happen on the Redis github site)
