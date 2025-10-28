{{-- resources/views/reviews/detail.blade.php --}}
@php
// Fallbacks and simple values (avoid advanced helpers)
$title = isset($review->title) ? $review->title : 'Review';
$rating = isset($review->data['rating']) ? (int)$review->data['rating'] : 0;
$status = isset($review->status) ? strtolower($review->status) : 'pending';

$badge = 'bg-amber-100 text-amber-800';
if ($status === 'approved') $badge = 'bg-green-100 text-green-800';
if ($status === 'rejected') $badge = 'bg-red-100 text-red-700';

$images = [];
if (isset($review->images) && is_array($review->images)) $images = $review->images;

// Simple pros/cons parsing if string CSV
$pros = [];
if (isset($review->pros)) {
if (is_array($review->pros)) $pros = $review->pros;
else $pros = array_filter(array_map('trim', explode(',', (string)$review->pros)));
}
$cons = [];
if (isset($review->cons)) {
if (is_array($review->cons)) $cons = $review->cons;
else $cons = array_filter(array_map('trim', explode(',', (string)$review->cons)));
}

// Side-card determination
// Expect a simple $type like 'agent' or 'property' (string) and $data (object/null)
$type = isset($type) ? strtolower((string)$type) : (isset($kind) ? strtolower((string)$kind) : '');
$showSide = !empty($data) && ($type === 'agent' || $type === 'property');

// Small date format without Carbon
$createdAt = isset($review->created_at) ? date('d M Y, h:i A', strtotime($review->created_at)) : '';
@endphp

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Review Detail</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome (replace with your own hosted version if needed) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="bg-gray-50">

    <!-- Header -->
    <header class="bg-white/80 backdrop-blur border-b border-gray-100 sticky top-0 z-20">
        <div class="max-w-6xl mx-auto h-14 px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ url()->previous() }}" class="text-gray-600 hover:text-gray-900 text-sm transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Back
                </a>
                <span class="text-sm text-gray-400">/</span>
                <h1 class="text-base font-semibold text-gray-900">Review Details</h1>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-full text-xs {{ $badge }}">{{ ucfirst($status) }}</span>
                @if(!empty($review->id))
                <span class="text-xs text-gray-400">#{{ $review->id }}</span>
                @endif
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 {{ $showSide ? 'lg:grid-cols-3' : '' }} gap-8">

            <!-- Review panel -->
            <section class="{{ $showSide ? 'lg:col-span-2' : 'lg:col-span-3' }}">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6" style="animation: fadeIn .4s ease both;">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $title }}</h2>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $createdAt }}
                                @if(!empty($review->data['email'])) · {{ $review->data['email'] }} @endif
                            </p>
                        </div>
                        <!-- Rating with Font Awesome -->
                        <div class="flex items-center gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if($i <=$rating)
                                <i class="fa-solid fa-star text-amber-500"></i>
                                @else
                                <i class="fa-regular fa-star text-gray-300"></i>
                                @endif
                                @endfor
                        </div>
                    </div>

                    <div class="mt-4 text-sm leading-6 text-gray-800 whitespace-pre-line">
                        {{ !empty($review->data['review']) ? $review->data['review'] : 'No review description' }}
                    </div>



                    @if(!empty($images))
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Attachments</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                            @foreach($images as $img)
                            @php
                            $src = $img;
                            // Basic URL normalization (no advanced helpers)
                            if (!empty($src)) {
                            $isHttp = strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0 || strpos($src, '/storage/') === 0;
                            if (!$isHttp) { $src = asset('storage/' . ltrim($src, '/')); }
                            }
                            @endphp
                            @if(!empty($src))
                            <a href="{{ $src }}" target="_blank" class="block">
                                <img src="{{ $src }}" class="w-full h-28 object-cover rounded-lg ring-1 ring-gray-200 hover:scale-[1.02] transition" alt="Attachment">
                            </a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </section>

            <!-- Side card (Agent/Property) -->
            @if($showSide)
            @php
            $isAgent = $type === 'agent';
            $avatar = null;
            if (!empty($data)) {
            if (!empty($data->image)) $avatar = $data->image;
            elseif (!empty($data->cover)) $avatar = $data->cover;
            }
            if (!empty($avatar)) {
            $isHttp = strpos($avatar, 'http://') === 0 || strpos($avatar, 'https://') === 0 || strpos($avatar, '/storage/') === 0;
            if (!$isHttp) { $avatar = asset('storage/' . ltrim($avatar, '/')); }
            }
            $titleRight = $isAgent ? (!empty($data->name) ? $data->name : 'Agent')
            : (!empty($data->title) ? $data->title : 'Property');
            $subRight = $isAgent ? (!empty($data->email) ? $data->email : (!empty($data->mobile) ? $data->mobile : ''))
            : (!empty($data->location) ? $data->location : '');
            @endphp

            <aside class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-100 p-6" style="animation: fadeInUp .38s ease both;">
                    <h3 class="text-sm font-semibold text-gray-900">
                        <i class="fa-solid fa-circle-info mr-1 text-blue-600"></i>
                        {{ $isAgent ? 'Agent Details' : 'Property Details' }}
                    </h3>

                    <div class="mt-4 flex items-start gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gray-100 ring-1 ring-gray-200 overflow-hidden flex items-center justify-center">
                            @if(!empty($avatar))
                            <img src="{{ $avatar }}" class="w-16 h-16 object-cover" alt="{{ $titleRight }}">
                            @else
                            <i class="fa-regular fa-image text-gray-400"></i>
                            @endif
                        </div>

                        <div class="min-w-0">
                            <div class="text-base font-semibold text-gray-900 truncate">{{ $titleRight }}</div>
                            @if(!empty($subRight))
                            <div class="text-sm text-gray-500 truncate">{{ $subRight }}</div>
                            @endif

                            @if($isAgent)
                            <div class="mt-2 flex flex-wrap gap-2">
                                @if(!empty($data->can_property_list))
                                <span class="px-2 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-700">
                                    <i class="fa-solid fa-list-check mr-1"></i> Can list
                                </span>
                                @endif
                                @if(!empty($data->required_approval))
                                <span class="px-2 py-0.5 rounded-full text-xs bg-amber-50 text-amber-700">
                                    <i class="fa-solid fa-user-shield mr-1"></i> Approval req.
                                </span>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('edit.agent',['id'=>$data->id]) }}"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fa-solid fa-user-pen mr-1"></i> Open Agent
                                </a>
                            </div>
                            @else
                            <div class="mt-2 text-sm text-gray-600 space-y-1">
                                @if(!empty($data->price))
                                <div><span class="text-gray-500">Price:</span> {{ $data->price }}</div>
                                @endif
                                @if(!empty($data->slug))
                                <div><span class="text-gray-500">Slug:</span> {{ $data->slug }}</div>
                                @endif
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('edit.property',['id'=>$data->id]) }}"
                                    class="text-sm text-blue-600 hover:text-blue-800">
                                    <i class="fa-solid fa-house-user mr-1"></i> Open Property
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>
            @endif
        </div>
    </main>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0
            }

            to {
                opacity: 1
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }
    </style>
</body>

</html>