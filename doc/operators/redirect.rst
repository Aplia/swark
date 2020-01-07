redirect
--------

Summary
~~~~~~~
Stops execution and redirects to the given URL. The URL can be specified
as a relative path in which the siteaccess and path-prefix is prefixed, or
as an absolute URL. In addition the url format can be tweaked using
parameter `type`. Redirection is done using the HTTP status code 302
(temporary redirect) by default but can be changed using parameter `status`.

Usage
~~~~~
::

    redirect( url [, status[, type]] )

or

::

    url|redirect

Parameters
~~~~~~~~~~
    =========== =============================================================== ======== =======
    Name        Description                                                     Required Default
    =========== =============================================================== ======== =======
    url         Address to redirect to                                          Yes
    status      HTTP status code                                                No       302
    type        Control generated url                                           No       
    =========== =============================================================== ======== =======

Examples
~~~~~~~~
::

    {redirect( 'book/article' )}

Stops execution and redirects to the relative path. The current eZ publish
siteaccess or other path-prefix is prefixed to the path. e.g. if there is no
prefix or siteaccess it redirects to `/book/article`, alternatively if the
siteaccess is `/en` it redirects to `/en/book/article`.

::

    {'http://www.seeds.no'|redirect}

Stops execution and redirects to absolute URL http://www.seeds.no.

::

    {redirect( $node.parent.url, 301 )}

Stops execution and redirects to the parent node of $node, returning status
code 301 (permanent redirection).

::

    {redirect( $node.parent.url, , 'abs')}

Stops execution and redirects to the parent node but force using an absolute
url. This will detect http vs https, even behind a proxy, and add the proper
scheme in front of the host.

::

    {redirect( '/about', , 'root')}

Stops execution and redirects using a relative url but without prefixing the
eZ publish siteaccess to the path. e.g. if the current siteaccess is '/en'
the resulting url will be `/about`.

::

    {redirect( '/about', , 'absroot')}

A combination of type `root` and `abs`, ie. it creates an absolute url from
the relative url but without prefixing the path element with the current
siteaccess. If the siteaccess is `/en` and the url for the current page is
`https://example.org/en/content/view` then the resulting url is
`https://example.org/about`.

::

    {redirect( '//example.org' )}

Redirects to another host and path but using the same scheme as the current
site. e.g. if the current url is `https://somwhere.example.org/about` will
redirect to `https://example.org`
