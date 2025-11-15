<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:


This project is a Laravel application skeleton that provides an intermediary API to work with the Google Gemini generative API.

Endpoints added (api/v1/marketing/*):
- POST /api/v1/marketing/facebook — generate a Facebook post text using input prompt.
- POST /api/v1/marketing/instagram — generate an Instagram caption using input prompt.
- POST /api/v1/marketing/podcast — generate a podcast script/outline using input prompt.
- POST /api/v1/marketing/image — generate an image from prompt.
- POST /api/v1/marketing/generation/audio — generate audio from prompt (TTS) and save it on the server.
- GET  /api/v1/marketing/generation/audio/list — return a list of generated audios (up to 20 recent audio files).
- POST /api/v1/marketing/generation/audio/send — send/download a generated audio by ID (body: `{"id": "<audio-id>"}`).
- POST /api/v1/marketing/video — generate video script and visual guidance from prompt.
