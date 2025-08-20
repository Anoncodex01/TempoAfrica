<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDF Export Not Available</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #333; }
        .container { max-width: 500px; margin: 60px auto; background: #fff; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 40px; text-align: center; }
        .icon { font-size: 48px; color: #d71418; margin-bottom: 16px; }
        .title { font-size: 1.5rem; font-weight: bold; margin-bottom: 12px; }
        .desc { color: #666; margin-bottom: 24px; }
        .cmd { background: #f3f4f6; color: #d71418; padding: 8px 16px; border-radius: 8px; font-family: monospace; display: inline-block; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon"><i class="fas fa-file-pdf"></i></div>
        <div class="title">PDF Export Not Available</div>
        <div class="desc">To enable PDF export, please install <b>barryvdh/laravel-dompdf</b>:</div>
        <div class="cmd">composer require barryvdh/laravel-dompdf</div>
        <div class="desc">After installing, try exporting again.</div>
        <a href="{{ url()->previous() }}" style="display:inline-block;margin-top:24px;color:#fff;background:#d71418;padding:10px 24px;border-radius:8px;text-decoration:none;font-weight:600;">Back</a>
    </div>
</body>
</html> 