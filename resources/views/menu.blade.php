  
<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark mb-4 ">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('home')}}">Gan4x4</a>
        
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">       

                @php($selected = substr(request()->getPathInfo(),1))
                @foreach ( __('menu') as $key => $val)

                    <li class="nav-item">
                        <a class="nav-link {{  $key == $selected  ? 'active' : '' }}" href="{{ route($key) }}"> {{ $val }}</a>
                    </li>
                @endforeach
                
                <li class="nav-item">                    
                    @php( $switch_locale = app()->getLocale() == 'ru' ? 'en' : 'ru' )    
                    <a class="nav-link" href="{{ route($switch_locale) }}"> {{ $switch_locale }}</a>
                </li>

            </ul>
            
        </div>    
        <div class="" >
            
            <ul class="navbar-nav flex-row flex-wrap ms-md-auto">
                <li class="nav-item col-6 col-md-auto">
                    <a href="https://stackoverflow.com/users/6656081"  class='nav-link' alt="Stack overflow">
                        <i class="fa fa-stack-overflow " aria-hidden="true"></i>
                    </a>
                </li>
                
                <li class="nav-item col-6 col-md-auto">
                    <a href="https://github.com/Gan4x4/" class='nav-link p-2' alt="Github">
                        <i class="fa fa-github " aria-hidden="true"></i>
                    </a>  
                </li>
                
                
                
              
                
            </ul>
                
        </div>
            
     <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>       
        
  </div>
</nav>
