<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i>
        {{ trans('backpack::base.dashboard') }}</a></li>

@if (auth('backpack')->user() && in_array(1, explode(',', auth('backpack')->user()->capabilities)))
    <x-backpack::menu-item title="Users" icon="la la-user" :link="backpack_url('user')" />
@endif
@if (auth('backpack')->user() && in_array(6, explode(',', auth('backpack')->user()->capabilities)))
    <x-backpack::menu-item title="Members" icon="la la-users" :link="backpack_url('member')" />
@endif
@if (auth('backpack')->user() && in_array(3, explode(',', auth('backpack')->user()->capabilities)))
    <x-backpack::menu-item title="Payments" icon="la la-money" :link="backpack_url('payments')" />
@endif

@php
    $user = auth('backpack')->user();
    $capabilities = $user ? explode(',', $user->capabilities) : [];
@endphp
@if ($user && in_array(4, $capabilities))
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="la la-calendar-check-o nav-icon"></i> Reports
        </a>

        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
@endif

@if (auth('backpack')->user() && in_array(5, explode(',', auth('backpack')->user()->capabilities)))
    <li><a class="dropdown-item" href="{{ backpack_url('report') }}"><i class="la la-calendar-check-o nav-icon"></i>
            &nbsp; Daily Check Ins</a></li>
@endif

@if (auth('backpack')->user() && in_array(6, explode(',', auth('backpack')->user()->capabilities)))
    <li><a class="dropdown-item" href="{{ backpack_url('dmember') }}"><i class="la la-calendar-check-o nav-icon"></i>
            &nbsp; Member</a></li>
@endif

@if (auth('backpack')->user() && in_array(7, explode(',', auth('backpack')->user()->capabilities)))
    <li><a class="dropdown-item" href="{{ backpack_url('pay') }}"><i class="la la-money nav-icon"></i> &nbsp;
            Payments</a></li>
@endif

@if (auth('backpack')->user() && in_array(8, explode(',', auth('backpack')->user()->capabilities)))
    <li><a class="dropdown-item" href="{{ backpack_url('cashflow') }}"><i class="la la-calendar-check-o nav-icon"></i>
            &nbsp; Cashflow</a></li>
@endif

</ul>
</li>
