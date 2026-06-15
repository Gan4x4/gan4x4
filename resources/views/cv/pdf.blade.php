<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <title>{{ $cv['name'] }} CV</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            color: #101820;
            font-family: "Noto Serif", "DejaVu Serif", Georgia, serif;
            font-size: 10.5pt;
            line-height: 1.42;
        }

        a {
            color: #174ea6;
            text-decoration: none;
        }

        h1,
        h2,
        h3,
        p,
        ul,
        dl {
            margin: 0;
        }

        ul {
            padding-left: 1.05em;
            margin-top: 0.3em;
        }

        li + li {
            margin-top: 0.18em;
        }

        .cv {
            width: 210mm;
            min-height: 297mm;
            padding: 13mm 17mm 11mm;
        }

        .cv-header {
            border-bottom: 1px solid #cfd6dd;
            padding-bottom: 4.5mm;
            margin-bottom: 4.5mm;
        }

        .cv-title {
            display: grid;
            grid-template-columns: 1fr 58mm;
            gap: 10mm;
            align-items: start;
        }

        h1 {
            font-size: 25pt;
            line-height: 1.05;
            font-weight: 700;
            letter-spacing: 0;
        }

        .headline {
            margin-top: 1.5mm;
            color: #3f4b55;
            font-size: 11.5pt;
            font-style: italic;
        }

        .contacts {
            display: grid;
            grid-template-columns: 18mm 1fr;
            gap: 1.1mm 2.5mm;
            font-size: 8.8pt;
        }

        .contacts dt {
            color: #59636d;
            font-style: italic;
        }

        .contacts dd {
            margin: 0;
            overflow-wrap: anywhere;
        }

        .summary {
            margin-top: 4mm;
            text-align: justify;
        }

        .columns {
            columns: 2;
            column-gap: 9mm;
        }

        .section {
            break-inside: avoid;
            margin-bottom: 4.4mm;
        }

        .section h2 {
            border-top: 1px solid #d9dee3;
            color: #101820;
            font-size: 10.4pt;
            font-weight: 700;
            letter-spacing: 0;
            padding-top: 2.1mm;
            margin-bottom: 2.2mm;
        }

        .section p + p,
        .section p + ul,
        .section ul + p {
            margin-top: 1.4mm;
        }

        .experience {
            break-inside: avoid;
            display: grid;
            grid-template-columns: 21mm 1fr;
            gap: 0 4mm;
            margin-bottom: 4mm;
        }

        .period {
            color: #59636d;
            font-style: italic;
            font-size: 8.8pt;
            white-space: nowrap;
        }

        .role {
            font-weight: 700;
        }

        .company {
            color: #59636d;
            font-size: 9pt;
            font-style: italic;
            margin-top: 0.6mm;
        }

        .description,
        .duties {
            margin-top: 1.5mm;
        }

        .footer {
            color: #68737d;
            border-top: 1px solid #d9dee3;
            margin-top: 3mm;
            padding-top: 2mm;
            font-size: 8.5pt;
        }
    </style>
</head>
<body>
    <article class="cv">
        <header class="cv-header">
            <div class="cv-title">
                <div>
                    <h1>{{ $cv['name'] }}</h1>
                    <p class="headline">{{ $cv['headline'] }}</p>
                </div>

                <dl class="contacts">
                    @foreach($cv['contacts'] as $contact)
                        <dt>{{ $contact['label'] }}</dt>
                        <dd>{!! $contact['html'] !!}</dd>
                    @endforeach
                </dl>
            </div>

            @if($cv['summary'])
                <div class="summary">{!! $cv['summary'] !!}</div>
            @endif
        </header>

        <main>
            <div class="columns">
                @foreach($cv['sections'] as $section)
                    <section class="section">
                        <h2>{{ $section['title'] }}</h2>
                        {!! $section['html'] !!}
                    </section>
                @endforeach
            </div>

            <section class="section">
                <h2>{{ $locale === 'ru' ? 'Опыт' : 'Experience' }}</h2>
                @foreach($cv['experiences'] as $experience)
                    <div class="experience">
                        <div class="period">{{ $experience['period'] }}</div>
                        <div>
                            <p class="role">{{ $experience['position'] }}</p>
                            <p class="company">
                                @if($experience['url'])
                                    <a href="{{ $experience['url'] }}">{{ $experience['company'] }}</a>
                                @else
                                    {{ $experience['company'] }}
                                @endif
                            </p>
                            @if($experience['description'])
                                <div class="description">{!! $experience['description'] !!}</div>
                            @endif
                            @if($experience['duties'])
                                <div class="duties">{!! $experience['duties'] !!}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </section>
        </main>

        <footer class="footer">
            {{ $locale === 'ru' ? 'Обновлено' : 'Updated' }}: {{ $generatedAt->format('Y-m-d') }}
        </footer>
    </article>
</body>
</html>
