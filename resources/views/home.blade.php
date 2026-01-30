@extends('app')

@section('content')


 
        <div class="container-fluid">
            <div class="row pt-3 ">
                <div class="col-md-12 project rounded-top p-3">
                    @if (app()->getLocale() == 'ru')
                        @include('home.ru')

                    @else
                        @include('home.en')
                    @endif
                </div>   
        
            </div>    <!-- row -->   
            
            <div class="row intro-text">
                <ul class="nav">
                    <li class='nav-item'>
                        <a href="https://stackoverflow.com/users/6656081"  class='nav-link'>
                            <i class="fa fa-stack-overflow fa-2x" aria-hidden="true"></i>
                            Stack overflow
                        </a>
                    </li>
                    <li class='nav-item'>
                        <a href="https://github.com/Gan4x4/" class='nav-link'>
                            <i class="fa fa-github fa-2x" aria-hidden="true"></i>
                            Github
                        </a>
                    </li>  
                    <li class='nav-item'>
                        <a href="https://bitbucket.org/Gan4x4/" class='nav-link' >
                            <i class="fa fa-bitbucket fa-2x" aria-hidden="true"></i>
                            Bitbucket
                        </a>                        
                    </li>  
                    <li class='nav-item'>
                        <!--
                        <img class="h-25 object-fit-cover" src ="https://istina.msu.ru/static/base/img/logo-msu-150x148.png">
                        -->
                        <a href="https://istina.msu.ru/workers/393403986/" class='nav-link' >
                            Istina
                        </a>                        
                    </li>  
                    <!--
                    <li class='nav-item'>
                        <a href="https://packagist.org/packages/gan4x4/" title="packagist" class='nav-link'>
                            <i class="fa fa-php fa-2x" aria-hidden="true"></i>
                            <i class="fa-brands fa-php"></i>
                            Packagist
                        </a>
                    </li>
                    -->
                </ul>
            </div>

       
        </div>
 


@endsection