@extends('app', ['selected'=>'education'])

@section('content')

    <div class="container">
    <h1 class="page-title">{{ __('menu.education') }}</h1>
    <table  class="table project">
    
        <tr>
            <td class="text-center">
                <a href="https://www.mati-rgtu.org/">
                    <img class="img-sm" src="design/work/mati.png" target="_blank">
                </a>
                <br>
                2000 - 2003
            </td>
            <td>
                <b style="text-transform: capitalize;" >State Aviation Technological University, Moscow</b>
                <br>
                <p>
                   PhD program (not completed)
                <br>
                    Dissertation topic: “Development and research of methods for designing and programming multi-agent systems.” 
                </p>
            </td>
        </tr>        
        
        
        <tr>
            <td class="text-center">
                <a href="https://www.mati-rgtu.org/" target="_blank">
                    <img class="img-sm" src="design/work/mati.png">
                </a>
                <br>
                1998 - 2000
            </td>
            <td>                
                <b style="text-transform: capitalize;" >State Aviation Technological University, Moscow</b>
                <p>
                    Master's degree <i class="fa fa-star"></i><i style="color:red"> (with honors)</i>
                    <br>in “Automated information processing and management systems” (Computer science).
                </p>
            </td>
        </tr>        
        
        <tr>
            <td class="text-center">
                <a href="https://www.mati-rgtu.org/">
                    <img class="img-sm" src="design/work/mati.png" target="_blank">
                </a>
                <br>
                1994 - 1998
            </td>
            <td>                
                <b style="text-transform: capitalize;" >State Aviation Technological University, Moscow</b>
                <p>
                    Bachelor's degree <br>in “Techniques and technologies” (Computer science).
                </p>
            </td>
        </tr>        
    
    </table>    
    </div>
    
@endsection
