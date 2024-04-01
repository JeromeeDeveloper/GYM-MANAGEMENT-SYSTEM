<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Responsive Fitness Website Design Tutorial</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">

    <link rel="stylesheet" href={{asset('./assets/css/style.css')}}>


</head>
<body>


<header>

<a href="/" class="logo"><span>BE</span>FIT</a>
<a href="/" class="logo"><span>GYM SYSTEM</a>
<div id="menu" class="fas fa-bars"></div>


<nav class="navbar">
    <ul>
        <li><a href="http://127.0.0.1:8000/admin/login" class="">Login</a></li>
    </ul>
</nav>

</header>
<section class="home" id="home">

<h1>Never Give Up! <br> Enter Code now</h1>

<form action="{{ route('members.search') }}" method="GET">
    <label for="search">Search Code</label>
    <input id="search" name="query" type="search" placeholder="Enter Code" autofocus required />
    <button type="submit">Go</button>
</form>
@if ($members->isNotEmpty() && request('query'))
    <h2 class="logo" style="color: orange"><span>Searched Results </h2>

    @foreach ($members as $member)
        <div>  <h2 style="color: white"> Name: {{ $member->firstname }} {{ $member->lastname }}</h2>
            <h2 style="color: white"> Code: {{ $member->code }} </h2>
            @if ($member->check_ins->isNotEmpty())
            <h2 style="color: white"> Check in Time: {{ $member->check_ins->last()->check_in_time }}</h2>
            @else
                <h2 style="color: white">No check-ins recorded.</h2>
            @endif
            <br>
        </div>
    @endforeach
@else
    <h2 style="color: white">No Code Searched.</h2>
@endif






</section>



<link rel="stylesheet" href={{asset('./assets/css/search.css')}}>
<script src="{{ asset('./assets/js/main.js') }}"></script>

</body>
</html>
