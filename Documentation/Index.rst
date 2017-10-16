.. Tip - just do it:
      don't use TABs (= \t, tabulators)
      replace each TAB by *three blanks* (enable RegExp for Search and Replace in your IDE)
      set TAB width and indentation to THREE in your IDE
      set 'Use blanks instead of TABs' in your IDE


.. With the following include we import some definition. We do this in each and every file.
   so we can change the definition at a single place. Use the relative path to the Includes.txt file,
   which may look as well like ../../../Includes.txt for a deeply nested source file.

.. include:: Includes.txt


.. Usually we define 'php' as default highlight language in Includes.txt.
   With the following 'highlight' directive we switch to reStructuredText as default highlight language.

.. highlight:: rst


.. The following, first section (= headline) is the 'Document Title'.


======================
My Public Info Project
======================


.. The following is 'field list' which is rendered as a horizontal table.
   Think of it as key-value pairs.


:Rendered:
      |today|

:Classification:
          csp

:Keywords:
          security, frontend, forEditors, forIntegrators, forBeginners, forIntermediates

:Author:
          András Ottó

:Email:
          typo3csp@gmail.com

:Language:
          en





.. attention::

   This extension is currently in an alpha phase. Changes are expected in the later on versions.



---------------------------------------------

*Content Security Policy*

An extension to make your frontend secure through the Content Security Policy header.


Do you have ideas / issues take a look at the external repository:

`GitHub <https://github.com/OttoAndras/typo3-csp>`__

If you want to support this extension be a sponsor!

`Sponsore the extension <https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=X4QJWL4GVE86W>`__


.. toctree::

   GettingStartedWithContentSecurityPolicy/Index
   Concept/Index
   Install/Index
   TypoScript/Index
   ForIntegrators/Index
   ForEditors/Index
   CompatibilityWithOtherExtensions/Index

