 @can('participate', $engagement)
     <p>
         <span class="font-semibold">{{ __('Software') }}:</span> {{ $meeting->meeting_software }}@if ($meeting->alternative_meeting_software)
             ({{ __('flexible, please contact us if you need to use another software') }})
         @endif
         <br />
         <span class="font-semibold">{{ __('Link to join') }}:</span> <a
             href="{{ $meeting->meeting_url }}">{{ $meeting->meeting_url }}</a>
     </p>
     @if ($meeting->additional_video_information)
         <div><span class="font-semibold">{{ __('Additional information to join') }}:</span>
             {!! Str::markdown($meeting->additional_video_information) !!}
         </div>
     @endif
 @else
     <p><span class="font-semibold">{{ __('Software') }}:</span> {{ $meeting->meeting_software }}@if ($meeting->alternative_meeting_software)
             ({{ __('flexible, please contact us if you need to use another software') }})
         @endif
     </p>
 @endcan
