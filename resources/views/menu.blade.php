  
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark mb-4" aria-label="Primary">
    <div class="container-fluid">
        @php($isAdmin = request()->is('admin') || request()->is('admin/*'))
        @php($routeName = request()->route()?->getName())
        @if($isAdmin)
            @php($menuItems = [
                'admin.index' => 'Admin',
                'admin.projects.index' => 'Projects',
                'admin.experiences.index' => 'Experience',
                'admin.videos.index' => 'Videos',
            ])
            @php($selected = 'admin.index')
            @if(str_starts_with((string) $routeName, 'admin.projects.'))
                @php($selected = 'admin.projects.index')
            @elseif(str_starts_with((string) $routeName, 'admin.experiences.'))
                @php($selected = 'admin.experiences.index')
            @elseif(str_starts_with((string) $routeName, 'admin.videos.'))
                @php($selected = 'admin.videos.index')
            @endif
            @php($mobileSectionTitle = $menuItems[$selected] ?? 'Admin')
        @else
            @php($menuItems = __('menu'))
            @php($pathKey = request()->segment(1) ?? '')
            @php($selected = array_key_exists((string) $routeName, $menuItems) ? $routeName : $pathKey)
            @php($mobileSectionTitle = ($selected && $selected !== 'home' && array_key_exists($selected, $menuItems)) ? $menuItems[$selected] : null)
        @endif

        <a class="navbar-brand" href="{{ route('home') }}">
            <span>Gan4x4</span>
            @if($mobileSectionTitle)
                <span class="brand-section d-inline d-md-none"> / {{ $mobileSectionTitle }}</span>
            @endif
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button> 
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">       

                @foreach($menuItems as $key => $val)

                    <li class="nav-item"> 
                        <a class="nav-link {{ $key == $selected ? 'active' : '' }}" href="{{ route($key) }}">{{ $val }}</a>
                    </li>
                @endforeach
                
                <li class="nav-item">                    
                    @php( $switch_locale = app()->getLocale() == 'ru' ? 'en' : 'ru' )    
                    <a class="nav-link" href="{{ route($switch_locale) }}">{{ $switch_locale }}</a>
                </li>

            </ul>
            
        </div>
        <div class="navbar-social">
            
            <ul class="navbar-nav flex-row flex-wrap ms-md-auto">
                <li class="nav-item col-6 col-md-auto">
                    <a href="{{ asset('storage/CV_Anton_Ganichev.pdf') }}" class='nav-link p-2' download aria-label="Download Anton Ganichev CV" title="Download Anton Ganichev CV">
                        <i class="fa-solid fa-file-arrow-down" aria-hidden="true"></i>
                    </a>
                </li>

                <li class="nav-item col-6 col-md-auto">
                    <a href="https://stackoverflow.com/users/6656081" class='nav-link' aria-label="Stack Overflow">
                        <i class="fa-brands fa-stack-overflow" aria-hidden="true"></i>
                    </a>
                </li>
                
                <li class="nav-item col-6 col-md-auto">
                    <a href="https://github.com/Gan4x4/" class='nav-link p-2' aria-label="GitHub">
                        <i class="fa-brands fa-github" aria-hidden="true"></i>
                    </a>  
                </li>
                
                
                
              
                
            </ul>
                
        </div>
        
  </div>
</nav>
