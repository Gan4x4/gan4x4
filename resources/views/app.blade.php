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
    <body class="page {{ $isAdmin ? 'is-admin' : 'is-public' }}">

     
      <header>
          @include('menu')
      </header>

      <main class="container site-main">

          @yield('content')

      </main>

      @if(!$isAdmin)
      <footer class="site-footer">
          <div class="container site-footer-inner">
              <div class="footer-meta">
                  <p class="footer-copy mb-1">&copy; {{ now()->year }} Anton Ganichev</p>
                  <p class="footer-role mb-0">{{ __('footer_role') }}</p>
              </div>
              <div class="footer-links-group">
                  <div class="footer-links-block">
                      <span class="footer-title">{{ __('footer_navigation') }}</span>
                      <a href="{{ route('projects') }}">{{ __('menu.projects') }}</a>
                      <a href="{{ route('experience') }}">{{ __('menu.experience') }}</a>
                      <a href="{{ route('video') }}">{{ __('menu.video') }}</a>
                  </div>
                  <div class="footer-links-block">
                      <span class="footer-title">{{ __('footer_profiles') }}</span>
                      <a href="https://github.com/Gan4x4/" target="_blank" rel="noopener noreferrer" aria-label="GitHub profile"><i class="fa-brands fa-github footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">GitHub</span></a>
                      <a href="https://stackoverflow.com/users/6656081" target="_blank" rel="noopener noreferrer" aria-label="Stack Overflow profile"><i class="fa-brands fa-stack-overflow footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">Stack Overflow</span></a>
                      <a href="https://bitbucket.org/Gan4x4/" target="_blank" rel="noopener noreferrer" aria-label="Bitbucket profile"><i class="fa-brands fa-bitbucket footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">Bitbucket</span></a>
                      <a href="https://www.linkedin.com/in/anton-ganichev-46528640/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn profile"><i class="fa-brands fa-linkedin footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">{{ __('footer_linkedin') }}</span></a>
                      <a href="https://istina.msu.ru/workers/393403986/" target="_blank" rel="noopener noreferrer" aria-label="Istina profile"><i class="fa-solid fa-graduation-cap footer-link-icon icon_Istina" aria-hidden="true"></i><span class="footer-link-label">Istina</span></a>
                      <a href="mailto:gan4x4@gmail.com" aria-label="Email address"><i class="fa-solid fa-envelope footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">gan4x4@gmail.com</span></a>
                      <a href="{{ route('cv.download') }}" download aria-label="Download Anton Ganichev CV" title="Download Anton Ganichev CV"><i class="fa-solid fa-file-arrow-down footer-link-icon" aria-hidden="true"></i><span class="footer-link-label">{{ __('footer_cv') }}</span></a>
                  </div>
              </div>
          </div>
      </footer>
      @endif

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>

  
</html>
