<section id="schedule" class="section-with-bg">
  <div class="container wow fadeInUp">
    <div class="section-header">
      <h2>{{ __('frontend.event_schedule') }}</h2>
      <p>{{ __('frontend.event_schedule_sub') }}</p>
    </div>

    <ul class="nav nav-tabs" role="tablist">
      @foreach($schedules as $key => $day)
        <li class="nav-item">
          <a class="nav-link{{ $key === 1 ? ' active' : '' }}" href="#day-{{ $key }}" role="tab" data-toggle="tab">{{ __('frontend.schedule_day') }} {{ $key }}</a>
        </li>
      @endforeach
    </ul>

    <div class="tab-content row justify-content-center">
      @foreach($schedules as $key => $day)
        <div role="tabpanel" class="col-lg-9 tab-pane fade{{ $key === 1 ? ' show active' : '' }}" id="day-{{ $key }}">
          @foreach($day as $schedule)
            <div class="row schedule-item">
              <div class="col-md-2">
                <div class="schedule-time">
                  <i class="fa fa-microphone" aria-hidden="true"></i>
                  <time>{{ \Carbon\Carbon::parse($schedule->start_time)->format("h:i A") }}</time>
                </div>
              </div>
              <div class="col-md-10">
                @if($schedule->speaker)
                  <div class="speaker">
                    <img src="{{ $schedule->speaker->photo->getUrl() }}" alt="{{ tr($schedule->speaker->name) }}">
                  </div>
                @endif
                <h4>{{ tr($schedule->title) }} @if($schedule->speaker)<span>{{ tr($schedule->speaker->name) }}</span>@endif</h4>
                @if($schedule->subtitle)
                  <p class="schedule-subtitle">{{ tr($schedule->subtitle) }}</p>
                @endif
                @if($schedule->desc)
                  <p class="schedule-desc">{!! tr($schedule->desc) !!}</p>
                @endif
              </div>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>
  </div>
</section>
