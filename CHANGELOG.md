# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

## [Unreleased]

## 0.11.0 - 2026-09-16

### Added

- Admin settings page (Settings ▸ Administration ▸ Security) to configure
  `maxAge`, `includeSubDomains` and `preload` without editing `config.php`
- `config.php` system values still take precedence over the GUI, so existing
  sysadmin setups keep working unchanged

### Changed

- Renamed the app from "HSTS Header" to **HSTS Manager**
  (app id `hsts` → `hstsmanager`, namespace `OCA\Hsts` → `OCA\HstsManager`)
- Skip execution on CLI (`occ` commands) to avoid interfering with them
- Set the HSTS header via native `header()` instead of the injected
  `IOutput` service
- Centralized all configuration read/write logic in a single
  `HstsConfigService`
- Raised the supported Nextcloud version range to 20–35
- This is a fork of "HSTS Header" by Klaus Herberth (sualko), now
  maintained by Andreas Weller, DF1PAW <weller@andreas-weller.de>

### Fixed

- Archive filename mismatch between the build and publish release scripts
