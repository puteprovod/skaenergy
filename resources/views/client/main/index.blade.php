@extends('client.layouts.main')

@section('content')
    <body class="bg-bg-gray min-h-full h-screen">
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId      : '299494925738188',
                xfbml      : true,
                version    : 'v17.0'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id){
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {return;}
            js = d.createElement(s); js.id = id;
            js.src = "https://connect.facebook.net/en_US/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>
    <div class="min-h-full h-auto mb-[-48px] after:block after:h-12">
        <div id="app" class="h-full">

        </div>
    </div>
    <footer>
        <div
            class="flex bg-ska-blue h-12 text-mild-white align-middle content-center text-center">
            <div class="my-auto mx-auto">
                <span class="font-light">&#169; ХАБАР (2003-{{ \Carbon\Carbon::today()->format('Y') }})</span>
                <span class="ml-20">
                      &#x1f9d1;&#x200d;&#x1f4bb; <a href="https://inshin.org/" class="underline">Inshin.org</a>
                </span>
            </div>
        </div>
    </footer>
    </body>
@endsection
