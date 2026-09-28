<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $newsletter->title }} - Muni University</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            color: #333; 
            line-height: 1.6; 
            margin: 0; 
            padding: 20px; 
        }
        .header { 
            border-bottom: 3px solid #8B0000; 
            padding-bottom: 15px; 
            margin-bottom: 30px; 
            text-align: center;
        }
        .header h1 { 
            color: #8B0000; 
            font-size: 22px; 
            margin: 0; 
        }
        .header p { 
            color: #666; 
            font-size: 12px; 
            margin: 5px 0 0 0; 
        }
        .article-title { 
            font-size: 20px; 
            color: #2c3e50; 
            margin-bottom: 10px; 
        }
        .article-meta { 
            font-size: 12px; 
            color: #777; 
            margin-bottom: 25px; 
            border-bottom: 1px solid #eee; 
            padding-bottom: 15px;
        }
        .content { 
            font-size: 14px; 
            text-align: justify; 
        }
        .content p { margin-bottom: 15px; }
        .content img { max-width: 100%; height: auto; margin: 15px 0; }
        .footer { 
            margin-top: 50px; 
            padding-top: 15px; 
            border-top: 2px solid #ffde00; 
            text-align: center; 
            font-size: 11px; 
            color: #555;
        }
        .footer a { color: #8B0000; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Muni University News & Media Portal</h1>
        <p>Transforming Lives | news.muni.ac.ug</p>
    </div>

    <h2 class="article-title">{{ $newsletter->title }}</h2>
    <div class="article-meta">
        Published on {{ \Carbon\Carbon::parse($newsletter->created_at)->format('F j, Y') }} | 
        Author: {{ $newsletter->author->full_name ?? $newsletter->author->username ?? 'Muni University' }}
    </div>

    <div class="content">
        @if($featuredImagePath)
            <img src="{{ $featuredImagePath }}" alt="{{ $newsletter->title }}" style="max-width: 100%; height: auto; margin: 15px 0;">
        @endif
        
        {!! $processedContent !!}
    </div>

    <div class="footer">
        <p>Read the full story and more updates at <a href="https://news.muni.ac.ug/newsletters/{{ $newsletter->slug }}">news.muni.ac.ug</a></p>
        <p>&copy; {{ date('Y') }} Muni University. All rights reserved.</p>
    </div>

</body>
</html>