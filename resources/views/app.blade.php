<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    @php($isAdmin = request()->is('admin') || request()->is('admin/*'))
    @php($pageTitle = trim($__env->yieldContent('title', __('title'))))
    @php($pageDescription = trim($__env->yieldContent('meta_description', __('description'))))
    @php($currentUrl = url()->current())
    @php($ogLocale = app()->getLocale() === 'ru' ? 'ru_RU' : 'en_US')
    @php($ogLocaleAlt = app()->getLocale() === 'ru' ? 'en_US' : 'ru_RU')
    @php($socialImage = asset('design/anton.jpg'))
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDescription }}">
    <meta name="keywords" content="@lang('meta')">
    <meta name="author" content="@lang('title')">
    <meta name="robots" content="{{ $isAdmin ? 'noindex,nofollow,noarchive' : 'index,follow,max-image-preview:large' }}">
    <meta name="theme-color" content="#0f1c1f">
    <link rel="canonical" href="{{ $currentUrl }}">

    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDescription }}">
    <meta property="og:url" content="{{ $currentUrl }}">
    <meta property="og:image" content="{{ $socialImage }}">
    <meta property="og:site_name" content="Gan4x4">
    <meta property="og:locale" content="{{ $ogLocale }}">
    <meta property="og:locale:alternate" content="{{ $ogLocaleAlt }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $pageDescription }}">
    <meta name="twitter:image" content="{{ $socialImage }}">
    
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
