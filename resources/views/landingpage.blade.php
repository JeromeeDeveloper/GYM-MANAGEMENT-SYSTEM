<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="">
<!--<![endif]-->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>GYM MANAGEMENT SYSTEM</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <!--Google Fonts link-->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,600,600i,700,700i"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,600i,700,700i" rel="stylesheet">


    <link rel="stylesheet" href="{{ asset('assets/assets2/css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/jquery.fancybox.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/magnific-popup.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/assets2/css/bootstrap-theme.min.css') }}"> --}}


    {{-- <!--For Plugins external css--> --}}
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/plugins.css') }}" />

    <!--Theme custom css -->
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/style.css') }}">

    <!--Theme Responsive css-->
    <link rel="stylesheet" href="{{ asset('assets/assets2/css/responsive.css') }}" />

    <script src="{{ asset('assets/assets2/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
    <script src="https://unpkg.com/html5-qrcode/minified/html5-qrcode.min.js"></script>
</head>
<style>
    /* CSS to center the modal */
    .card-body {
        position: relative;
        left: 370px;
        border: 1px solid #ccc;
        margin-top: 30px;
    }
</style>

<body data-spy="scroll" data-target=".navbar-collapse">
    <script src="{{ asset('assets/js/qrcode.js') }}"></script>
    <div class='preloader'>
        <div class='loaded'>&nbsp;</div>
    </div>
    <div class="culmn">
        <header id="main_menu" class="header navbar-fixed-top">
            <div class="main_menu_bg">
                <div class="container">
                    <div class="row">
                        <div class="nave_menu">
                            <nav class="navbar navbar-default">
                                <div class="container-fluid">
                                    <!-- Brand and toggle get grouped for better mobile display -->
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                        </button>
                                        <a class="navbar-brand" href="#home">
                                            <img src="{{ asset('assets/images/logogym.png') }}" />

                                        </a>
                                    </div>
                                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="#home">HOME</a></li>
                                            <li><a href="#history">ABOUT THE GYM</a></li>
                                            <li><a href="#pricing">WORKOUTS</a></li>
                                            <li><a href="{{ route('backpack.auth.login') }}">Admin Login</a></li>
                                        </ul>


                                    </div>

                                </div>
                            </nav>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <section id="home" class="home">
            <div class="overlay">
                <div class="home_skew_border">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-12 ">
                                <div class="main_home_slider text-center">
                                    <div class="single_home_slider">
                                        <div class="main_home wow fadeInUp" data-wow-duration="700ms">

                                            <h1>WELCOME TO GMS</h1>
                                            <h3>Enter Code | Check in</h3>
                                            <div class="separator"></div>

                                            {{-- <div class="home_btn"> --}}
                                            <div class="box">
                                                <form id="searchForm" action="{{ route('members.search') }}"
                                                    method="GET">
                                                    <input id="search" type="search" class="input2" name="query"
                                                        onmouseout="this.value = ''; this.blur();">
                                                    <button type="submit" id="searchBtn" aria-hidden="true"></button>
                                                </form>
                                                @if ($members->isNotEmpty() && request('query'))

                                                    <div class="container">
                                                        <div class="row justify-content-center">
                                                            @foreach ($members as $index => $member)
                                                                <div class="col-md-4">
                                                                    <div class="card mb-3 custom-card">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title">Member Info</h5>
                                                                            <p class="card-text">Name:
                                                                                {{ $member->firstname }}
                                                                                {{ $member->lastname }}</p>
                                                                            <p class="card-text">Code:
                                                                                {{ $member->code }}</p>
                                                                            @php
                                                                                $latest_membership = $latest_memberships
                                                                                    ->where('member_id', $member->id)
                                                                                    ->first();
                                                                            @endphp
                                                                            <p class="card-text">Status:
                                                                                {{ $latest_membership ? $latest_membership->status : 'No membership status' }}
                                                                            </p>
                                                                            <p class="card-text">Annual Status:
                                                                                {{ $latest_membership ? $latest_membership->annual_status : 'No membership Annual status' }}
                                                                            </p>
                                                                            @if ($member->check_ins->isNotEmpty())
                                                                                <p class="card-text">Check in Time:
                                                                                    {{ \Carbon\Carbon::parse($member->check_ins->last()->check_in_time)->format('h:i A F d, Y') }}
                                                                                </p>
                                                                            @else
                                                                                <p class="card-text">No check-ins
                                                                                    recorded.</p>
                                                                            @endif

                                                                            <p id="timer{{ $index }}"
                                                                                class="card-text">Time Remaining To
                                                                                Exit: 10 seconds</p>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger float-right"
                                                                                onclick="closeCardOnClick(this, {{ $index }})">Close</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <h2 style="color: white; text-align: center;">Provide correct code.
                                                    </h2>
                                                @endif

                                            </div>


                                        </div>
                                    </div>
                                    <div class="single_home_slider">
                                        <div class="main_home wow fadeInUp" data-wow-duration="700ms">
                                            <h1>QR SCANNER</h1>
                                            <h3>Scan for auto check-in!</h3>

                                            <div style="display: inline-block; text-align: left;">
                                                <div id="reader" style="width: 290px;"></div>
                                                <div id="show" style="display: none;">
                                                    <h4>Scanned Result</h4>
                                                    <p style="color: blue;" id="result"></p>

                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                    <div class="single_home_slider">
                                        <div class="main_home wow fadeInUp" data-wow-duration="700ms">

                                            <h1>Gym Management System</h1>
                                            <h3>Our Gym Members Are Our First Priority</h3>
                                            <div class="separator"></div>

                                            <div class="home_btn">
                                                <a href="https://bootstrapthemes.co" class="btn btn-lg m_t_10">GET
                                                    STARTED NOW</a>
                                                <a href="https://bootstrapthemes.co" class="btn btn-default">LEARN
                                                    MORE</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scrooldown">
                        <a href="#feature"><i class="fa fa-arrow-down"></i></a>
                    </div>
                </div>
            </div>
        </section>

        <section id="feature" class="feature sections">
            <div class="container">
                <div class="row">
                    <div class="main_feature text-center">

                        <div class="col-sm-3">
                            <div class="single_feature">
                                <div class="single_feature_icon">
                                    <i class="fa fa-clone"></i>
                                </div>

                                <h4>CHECKED IN FILTER DATES</h4>
                                <div class="separator3"></div>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="single_feature">
                                <div class="single_feature_icon">
                                    <i class="fa fa-heart-o"></i>
                                </div>

                                <h4>PAYMENTS ORGANIZED</h4>
                                <div class="separator3"></div>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="single_feature">
                                <div class="single_feature_icon">
                                    <i class="fa fa-lightbulb-o"></i>
                                </div>
                                <h4>MEMBERS DAILY REPORTS</h4>
                                <div class="separator3"></div>

                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="single_feature">
                                <div class="single_feature_icon">
                                    <i class="fa fa-comments-o"></i>
                                </div>

                                <h4>VISUALIZE ACTIVE MEMBERS</h4>
                                <div class="separator3"></div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

        <hr />

        <section id="history" class="history sections">
            <div class="container">
                <div class="row">
                    <div class="main_history">
                        <div class="col-sm-6">
                            <div class="single_history_img">
                                <img src="{{ asset('assets/assets2/images/giphy.gif') }}" alt="" />
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="single_history_content">
                                <div class="head_title">
                                    <h2>GYM MINI PROJECT HISTORY</h2>
                                </div>
                                <p>MADE USING BACKPACK CRUD CONTROLLER - JEROME B. PORCADO </p>

                                <a href="" class="btn btn-lg">BROWSE OUR WORK</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section id="pricing" class="pricing">
            <div class="container">
                <div class="row">
                    <div class="main_pricing_area sections">
                        <div class="head_title text-center">
                            <h2>WORKOUTS</h2>
                            <div class="subtitle">
                                Upcoming!
                            </div>
                            <div class="separator"></div>
                        </div>

                        <div class="col-md-4 col-sm-6">
                            <div class="single_pricing">
                                <div class="pricing_head">
                                    <h3>STARTER</h3>
                                    <div class="pricing_price">
                                        <div class="p_r text-center">10php</div>
                                        <div class="m_t text-center">per month</div>
                                    </div>
                                </div>

                                <div class="pricing_body">
                                    <ul>
                                        <li>Push up</li>
                                        <li>Sit up</li>
                                        <li>Running</li>
                                        <li>Jumping jacks</li>
                                    </ul>
                                    <a href="https://bootstrapthemes.co" class="btn btn-md">CHOOSE PLAN</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="single_pricing pricing_business">
                                <div class="pricing_head ">
                                    <h3>PREMIUM</h3>
                                    <div class="pricing_price">
                                        <div class="p_r text-center">10php</div>
                                        <div class="m_t text-center">per year</div>
                                    </div>
                                </div>

                                <div class="pricing_body">
                                    <ul>
                                        <li>Push up</li>
                                        <li>Sit up</li>
                                        <li>Running</li>
                                        <li>Jumping jacks</li>
                                    </ul>
                                    <a href="https://bootstrapthemes.co" class="btn btn-md">CHOOSE PLAN</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="single_pricing">
                                <div class="pricing_head">
                                    <h3>BUSINESS</h3>
                                    <div class="pricing_price">
                                        <div class="p_r text-center">10php</div>
                                        <div class="m_t text-center">per quarter</div>
                                    </div>
                                </div>

                                <div class="pricing_body">
                                    <ul>
                                        <li>Push up</li>
                                        <li>Sit up</li>
                                        <li>Running</li>
                                        <li>Jumping jacks</li>
                                    </ul>
                                    <a href="https://bootstrapthemes.co" class="btn btn-md">CHOOSE PLAN</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="main_footer">
                            <div class="row">
                                <div class="col-sm-6 col-xs-12">
                                    <div class="flowus">
                                        <a href=""><i class="fa fa-facebook"></i></a>
                                        <a href=""><i class="fa fa-twitter"></i></a>
                                        <a href=""><i class="fa fa-google-plus"></i></a>
                                        <a href=""><i class="fa fa-instagram"></i></a>
                                        <a href=""><i class="fa fa-youtube"></i></a>
                                        <a href=""><i class="fa fa-dribbble"></i></a>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-xs-12">
                                    <div class="copyright_text">
                                        <p class=" wow fadeInRight" data-wow-duration="1s">Made</i> by<a
                                                href="https://bootstrapthemes.co">Jerome </a>2024. All Rights
                                            Reserved</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>


    <div class="scrollup">
        <a href="#"><i class="fa fa-chevron-up"></i></a>
    </div>

    <script src="{{ asset('assets/assets2/js/vendor/jquery-1.11.2.min.js') }}"></script>
    <script src="{{ asset('assets/assets2/js/vendor/bootstrap.min.js') }}"></script>

    <script src="{{ asset('assets/assets2/js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('assets/assets2/js/jquery.mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/assets2/js/jquery.easing.1.3.js') }}"></script>
    <script src="{{ asset('assets/assets2/js/jquery.masonry.min.js') }}"></script>

    <script src="{{ asset('assets/assets2/css/slick/slick.js') }}"></script>
    <script src="{{ asset('assets/assets2/css/slick/slick.min.js') }}"></script>


    <script src="{{ asset('assets/assets2/js/plugins.js') }}"></script>
    <script src="{{ asset('assets/assets2/js/main.js') }}"></script>


    <script>
        function closeCardOnClick(button, index) {

            var card = button.closest('.card');

            card.style.display = 'none';
        }


        function startCountdown(seconds, button, index) {
            var countdown = seconds;
            var timerElement = document.getElementById('timer' + index);


            var timerId = setInterval(function() {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(timerId);
                    closeCardOnClick(button, index);
                } else {

                    timerElement.textContent = 'Time Remaining: ' + countdown + ' seconds';
                }
            }, 1000);

           // when the close button is clicked
            button.addEventListener('click', function() {
                clearInterval(timerId);
                closeCardOnClick(button, index);
            });
        }

        function handleSearchResults() {
            var queryParam = getUrlParameter('query');
            if (queryParam) {
                var buttons = document.querySelectorAll('.btn-danger');
                buttons.forEach(function(button, index) {
                    startCountdown(10, button, index);
                });
            }
        }

        function getUrlParameter(name) {
            name = name.replace(/[\[\]]/g, '\\$&');
            var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                results = regex.exec(window.location.href);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }

        handleSearchResults();

        const html5Qrcode = new Html5Qrcode('reader');
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            if (decodedText) {
                document.getElementById('show').style.display = 'block';
                document.getElementById('result').textContent = decodedText;
                html5Qrcode.stop();
            }
        }
        const config = {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        }
        html5Qrcode.start({
            facingMode: "environment"
        }, config, qrCodeSuccessCallback);

        document.addEventListener('DOMContentLoaded', function() {

            const checkInButtons = document.querySelectorAll('searchBtn');

            checkInButtons.forEach(function(button) {
                button.addEventListener('click', function() {

                    const memberId = button.getAttribute('data-member-id');

                    fetch('/check-ins', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                member_id: memberId
                            })
                        })
                        .then(response => {
                            if (response.ok) {

                                alert('Check-in successful!');
                            } else {

                                alert('Failed to check-in. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred. Please try again.');
                        });
                });
            });
        });
    </script>




</body>

</html>
