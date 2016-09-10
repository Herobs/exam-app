<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <!--><html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta name="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="description" content="A web application for online programing languages exam">
        <meta name="author" content="Herobs <herobs@herobs.cn>">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="/assets/js/html5shiv.js"></script>
            <script src="/assets/js/respond.js"></script>
            <script src="/assets/js/es5-shim.js"></script>
            <script src="//cdn.bootcss.com/mathjax/2.5.3/MathJax.js?config=TeX-AMS-MML_HTMLorMML"></script>
            <script type="text/x-mathjax-config">
                MathJax.Hub.Config({
                    tex2jax: { inlineMath: [['$', '$']] },
                    messageStyle: "none"
                });
            </script>
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('assets/themes/' . config('config.theme') . '/bundle.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/katex.min.css') }}">
        <title>@yield('title')</title>
    </head>
    <body>
        @yield('content')
        <script src="{{ asset('assets/js/bundle.js') }}"></script>
    </body>
</html>
