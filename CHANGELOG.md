# Changelog

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

### Miscellaneous 
* Updated Readme to reflect WP version requirement. has_shortcode()
was introduced in version 3.6.

## v0.1

* Initial Release