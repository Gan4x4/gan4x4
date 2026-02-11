<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@lang('title')</title>
    <meta name="description" content="@lang('description')">
    <meta name="keywords" content="@lang('meta')">
    <meta name="author" content="@lang('title')">
    <meta name="robots" content="index,follow">
    <meta name="theme-color" content="#0f1c1f">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="@lang('title')">
    <meta property="og:description" content="@lang('description')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('design/anton.jpg') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@lang('title')">
    <meta name="twitter:description" content="@lang('description')">
    <meta name="twitter:image" content="{{ asset('design/anton.jpg') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
    
    <link rel="stylesheet" href="/css/main.css" >
    @yield('head')
    @yield('structured_data')
    </head>
    <body class="page">

     
      <header>
          @include('menu')
      </header>

      <main class="container site-main">

          @yield('content')

      </main>
        
      <!--
    <footer class="mt-auto">
    <p>Cover template for <a href="https://getbootstrap.com/" class="text-white">Bootstrap</a>, by <a href="https://twitter.com/mdo" class="text-white">@mdo</a>.</p>
  </footer>
     -->
        
        

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>

  
</html>
