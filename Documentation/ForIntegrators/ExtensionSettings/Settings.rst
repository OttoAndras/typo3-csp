.. include:: ../../Includes.txt

Main settings
=============


Script method
-------------

There are two basic way to handle JavaScript resources on your page. Hash and Nonce.

Default value is **Hash** and it is useful if you want to have an exact validation on the script. It means it will validate
the whole content. I recommend this way if you have only some inline script blocks.

**Nonce** on the other hand is useful if you have many script parts. It works like a simple entrance control system.
Every script with the given pass could go through. The pass (=nonce) will be generated newly each time the page is being generated.
It is NOT recommended with a long caching lifetime for the page. (It could be bypassed then)
Of course it works ell with the standard one day (or lower) principe.