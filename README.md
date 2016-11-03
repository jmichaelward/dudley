# Plugin Name
Contributors: 3five, jmichaelward
Donate link: 
Tags: advanced custom fields, patterns library
Requires at least: 4.5.3
Tested up to: 4.6.1
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

# Description
In the agency world, clients hire companies like 3five to develop fully-custom
themes for their website. Designers create mockups, developers build these mockups
out to spec, and before long, the site is live. What we sometimes fail to recognize in
the process is that, although the theme is custom, many of the components that comprise
it are a reimagining of a component that was developed in a previous project.

Web users have come to expect certain kinds of functionality on a site and, as a 
result, a variety of design patterns have emerged. Agencies like ours have a tendency to 
recreate these patterns from scratch each time we take on a new project: image carousels, 
FAQ accordions, huge banner areas with headlines and calls to actions, featured links, 
and so on.

The goal of the 3five ACF Patterns plugin is to simplify the process. Instead of building *everything* 
from scratch, what if we reused some of the most common patterns, so that we could instead
focus our efforts on the parts of a site that are truly custom?

This plugin provides out-of-the-box access to a series of packages developers can install.
These packages contain JSON files that define data structures using Advanced Custom Fields, 
PHP model classes for accessing and outputting that data, template views with HTML markup for each module, 
and base CSS and JavaScript to provide each module with its core visual appearance and interactivity.

## Requirements
- Latest stable version of Composer
- PHP 5.4 or greater

## Installation
1. Clone this repo into the plugins directory of your WordPress installation `git clone git@bitbucket.org:3five/3five-acf-patterns.git` 
2. Browse the [3five Patterns Library](https://bitbucket.org/account/user/3five/projects/PL) or the [Satis repository](http://packages.3five.com)for possible packages to include.
3. Configure the plugin's `composer.json` file to require the packages you need. Here is an example configuration that uses the Social Media Accounts package:
    
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
    "config": {
        "secure-http": false
    },
    "autoload": {
        "psr-4": {
            "Tfive\\ACF\\": "src/"
        }
    },
    "minimum-stability": "dev",
    "require": {
        "acfpatterns/acfpatterns": "*",
        "acfpatterns/social-media-accounts: "*"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "http://packages.3five.com"
        }
    ]
}
```
4. In the command line, from the root of the plugin directory, run `composer validate` to confirm that your `composer.json`
    file is properly formatted.
5. Next, run `composer install -a`. The `-a` flag generates an autoloader classmap that the plugin uses to identify the
    location of installed classes. This allows the plugin to automatically register which patterns you intend to use,
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
1. The field group has been assigned to the correct template or post type.
2. All of the required fields have been entered into the module.
3. You've called the correct action (these should be documented in the repo for each package, but are also viewable
    in the main PHP class. Each module has a static property called `$action_name` that is used to build the name of 
    the action. All actions are prefixed with `tf_acf_`).
4. You've called the action in the correct template.

## Location of Patterns files
From the plugin root, files for individual patterns are stored under `/vendor/acfpatterns`. `/vendor/acfpatterns/acfpatterns`
contains all of the base files needed to power each package; every other package will live in a directory with its name. 
Each package contains a JSON file with its default ACF data structure, an `assets/` directory for its SCSS and JavaScript
files, a `views/` directory for its template markup, and a `src/` directory for its logic.


# Writing Custom Patterns

## Classes/Data Model
This plugin supports the ability for devs to write their own custom ACF modules that follow the conventions established 
by the ACF Patterns packages. PHP Models for each new module should be saved in their own directory under `src/Pattern`, 
and classes should have the `Tfive\ACF\Pattern\[ClassName]` namespace.

To function, model classes must have a public static property called `$action_name`, the name of which is typically a
lowercase and underscored version of the class name. In the example, this might be `mega_menu`. The action name 
determines the final name of the action that gets used in the theme templates (e.g., `<?php do_action( 'tf_acf_mega_menu' ); ?>`),
and also what the name of the template view file should be (`mega-menu.php`).

## ACF Field Groups
You can create custom ACF Field Groups using whatever method you prefer: GUI or PHP. Packages imported into the plugin rely
on ACF's JSON API for data storage, and allows devs to quickly update those field groups using the GUI interface. That said,
for consistency, you may opt to also use the GUI for new field groups, which will be saved automatically into the `acf-json`
directory and checked in to version control (assuming you're tracking plugins in your project). Traditional PHP field groups 
are fine, as well, and in fact, it may be worthwhile to build support into this plugin for saving those groups so that all
fields are in the same place. 

## Views
All custom template views should be saved in the `views/` directory located in the root of this plugin, and they should be 
given a lowercase, hyphenated name that matches the action given to the PHP model (e.g., if the action name is `mega_menu`, 
the view should be named `mega-menu.php`).

Each template view exposes a `$module` variable that you can reference when creating your new templates. This variable 
refers to the class object of the Pattern you've created, and exposes all of its public methods for use in templating. 
Assuming you've set the requirements for outputting your module, you should have require very few `if` conditions in 
your template, and can instead focus on outputting your content. This makes templates easier to read, and allows you 
to write simple markupsuch as `<h1><?php $module->heading(); ?></h1>`, because all of your validation and data 
sanitization will occur in the data model.

For example templates, please refer to the `views/` directory stored in each package in the 
[Patterns Library Project](https://bitbucket.org/account/user/3five/projects/PL):

### Example:
Bjorn wants to create a custom module called `MegaMenu`. He navigates to `src/Pattern` and creates a directory called
`MegaMenu`. Inside that directory, he creates a new file named `MegaMenu.php` and begins writing his class, also 
named `MegaMenu`. That class has the namespace `Tfive\ACF\Pattern\MegaMenu`, and `extends` one of the base package's 
abstract classes, depending on whether that module is standalone, or if it contains things like repeating elements
(e.g., a standalone module that just has a couple of data fields would extend `AbstractPattern`, whereas a module that 
contains an ACF repeater would extend `AbstractRepeater`, and build an array of the Repeated Item's objects. In this 
particular example, `MegaMenu` might have menu items, so it would extend `AbstractRepeater` and Bjorn would create another
class called `MegaMenu` item with the same `Tfive\ACF\Pattern\MegaMenu` namespace, but which would extend `AbstractPattern`. 
Please refer to the 3five Wiki for more details).

## Importing SCSS and JavaScript
Long-term, we're seeking to have a more integrated solution for pulling in these files. For now, you should be able to 
add them to your theme build tools to import them directly from their package `assets/` directory.

## Overriding markup
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