# Changelog

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

## v0.1.2

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