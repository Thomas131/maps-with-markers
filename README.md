How to install?:
================

At first go to [https://console.developers.google.com/project](https://console.developers.google.com/project) and create a project. Goto "APIs & auth" and there to APIs. Activate "Google Maps JavaScript API v3". If you want, you can disable the other APIs. Then go to Credentials (also in "APIs & auth") and click there at "Create new Keypair" (in Public API access). Then it asks, which key you want to generate. Choose "Browser key". Then you have to insert the urls, that should be allowed to use the API-Key. For my testing-website, I inserted for example:  
```
thomas13.aquarius.uberspace.de/*  
*.thomas13.aquarius.uberspace.de/*
```  
Then Copy&Paste the API-Key to ``$API_KEY`` the config.php.

If you want to use the default login method, go to shagen.php and insert your password, you want to use. Insert the output string to ``$PW`` in config.php. Then go to index.php and you should be logged in (You should see buttons on the right_top side of the map).

Then open Google Maps and search the region, that sould be visable on the map. The URL will show you the x- and y-Position and the zoom for the config.php. For example:
__https://www.google.at/maps/@50,11.5,6z?hl=de__
results into  
```
$CENTER_X = 11.5;  
$CENTER_Y = 50;  
$DEFAULT_ZOOM = 6;  
```

With ``$DEFAULT_ZOOM_ON_SEARCH`` you can config, how much to zoom on searching a place. Try searching a place on Google Maps and copy the zoom-value from the URL.

In ``$CATEGORIES`` you can define, which Categories you want to use. It is written as a JSON-String. For example:
```
$CATEGORIES = '["First Category", "Secound Category", "Third Category"]';
```  
``$FILE_EXT`` is the used File extension. I recommend .png with alpha-chanal. __Don't use .svg! They aren't supported in a lot of major browsers!__

You can change the login-method, if you want. In this case (bool)``$IS_ADMIN`` should be set to true or false.