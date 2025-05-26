# eZ Publish Basic - Features Roadmap for 2025 Q3

- Completed: PHP 4/5/7/8 Bugfixes and Testing
- Completed: Feature accuracy bugfixing and testing. Ensuring all features work as expected without negative features.
- In progress: Release Preparation: General refinements in preparation for first release.
- Active Development and Testing is in the 'Release Preparation' task primarily.

# Upcoming Roadmap for 2025 Q4

- Remove userland register globals patch: Refactor framework indexes and all modules to use no longer require our register globals userland features as not secure enough.
  - The goal is to replace all local expected to be set variables to use _REQUEST variables to replace usages of these variables with all more specific scopes _SERVER, _REQUEST, _GET, _POST scopes. 
  - This is a insecure programing technique which eZ P ublsih 2 suffered in silence for years. We aim to make eZ Publish Baic work without our user land implementation of register globals for a more specifically scoped set of module view code which means greater request / server / installation security

# Upcoming Roadmap for 2026 Q1

None Scheduled; Please contribute to expanding our simple and easy to develop cms framework e-commerce website development system.

# Suggestions

- Add dependency injection to framework and kernel.
- Add sqlite support to database abstraction and heavily document and test installation and usage to ensure all works as designed.
- Move all uploaded files into var/storage folder organized. Refactor kernel modules which write to disk, read from disk. ezfilemanager, ezimagecatalogue, etc.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony but with laravel instead of symfony.
