<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- 🏷️ Dynamic Title: Use meta_title if available, else fallback to page title -->
  <title>{{ $page->meta_title ?? $page->title }}</title>

  <!-- 🧠 SEO Meta Tags -->
  <meta name="description" content="{{ $page->meta_description }}">
  <meta name="keywords" content="{{ $page->meta_keywords }}">

  <!-- 🖼️ Open Graph (OG) Image for social media preview -->
  <meta property="og:image" content="{{ $page->meta_og_image }}">

  <!-- 🎨 Inject page-specific CSS -->
  <style>{!! $page->css !!}</style>
</head>

<body>
  <!-- 🧱 Render saved HTML content from GrapesJS editor -->
  {!! $page->html !!}
</body>
</html>
