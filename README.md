# regif

A PHP web service that reverses GIFs. Give it a URL pointing to a `.gif`, get back the same GIF playing in reverse.

## What It Does

Send a GET request with a `url` parameter pointing to a GIF, and regif will:
1. Download the GIF
2. Decode it into individual frames
3. Reverse the frame order
4. Re-encode it as a new GIF
5. Stream it back directly as `image/gif`

The output file is cleaned up immediately after streaming — no storage footprint.

## How to Use

```
GET /reverse.php?url=https://example.com/animation.gif
```

Returns the reversed GIF as a binary response. Embed directly in an `<img>` tag or download it.

## How to Run

Requires PHP and Composer.

```bash
composer install
```

Then serve with any PHP-capable web server (Apache, Nginx + php-fpm, or `php -S localhost:8000`).

Create writable `img/` and `out/` directories:
```bash
mkdir img out
chmod 777 img out
```

## Dependencies

- [`stil/gif-endec`](https://github.com/stil/gif-endec) — GIF encode/decode library (via Composer)

## Notes

Built in 2016. Simple and does exactly one thing. The `url` parameter is passed directly to `file_get_contents()` — not suitable for public-facing deployment without input validation.
