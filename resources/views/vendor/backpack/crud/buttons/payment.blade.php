@if($crud->hasAccess('payment') && (auth('backpack')->user() && in_array(2, explode(',', auth('backpack')->user()->capabilities))))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/payment') }}" class="btn btn-sm btn-link"><i class="la la-money"></i> Payment</a>
@endif


