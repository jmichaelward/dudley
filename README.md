# Plugin Name
Contributors: 3five, jmichaelward
Donate link: 
Tags: advanced custom fields, patterns library
Requires at least: 4.5.3
Tested up to: 4.5.3
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

# Description
In the agency world, we tend to recreate the same types of modular components over and 
over between projects - image carousels, FAQ accordions, huge hero areas with 
headlines and calls to actions - the list goes on.

This plugin serves as a framework for quickly integrating existing 
development patterns into client projects. It enables developers to pull
in JSON files that define data structures in Advanced Custom Fields, and 
combine it with a data model and markup to quickly scaffold out new page 
templates.

For now, this repo contains only the development framework - there are no
sample modules, markup, or ACF data structures. In time, 3five will integrate
the ability for developers to define which modules to import into a project 
via a composer.json file, and provide tools for importing base CSS and 
JavaScript the modules need in order to render and function. 

# Installation Requirements
In order to install the components that power this plugin, you need to have Composer installed. Additionally,
this plugin requires PHP 5.4 or greater to function, as it relies on some modern PHP conventions, such as 
namespaces, short array syntax, and more.

# Installation
1. Clone this repo into the plugins directory of your WordPress installation `git clone git@bitbucket.org:3five/3five-acf-patterns` 
2. Browse the [3five Patterns Library](https://bitbucket.org/account/user/3five/projects/PL) for possible packages to include.
3. Configure the `composer.json` file within the plugin to require those packages (Note: until/unless these packages go public,
    we'll need to define both the name of the package (under "require") and the location of the repository (under 
    "repositories"). Here is an example configuration that uses the Social Media Accounts package:
    
    ```
    {
        "name": "3five/3five-acf-patterns",
        "description": "A WordPress plugin framework for installing reusable site theme modules.",
        "type": "project",
        "authors": [
            {
                "name": "Jeremy Ward",
                "email": "jeremy@3five.com"
            }
        ],
        "minimum-stability": "beta",
        "require": {
            "acfpatterns/acfpatterns": "dev-master",
            "acfpatterns/social-media-accounts": "dev-master"
        },
        "repositories": [
            {
                "type": "git",
                "url": "git@bitbucket.org:3five/3five-acf-patterns-base-package.git"
            },
            {
                "type": "git",
                "url": "git@bitbucket.org:3five/social-media-accounts.git"
            }
        ]
    }
    ```
4. In the command line, from the root of the plugin directory, run `composer validate` to confirm that your `composer.json`
    file is properly formatted.
5. Next, run `composer install -a`. The `-a` flag generates an autoloader classmap that the plugin uses to identify the
    location of installed packages. This allows the plugin to automatically register which Patterns you intend to use,
    and set up the actions you'll need to pull them into your theme templates. 
    
    *Note: if you forgot to type the `-a` flag, you can repair the installation by running `composer dump-autoload --optimize`.
    In fact, this command will need to be run anytime you make a change to your `composer.json` file.*
6. Finally, make sure you already have ACF installed. If you do, you can now activate the 3five ACF Patterns plugin from 
    the WordPress Dashboard or WP-CLI. 
    
# How To Use Patterns
During activation, the 3five ACF Patterns plugin copies the ACF field group JSON file associated with each package into 
the `acf-json` directory. This makes the fields available for sync and allows you to update their individual settings using
the front-end interface. Most patterns are likely assigned to a Page or Post by default, and in general, you'll need to update
them on a per-project basis so they can be used on the actual templates where you need them.

Once configured, you should now be able to populate the field group with data and call that pattern's associated action
within the template to which you've assigned it.

### _Example:_
The Social Media Accounts module connects by default to the Options page that's automatically registered by the plugin.
By default, a few social networks are defined: Facebook, Twitter, LinkedIn, etc. If a client requires a particular network,
you can first sync the field group with the WordPress database, then make changes to field group by navigating to it in the
Dashboard.

Once your changes have been saved, navigate to the options page and enter in some sample data. Finally, in the 
template where you need to call it, type in `<?php do_action( 'tf_acf_social_media_accounts ); ?>`. You should now see
links with text of the names of the networks, and the URLs should direct to the addresses you provided. All that's
left now is to style the module!

_Note:_ If you follow these steps above and don't see any output, confirm the following:
1. The field group has been assigned to the correct template or post type
2. All of the required fields have been entered into the module
3. You've called the correct action (these should be documented in the repo for each package, but are also viewable
    in the main PHP class. Each module has a static property called `$action_name` that is used to build the name of 
    the action. All actions are prefixed with `tf_acf_`).
4. You've called the action in the correct template.

# Location of Patterns files
From the plugin root, files for individual patterns are stored under `/vendor/acfpatterns`. `/vendor/acfpatterns/acfpatterns`
contains all of the base files needed to power each package; every other package will live in a directory with its name. 
Each package contains a JSON file with its default ACF data structure, an `assets/` directory for its SCSS and JavaScript
files, a `views/` directory for its template markup, and a `src/` directory for its logic.

# Importing SCSS and JavaScript
Long-term, we're seeking to have a more integrated solution for pulling in these files. For now, you should be able to 
add them to your theme build tools to import them directly from their package `assets/` directory.

# Overriding markup
For the most part, we shouldn't need to override the markup or data structure for a module, because if something requires
a lot of customization, then it may not be an instance of the pattern we're choosing. That said, if you want to modify
an individual module's markup, you can do so by simply creating a directory in the root of your theme called
`acf-modules/`, and placing a view file in that directory that has the same name as the name of the file in the 
package's `views/` directory. The 3five ACF Patterns plugin exposes a `$module` variable that you can use to access that 
module's data.

# Questions
Any questions about how this works, or suggestions for improvements can be directed to 
Jeremy Ward at [jeremy@3five.com](mailto:jeremy@3five.com). More packages will be added to the Patterns Library
project in the coming weeks.