<div class="stack">
    <div class="box stack">
        <h3>{{ __('Meeting information') }}</h3>
        <h4>{{ __('Date and time') }}</h4>
        <p>{{ Carbon\Carbon::now()->locale(locale())->isoFormat('LLLL') }}</p>
        <h4>{{ __('Location') }}</h4>
        <p>{{ __('Virtual meeting') }}: <a href="https://zoom.us/1234567">https://zoom.us/1234567</a></p>
    </div>
    <div class="box stack">
        <h3>{{ __('Access and accomodations') }}</h3>
        <h4>{{ __('For communication') }}</h4>
        <ul role="list">
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{{ __('Large text') }}</strong> – {{ __('provided') }}</li>
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{{ __('Plain language') }}</strong> – {{ __('provided') }}</li>
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{{ __('Materials in advance') }}</strong> – {{ __('provided') }}</li>
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{{ __('Materials translated into :language', ['language' => get_locale_name('fr', locale())]) }}</strong> – {{ __('provided') }}</li>
        </ul>
        <h4>{{ __('For meetings') }}</h4>
        <ul role="list">
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{!! __('<abbr title="American Sign Language">ASL</abbr> interpretation') !!}</strong> – {{ __('provided') }}</li>
            <li><x-heroicon-o-x-circle width="24" height="24" aria-hidden="true" /> <strong><abbr title="{{ __('Communication Access Realtime Translation') }}">CART</abbr></strong> – {{ __('not provided') }}</li>
            <li><x-heroicon-o-check-circle width="24" height="24" aria-hidden="true" /> <strong>{{ __('Automatic captioning') }}</strong> – {{ __('provided') }}</li>
            <li><x-heroicon-o-dots-circle-horizontal width="24" height="24" aria-hidden="true" /> <strong>{{ __('Language interpretation for :language', ['language' => get_locale_name('fr', locale())]) }}</strong> – {{ __('booking in progress') }}</li>
        </ul>
    </div>
    <div class="box stack">
        <h3>{{ __('Materials') }}</h3>
        <h4>{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('en', locale())]) }}</h4>
        <p>{{ __('Description of this document.') }}</p>
        <p>
            <a class="button" href="#">
                {{ __('View') }} <span class="visually-hidden">{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('en', locale())]) }}</span>
            </a> <a class="button" href="#">
                {{ __('Download') }} <span class="visually-hidden">{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('en', locale())]) }}</span>
            </a>
        </p>
        <h4>{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('fr', locale())]) }}</h4>
        <p>{{ __('Description of this document.') }}</p>
        <p>
            <a class="button" href="#">
                {{ __('View') }} <span class="visually-hidden">{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('fr', locale())]) }}</span>
            </a> <a class="button" href="#">
                {{ __('Download') }} <span class="visually-hidden">{{ __('Summary of feedback (draft) – :language', ['language' => get_locale_name('fr', locale())]) }}</span>
            </a>
        </p>
        <h4>{{ __('Draft accessibility report – :language', ['language' => get_locale_name('en', locale())]) }}</h4>
        <p>{{ __('Description of this document.') }}</p>
        <p>
            <a class="button" href="#">
                {{ __('View') }} <span class="visually-hidden">{{ __('Draft accessibility report – :language', ['language' => get_locale_name('en', locale())]) }}</span>
            </a> <a class="button" href="#">
                {{ __('Download') }} <span class="visually-hidden">{{ __('Draft accessibility report – :language', ['language' => get_locale_name('en', locale())]) }}</span>
            </a>
        </p>
        <h4>{{ __('Draft accessibility report – :language', ['language' => get_locale_name('fr', locale())]) }}</h4>
        <p>{{ __('Description of this document.') }}</p>
        <p>
            <a class="button" href="#">
                {{ __('View') }} <span class="visually-hidden">{{ __('Draft accessibility report – :language', ['language' => get_locale_name('fr', locale())]) }}</span>
            </a> <a class="button" href="#">
                {{ __('Download') }} <span class="visually-hidden">{{ __('Draft accessibility report – :language', ['language' => get_locale_name('fr', locale())]) }}</span>
            </a>
        </p>
    </div>
</div>
