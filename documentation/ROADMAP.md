# eZ Publish Basic - Features Roadmap for 2025 Q1

- In Progress: PHP 4 Bugfixes
- In Progress: PHP 5 Bugfixes
- In Progress: PHP 7 Bugfixes
- In Progress: PHP 8 Bugfixes & Testing
- Active Development and Testing is in the 'PHP 8 Bugfixes & Testing' task primarily.

# Upcoming Roadmap for 2025 Q2

- Refactor framework indexes and all modules to use no longer require our register globals userland features as not secure enough.
  - The goal is to replace all local expected to be set variables to use _REQUEST variables to replace usages of these variables with all more specific scopes _SERVER, _REQUEST, _GET, _POST scopes. 
  - This is a insecure programing technique which eZ P ublsih 2 suffered in silence for years. We aim to make eZ Publish Baic work without our user land implementation of register globals for a more specifically scoped set of module view code which means greater request / server / installation security

# Suggestions

- Add dependency injection to framework and kernel.
- Add sqlite support to database abstraction and heavily document and test installation and usage to ensure all works as designed.
- Move all uploaded files into var/storage folder organized. Refactor kernel modules which write to disk, read from disk. ezfilemanager, ezimagecatalogue, etc.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony but with laravel instead of symfony.