<section id="buy-tickets" class="section-with-bg wow fadeInUp">
  <div class="container">

    <div class="section-header">
      <h2>{{ __('frontend.buy_tickets') }}</h2>
      <p>{{ __('frontend.buy_tickets_sub') }}</p>
    </div>

    <div class="row">
      @foreach($prices as $price)
        <div class="col-lg-4">
          <div class="card mb-5 mb-lg-0">
            <div class="card-body">
              <h5 class="card-title text-muted text-uppercase text-center">{{ tr($price->name) }}</h5>
              <h6 class="card-price text-center">${{ number_format($price->price) }}</h6>
              <hr>
              <ul class="fa-ul">
                @foreach($amenities as $amenity)
                  <li @if(!$price->amenities->contains($amenity->id))class="text-muted"@endif>
                    <span class="fa-li"><i class="fa fa-{{ $price->amenities->contains($amenity->id) ? 'check' : 'times' }}"></i></span>{{ tr($amenity->name) }}
                  </li>
                @endforeach
              </ul>
              <hr>
              <div class="text-center">
                <button type="button" class="btn" data-toggle="modal" data-target="#buy-ticket-modal" data-ticket-type="{{ Str::slug(tr($price->name)) }}">{{ __('frontend.buy_now') }}</button>
              </div>
            </div>
          </div>
        </div>
      @endforeach
  </div>

  <!-- Modal Order Form -->
  <div id="buy-ticket-modal" class="modal fade">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">{{ __('frontend.buy_tickets') }}</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('event.register') }}">
            @csrf
            <div class="form-group">
              <input type="text" class="form-control" name="name" placeholder="{{ __('frontend.your_name') }}" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" placeholder="{{ __('frontend.your_email') }}" value="{{ old('email') }}" required>
            </div>
            <div class="form-group">
              <select id="ticket-type" name="ticket_type" class="form-control" >
                <option value="">{{ __('frontend.select_ticket_type') }}</option>
                @foreach($prices as $price)
                  <option value="{{ Str::slug(tr($price->name)) }}" {{ old('ticket_type') == Str::slug(tr($price->name)) ? 'selected' : '' }}>{{ tr($price->name) }}</option>
                @endforeach
              </select>
            </div>
            <div class="text-center">
              <button type="submit" class="btn">{{ __('frontend.buy_now') }}</button>
            </div>
          </form>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

</section>
