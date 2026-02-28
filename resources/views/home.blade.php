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
            
        </div>
 


@endsection

@section('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": "{{ url('/') }}#website",
      "url": "{{ url('/') }}",
      "name": "Gan4x4",
      "inLanguage": "{{ app()->getLocale() }}"
    },
    {
      "@type": "Person",
      "@id": "{{ url('/') }}#person",
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
    },
    {
      "@type": "ProfilePage",
      "@id": "{{ url()->current() }}#webpage",
      "url": "{{ url()->current() }}",
      "name": "@lang('title')",
      "inLanguage": "{{ app()->getLocale() }}",
      "isPartOf": {
        "@id": "{{ url('/') }}#website"
      },
      "mainEntity": {
        "@id": "{{ url('/') }}#person"
      }
    }
  ]
}
</script>
@endsection
