.. include:: ../../Includes.txt


SCRIPT
^^^^^^

The content object "SCRIPT" can be used to output javascript code direct into markup.

It is basically an extension to the :ref:`TEXT object <https://docs.typo3.org/typo3cms/TyposcriptReference/ContentObjects/Text/Index.html>`.


.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         value

   Data type
         string /:ref:`stdWrap <stdwrap>`

   Description
         Text, which you want to output.


.. container:: table-row

   Property
         *(stdWrap properties...)*

   Data type
         :ref:`->stdWrap <stdwrap>`

   Description
         stdWrap properties are available on the very rootlevel of the
         object. This is non-standard! You should use these stdWrap
         properties consistently to those of the other cObjects by
         accessing them through the property "stdWrap".

.. container:: table-row

   Property
         hashMethod

   Data type
         string

   Description
         This method will be used to calculate a hash for the content security policy header. Possible values are
         sha256 or sha512

.. container:: table-row

   Property
         trimScript

   Data type
         boolean

   Description
         It is "1" by default, but can be turned off if it is explicit needed. (It is not recommended)

.. ###### END~OF~TABLE ######


Examples:
"""""""""

::

   10 = SCRIPT
   10.value = alert("It works!");

The above example results:

::

   <script>alert("It works!");</script>

