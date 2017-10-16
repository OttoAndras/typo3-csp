.. include:: ../Includes.txt

Install
^^^^^^^

You only need to install the extension and include the extension TypoScript template in your site template
where you want to use the content security policy header feature.

.. attention::

   Pls read the **For Integrators / TypoScript / How to migrate JavaScript** section before you activate the extension
   adding its template to your site. If you are not carefully it can disable some of your code on the client side.

.. attention::

   The extension can be disabled setting the **config.csp.enabled** constants to *0*.
   This value is *1* by default, so the extension is enabled.