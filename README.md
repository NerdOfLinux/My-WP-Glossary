# My WP Glossary
## A maintained and completely free WordPress glossary plugin.

## Installation
As this plugin is not yet on the official WordPress plugin repository, you'll need to manually install the plugin. To do this, download this repository as a ZIP file, then go to the WordPress plugins page, "Add New", then "Upload Plugin".

## Glossary Items
Upon activating the plugin, a glossary post type will be registered. To add new items, simply click the "Glossary Items" menu option, which should be somewhere under Posts.
### Archive
To access your glossary archives, simply go to your website "/glossary". This will generate an alphabetized list of glossary items, which will appear like posts.

## Glossary Index
To create an index page for your glossary, containing all of your items grouped by the first letter of their titles, simply add the following shortcode to a new page:

```wordpress
[my_wp_glossary_index]
```

The page contents are cached for an hour, or until a glossary item is updated. If the glossary index does not automatically update, you can manually clear your transients (specifically, the `my_wp_glossary_index_cache` transient).

## Settings
You'll notice that a "Glossary" sub-menu is added when you hover over the "Settings" menu item. This is where you can configure some plugin behaviors.
### Show Glossary Items on Home Page
When enabled, this will add glossary items to the home page just like posts. They will be ordered by date, like posts, instead of alphabetically.
### Show Glossary Items In Search
When enabled, this will add glossary items to the search results of your site.
### Show Glossary Items In API (Enables Gutenberg)
This will enable visibility of your glossary items in the WordPress REST API. Doing this will also enable Gutenberg support for the glossary post type.
