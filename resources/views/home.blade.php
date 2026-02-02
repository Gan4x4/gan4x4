@extends('app')

@section('content')


        <div class="container-fluid">
            <div class="row pt-3">
                <section class="col-md-12 profile-card">
                    @if (app()->getLocale() == 'ru')
                        @include('home.ru')
                    @else
                        @include('home.en')
                    @endif
                </section>   
            </div>
            
            <div class="row intro-text">
                <ul class="nav profile-links">
                    <li class='nav-item'>
                        <a href="https://stackoverflow.com/users/6656081" class='nav-link'>
                            <i class="fa fa-stack-overflow fa-2x" aria-hidden="true"></i>
                            Stack Overflow
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a href="https://github.com/Gan4x4/" class='nav-link'>
                            <i class="fa fa-github fa-2x" aria-hidden="true"></i>
                            GitHub
                        </a>
                    </li>  
                    <li class='nav-item'>
                        <a href="https://bitbucket.org/Gan4x4/" class='nav-link' >
                            <i class="fa fa-bitbucket fa-2x" aria-hidden="true"></i>
                            Bitbucket
                        </a>                        
                    </li>  
                    <li class='nav-item'>
                        <a href="https://istina.msu.ru/workers/393403986/" class='nav-link' >
                            Istina
                        </a>                        
                    </li>  
                </ul>
            </div>
        </div>
 


@endsection

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Person",
  "name": "@lang('title')",
  "jobTitle": "@lang('description')",
  "url": "{{ url('/') }}",
  "image": "{{ asset('design/anton.jpg') }}",
  "sameAs": [
    "https://stackoverflow.com/users/6656081",
    "https://github.com/Gan4x4/",
    "https://bitbucket.org/Gan4x4/",
    "https://istina.msu.ru/workers/393403986/"
  ]
}
</script>
@endsection
