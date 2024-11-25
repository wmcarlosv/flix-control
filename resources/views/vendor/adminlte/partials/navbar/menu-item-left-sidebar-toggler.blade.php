<li class="nav-item">
    <a class="nav-link" data-widget="pushmenu" href="#"
        @if(config('adminlte.sidebar_collapse_remember'))
            data-enable-remember="true"
        @endif
        @if(!config('adminlte.sidebar_collapse_remember_no_transition'))
            data-no-transition-after-reload="false"
        @endif
        @if(config('adminlte.sidebar_collapse_auto_size'))
            data-auto-collapse-size="{{ config('adminlte.sidebar_collapse_auto_size') }}"
        @endif>
        <i class="fas fa-bars"></i>
        <span class="sr-only">{{ __('adminlte::adminlte.toggle_navigation') }}</span>
    </a>
</li>
 <style type="text/css">
        p.content-credits{
            margin-top: 8px;
        }
    </style>
@if(Auth::user()->role == 'reseller')
    @php
        $settings = \App\Models\Setting::first();
        if(!$settings){
            $settings = [];
        }

        $symbol = "$";
        if(!empty($settings->currency)){
            $currency = json_decode($settings->currency, true);
            $symbol = $currency['symbol'];
        }

    @endphp

    <p class="content-credits">
        <span>Creditos Disponibles: <b>{{$symbol}} {{number_format(Auth::user()->total_credits,2,',','.')}}</b></span>
    </p>
@endif
