{{-- Standalone error page: no database, cache, or Vite, so it still renders when the app itself is broken. --}}
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="robots" content="noindex">
        <title>@yield('title') - {{ config('app.name') }}</title>
        <style>
            body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 1.5rem; box-sizing: border-box; background: #0f172a; color: #cbd5e1; font-family: ui-sans-serif, system-ui, sans-serif; text-align: center; }
            .code { margin: 0; font-size: .875rem; font-weight: 600; letter-spacing: .1em; text-transform: uppercase; color: #fbbf24; }
            h1 { margin: .75rem 0 0; font-size: 1.875rem; font-weight: 600; color: #fff; }
            p.message { max-width: 32rem; margin: 1rem auto 0; line-height: 1.6; }
            a { display: inline-block; margin-top: 2.5rem; padding: .5rem 1rem; border-radius: .375rem; background: #fbbf24; color: #0f172a; font-size: .875rem; font-weight: 600; text-decoration: none; }
        </style>
    </head>
    <body>
        <main>
            <p class="code">Error @yield('code')</p>
            <h1>@yield('title')</h1>
            <p class="message">@yield('message')</p>
            <a href="{{ url('/') }}">Kembali ke Beranda</a>
        </main>
    </body>
</html>
