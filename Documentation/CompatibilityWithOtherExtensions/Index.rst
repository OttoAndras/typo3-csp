.. include:: ../Includes.txt

Compatibility with other extensions
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

sourceopt (v1.0.0)

.. attention::

   You need to rely on the **trimScript = true** default. If you don't trim a script, sourceopt may change it to
   a one line script, and because of that the hash won't match.
   If the script runs through the trimScript method, it will work even with *sourceopt.formatHtml = 1*
   (which means basically a one-line-html output)