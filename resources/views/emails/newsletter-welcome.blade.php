<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Muni University News & Media Portal</title>
</head>
<body style="margin:0; padding:0; background-color:#f7f9fc; font-family:'Source Sans Pro', Arial, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f9fc; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; background-color:#ffffff; border-radius:2px; overflow:hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #ffde00;">
                    <!-- Header with Logo -->
                    <tr>
                        <td align="center" style="background-color:#ffffff; padding: 30px 30px 20px; border-bottom: 3px solid #8B0000;">
                            <img src="{{ asset('assets/images/muni-logo.png') }}" alt="Muni University Logo" width="80" height="80" style="display:block; margin: 0 auto 15px; max-width:80px;">
                            <h1 style="margin:0; font-family:'Merriweather', Georgia, serif; font-size:22px; font-weight:800; color:#8B0000; line-height:1.2;">Muni University</h1>
                            <p style="margin:5px 0 0; font-size:12px; letter-spacing:2px; text-transform:uppercase; color:#5C0000; font-weight:700;">Transforming Lives</p>
                            <p style="margin:5px 0 0; font-size:12px; color:#24AAE1; font-weight:600;">news.muni.ac.ug</p>
                        </td>
                    </tr>
                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px;">
                            <h2 style="margin:0 0 15px; font-family:'Merriweather', Georgia, serif; font-size:20px; color:#5C0000; font-weight:700;">Welcome aboard! 🎉</h2>
                            <p style="margin:0 0 15px; font-size:16px; line-height:1.6; color:#4a5568;">Thank you for subscribing to the <strong style="color:#8B0000;">Muni University News & Media Portal</strong>.</p>
                            <p style="margin:0 0 15px; font-size:15px; line-height:1.6; color:#4a5568;">You’ll now receive the latest news, announcements, events, research highlights, and inspiring student & staff stories — straight to your inbox at <strong>{{ $email ?? 'your email' }}</strong>.</p>
                            <p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#4a5568;">We’re committed to keeping our community informed and connected as we live our tagline every day: <em style="color:#8B0000; font-weight:600;">“Transforming Lives”</em>.</p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin: 25px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/') }}" style="display:inline-block; background-color:#8B0000; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:2px; font-weight:700; font-size:14px; text-transform:uppercase; letter-spacing:0.5px;">Visit the Portal</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0; font-size:13px; line-height:1.5; color:#718096; border-left: 3px solid #ffde00; padding-left:12px; background-color:#f7f9fc; padding:10px 12px;">Tip: Add <a href="mailto:info@muni.ac.ug" style="color:#24AAE1; text-decoration:none;">info@muni.ac.ug</a> to your contacts so you never miss an update.</p>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color:#8B0000; padding: 20px 30px; border-top: 3px solid #ffde00;">
                            <p style="margin:0 0 8px; font-size:13px; color:#ffffff; font-weight:600;">Muni University — Transforming Lives</p>
                            <p style="margin:0 0 8px; font-size:12px; color:#ffde00;">Arua City, Uganda • P.O. Box 725</p>
                            <p style="margin:0 0 12px; font-size:12px; color:#ffffff; opacity:0.9;">
                                <a href="https://www.muni.ac.ug" style="color:#ffde00; text-decoration:none;">muni.ac.ug</a> &nbsp;|&nbsp;
                                <a href="https://news.muni.ac.ug" style="color:#ffde00; text-decoration:none;">news.muni.ac.ug</a> &nbsp;|&nbsp;
                                <a href="mailto:info@muni.ac.ug" style="color:#ffde00; text-decoration:none;">info@muni.ac.ug</a>
                            </p>
                            <p style="margin:0; font-size:11px; color:#ffffff; opacity:0.7;">&copy; {{ date('Y') }} Muni University. You’re receiving this because you subscribed at news.muni.ac.ug.<br>If you didn’t subscribe, please ignore this email.</p>
                        </td>
                    </tr>
                </table>
                <p style="margin:15px 0 0; font-size:11px; color:#718096; text-align:center;">This is an automated message, please do not reply directly.</p>
            </td>
        </tr>
    </table>
</body>
</html>
