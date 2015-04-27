# Changelog

### v0.6.0

#### New Features

* Added ability to set whether or not to include the service fee on an individual price.

#### Bug Fixes
* Fixed some input sanitization.
* Fixed bug where the price name was undefined in the hidden prices section of the event list options.

### v0.5.0

#### New Features
* Added ability to set a price's interval.

#### Bug Fixes
* Fixed bug that made the price's quantity wacky when changing the max quantity or the interval.

### v0.4.1

#### Bug Fixes
* Fixed bug where event list display options weren't being applied if the cache wasn't enabled. #fixes 10
* Added various empty index.php files to prevent directory listings on misconfigured servers.


### v0.4.0

#### New Features
* Added ability to change the text of the calendar's event list text.
* Added ability to change the text of the calendar's buy tickets links.

#### Improvements

* Updated FAQ.
* Added some debug information gathering to the help tab.
* Major reorganization of code base.

#### Bug Fixes

* Fixed link to the setup wizard on the help tab.
* Fixed bug where the Welcome message wasn't being displayed properly when the
data wasn't cached.
* Fixed bug where custom date format wasn't being displayed properly on the calendar. Fixes #11

### v0.3.1

#### Bug Fixes

* Fixed bug where events without dates would throw errors.

### v0.3.0

#### New Features

* Added ability to include service fee in price value.
* Added ability to set a max quantity sold per price.
* Added ability to sort events chronologically or reverse chronologically.

#### Bug Fixes

* Fixed issue where prices were not hidden if the data was not cached.

### v0.2

#### New Features
* Users can now add custom CSS for the event listing and calendar
widget/shortcode rules by going to new "Appearance" tab in the
plugin settings.

* Users can now manually hide prices that they do not wish to make
public.
    * **Hiding Prices**: When logged into Wordpress as an admin,
    view the post that contains the event listing. You'll see a
    (HIDE PRICE) button.
    Clicking that will prevent the price from being displayed to
    anyone who isn't an admin.

    * **Showing Prices**: After hiding a price, the hide price link
    will become a (DISPLAY PRICE) link.
    You can also go to the plugin's options page and go to the
    "Password Price Settings" tab and choose to display them
    there.

#### Improvements
* Event listing now properly displays a single event when `event_id`
is passed in the shortcode without clearing the cache.

#### Bug Fixes
* Fixed issue with calendar not loading properly if using as a widget.
* Fixed issue where deactivated prices were showing. #6
* Fixed issue where the error field in the event list template was not
displaying properly.
* Fixed issue where the selectedDate in the ractive instance was not being
set upon data load.

#### Misc
* Updated BptAPI library to latest version.
* Updated Ractive to version 0.6

### v0.1.31

#### Bug Fix
* Fixed rogue console.log();

* Fixed issue where the default title "New Title" was being displayed
above shortcode calendars.

#### New Option in Settings
* Added Calendar Options settings. You can now set the "Show upcoming
Events in Calendar" option. When enabled, this will show the next 5
events in the event listing if the clicked day does not have any events.
When switching months, it will also show all of the upcoming events in
that month.

#### Improvements
* Refactored Calendar Javascript

#### Bug Fixes
* Fixed issue where shortcodes weren't being placed in the proper place.
* Fixed various typos and grammatical errors.

### v0.1.2

### Improvements
* Added proper uninstall functions.

### Bug Fixes
* Fixed issue where event calendar wasn't being displayed if a widget
wasn't in place.
* Fixed issue where the cache wasn't being deleted properly.

### Miscellaneous
* Updated header in main plugin file.

## v0.1.1
### Improvements
* Users can now list multiple events in the same shortcode event_id
attribute.

### Bug Fixes
* Added 100% width to the pricing table on the default event list theme.
* Fixed issue with PHP versions below 5.3. Changed short array syntax
to array()
* Added proper checks for various shortcode spelling.
* Updated BptAPI library to latest version which fixes a bug where
API errors weren't being returned as an array.
* Fixed bug where event list is displayed only when there is no error.
* Fixed bug where using the event ID of an event not belonging to the
default producer would call the BPT API using the default client ID.
* Fixed issue with loading gif not displaying.
* Fixed issue where data from the API was returned too early.

### Miscellaneous
* Updated Readme to reflect WP version requirement. has_shortcode()
was introduced in version 3.6.

## v0.1

* Initial Release
