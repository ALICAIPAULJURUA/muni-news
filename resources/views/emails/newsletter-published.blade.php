<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Newsletter: {{ $newsletter->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #333333; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; }
        .header { background-color: #8B0000; color: #ffffff; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 30px 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #8B0000; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777777; border-top: 3px solid #ffde00; }
        .unsubscribe-link { color: #8B0000; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Muni University News &amp; Media</h1>
            <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.9;">Transforming Lives</p>
        </div>
        
        <div class="content">
            <h2 style="color: #8B0000; margin-top: 0;">{{ $newsletter->title }}</h2>
            <p style="font-size: 14px; color: #666666;">Published on {{ \Carbon\Carbon::parse($newsletter->created_at)->format('F j, Y') }}</p>
            
            <p>Hello,</p>
            <p>A new newsletter has just been published on the Muni University News Portal. You are receiving this because you subscribed to our updates.</p>
            
            <p><strong>Excerpt:</strong><br>{{ Str::limit(strip_tags($newsletter->content), 200) }}</p>
            
            <a href="{{ $newsletterUrl }}" class="btn">Read Full Newsletter</a>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Muni University. All rights reserved.</p>
            <p>If you no longer wish to receive these emails, you can <a href="{{ $unsubscribeUrl }}" class="unsubscribe-link">unsubscribe here</a>.</p>
        </div>
    </div>
</body>
</html>