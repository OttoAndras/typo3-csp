.. include:: ../../Includes.txt

csp:script
==========

With the script ViewHelper you can add javascript code blocks to your fluid template. In the background the script
will be trimmed and compressed and a hash value will be registered in the content security policy header.

.. attention::

   The extension favours **hash** over **nonce** because it checks the content of the script also.

Properties
----------

All the :ref:`universal tag attributes <UniversalTagAttributes>`

All the :ref:`universal form field attributes <UniversalFormFieldAttributes>`

Exclusive properties for the HTML-Element
#########################################

hashMethod
~~~~~~~~~~
:aspect:`Variable type`
    String

:aspect:`Description`
    This option decides which hash algorithm to use. Possible values: sha256, sha512

:aspect:`Default value`
    sha256

:aspect:`Mandatory`
    No

Examples
--------

::

 <csp:script>alert("It works.");</csp:script>

