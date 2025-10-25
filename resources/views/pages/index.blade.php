<h1>All Pages</h1>

<a href="{{ route('pages.create') }}">Create New Page</a>
{{ session('user_id') }}

<ul>
  @foreach($pages as $page)
  <li>
    {{ $page->title }} —
    <a href="{{ route('pages.edit', $page->id) }}">Edit</a>
  </li>
  @endforeach
</ul>