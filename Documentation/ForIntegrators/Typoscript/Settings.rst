.. include:: ../../Includes.txt

TypoScript settings
===================


Main settings
^^^^^^^^^^^^^

Properties inside **plugin.tx_csp.settings**


Presets
^^^^^^^

Presets are predefined content security policy settings for a given concrete use case. For instance:
Google maps, Google Analytics, YouTube and so on...

There are some presets delivered with the extension:

- Google maps
- Google Analytics
- YouTube
- Vimeo
- Google fonts
- jQuery

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
            default
            font
            frame
            img
            media
            object
            script
            style
         Complete list with browser support: https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP#Browser_compatibility
         Value is a space separated string of hosts.

   Example
         plugin.tx_csp.settings.presets.jQuery.rules.style = fonts.googleapis.com fonts.gstatic.com

.. ###### END~OF~TABLE ######

Examples
--------

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
^^^^^^^^^^^^^^^^^^

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