# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.2] - 2025-Dec-7

### Fixed

- Corrected server and client URLs.

## [1.3.1] - 2025-Dec-9

### Changed

- Updated supported WordPress version to 6.9

- Broken link in plugin's page when display admin pages option is false

### Fixed

- Double confirmation on saving access token

## [1.3.0] - 2025-Nov-9

### Fixed

- When deleting the plugin the client secret option's handle was wrong

## [1.2.1] - 2025-June-4

### Added

- Added option to hide Blogify Menu Admin Pages via Settings Page

### Fixed

- Deleting the plugin was failing due to undefined constant
- Client Secret was null for new installations when registering route to Blogify

## [1.2.0] - 2025-May-24

### Changed

- Project Directory structure
- Registration of various hooks

### Added

- Added REST endpoint for returning author and categories
- A reusable function for creating posts

### Removed

- Post Type from Publish Dialog in All Blogs Page

## [1.1.3] - 2025-Apr-20

### Added

- Added REST endpoint for adding images to Media Library  

### Fixed

- Set blog image as thumbanil when publishing from within the plugin
- Set meta tags and meta description when publishing from within the plugin


## [1.1.2] - 2024-Nov-09

### Added

- Remove blogify_client_secret upon uninstallation

### Fixed

- Unsubscribe site_url upon deactivation


## [1.1.1] - 2024-Oct-17

### Fixed

- Undefined array key warnings for blog_id and blog['image'] in All Blogs Page
- Use system specific max number for random_int()

## [1.1.0] - 2024-Oct-17

### Added

- Added Publish button in All Blogs Page 

### Removed

- Dashboard Page

## [1.0.0] - 2024-Jul-10

### Added

- REST API for publishing blogs from Blogify.ai.
- Dashboard Page
- Subscription Page
- All Blogs Page with pagination
- Access Token Based Authorization