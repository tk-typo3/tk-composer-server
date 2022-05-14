# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
* Added
  * Button for list module added to directly download the `auth.json` file for any account.
* Changed
  * Codestyle updated.

## [1.2.1] - 2021-11-14
* Fixed
  * Time limit for update request suspended.

## [1.2.0] - 2021-11-12
* Fixed
  * Configuration variable for copyright notice fixed.
  * Return type of ExtconfViewHelper fixed.
* Added
  * Service added to optimize handling of EXTCONF values.
  * Configuration option for cookie name added.
  * Configuration option for cookie lifetime added.
  * Configuration option for brute force sleep duration added.
  * Option added to disable update URI.
  * Monospace font for password field added.
* Changed
  * Configuration variable for update URI moved to _ext_localconf.php_.
  * Composer cache directory set.
  * Default value for frontend disabling added.
  * Update command renamed.
  * Template paths made configurable.
  * Command description updated.
  * ReadMe updated.
  * Code optimized.

## [1.1.1] - 2021-11-07
* Fixed
  * Repository label fixed.

## [1.1.0] - 2021-11-07
* Fixed
  * Evaluation of repository URLs fixed.
* Changed
  * Web frontend listener updated to skip handling if disabled.
  * Frontend completely disabled, if no response handler is found.
  * TCA order of fields `repository_groups` and `accounts` of repositories switched.
  * Repository label updated to display URL and package name.
  * Exclude flags updated to hide inaccessible fields in access management.
  * Hook changed to update non-defined repository package on every save.
  * Package includes changed to reflect the package name instead of the repository UID.
  * ReadMe updated.

## [1.0.0] - 2021-11-06
Initial version committed.
