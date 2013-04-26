Symfony Basic Install
========================

I'm tired of forgetting how to setup symfony2 to create quick projects, so I thought I'd setup this basic thing to get up and running quick.

It contains:

  - Bootstrap and a basic layout.html.twig
  - Angular.js
  - jQuery.js


# Installation
  1. Simply clone this, or copy it, then delete the .git so you can create a new git repo.
  2. Run `php composer.phar update`
  2.  `rm -rf app/cache/*`
  3. `rm -rf app/logs/*`
  4. `sudo chmod +a "_www allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs`
  5. `sudo chmod +a "``whoami`` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs`
  6. If you get any trouble after this, restart apache.