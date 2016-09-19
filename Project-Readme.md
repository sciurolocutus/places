The project
===========

Create a one-page web application which uses a public API as its data source, and which does some sort of graphical representation thereof.

In this case, I chose to use Google's geolocation / place lookup services as the public API, and I used d3js to create a graphical display of the data looked up.

Bar Crawl
---------

I took one of the suggested ideas, "list nearby bars". I decided I wanted to modulate on the theme just a little, so I turned it into a "bar crawl".
Bar locations found in the search area would be drawn on an SVG element (representing the map) in a sequence, suggesting an order in which to visit them, in a purported night of drunken revelry (unrealistically, I have the default maximum set to 20, which is of course a ridiculous number if one is to imbibe *anything* at each establishment).
The idea is you type in your zip code, hit a button, and watch the bar crawl sequence appear before your eyes.

What it entails
---------------

#The data
Clearly, we need a way to access this data. I wrote this project in Symfony, and it was pretty simple to make a very thin Controller on top of a data model, to provide access to a list of bars as JSON. This was implemented in `BarServiceController`. And yes, I'm aware that there are slightly more standard ways of creating thin Controllers over Models with more advanced features bundled in, but I chose not to delve into those, as this was intended to be a simple project, and this was all I needed for now. More to learn later, I'm sure.
The model started off with a simple mock, since I wanted to shore up the main page's controller and template, to have something functional to look at, before delving into Google's APIs. I made a `Coordinate` class just to be a POPO (plain-old PHP Object), and then `CoordinateFaker` to give us random generators of `Coordinate`s. The `BarServiceController` is a thin controller on top of a CoordinateFaker.

#note
The requirement stated "one-page". This implementation is clearly not one-page, unless you count "one page that is rendered to the user", and discount the "bars" service as a separate page.

#The page
The page is... well, a simple HTML template in twig, along with a .js file pertinent. It has a div where the SVG gets rendered. It uses d3.js to render a SVG object.
I will bet there are js libraries out there which are great at animation, but I ended up "hacking" together an animation that involves a recursive function which takes coordinates pairwise and draws a circle and line, and then schedules the next recursive invocation (down to a base-case).
Clearly there's a lot for me to learn on the front-end.
