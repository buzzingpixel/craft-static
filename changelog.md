# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.1] - 2019-07-22
### Fixed
- Fixed a bug where Craft Static was not compatible with Craft 3.1

## [2.0.0] - 2019-07-20
### New
- Added tracking of future or expiring entries (note that in order to take advantage of this tracking, you'll need to be running a cron job as explained in the readme so that when an entry goes live from future dating or has expired the cache can be cleared)
### Changed
- Updated required PHP version to 7.2 or greater
- Updated required version of CraftCMS to 3.1.0 or greater

## [1.0.4] - 2018-06-24
### Fixed
- Fixed a bug where the plugin may cause Twig to be loaded before it should be (Thanks Brandon Kelly!)
- Fixed a bug where the extension might not be available if the Template Mode ever changes from CP to Site, or vise-versa (again, Thanks Brandon Kelly)
  Fixed `@throws` annotations for some methods (you're welcome, fellow PHPStorm users)
  Fixed an incorrect throw in the StaticHandlerService (must have been an auto-complete flub when it was first written)
  Fixed a deprecated `className()` call in the Twig Extension node handler
### Changed
- Updated readme to include example of using static caching purely from a PHP controller
- Updated copyright year in all applicable places

## [1.0.3] - 2018-02-04
### Fixed
- Removed security advisories requirement to fix weird conflicts

## [1.0.2] - 2018-02-04
### Fixed
- Fixed a PHP error that was occurring when visiting the clear caches section of the CP

## [1.0.1] - 2017-10-25
### New
- Added the console purge utility
### Fixed
- Fixed the plugin name in the composer file
- Fixed the plugin handle

## [1.0.0] - 2017-10-25
### New
- Initial Release
