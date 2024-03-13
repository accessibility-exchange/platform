# Changelog

## [1.3.3](https://github.com/accessibility-exchange/platform/compare/v1.3.2...v1.3.3) (2024-03-07)


### Bug Fixes

* aria-labelledby references (resolves [#2168](https://github.com/accessibility-exchange/platform/issues/2168)) ([#2169](https://github.com/accessibility-exchange/platform/issues/2169)) ([419e1c0](https://github.com/accessibility-exchange/platform/commit/419e1c0d721a2c5337b004240264f1d0c15c88a8))
* passing model into localizedRouteIs method may cause error (resolves [#2166](https://github.com/accessibility-exchange/platform/issues/2166)) ([#2167](https://github.com/accessibility-exchange/platform/issues/2167)) ([4cced2f](https://github.com/accessibility-exchange/platform/commit/4cced2f9021eba786767b45e6781bee63e87a5bc))


### Miscellaneous Chores

* **deps-dev:** bump @commitlint/cli from 18.6.0 to 19.0.3 ([#2161](https://github.com/accessibility-exchange/platform/issues/2161)) ([10933d4](https://github.com/accessibility-exchange/platform/commit/10933d425905562499964605b31c1548b5867cfe))
* **deps-dev:** bump barryvdh/laravel-ide-helper from 2.15.0 to 2.15.1 ([#2156](https://github.com/accessibility-exchange/platform/issues/2156)) ([41db091](https://github.com/accessibility-exchange/platform/commit/41db091b5d50ad949ec2280e1033bf79cfda5fe5))
* **deps-dev:** bump eslint-config-prettier from 9.0.0 to 9.1.0 ([#2160](https://github.com/accessibility-exchange/platform/issues/2160)) ([0e0054e](https://github.com/accessibility-exchange/platform/commit/0e0054ead543e2e7826c2f5f0a7198b4573149e4))
* **deps-dev:** bump laravel/pint from 1.13.11 to 1.14.0 ([#2154](https://github.com/accessibility-exchange/platform/issues/2154)) ([f2da2b9](https://github.com/accessibility-exchange/platform/commit/f2da2b9c3e29e2b5a29b2be8f6c3fbab47835bdf))
* **deps-dev:** bump laravel/sail from 1.27.4 to 1.28.1 ([#2155](https://github.com/accessibility-exchange/platform/issues/2155)) ([30d3b34](https://github.com/accessibility-exchange/platform/commit/30d3b34764aca6a80c721df78109f021e6ecf83b))
* **deps-dev:** bump postcss from 8.4.33 to 8.4.35 ([#2158](https://github.com/accessibility-exchange/platform/issues/2158)) ([d16e9dc](https://github.com/accessibility-exchange/platform/commit/d16e9dc3a728cb0768c5e5094bb58d1d7f3fef85))
* **deps-dev:** bump vite from 3.2.2 to 5.1.5 ([#2162](https://github.com/accessibility-exchange/platform/issues/2162)) ([12bbd25](https://github.com/accessibility-exchange/platform/commit/12bbd25e87adfcfa910a97b45a203cb87ef40e40))
* **deps-dev:** bump vite-plugin-full-reload from 1.0.5 to 1.1.0 ([#2159](https://github.com/accessibility-exchange/platform/issues/2159)) ([439ddbe](https://github.com/accessibility-exchange/platform/commit/439ddbe72fab1ded4a9f29c73f123423bf94202d))
* **deps:** bump codeat3/blade-forkawesome from 1.9.3 to 1.10.0 ([#2153](https://github.com/accessibility-exchange/platform/issues/2153)) ([5fd8ac5](https://github.com/accessibility-exchange/platform/commit/5fd8ac566c91f915bda3e64b8d400fe6c8587a1b))
* **deps:** bump livewire/livewire from 3.4.4 to 3.4.6 ([#2157](https://github.com/accessibility-exchange/platform/issues/2157)) ([eb6ddc7](https://github.com/accessibility-exchange/platform/commit/eb6ddc706c763bb81ac99489cda3cc8de9b7ea17))
* **deps:** bump webfactory/ssh-agent from 0.8.0 to 0.9.0 ([#2163](https://github.com/accessibility-exchange/platform/issues/2163)) ([e835280](https://github.com/accessibility-exchange/platform/commit/e8352801b47e9768dbf6cd5b3b1c1ef7e17f234d))

## [1.3.2](https://github.com/accessibility-exchange/platform/compare/v1.3.1...v1.3.2) (2024-02-21)


### Bug Fixes

* clarify wording in validation messages for language requirements (resolves [#1824](https://github.com/accessibility-exchange/platform/issues/1824)) ([eb97bc8](https://github.com/accessibility-exchange/platform/commit/eb97bc892417bc1ffd08f4b124bca8f9bd59547b))
* validation error when not filling in both French and English translations for FRO 'about' field (resolves [#2148](https://github.com/accessibility-exchange/platform/issues/2148)) ([eb97bc8](https://github.com/accessibility-exchange/platform/commit/eb97bc892417bc1ffd08f4b124bca8f9bd59547b))


### Miscellaneous Chores

* **localization:** update translations ([#2151](https://github.com/accessibility-exchange/platform/issues/2151)) ([4376b26](https://github.com/accessibility-exchange/platform/commit/4376b264e7ee0e327ae48ac8987b4e9ab65a55bc))

## [1.3.1](https://github.com/accessibility-exchange/platform/compare/v1.3.0...v1.3.1) (2024-02-20)


### Bug Fixes

* preferred_contact_language column not found (resolves [#2145](https://github.com/accessibility-exchange/platform/issues/2145)) ([#2146](https://github.com/accessibility-exchange/platform/issues/2146)) ([cabc382](https://github.com/accessibility-exchange/platform/commit/cabc38206a2841fa5b30582193b87257ab6ca417))

## [1.3.0](https://github.com/accessibility-exchange/platform/compare/v1.2.5...v1.3.0) (2024-02-20)


### Features

* add models related to site data to filament admin (resolves [#2080](https://github.com/accessibility-exchange/platform/issues/2080)) ([#2123](https://github.com/accessibility-exchange/platform/issues/2123)) ([5000a6d](https://github.com/accessibility-exchange/platform/commit/5000a6d11838ef6934d6feec422eb864faf1f28e))
* add preferred contact language (resolves [#2053](https://github.com/accessibility-exchange/platform/issues/2053)) ([#2137](https://github.com/accessibility-exchange/platform/issues/2137)) ([a20842c](https://github.com/accessibility-exchange/platform/commit/a20842c99282c97657e91071241ad0b68a254610))
* select cp role by default for individual users (resolves [#2135](https://github.com/accessibility-exchange/platform/issues/2135)) ([#2139](https://github.com/accessibility-exchange/platform/issues/2139)) ([c585c7a](https://github.com/accessibility-exchange/platform/commit/c585c7a2ac36628adfa401f29a048de0b3523a20))


### Bug Fixes

* accessing settings during database reset causes error (resolves [#2106](https://github.com/accessibility-exchange/platform/issues/2106)) ([#2134](https://github.com/accessibility-exchange/platform/issues/2134)) ([700eeca](https://github.com/accessibility-exchange/platform/commit/700eecadb4efc11fa86fd053baa90b09b8705dd3))
* add hearth translations to crowdin (resolves [#2043](https://github.com/accessibility-exchange/platform/issues/2043)) ([#2129](https://github.com/accessibility-exchange/platform/issues/2129)) ([637ba51](https://github.com/accessibility-exchange/platform/commit/637ba51b78e8dbbb54ae2fbe09ad1b0675b46b72))
* admin page markdown editor has contrast issues in dark theme (resolves [#1965](https://github.com/accessibility-exchange/platform/issues/1965)) ([#2095](https://github.com/accessibility-exchange/platform/issues/2095)) ([0258af2](https://github.com/accessibility-exchange/platform/commit/0258af2d24edcf1b758e459525c1ed3e6645ead8))
* dashboard breaks with Invitation notification and site is using a signed language locale (resolves [#2054](https://github.com/accessibility-exchange/platform/issues/2054)) ([#2100](https://github.com/accessibility-exchange/platform/issues/2100)) ([4fa2123](https://github.com/accessibility-exchange/platform/commit/4fa21232eecbf54f5f027ee65c3c61eebc6341a7))
* default selected on ethnoracial identity (resolves [#1692](https://github.com/accessibility-exchange/platform/issues/1692)) ([#2098](https://github.com/accessibility-exchange/platform/issues/2098)) ([ad9a72b](https://github.com/accessibility-exchange/platform/commit/ad9a72be71a75d4f74938c05480043730f8a3578))
* duplicated error messages (resolves [#1827](https://github.com/accessibility-exchange/platform/issues/1827)) ([#2083](https://github.com/accessibility-exchange/platform/issues/2083)) ([3e3a9de](https://github.com/accessibility-exchange/platform/commit/3e3a9ded9f5baa4cac3d9793fb4f865e943aa675))
* incorrectly forcing a language constituency selection for a CC page (resolves [#2031](https://github.com/accessibility-exchange/platform/issues/2031)) ([#2143](https://github.com/accessibility-exchange/platform/issues/2143)) ([8057ba1](https://github.com/accessibility-exchange/platform/commit/8057ba1a5ee3edb84c3d15b6614696c51dbeb0d4))
* individual page validation error message not showing (resolves [#1753](https://github.com/accessibility-exchange/platform/issues/1753)) ([#2099](https://github.com/accessibility-exchange/platform/issues/2099)) ([cb1421c](https://github.com/accessibility-exchange/platform/commit/cb1421ce37f61dd5bfee506dd0a4d9259dd28d11))
* no validation error when accepted formats not entered (resolves [#1830](https://github.com/accessibility-exchange/platform/issues/1830)) ([#2107](https://github.com/accessibility-exchange/platform/issues/2107)) ([c7aa4c3](https://github.com/accessibility-exchange/platform/commit/c7aa4c3ab56878b68b3b91946c33a7c246455e5c))
* participant should be first individual role (resolves [#2064](https://github.com/accessibility-exchange/platform/issues/2064)) ([#2084](https://github.com/accessibility-exchange/platform/issues/2084)) ([e4dbd08](https://github.com/accessibility-exchange/platform/commit/e4dbd08782346bb9ad03e5ac09556450d378b495))
* project and engagement names should not have to be unique (resolves [#2140](https://github.com/accessibility-exchange/platform/issues/2140)) ([#2141](https://github.com/accessibility-exchange/platform/issues/2141)) ([6db4d21](https://github.com/accessibility-exchange/platform/commit/6db4d21c357b878de5784da7dea6295bce27c856))
* quick exit destination (resolves [#2074](https://github.com/accessibility-exchange/platform/issues/2074)) ([#2081](https://github.com/accessibility-exchange/platform/issues/2081)) ([f7aa400](https://github.com/accessibility-exchange/platform/commit/f7aa4002f125c6c632c7e0297be4359dbdcd4a18))
* remove unused CommunicationTool model (resolves [#2109](https://github.com/accessibility-exchange/platform/issues/2109)) ([#2124](https://github.com/accessibility-exchange/platform/issues/2124)) ([7df3a65](https://github.com/accessibility-exchange/platform/commit/7df3a6585a551899416a090c384f0aaf445ad565))
* rename Impacts to Areas of Accessibility Planning (resolves [#2110](https://github.com/accessibility-exchange/platform/issues/2110)) ([#2126](https://github.com/accessibility-exchange/platform/issues/2126)) ([92c64e9](https://github.com/accessibility-exchange/platform/commit/92c64e9ff3426691631671da4239429c178f2cfb))
* update Community Organization related LSQ videos (resolves [#2096](https://github.com/accessibility-exchange/platform/issues/2096)) ([#2097](https://github.com/accessibility-exchange/platform/issues/2097)) ([b4b3a58](https://github.com/accessibility-exchange/platform/commit/b4b3a581bf91cda59d4669bbb40dd4f34020c35f))
* validation error with only other for outcome analysis (resolves [#2057](https://github.com/accessibility-exchange/platform/issues/2057)) ([#2104](https://github.com/accessibility-exchange/platform/issues/2104)) ([0458cf9](https://github.com/accessibility-exchange/platform/commit/0458cf9a0b6877f94a0269155918c9376a0609cf))


### Miscellaneous Chores

* **deps-dev:** bump @commitlint/clil from 17.7.1 to 18.6.0 ([#2127](https://github.com/accessibility-exchange/platform/issues/2127)) ([6a90204](https://github.com/accessibility-exchange/platform/commit/6a902044ce1ec9ebb7a963e39713290ee33195f9))
* **deps-dev:** bump @commitlint/config-conventional from 18.1.0 to 18.6.0 ([#2117](https://github.com/accessibility-exchange/platform/issues/2117)) ([c245e0f](https://github.com/accessibility-exchange/platform/commit/c245e0f7ff30929c379a386de3fcbde12b5205bb))
* **deps-dev:** bump @tailwindcss/forms from 0.5.6 to 0.5.7 ([#2040](https://github.com/accessibility-exchange/platform/issues/2040)) ([27eef78](https://github.com/accessibility-exchange/platform/commit/27eef787089784ed0c96b3643f85a6664cd1464e))
* **deps-dev:** bump eslint-plugin-jsonc from 2.12.0 to 2.13.0 ([#2116](https://github.com/accessibility-exchange/platform/issues/2116)) ([7d8a791](https://github.com/accessibility-exchange/platform/commit/7d8a79159628709e7c10250e22daf1f096c0b80c))
* **deps-dev:** bump laravel-vite-plugin from 0.7.8 to 0.8.1 ([#2037](https://github.com/accessibility-exchange/platform/issues/2037)) ([25257b7](https://github.com/accessibility-exchange/platform/commit/25257b76501048b60966c6f0b09f9aa336a5db4b))
* **deps-dev:** bump laravel/pint from 1.13.5 to 1.13.7 ([#2068](https://github.com/accessibility-exchange/platform/issues/2068)) ([40e771f](https://github.com/accessibility-exchange/platform/commit/40e771fee6c2c1d6d4434f8670112789c2242507))
* **deps-dev:** bump laravel/sail from 1.26.0 to 1.26.3 ([#2066](https://github.com/accessibility-exchange/platform/issues/2066)) ([5e04730](https://github.com/accessibility-exchange/platform/commit/5e04730e6db16a1c4400100a27e6d7939d425775))
* **deps-dev:** bump lint-staged from 14.0.1 to 15.2.0 ([#2089](https://github.com/accessibility-exchange/platform/issues/2089)) ([ccbd4c3](https://github.com/accessibility-exchange/platform/commit/ccbd4c36dd86db20817fd61bb1f63338f27a8b3e))
* **deps-dev:** bump pestphp/pest from 2.23.2 to 2.30.0 ([#2091](https://github.com/accessibility-exchange/platform/issues/2091)) ([460e347](https://github.com/accessibility-exchange/platform/commit/460e347fafd81acfc0399352507ed9e9d2a8dbe0))
* **deps-dev:** bump postcss-custom-media from 9.1.3 to 10.0.2 ([#2120](https://github.com/accessibility-exchange/platform/issues/2120)) ([ff6190e](https://github.com/accessibility-exchange/platform/commit/ff6190e43e215db53fa73d38ace4494bd741c6ef))
* **deps-dev:** bump postcss-import from 15.1.0 to 16.0.0 ([#2119](https://github.com/accessibility-exchange/platform/issues/2119)) ([11f1a37](https://github.com/accessibility-exchange/platform/commit/11f1a37bd1e146e72a713a967ba16071ed64c31f))
* **deps-dev:** bump stylelint and stylelint-config-standard ([#2092](https://github.com/accessibility-exchange/platform/issues/2092)) ([4fcffe2](https://github.com/accessibility-exchange/platform/commit/4fcffe2eb58a450b10addb4bdebd496df6004b8f))
* **deps-dev:** bump stylelint from 16.1.0 to 16.2.0 ([#2118](https://github.com/accessibility-exchange/platform/issues/2118)) ([e98fe37](https://github.com/accessibility-exchange/platform/commit/e98fe3796d306cdc8143f8451177b1fd4e63f530))
* **deps-dev:** bump tailwindcss from 3.3.3 to 3.4.1 ([#2093](https://github.com/accessibility-exchange/platform/issues/2093)) ([1c6f795](https://github.com/accessibility-exchange/platform/commit/1c6f79576d96a4e4bc6df9c9890ce58d52eb9a1c))
* **deps:** bump actions/cache from 3 to 4 ([#2122](https://github.com/accessibility-exchange/platform/issues/2122)) ([63932c7](https://github.com/accessibility-exchange/platform/commit/63932c79b505c1bf197ba106764607ddd3199686))
* **deps:** bump actions/download-artifact from 3 to 4 ([#2085](https://github.com/accessibility-exchange/platform/issues/2085)) ([3f13b8f](https://github.com/accessibility-exchange/platform/commit/3f13b8f31146e6230ef2fa47830224a0e9f45303))
* **deps:** bump actions/upload-artifact from 3 to 4 ([#2086](https://github.com/accessibility-exchange/platform/issues/2086)) ([dce7f0f](https://github.com/accessibility-exchange/platform/commit/dce7f0f9ed348d97a883db3c4d2e54d99513e061))
* **deps:** bump axlon/laravel-postal-code-validation from 3.4.0 to 3.5.0 ([#2111](https://github.com/accessibility-exchange/platform/issues/2111)) ([c4bc7c4](https://github.com/accessibility-exchange/platform/commit/c4bc7c459d9fa2a088b9634385937ffbe58dbc72))
* **deps:** bump codecov/codecov-action from 3 to 4 ([#2121](https://github.com/accessibility-exchange/platform/issues/2121)) ([108eae5](https://github.com/accessibility-exchange/platform/commit/108eae52e69d4a417880719fafe729c37e8c4471))
* **deps:** bump filament/spatie-laravel-settings-plugin from 3.1.37 to 3.2.22 ([#2112](https://github.com/accessibility-exchange/platform/issues/2112)) ([21185a2](https://github.com/accessibility-exchange/platform/commit/21185a251f6cd7a83ff56ab68c9e33c4464ad17c))
* **deps:** bump google-github-actions/release-please-action from 3 to 4 ([#2087](https://github.com/accessibility-exchange/platform/issues/2087)) ([f65570c](https://github.com/accessibility-exchange/platform/commit/f65570c810e486ced471c56b385b496aa874080a))
* **deps:** bump larastan from 2.x-dev to 2.8.1 ([#2103](https://github.com/accessibility-exchange/platform/issues/2103)) ([b4f8c3f](https://github.com/accessibility-exchange/platform/commit/b4f8c3f4848595255c81061007e2427da994de0b))
* **deps:** bump league/flysystem-aws-s3-v3 from 3.16.0 to 3.22.0 ([#2069](https://github.com/accessibility-exchange/platform/issues/2069)) ([b76d89f](https://github.com/accessibility-exchange/platform/commit/b76d89f06a2d259890886f02ef851448250c1edb))
* **deps:** bump livewire/livewire from 3.0.8 to 3.3.5 ([#2094](https://github.com/accessibility-exchange/platform/issues/2094)) ([b8a659d](https://github.com/accessibility-exchange/platform/commit/b8a659d143968d182735a4b2e28ec270bb3cda7e))
* **deps:** bump ralphjsmit/livewire-urls from 1.3.0 to 1.3.1 ([#2115](https://github.com/accessibility-exchange/platform/issues/2115)) ([3d31bfb](https://github.com/accessibility-exchange/platform/commit/3d31bfb6b040bc49da0c2af1da653a26c951a7a0))
* **deps:** bump spatie/laravel-ciphersweet from 1.4.1 to 1.5.0 ([#2113](https://github.com/accessibility-exchange/platform/issues/2113)) ([3716a94](https://github.com/accessibility-exchange/platform/commit/3716a94531cc4e78d0423bd1504d1345a51f25b6))
* **deps:** bump spatie/laravel-ignition from 2.3.1 to 2.4.1 ([#2114](https://github.com/accessibility-exchange/platform/issues/2114)) ([5e6bb4b](https://github.com/accessibility-exchange/platform/commit/5e6bb4b51205b3e498da0acfb00968e521c26bce))
* **localization:** update translations ([#2105](https://github.com/accessibility-exchange/platform/issues/2105)) ([76e21a9](https://github.com/accessibility-exchange/platform/commit/76e21a9dd74969f0630a04564e4a55d743dee80c))
* **localization:** update translations ([#2108](https://github.com/accessibility-exchange/platform/issues/2108)) ([ff83f6a](https://github.com/accessibility-exchange/platform/commit/ff83f6a2c4076df43c78ab313728d3888580b54c))
* **localization:** update translations ([#2125](https://github.com/accessibility-exchange/platform/issues/2125)) ([8cbf01c](https://github.com/accessibility-exchange/platform/commit/8cbf01c0b916fd0fbf48c4a95a270fdbea743ec0))
* **localization:** update translations ([#2138](https://github.com/accessibility-exchange/platform/issues/2138)) ([7dbfcc4](https://github.com/accessibility-exchange/platform/commit/7dbfcc49063e252ccb904ca880e25881de975e9c))
* **localization:** update translations ([#2144](https://github.com/accessibility-exchange/platform/issues/2144)) ([562239c](https://github.com/accessibility-exchange/platform/commit/562239c97d2cfefa8c046ec83e9508b6a9426cd8))
* **localization:** use curly quotes in hearth localizations ([#2132](https://github.com/accessibility-exchange/platform/issues/2132)) ([449dff5](https://github.com/accessibility-exchange/platform/commit/449dff533259570bc3050dfb0d3852e196b63f2a))

## [1.2.5](https://github.com/accessibility-exchange/platform/compare/v1.2.4...v1.2.5) (2023-12-12)


### Bug Fixes

* can't add other engagement languages ([#2027](https://github.com/accessibility-exchange/platform/issues/2027)) ([3220f3f](https://github.com/accessibility-exchange/platform/commit/3220f3f8e7535d93bc9d62e38a925e1a26f0efdf))
* fonts fail to load on www subdomain (resolves [#2006](https://github.com/accessibility-exchange/platform/issues/2006)) ([#2063](https://github.com/accessibility-exchange/platform/issues/2063)) ([290a2b2](https://github.com/accessibility-exchange/platform/commit/290a2b203874473c6ae1906b208ed53cf788e5f5))
* localize attributes properly ([#2016](https://github.com/accessibility-exchange/platform/issues/2016)) ([fbd95be](https://github.com/accessibility-exchange/platform/commit/fbd95be0f5d3e17d4366f72808778875e3937aeb))
* localize notifications properly (resolves [#2015](https://github.com/accessibility-exchange/platform/issues/2015), [#2012](https://github.com/accessibility-exchange/platform/issues/2012)) ([#2050](https://github.com/accessibility-exchange/platform/issues/2050)) ([d19c4ae](https://github.com/accessibility-exchange/platform/commit/d19c4ae692abd17f7bc946cd0de80970c515f0f4))
* misaligned heading area and main area (resolves [#1688](https://github.com/accessibility-exchange/platform/issues/1688)) ([#2060](https://github.com/accessibility-exchange/platform/issues/2060)) ([010d752](https://github.com/accessibility-exchange/platform/commit/010d752af7d57d8ca95d9a1d62a6ab367399acf5))
* model lsq translations don't fallback to fr (resolves [#2014](https://github.com/accessibility-exchange/platform/issues/2014), [#2026](https://github.com/accessibility-exchange/platform/issues/2026)) ([#2027](https://github.com/accessibility-exchange/platform/issues/2027)) ([3220f3f](https://github.com/accessibility-exchange/platform/commit/3220f3f8e7535d93bc9d62e38a925e1a26f0efdf))
* not displaying content in translation ([#2027](https://github.com/accessibility-exchange/platform/issues/2027)) ([3220f3f](https://github.com/accessibility-exchange/platform/commit/3220f3f8e7535d93bc9d62e38a925e1a26f0efdf))
* rendering of language changer links (resolves [#2028](https://github.com/accessibility-exchange/platform/issues/2028), [#2029](https://github.com/accessibility-exchange/platform/issues/2029)) ([#2027](https://github.com/accessibility-exchange/platform/issues/2027)) ([3220f3f](https://github.com/accessibility-exchange/platform/commit/3220f3f8e7535d93bc9d62e38a925e1a26f0efdf))
* response time in other languages not saved ([#2027](https://github.com/accessibility-exchange/platform/issues/2027)) ([3220f3f](https://github.com/accessibility-exchange/platform/commit/3220f3f8e7535d93bc9d62e38a925e1a26f0efdf))
* update LSQ locale's Translatable inputs default to English (resolves [#1715](https://github.com/accessibility-exchange/platform/issues/1715)) ([#2062](https://github.com/accessibility-exchange/platform/issues/2062)) ([ccaa90c](https://github.com/accessibility-exchange/platform/commit/ccaa90c2f0f7ec2c3d5825b3e7a50b1afc7d3600))


### Miscellaneous Chores

* **localization:** update translations ([#2048](https://github.com/accessibility-exchange/platform/issues/2048)) ([780877b](https://github.com/accessibility-exchange/platform/commit/780877b8a1f1a54d438c77ec8f5b1e020a13f397))
* **localization:** update translations ([#2059](https://github.com/accessibility-exchange/platform/issues/2059)) ([cb688c2](https://github.com/accessibility-exchange/platform/commit/cb688c29c18f912f54320f7d16713ba1ad1f1048))
* **localization:** update translations ([#2061](https://github.com/accessibility-exchange/platform/issues/2061)) ([231b745](https://github.com/accessibility-exchange/platform/commit/231b745630aaf4f8b56094e1b874d27ff1467a8c))
* **localization:** update translations ([#2070](https://github.com/accessibility-exchange/platform/issues/2070)) ([9a10088](https://github.com/accessibility-exchange/platform/commit/9a1008853b1fd4336b40c7d87394312913632d70))

## [1.2.4](https://github.com/accessibility-exchange/platform/compare/v1.2.3...v1.2.4) (2023-12-04)


### Bug Fixes

* course seeder not run in production (resolves [#2013](https://github.com/accessibility-exchange/platform/issues/2013)) [#2025](https://github.com/accessibility-exchange/platform/issues/2025) ([59004ee](https://github.com/accessibility-exchange/platform/commit/59004ee2b6c5c72feb6556708c68afa3838f2f6e))
* pagination throws error (resolves [#2045](https://github.com/accessibility-exchange/platform/issues/2045)) [#2046](https://github.com/accessibility-exchange/platform/issues/2046) ([4484241](https://github.com/accessibility-exchange/platform/commit/44842413578b657d9f444195127f9b6c2523bf97))
* resolve unsupported use of trans_choice (resolves [#2022](https://github.com/accessibility-exchange/platform/issues/2022)) [#2023](https://github.com/accessibility-exchange/platform/issues/2023) ([e4ffdc3](https://github.com/accessibility-exchange/platform/commit/e4ffdc330eda0f64b8faaa94c6cec1a97fd99b81))


### Miscellaneous Chores

* **localization:** translate en.json into French, Canada ([5c2328d](https://github.com/accessibility-exchange/platform/commit/5c2328d5d7e52fad3662b4e96902674ea59db8cb))
* **localization:** update translations ([#2041](https://github.com/accessibility-exchange/platform/issues/2041)) ([5c2328d](https://github.com/accessibility-exchange/platform/commit/5c2328d5d7e52fad3662b4e96902674ea59db8cb))

## Changelog

All notable changes to this project will be documented in [GitHub Releases](https://github.com/accessibility-exchange/platform/releases).
