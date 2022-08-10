# Changelog

All notable changes to this project will be documented in this file.

## v1.0.0-alpha3 - 2022-08-08

### What's Changed

* chore: switch from PHP CS Fixer to Laravel Pint by @greatislander in https://github.com/accessibility-exchange/platform/pull/555
* feat: expand language list using umpirsky/language-list by @greatislander in https://github.com/accessibility-exchange/platform/pull/557
* fix: allow editing of Individual page (fix #558) by @greatislander in https://github.com/accessibility-exchange/platform/pull/559
* fix: address issues with working language selection (fix #560) by @greatislander in https://github.com/accessibility-exchange/platform/pull/561
* chore: switch assets to Vite by @greatislander in https://github.com/accessibility-exchange/platform/pull/564
* feat: encrypt individual data (towards #111) by @greatislander in https://github.com/accessibility-exchange/platform/pull/563
* feat: update Individual page creation with new constituency relationships by @greatislander in https://github.com/accessibility-exchange/platform/pull/566
* feat: support viewing individual pages in different languages by @greatislander in https://github.com/accessibility-exchange/platform/pull/571
* feat: settings (resolves #317) by @greatislander in https://github.com/accessibility-exchange/platform/pull/582
* feat: consolidate database migrations (resolves #598) by @greatislander in https://github.com/accessibility-exchange/platform/pull/600
* fix: ensure that empty nested arrays aren't saved (resolves #506) by @greatislander in https://github.com/accessibility-exchange/platform/pull/610
* feat: clean up orphaned CSS by @greatislander in https://github.com/accessibility-exchange/platform/pull/621
* fix: differentiate radio buttons from checkboxes (resolves #634) by @greatislander in https://github.com/accessibility-exchange/platform/pull/635
* fix: resolve various signup issues by @greatislander in https://github.com/accessibility-exchange/platform/pull/636
* fix: add validation message for phone numbers (fix #639) by @greatislander in https://github.com/accessibility-exchange/platform/pull/649
* ci: changed caching declaration (#662) by @marvinroman in https://github.com/accessibility-exchange/platform/pull/686
* fix: prevent participants from creating pages (fix #638) by @greatislander in https://github.com/accessibility-exchange/platform/pull/692
* fix: persist individual meeting types (fix #643) by @greatislander in https://github.com/accessibility-exchange/platform/pull/694
* fix: individual communication and consultation preferences (fix #644) by @greatislander in https://github.com/accessibility-exchange/platform/pull/695
* fix: success message in notification settings is cut off (fix #664) by @greatislander in https://github.com/accessibility-exchange/platform/pull/696
* fix: update success message on save, redirect to published page by @greatislander in https://github.com/accessibility-exchange/platform/pull/698
* fix: remove unused links, add contact info and socials by @greatislander in https://github.com/accessibility-exchange/platform/pull/699
* feat: user projects page (resolves #652, resolves #653, resolves #654, resolves #655) by @greatislander in https://github.com/accessibility-exchange/platform/pull/691
* feat: add footer CTA to browse all projects, add CTA to create project by @greatislander in https://github.com/accessibility-exchange/platform/pull/704
* chore: add seeders for testing (part 1 of 2) by @greatislander in https://github.com/accessibility-exchange/platform/pull/707
* feat: create and edit projects by @greatislander in https://github.com/accessibility-exchange/platform/pull/706
* fix: rename language accordions (resolves #669) by @greatislander in https://github.com/accessibility-exchange/platform/pull/697
* feat: update project views (resolves #319) by @greatislander in https://github.com/accessibility-exchange/platform/pull/708
* fix: resolve 500 error on Regulated Organization projects tab (fix #710) by @greatislander in https://github.com/accessibility-exchange/platform/pull/711
* chore: seed projects by @greatislander in https://github.com/accessibility-exchange/platform/pull/712

### New Contributors

* @marvinroman made their first contribution in [#686](https://github.com/accessibility-exchange/platform/pull/686)

**Full Changelog**: https://github.com/accessibility-exchange/platform/compare/v1.0.0-alpha2...v1.0.0-alpha3

## v1.0.0-alpha2 - 2022-06-23

### What's Changed

* fix: replace Hearth button component with `<button>` by @greatislander in https://github.com/accessibility-exchange/platform/pull/486
* feat: refine creation and editing of regulated organizations by @greatislander in https://github.com/accessibility-exchange/platform/pull/485
* feat: add descriptions to Sector model, add prepareForForm macro by @greatislander in https://github.com/accessibility-exchange/platform/pull/490
* feat: join requests for Organizations and RegulatedOrganizations (resolves #276) by @greatislander in https://github.com/accessibility-exchange/platform/pull/498
* feat: invitations for RegulatedOrganizations (resolves #153) by @greatislander in https://github.com/accessibility-exchange/platform/pull/502
* feat: view RegulatedOrganizations by @greatislander in https://github.com/accessibility-exchange/platform/pull/505
* fix: remove join requests (resolves #511) by @greatislander in https://github.com/accessibility-exchange/platform/pull/515
* feat: drop PHP 8.0 support by @greatislander in https://github.com/accessibility-exchange/platform/pull/523
* feat: add service areas to RegulatedOrganizations (resolves #509) by @greatislander in https://github.com/accessibility-exchange/platform/pull/525
* feat: add RegulatedOrganization to notification list (fix #504) by @greatislander in https://github.com/accessibility-exchange/platform/pull/526
* feat: rename CommunityMember to Individual by @greatislander in https://github.com/accessibility-exchange/platform/pull/527
* feat: create and edit Organizations (resolves #149) by @greatislander in https://github.com/accessibility-exchange/platform/pull/529
* feat: view organizations (resolves #290, resolves #303) by @greatislander in https://github.com/accessibility-exchange/platform/pull/533
* feat: home page and about pages by @greatislander in https://github.com/accessibility-exchange/platform/pull/545

**Full Changelog**: https://github.com/accessibility-exchange/platform/compare/v1.0.0-alpha1...v1.0.0-alpha2

## v1.0.0-alpha1 - 2022-05-02

### What's Changed

Initial alpha release, incorporating the [Community Members](https://github.com/accessibility-exchange/platform/milestone/2) milestone and other preliminary features.

### New Contributors

* @greatislander made their first contribution in https://github.com/accessibility-exchange/platform/pull/1
* [@gtirloni](https://github.com/gtirloni) made their first contribution in https://github.com/accessibility-exchange/platform/pull/6

**Full Changelog**: https://github.com/accessibility-exchange/platform/commits/v1.0.0-alpha1
