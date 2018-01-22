.. include:: ../../Includes.txt

Main settings
=============

Properties inside **plugin.tx_csp.settings**


Presets
-------

Presets are predefined content security policy settings for a given concrete use case. For instance:
Google maps, Google Analytics, YouTube and so on...

There are some presets delivered with the extension:

- Google maps
- Google Analytics
- YouTube
- Vimeo
- Google fonts
- jQuery
- TypeKit

There are constants to change the predefined hosts if necessary but these are more as examples as hard coded rules.

.. note::

    Since TYPO3 supports Vimeo and YouTube videos through the media element. These two are enabled by default, the others
    are deactivated.

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         presets.<preset_name>.enabled

   Data type
         boolean

   Description
         You can enable or disable a preset.

   Example
         plugin.tx_csp.settings.presets.jQuery.enabled = 0


.. container:: table-row

   Property
         presets.<preset_name>.rules.<directive>

   Data type
         string

   Description
         With the option *rules* you can set multiple directive values added to the content security policy header.
         Possible values for directive:
         default,font,frame,img,media,object,script,style
         Complete list with browser support: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP#Browser_compatibility
         Value is a space separated string of hosts.

   Example
         plugin.tx_csp.settings.presets.jQuery.rules.style = fonts.googleapis.com fonts.gstatic.com

.. ###### END~OF~TABLE ######

Examples
~~~~~~~~

::

    plugin.tx_csp.settings.presets {
        googleFonts {
            enabled = 0
            rules {
                style = fonts.googleapis.com fonts.gstatic.com
                font = fonts.googleapis.com fonts.gstatic.com
            }
        }

        jQuery {
            enabled = 1
            rules {
                script = ajax.googleapis.com
            }
        }
    }

Additional domains
------------------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         additionalSources.<directive>.<index>

   Data type
         string

   Description
         General sources set for the whole website. Possible values are domains (ajax.googleapis.com) or the following values
         self => (the website itself, it is already set for the most common directives)
         unsafe-inline => Allows use of inline source elements such as style attribute, onclick, or script tag bodies
         (depends on the context of the source it is applied to) and javascript: URIs
         unsafe-eval => Allows unsafe dynamic code evaluation such as JavaScript eval()
         none => Prevents loading resources from any source.
         See the full list: https://content-security-policy.com/#source_list

.. ###### END~OF~TABLE ######

Reporting
---------

.. ### BEGIN~OF~TABLE ###

.. container:: table-row

   Property
         reportOnly

   Data type
         boolean

   Description
         If this option set, then instead of the normal Content-Security-Policy Header the Content-Security-Policy-Report-Only
         header will be sent with a report-uri directive. This is very useful to test the settings. If reportOnly mode set, then
         any violation will be only listed on the browser's console and sent to an endpoint as a report.
         Default fallback (if report-uri not set) is /typo3conf/ext/csp/Resources/Public/report.php.
         The script will write the browser reports in a file (csp-violations.log) in the typo3temp/logs/ (v7) or
         typo3temp/var/logs/ (v8).


.. container:: table-row

   Property
         report-uri

   Data type
         string

   Description
         An endpoint for the browser reports. It has a default if reportOnly set. (you can use it for an example for own implementation)
         Report-uri can be used without the ReportOnly, in this case the validations will be blocked but also reported.

.. ###### END~OF~TABLE ######