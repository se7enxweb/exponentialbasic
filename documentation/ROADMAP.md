# eZ Publish Basic - Features Roadmap for 2025 Q3

- Completed: PHP 4/5/7/8 Bugfixes and Testing
- Completed: Feature accuracy bugfixing and testing. Ensuring all features work as expected without negative features.
- In progress: Release Preparation: General refinements in preparation for first release.
- Active Development and Testing is in the 'Release Preparation' task primarily.

# Upcoming Roadmap for 2025 Q4

- Remove userland register globals patch: Refactor framework indexes and all modules to use no longer require our register globals userland features as not secure enough.
  - The goal is to replace all local expected to be set variables to use _REQUEST variables to replace usages of these variables with all more specific scopes _SERVER, _REQUEST, _GET, _POST scopes. 
  - This is a insecure programing technique which eZ publsih 2 suffered in silence for years. We aim to make eZ Publish Baic work without our user land implementation of register globals for a more specifically scoped set of module view code which means greater request / server / installation security
  - Our initial goal is to rewrite the software on a per user module; per module view basis to begin to replace the use of global variables with all more specific scopes _SERVER, _REQUEST, _GET, _POST scopes as required. This will take considerable time, attention to details and extensive testing to replace all the variables in code form successfully without negative features being introduced in the 34 modules included by default in eZ Publish Basic. This means we are addressing the user modules views first. We feel as the admin site and it's module views should be protected by an httpd login prompt and not available to the general internet by default thus more securely protected. 

# Upcoming Roadmap for 2026 Q1

None Scheduled; Please contribute to expanding our simple and easy to develop cms framework e-commerce website development system.

# Suggestions

- Add dependency injection to framework and kernel.
- Add sqlite support to database abstraction and heavily document and test installation and usage to ensure all works as designed.
- Move all uploaded files into var/storage folder organized. Refactor kernel modules which write to disk, read from disk. ezfilemanager, ezimagecatalogue, etc.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony.
- Build a hybrid kernel like eZ Publish Legacy Platform with symfony but with laravel instead of symfony.
