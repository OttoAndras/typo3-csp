.. include:: ../../Includes.txt

How to migrate javascript
=========================

There are four different cases to check before you activate the extension.

.. attention::

   Before you activate the extension check you site and set the **script-src** directive correctly

The goal is, that your site is able to run without the unsafe-inline and/or unsafe-eval options for script-src.

1) Your own JavaScript files:

<script src="https//your_domain.com/js/library.js"></script>

These urls considered as "self" running urls. You don't need to do anything,
each scripts referenced under the same domain are enabled.

However, if you have a subdomain which is not the same as the domain of the page, you should enable it explicit:

<script src="https//subdomain.your_domain.com/js/library.js"></script>

plugin.tx_csp.settings.additionalSources.script.1 = subdomain.your_domain.com

(You can add multiple domains at once or with different indexes)

2) External JavaScript files

Do you reference external files like jQuery or Google Analytics?
Then you need to enable these domain for the script-src directive.
Sometimes -like in this case- you need to think about what these scripts are do. Like tracking codes with small images.
You can use the **additionalSources** as by 1) but for these there is an other way to group the settings pro usecase.

Some popular sources are added by default however you need to enable them if you are using them.

- YouTube           (enabled by default)
- Vimeo             (enabled by default)
- GoogleAnalytics   (disabled)
- jQuery            (disabled)
- Google fonts      (disabled)
- Google maps       (disabled)

For the later four cases you can enable what you need with the following TypoScript constants:

plugin.tx_csp.settings.presets.googleAnalytics.enabled = 1
plugin.tx_csp.settings.presets.jQuery.enabled = 1
plugin.tx_csp.settings.presets.googleFonts.enabled = 1
plugin.tx_csp.settings.presets.googleMaps.enabled = 1

*You can define your own presets. Please read the* **Typoscript / Settings** *section.*

3) Inline scripts

If you have <script> </script> declarations in your markup you need to change the generation of them.
3a) If you have a classic TypoScript like this:

::

    page.headerData.10 = TEXT
    page.headerData.10 (
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-XXXXXXX-X']);
      _gaq.push(['_gat._anonymizeIp']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </script>
    )

You can make two easy change to make this declaration valid for your content security policy header.

a) Change the TEXT to a new content object (introduced by this extension): SCRIPT
b) Delete the <script> tag declartion it will be added automatically.

Result:
::

    page.headerData.10 = SCRIPT
    page.headerData.10 (
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-XXXXXXX-X']);
      _gaq.push(['_gat._anonymizeIp']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    )

3b) In your Fluid template

If you have some kind of script in your fluid. You can use the ViewHelper csp:script. The script above would look like
this if you use inside fluid.

::

    {namespace csp=AndrasOtto\Csp\ViewHelpers}

    <csp:script>
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-XXXXXXX-X']);
      _gaq.push(['_gat._anonymizeIp']);
      _gaq.push(['_trackPageview']);
      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();
    </csp:script>

3c) Hook or a DataProcessor or a userFunc

If you generate a script like string in some php code. You can use the getValidScriptTag static method
of the ScriptUtility class.

::

    ScriptUtility::getValidScriptTag($script);


.. note::

   Script should not have <script> and </script> in the string, it should contain only your JavaScript code only.

4) Action declaration (f.i. onclick) or "javascript:" links.

These are also blocked by the content security policy and currently it is hard to enable them one by one.
It is also recommended not to write code like these but use javascript or jQuery and register eventListeners in your
javascript files instead.


.. attention::

    If you can't resolve each of the above points then you can't use a strict CSP header without loosing functionality
    of your website. In this case you can still use CSP if you add:
    **plugin.tx_csp.settings.additionalSources.script.1 = unsafe-inline** to your TypoScript setup.
    However with this option the header does not really useful since a XSS attack is still possible.