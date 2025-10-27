{{-- resources/views/properties/form.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<?php $val = isset($vals) ? true : false ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $val ? 'Edit Property' : 'Add Property' }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Inter (luxury, high-legibility) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">

    <!-- Remix Icon (font icons) -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.3.0/remixicon.min.css"
        integrity="sha512-uqgk5o2XyKZ0H2N0hvsHBC6nQX4vY0qNqBQGqVAUlxk9b7HwX5BxQGQk8Z0k2t57zG8c0oJkR3QWcL5v6S7qkA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        :root {
            --card-blur: 10px;
            --glass-bg: rgba(255, 255, 255, .68);
            --glass-ring: rgba(255, 255, 255, .45);
        }

        body {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica Neue, Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .fade-in {
            animation: fadeIn .4s ease-out
        }

        /* Glass nav */
        .glass-nav {
            background: rgba(255, 255, 255, .6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, .35);
            box-shadow: 0 8px 30px rgba(0, 0, 0, .06);
        }

        /* Glass card */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(var(--card-blur));
            -webkit-backdrop-filter: blur(var(--card-blur));
            border: 1px solid var(--glass-ring);
            border-radius: 16px;
            box-shadow: 0 15px 35px rgba(16, 24, 40, .12);
            transition: box-shadow .2s ease, transform .2s ease;
        }

        .glass-card:hover {
            box-shadow: 0 22px 48px rgba(16, 24, 40, .16);
            transform: translateY(-2px)
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: .5rem
        }

        /* Global polish */
        input,
        select,
        textarea,
        button {
            transition: box-shadow .25s ease, transform .2s ease, background-color .25s ease, border-color .25s ease
        }

        /* Small icon motion */
        .icon-float {
            transition: transform .3s ease, opacity .3s ease
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Glass top bar -->
    <nav class="glass-nav fixed top-0 left-0 right-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="" class="group text-gray-700 hover:text-gray-900 rounded-lg p-2 hover:bg-white/50 transition">
                        <i class="ri-arrow-left-line text-xl icon-float group-hover:-translate-x-0.5 group-hover:scale-110"></i>
                    </a>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">
                        {{ $val ? 'Edit Property' : 'Add Property' }}
                    </h1>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-medium text-gray-800">{{ session('user_name') ?? 'User' }}</p>
                        <p class="text-xs text-gray-500">{{ session('user_email') ?? '' }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(session('user_name') ?? 'U',0,1)) }}
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6 fade-in">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ $val ? route('update.property', $property->id) : route('save.property') }}"
                method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @if($val) @method('PUT') @endif

                {{-- Basics --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php $t = old('prop_type', $property->type ?? ''); @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="prop_type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select</option>
                                <option value="house" {{ $t==='house'?'selected':'' }}>House</option>
                                <option value="apartment" {{ $t==='apartment'?'selected':'' }}>Apartment</option>
                                <option value="townhouse" {{ $t==='townhouse'?'selected':'' }}>Townhouse</option>
                                <option value="land" {{ $t==='land'?'selected':'' }}>Land</option>
                                <option value="commercial" {{ $t==='commercial'?'selected':'' }}>Commercial</option>
                            </select>
                        </div>
                        @php $s = old('prop_status', $property->status ?? 'draft'); @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="prop_status" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="draft" {{ $s==='draft'?'selected':'' }}>Draft</option>
                                <option value="published" {{ $s==='published'?'selected':'' }}>Published</option>
                                <option value="archived" {{ $s==='archived'?'selected':'' }}>Archived</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="prop_title"
                                value="{{ old('prop_title', $property->title ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea name="prop_description" rows="5"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('prop_description', $property->description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Address --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Address</h2>
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Number</label>
                            <input type="text" name="prop_unit_number" value="{{ old('prop_unit_number', $property->unit_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Number</label>
                            <input type="text" name="prop_street_number" value="{{ old('prop_street_number', $property->street_number ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Name</label>
                            <input type="text" name="prop_street_name" value="{{ old('prop_street_name', $property->street_name ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" name="prop_city" value="{{ old('prop_city', $property->city ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <input type="text" name="prop_state" value="{{ old('prop_state', $property->state ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" name="prop_postal_code" value="{{ old('prop_postal_code', $property->postal_code ?? '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Details</h2>
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                            <input type="number" name="prop_bedrooms" value="{{ old('prop_bedrooms', $property->bedrooms ?? '') }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                            <input type="number" name="prop_bathrooms" value="{{ old('prop_bathrooms', $property->bathrooms ?? '') }}" min="0" step=".5" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Car Spaces</label>
                            <input type="number" name="prop_car_spaces" value="{{ old('prop_car_spaces', $property->car_spaces ?? '') }}" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Land Size</label>
                            <input type="number" name="prop_land_size" value="{{ old('prop_land_size', $property->land_size ?? '') }}" min="0" step=".01" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Size Type</label>
                            @php $lt = old('prop_land_size_type', $property->land_size_type ?? ''); @endphp
                            <select name="prop_land_size_type" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="sqm" {{ $lt==='sqm'?'selected':'' }}>sqm</option>
                                <option value="sqft" {{ $lt==='sqft'?'selected':'' }}>sq ft</option>
                                <option value="acre" {{ $lt==='acre'?'selected':'' }}>acre</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Media --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Media</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Images</label>
                            <input type="file" name="images[]" accept="image/*" multiple id="images" class="block w-full text-sm">
                            <div id="imagePreview" class="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>

                            @if($val && !empty($property->images))
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Images</p>
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                    @foreach($property->images as $idx => $path)
                                    <label class="block">
                                        <img src="{{ Storage::url($path) }}" class="w-full h-20 object-cover rounded-lg" alt="image {{ $idx }}">
                                        <div class="mt-1 flex items-center gap-2 text-xs">
                                            <input type="checkbox" name="remove_images[]" value="{{ $idx }}" class="rounded">
                                            <span>Remove</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Videos</label>
                            <input type="file" name="videos[]" accept="video/*" multiple id="videos" class="block w-full text-sm">
                            <ul id="videoList" class="mt-3 text-sm text-gray-600 space-y-1"></ul>

                            @if($val && !empty($property->videos))
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Videos</p>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($property->videos as $idx => $path)
                                    <li class="flex items-center justify-between">
                                        <a href="{{ Storage::url($path) }}" target="_blank" class="text-blue-600 hover:underline">Video {{ $idx+1 }}</a>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" name="remove_videos[]" value="{{ $idx }}" class="rounded">
                                            <span>Remove</span>
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Floor Plan Images</label>
                            <input type="file" name="floor_plan_images[]" accept="image/*" multiple id="floorplans" class="block w-full text-sm">
                            <div id="floorPreview" class="mt-4 grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3"></div>

                            @if($val && !empty($property->floor_plan_images))
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Floor Plans</p>
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                    @foreach($property->floor_plan_images as $idx => $path)
                                    <label class="block">
                                        <img src="{{ Storage::url($path) }}" class="w-full h-20 object-cover rounded-lg" alt="floor {{ $idx }}">
                                        <div class="mt-1 flex items-center gap-2 text-xs">
                                            <input type="checkbox" name="remove_floor_plans[]" value="{{ $idx }}" class="rounded">
                                            <span>Remove</span>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Documents (PDF/Docs)</label>
                            <input type="file" name="documents[]" accept=".pdf,.doc,.docx" multiple id="docs" class="block w-full text-sm">
                            <input type="text" name="documents_name" value="{{ old('documents_name', $property->documents_name ?? '') }}" placeholder="Document group name (optional)" class="mt-3 w-full px-4 py-2 border border-gray-300 rounded-lg">
                            <ul id="docList" class="mt-3 text-sm text-gray-600 space-y-1"></ul>

                            @if($val && !empty($property->documents))
                            <div class="mt-4">
                                <p class="text-sm font-medium text-gray-700 mb-2">Current Documents</p>
                                <ul class="text-sm text-gray-600 space-y-1">
                                    @foreach($property->documents as $idx => $path)
                                    <li class="flex items-center justify-between">
                                        <a href="{{ Storage::url($path) }}" target="_blank" class="text-blue-600 hover:underline">Document {{ $idx+1 }}</a>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="checkbox" name="remove_documents[]" value="{{ $idx }}" class="rounded">
                                            <span>Remove</span>
                                        </label>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Pricing --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pricing</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        @php $c = old('prop_contract', $property->contract ?? 'sale'); @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contract</label>
                            <select name="prop_contract" id="contract" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select</option>
                                <option value="sale" {{ $c==='sale'?'selected':'' }}>Sale</option>
                                <option value="rent" {{ $c==='rent'?'selected':'' }}>Rent</option>
                                <option value="sold" {{ $c==='sold'?'selected':'' }}>Sold</option>
                                <option value="leased" {{ $c==='leased'?'selected':'' }}>Leased</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                            <input type="number" name="prop_min_price" id="min_price" value="{{ old('prop_min_price', $property->min_price ?? '') }}" min="0" step="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                            <input type="number" name="prop_max_price" id="max_price" value="{{ old('prop_max_price', $property->max_price ?? '') }}" min="0" step="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Map URL</label>
                            <input type="url" name="prop_map_url" value="{{ old('prop_map_url', $property->map_url ?? '') }}" placeholder="https://maps.google.com/..." class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    <p class="mt-3 text-xs text-gray-500">Prices display as a range if both values provided.</p>
                </div>

                {{-- Assignment --}}
                <div class="glass-card rounded-2xl p-6 fade-in">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Assignment</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Agents (comma separated)</label>
                            <input type="text" name="prop_agents" value="{{ old('prop_agents', isset($property->agents) ? implode(', ', $property->agents) : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Inspection Times (comma separated)</label>
                            <input type="text" name="prop_inspection" value="{{ old('prop_inspection', isset($property->inspection) ? implode(', ', $property->inspection) : '') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit"
                        class="group px-5 py-2.5 rounded-xl inline-flex items-center text-white
                         bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600
                         hover:from-blue-500 hover:to-purple-500
                         shadow-lg shadow-indigo-500/20 ring-1 ring-white/20 hover:ring-white/40
                         transition-all duration-300 hover:scale-[1.02] active:scale-95">
                        <i class="ri-add-line mr-2 transition-transform duration-300 group-hover:rotate-90"></i>
                        {{ $val ? 'Update Property' : 'Save Property' }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Images preview
        const images = document.getElementById('images');
        const imagePreview = document.getElementById('imagePreview');
        images?.addEventListener('change', (e) => {
            imagePreview.innerHTML = '';
            [...e.target.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = ev => {
                    const d = document.createElement('div');
                    d.className = 'thumb w-20 h-20';
                    d.innerHTML = `<img src="${ev.target.result}" alt="${file.name}">`;
                    imagePreview.appendChild(d);
                };
                reader.readAsDataURL(file);
            });
        });

        // Floor plan preview
        const floor = document.getElementById('floorplans');
        const floorPreview = document.getElementById('floorPreview');
        floor?.addEventListener('change', (e) => {
            floorPreview.innerHTML = '';
            [...e.target.files].forEach(file => {
                const reader = new FileReader();
                reader.onload = ev => {
                    const d = document.createElement('div');
                    d.className = 'thumb w-20 h-20';
                    d.innerHTML = `<img src="${ev.target.result}" alt="${file.name}">`;
                    floorPreview.appendChild(d);
                };
                reader.readAsDataURL(file);
            });
        });

        // Video / doc file lists
        const videos = document.getElementById('videos');
        const videoList = document.getElementById('videoList');
        videos?.addEventListener('change', (e) => {
            videoList.innerHTML = '';
            [...e.target.files].forEach(f => {
                const li = document.createElement('li');
                li.textContent = `• ${f.name}`;
                videoList.appendChild(li);
            });
        });

        const docs = document.getElementById('docs');
        const docList = document.getElementById('docList');
        docs?.addEventListener('change', (e) => {
            docList.innerHTML = '';
            [...e.target.files].forEach(f => {
                const li = document.createElement('li');
                li.textContent = `• ${f.name}`;
                docList.appendChild(li);
            });
        });

        // Contract pulse
        const minP = document.getElementById('min_price');
        const maxP = document.getElementById('max_price');
        const contract = document.getElementById('contract');
        const pulse = (el) => {
            if (!el) return;
            el.classList.add('ring-2', 'ring-blue-300');
            setTimeout(() => el.classList.remove('ring-2', 'ring-blue-300'), 300);
        };
        contract?.addEventListener('change', () => {
            pulse(minP);
            pulse(maxP);
        });
    </script>
</body>

</html>