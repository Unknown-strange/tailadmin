<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <style>
      html, body { height: 100%; margin: 0; }
      body { display: grid; place-items: center; font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Helvetica Neue, Arial, "Apple Color Emoji", "Segoe UI Emoji"; background: #0f172a; color: #e2e8f0; }
      .card { text-align: center; }
      .logo { font-size: 48px; font-weight: 800; letter-spacing: 1px; }
      .meta { margin-top: 8px; color: #94a3b8; font-size: 14px; }
      a { color: #60a5fa; text-decoration: none; }
      a:hover { text-decoration: underline; }
    </style>
  </head>
  <body>
    <div class="card">
      <div class="logo">Laravel</div>
      <div class="meta">Version {{ app()->version() }}</div>
      <div class="meta" style="margin-top:16px;">
        <a href="https://laravel.com/docs" target="_blank" rel="noopener noreferrer">Documentation</a>
      </div>
    </div>
  </body>
  </html>


