<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unsubscribed - Muni University News & Media</title>
    <style>
        body { font-family: 'Source Sans Pro', Arial, sans-serif; margin: 0; padding: 0; background-color: #f7f9fc; }
        .wrapper { min-height: 100vh; display: flex; flex-direction: column; }
        .brand-header { background-color: #8B0000; color: #ffffff; padding: 28px 20px; text-align: center; border-bottom: 4px solid #ffde00; }
        .brand-header h1 { margin: 0; font-size: 24px; font-weight: 800; }
        .brand-header p { margin: 5px 0 0; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; color: #ffde00; }
        .card { background: #ffffff; max-width: 520px; margin: 48px auto; padding: 40px 32px; border-radius: 4px; box-shadow: 0 6px 18px rgba(0,0,0,0.08); border-top: 3px solid #8B0000; text-align: center; }
        .card .check { width: 64px; height: 64px; line-height: 64px; margin: 0 auto 18px; border-radius: 50%; background-color: #198754; color: #ffffff; font-size: 30px; font-weight: 800; }
        .card h2 { margin: 0 0 10px; color: #8B0000; font-size: 22px; }
        .card p { margin: 0 0 20px; color: #4a5568; font-size: 15px; line-height: 1.6; }
        .card .email { font-weight: 700; color: #5C0000; word-break: break-all; }
        .btn { display: inline-block; background-color: #8B0000; color: #ffffff !important; text-decoration: none; padding: 12px 26px; border-radius: 4px; font-weight: 700; font-size: 14px; }
        .btn:hover { background-color: #5C0000; }
        .footer { margin-top: auto; background-color: #8B0000; border-top: 3px solid #ffde00; padding: 20px; text-align: center; font-size: 12px; color: #ffffff; opacity: 0.9; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="brand-header">
            <h1>Muni University News &amp; Media</h1>
            <p>Transforming Lives</p>
        </div>

        <div class="card">
            <div class="check">&#10003;</div>
            <h2>You have been successfully unsubscribed</h2>
            <p><span class="email">{{ $email }}</span> has been removed from the Muni University newsletter mailing list.</p>
            <p style="font-size:14px;">We're sorry to see you go. If this was a mistake, you can subscribe again anytime from our portal.</p>
            <a href="{{ url('/') }}" class="btn">Back to the News Portal</a>
        </div>

        <div class="footer">&copy; {{ date('Y') }} Muni University. Arua City, Uganda - P.O. Box 725.</div>
    </div>
</body>
</html>