@extends('app', ['selected'=>'education'])

@section('content')

    <div class="container">
    <h1 class="page-title">{{ __('menu.education') }}</h1>
    <table  class="table project">
    
        <tr>
            <td  class="text-center">
                <a href="https://www.mati-rgtu.org/">
                    <img class="img-sm" src="design/work/mati.png" target="_blank">
                </a>
                <br>
                2000 - 2003
            </td>
            <td>
                <b style="text-transform: capitalize;" >Московский авиационно‑технологический университет, Москва (МАТИ РГТУ)</b>
                <br>
                <p>
                   
                   Аспирантура <i>(не защищался)</i>
                <br>
                    
                    
                    Тема диссертации: "Разработка и исследование методов проектирования и программирования многоагентных систем".
                    
                </p>
            </td>
        </tr>        
        
        
        <tr>
            <td  class="text-center" target="_blank">
                <a href="https://www.mati-rgtu.org/">
                    <img class="img-sm" src="design/work/mati.png">
                </a>
                <br>
                1998 - 2000
            </td>
            <td>                
                <b style="text-transform: capitalize;" >Московский авиационно‑технологический университет, Москва (МАТИ РГТУ)</b>
                <p>
                   Кафедра: "Информационные технологии"<br>
                   Инженер <i class="fa fa-star"></i><i style="color:red">(с отличием)</i>
                   <br> 
                   Специальность "Автоматизированные системы обработки информации и управления".

                </p>
            </td>
        </tr>        
        
        <tr>
            <td  class="text-center" target="_blank">
                <a href="https://www.mati-rgtu.org/">
                    <img class="img-sm" src="design/work/mati.png">
                </a>
                <br>
                1994 - 1998
            </td>
            <td>                
                <b style="text-transform: capitalize;" >Московский авиационно‑технологический университет, Москва (МАТИ РГТУ)</b>
                <p>
                    Кафедра: "Информационные технологии"<br>
                    Бакалавр по направлению "Техника и технологии".
                </p>
            </td>
        </tr>        
    
    </table>    
    </div>
    
@endsection
