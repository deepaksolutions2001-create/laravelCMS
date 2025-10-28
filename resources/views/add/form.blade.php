<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Form Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- TailwindCSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-50">

    <div class="max-w-5xl mx-auto p-6">
        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <i class="fa-solid fa-file-lines text-blue-600"></i>
                Form Details
            </h1>
            <p class="text-gray-600">Full information for this submission.</p>
        </div>

        {{-- Summary card --}}
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-hashtag text-gray-400"></i>
                    <span class="text-gray-500">ID:</span>
                    <span class="text-gray-900 font-medium">{{ $submission->id }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-list text-gray-400"></i>
                    <span class="text-gray-500">Type:</span>
                    <span class="text-gray-900 font-medium capitalize">
                        {{ str_replace('_',' ', (string)$submission->form_type) }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-user text-gray-400"></i>
                    <span class="text-gray-500">Name:</span>
                    <span class="text-gray-900">{{ $submission->name ?? '-' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-bullhorn text-gray-400"></i>
                    <span class="text-gray-500">Source:</span>
                    <span class="text-gray-900">{{ $submission->source ?? '-' }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-gray-400"></i>
                    <span class="text-gray-500">Created:</span>
                    <span class="text-gray-900">
                        {{ isset($submission->created_at) ? \Illuminate\Support\Carbon::parse($submission->created_at)->format('d M Y, h:i A') : '-' }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-rotate text-gray-400"></i>
                    <span class="text-gray-500">Updated:</span>
                    <span class="text-gray-900">
                        {{ isset($submission->updated_at) ? \Illuminate\Support\Carbon::parse($submission->updated_at)->format('d M Y, h:i A') : '-' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Form data --}}
        <div class="mt-6 bg-white rounded-xl shadow border border-gray-100">
            <div class="px-6 py-4 border-b bg-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-database text-blue-600"></i>
                    <h2 class="font-semibold text-gray-800">Form Data</h2>
                </div>

            </div>

            <div class="p-6">
                @php
                // Recursive renderer: prints nested arrays/objects with path labels
                $render = function ($value, $keyPath = '') use (&$render) {
                if (is_array($value)) {
                echo '<div class="space-y-3">';
                    foreach ($value as $k => $v) {
                    $path = $keyPath === '' ? (string)$k : $keyPath.'.'.$k;
                    echo '<div class="border-b last:border-0 border-gray-100 pb-3">';
                        echo '<div class="text-xs uppercase tracking-wide text-gray-500 mb-1">'.\Illuminate\Support\Str::headline((string)$path).'</div>';
                        if (is_array($v)) {
                        echo '<div class="rounded-lg bg-gray-50 p-3">';
                            $render($v, $path);
                            echo '</div>';
                        } else {
                        $display = is_bool($v) ? ($v ? 'true' : 'false') : (string)$v;
                        echo '<div class="text-gray-900">'.e($display).'</div>';
                        }
                        echo '</div>';
                    }
                    echo '</div>';
                } else {
                $display = is_bool($value) ? ($value ? 'true' : 'false') : (string)$value;
                echo '<div class="text-gray-900">'.e($display).'</div>';
                }
                };
                @endphp

                @if (!empty($submission->data))
                {!! $render(is_array($submission->data) ? $submission->data : (array)$submission->data) !!}
                @else
                <div class="text-gray-500">No data available.</div>
                @endif
            </div>
        </div>

        {{-- Actions --}}
        <div class="mt-6 flex items-center gap-3">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">
                <i class="fa-solid fa-arrow-left"></i> Back
            </a>

            {{-- Replace href with your index route for this form type --}}
            <form action="{{ route('delete.form',['id'=>$submission->id]) }}" method="post" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">
                @csrf
                @method('DELETE')
                <button><i class="fa-solid fa-list"></i>
                    Delete</button>
            </form>
        </div>
    </div>

</body>

</html>