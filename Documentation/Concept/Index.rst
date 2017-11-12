.. include:: ../Includes.txt

Concept
=======

Secure the content in a CMS is not as trivial as on a static website. Different resources could be added through editors
without any interaction of the development others are referenced in the developer phase.

The general idea is that we are exactly know our content after a Page successfully generated. However parsing the result
is not a good way (it could lead to a whitelisted XSS) we need to register sources and generate the header after the
rendering is done.

.. attention::

   The extension uses the hook **contentPostProc-all**.
   In the registered function the whole Content Security Policy header will be generated,
   it **replaces** any previously defined value and if the cache enabled it will cache
   the header with a tag "*pageId_<uid>*" so if the page are modified the cache entry will be flushed.


How to register the used resources?
-----------------------------------

The first thing that we need to make a difference between two art of content. Dynamic and static.
(Some of the dynamic could be added as part of a template to of course, but for this case they are only
important because they could be changed by a user.)

**Dynamic / (Added by editors)**
IFrame
Videos / Audios
Images
Html

**Static / (Added in development):**
CSS (files and inline)
JavaScript (files and inline scripts)
Fonts

Here are some basic rules how the extension handles these resources:

   - With the scripts it is easier to deal with. **CSS**, **JavaScript files** and **fonts** are easy to whitelist in the general settings for the Content Security Policy header.
   - **Inline CSS** is enabled by default because a lot of JQuery Plugins are using dynamic styles to handle positioning elements and dynamically show / hide them.
   - **Inline JavaScript** (script tag) needs a special handling because the script block needs to be witelisted with a hash. Because of that there is a new ContentObject introduced "SCRIPT" to add a script through TypoScript and a ViewHelper for Fluid to enable script addition in the Templates.
   - **Images** seen always from the FAL so if there is a CDN or external storage it should be whitelisted manually in the general settings for the Content Security Policy header. See ToDo: Link to TypoScript.
   - **Videos / Audios** this is just like te images with the addition that vimeo and YouTube presetting are added and activated by default since TYPO3 has a default handling for both.
   - **IFrame** is a special and not as easy part. This is of course one of the most unsecured element can be added from an editor. However sometimes it is necessary and the editor trusts the source of the iframe. For this cases a new content element "Iframe" is introduced.
   - **Html** means the Html content element and as it can handle iframes, scripts and any kind of elements and its parsing not as easy and effective, the extension **deactivates the element**.