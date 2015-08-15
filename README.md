# HP_PNG

HP_PNG is a $_GET-using, dynamic image creator designed for use with resource bars for PbP games.  
Available variables are as follows:

####'c':
* The color of the HP bar, in RGB hex.  Defaults to _'00dd00'_ (green)

####'c2':
* The secondary bottom color of the HP bar, in RGB hex. Darker version of 'c' by default.

####'bg':
* The color of the background / empty HP field, in RGB hex.  Defaults to _'777777_' (grey)

####'bg2':
* The secondary bottom color of the background, in RGB hex. Darker version of 'bg' by default.

####'ol':
* The color of the outline, in RGB hex. Defaults to _'000000'_.

####'w':
* The width of the image in pixels.  Defaults to _200 pixels_.

####'h':
* The height of the image in pixels.  Defaults to _40 pixels_.

####'mhp':
* The user's maximum HP.  Defaults to _100_.

####'chp':
* The users current HP.  Defaults to _100_.

####'text':
* Display text if exists.  Defaults to not display.

Like all $_GET requests, variables should be appended to the end of the URL.  For example:
* localhost:8080/dev/index.php?c=dd0000&mhp=543&chp=500&text
* This URL will create a red bar with 500 out of 543 HP, with the HP text overlaid on top of the image.
