# Plugin Name
Contributors: jmichaelward, 3five
Donate link: 
Tags: patterns library, web components, modular design system
Requires at least: 4.5.3
Tested up to: 4.7.4
Stable tag: 0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

# Description
Clients hire agencies and freelancers to develop fully-custom
themes for their website. Designers create mockups, developers build
these mockups out to spec, and before long, the site is live. What we
sometimes fail to recognize in the process is that, although the theme
is custom, many of the components that comprise it are a reimagining of
a component that was developed in a previous project.

Web users have come to expect certain kinds of functionality on a site
and, as a result, a variety of design patterns have emerged. Developers
have a tendency to recreate these patterns from scratch each time we
take on a new project: image carousels, FAQ accordions, huge banner
areas with headlines and calls to actions, featured links, and so on.

The goal of the Dudley plugin is to simplify the process. Instead of
building *everything* from scratch, what if we reused some of the most
common patterns, so that we could instead focus our efforts on the parts
of a site that are truly custom?

This plugin provides out-of-the-box access to a series of packages for
developers to install. Generally, these packages should contain the
following:
- Code that defines the meta fields for each module (e.g., something like an
ACF JSON file, or a custom PHP file)
- PHP model classes for accessing and outputting that data
- Template view files with HTML markup for each module
- Base CSS and JavaScript that provides the core visual structure and
interactivity required by the module

## Requirements
- Latest stable version of Composer
- PHP 5.4 or greater

## Installation
- Clone this repo into the plugins directory of your WordPress installation `git clone git@bitbucket.org:jmw-patterns/dudley.git`
- Search for packages to install on [Packagist.org](https://packagist.org).
All native Dudley packages are prefaced with `dudley`.
- Configure Dudley's `composer.json` file to require the packages you need.
Here is an example configuration that uses the Social Media Accounts package:
    
```
{
    "name": "dudley/dudley",
    "description": "A WordPress plugin framework for installing reusable site theme modules.",
    "type": "wordpress-plugin",
    "authors": [
        {
            "name": "Jeremy Ward",
            "email": "jeremy@jmichaelward.com"
        },
        {
            "name": "3five, Inc.",
            "email": "wordpress@3five.com"
        }
    ],
    "autoload": {
        "psr-4": {
            "Dudley\\Patterns\\": "src/"
        }
    },
    "minimum-stability": "dev",
    "require": {
        "dudley/base-package": "*",
        "dudley/social-media-accounts: "*"
    }
}
```
- In the command line, from the root of the plugin directory, run `composer validate` to confirm that your `composer.json`
    file is properly formatted.
- Next, run `composer install`. All of your required packages will install into the `vendor` directory.
    
# How To Use Patterns
Example if using Advanced Custom Fields:
During activation, the Dudley plugin copies the ACF field group JSON file associated with each package into
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
template where you need to call it, type in `<?php do_action( 'dudley_acf_social_media_accounts ); ?>`. You should now see
links with text of the names of the networks, and the URLs should direct to the addresses you provided. All that's
left now is to style the module!

_Note:_ If you follow these steps above and don't see any output, confirm the following:
1. The field group has been assigned to the correct template or post type.
2. All of the required fields have been entered into the module.
3. You've called the correct action (these should be documented in the repo for each package, but are also viewable
    in the main PHP class. Each module has a static property called `$action_name` that is used to build the name of 
    the action. All actions are prefixed with `dudley_`).
4. You've called the action in the correct template.

## Location of Patterns files
From the plugin root, files for individual patterns are stored under `/vendor/dudley`. `/vendor/dudley/base-package`
contains all of the base files needed to power each package; every other package will live in a directory with its name. 
Each package contains a JSON file with its default ACF data structure, an `assets/` directory for its SCSS and JavaScript
files, a `views/` directory for its template markup, and a `src/` directory for its logic.


# Writing Custom Patterns

## Classes/Data Model
This plugin supports the ability for devs to write their own custom modules that follow the conventions established
by Dudley. PHP Models for each new module should be saved in their own directory under `src/Pattern`,
and classes should follow the namespace convention of `Dudley\Patterns\Pattern\[ClassName]`.

To function, model classes must have a public static property called `$action_name`, the name of which is typically a
lowercase and underscored version of the class name. In the example, this might be `mega_menu`. The action name 
determines the final name of the action that gets used in the theme templates (e.g., `<?php do_action( 'dudley_acf_mega_menu' ); ?>`),
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

## Importing SCSS and JavaScript
Long-term, we're seeking to have a more integrated solution for pulling in these files. For now, you should be able to 
add them to your theme build tools to import them directly from their package `assets/` directory.

## Overriding markup
For the most part, we shouldn't need to override the markup or data structure for a module, because if something requires
a lot of customization, then it may not be an instance of the pattern we're choosing. That said, if you want to modify
an individual module's markup, you can do so by simply creating a directory in the root of your theme called
`dudley-modules/`, and placing a view file in that directory that has the same name as the name of the file in the
package's `views/` directory. The Dudley plugin exposes a `$module` variable that you can use to access that
module's data.
