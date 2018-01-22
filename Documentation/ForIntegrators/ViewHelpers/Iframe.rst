.. include:: ../../Includes.txt

csp:iframe
==========

This ViewHelper is a wrapper for the Iframe class to enable a usage in fluid templates. If there is a need to
bind an iframe through fluid in a template. This viewhelper extracts the host and adds it to the page's content
security policy header.


Properties
----------

src
~~~
:aspect:`Variable type`
    String

:aspect:`Description`
    The url of the iframe. (The host will be added to the csp header from this url)

:aspect:`Default value`
    Empty

:aspect:`Mandatory`
    Yes

class
~~~~~
:aspect:`Variable type`
    String

:aspect:`Description`
    CSS classes for the 'class' attribute

:aspect:`Default value`
    Empty

:aspect:`Mandatory`
    No

name
~~~~
:aspect:`Variable type`
    String

:aspect:`Description`
    Value for the 'name' attribute

:aspect:`Default value`
    Empty

:aspect:`Mandatory`
    No

width
~~~~~
:aspect:`Variable type`
    Integer

:aspect:`Description`
    A positive integer or zero. This is the width of the iframe element.
    A zero value means that the attribute is not present.

:aspect:`Default value`
    0

:aspect:`Mandatory`
    No

height
~~~~~~
:aspect:`Variable type`
    Integer

:aspect:`Description`
    A positive integer or zero. This is the height of the iframe element.
    A zero value means that the attribute is not present.

:aspect:`Default value`
    0

:aspect:`Mandatory`
    No

allowFullScreen
~~~~~~~~~~~~~~~
:aspect:`Variable type`
    Boolean

:aspect:`Description`
    Allows the iframe to show its content in a full screen mode.

:aspect:`Default value`
    0

:aspect:`Mandatory`
    No

allowPaymentRequest
~~~~~~~~~~~~~~~~~~~
:aspect:`Variable type`
    Boolean

:aspect:`Description`
    This attribute can be set to true if the contents of a cross-origin
    <iframe> should be allowed to invoke the Payment Request API

:aspect:`Default value`
    0

:aspect:`Mandatory`
    No


Examples
--------

::

 <csp:iframe
        src="https://www.foo.bar"
        class="bar foo"
        name="foo-bar"
        width="300"
        height="200"
        allowFullScreen="1"
        allowPaymentRequest="0"
 >

Result:
::

  <iframe src="https://www.foo.bar" name="foo-bar" class="bar foo" width="300" height="200"
  allowfllscreen="allowfullscreen"></iframe>

