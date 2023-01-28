<?php

namespace App\Utils\Enums;

class StatusCode
{

    /*
     *  The HTTP 200 OK success status response code indicates that the request has succeeded.
     *  A 200 response is cacheable by default.
     */
    const OK = 200;

    /*
     *  The HyperText Transfer Protocol (HTTP) 400 Bad Request response status code indicates that the
     *  server cannot or will not process the request due to something that is perceived to be a client
     *  error (for example, malformed request syntax, invalid request message framing, or deceptive request routing).
     */
    const INVALID = 400;

    /*
     *  The HTTP 403 Forbidden response status code indicates that the server understands
     *  the request but refuses to authorize it.
     *  This status is similar to 401, but for the 403 Forbidden status code re-authenticating
     *  makes no difference. The access is permanently forbidden and tied to the application logic,
     *  such as insufficient rights to a resource.
     */
    const FORBIDDEN = 403;

    /*
     *  The HyperText Transfer Protocol (HTTP) 401 Unauthorized response status code indicates that the client
     *  request has not been completed because it lacks valid authentication credentials for the requested resource.
     *  This status code is sent with an HTTP WWW-Authenticate response header that contains information on
     *  how the client can request for the resource again after prompting the user for authentication credentials.
     *  This status code is similar to the 403 Forbidden status code, except that in situations resulting in
     *  this status code, user authentication can allow access to the resource.
     */
    const UNAUTHORIZED = 401;

    /*
     *  The HTTP 404 Not Found response status code indicates that the server cannot find the requested resource.
     *  Links that lead to a 404 page are often called broken or dead links and can be subject to
     */
    const NOTFOUND = 404;

    /*
     *  The HyperText Transfer Protocol (HTTP) 410 Gone client error response code indicates that
     *  access to the target resource is no longer available at the origin server and that this condition
     * is likely to be permanent.
     */
    const GONE = 410;

    const FORCE_MOBILE_UPDATE = 2004;

    /*
     *  The HyperText Transfer Protocol (HTTP) 500 Internal Server Error server error response code indicates
     *  that the server encountered an unexpected condition that prevented it from fulfilling the request.
     */
    const ERROR = 500;
}

