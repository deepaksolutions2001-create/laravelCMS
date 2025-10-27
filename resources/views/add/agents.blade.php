{{-- resources/views/agents/form.blade.php --}}
@php $isEdit = isset($agent)?true:false; @endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $isEdit ? 'Edit Agent' : 'Add Agent' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50">
    <nav class="bg-white shadow-sm fixed top-0 left-0 right-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h12a1 1 0 001-1V10" />
                        </svg>
                    </a>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">{{ $isEdit ? 'Edit Agent' : 'Add Agent' }}</h1>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 rounded-lg p-4 mb-6">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ $isEdit ? route('update.agent', ['id'=>$agent->id]) : route('save.agent') }}"
                method="POST" enctype="multipart/form-data" class="space-y-8 bg-white rounded-xl shadow-md p-6">
                @csrf
                @if($isEdit) @method('PUT') @endif

                {{-- Identity --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Identity</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" name="agent_name" value="{{ old('name', $agent->name ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="agent_title" value="{{ old('title', $agent->title ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Senior Agent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" name="agent_email" value="{{ old('email', $agent->email ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Mobile</label>
                            <input type="text" name="agent_mobile" value="{{ old('mobile', $agent->mobile ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Account --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Account</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" name="agent_username" value="{{ old('username', $agent->username ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password {{ $isEdit ? '(leave blank to keep)' : '' }}</label>
                            <input type="password" name="agent_password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" {{ $isEdit ? '' : 'required' }}>
                        </div>
                    </div>
                </div>

                {{-- Photo --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Photo</h2>
                    <div class="flex items-center gap-6">
                        @php
                        $initials = collect(explode(' ', old('name', $agent->name ?? '')))->filter()->map(fn($w)=>substr($w,0,1))->take(2)->implode('') ?: 'AG';
                        @endphp
                        <div id="avatarPreviewWrap" class="w-20 h-20">
                            @if(!empty($agent?->image))
                            <img id="avatarPreview" src="{{ $agent->image }}" class="w-20 h-20 rounded-full object-cover border border-gray-200">
                            @else
                            <div id="avatarFallback" class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                {{ $initials }}
                            </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="agent_image" id="image" accept="image/*"
                                class="block w-full text-sm text-gray-700">
                            <p class="text-xs text-gray-500 mt-1">JPG/PNG, up to 2MB.</p>
                        </div>
                    </div>
                </div>

                {{-- Permissions and limits --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Permissions & Limits</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Can List Property</label>
                            @php $can = old('can_property_list', $agent->can_property_list ?? 0); @endphp
                            <select name="agent_can_property_list" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="0" {{ (string)$can==='0'?'selected':'' }}>No</option>
                                <option value="1" {{ (string)$can==='1'?'selected':'' }}>Yes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Required Approval</label>
                            @php $req = old('required_approval', $agent->required_approval ?? 1); @endphp
                            <select name="agent_required_approval" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="0" {{ (string)$req==='0'?'selected':'' }}>No</option>
                                <option value="1" {{ (string)$req==='1'?'selected':'' }}>Yes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Listings Limit</label>
                            <input type="number" name="agent_listings_limit" min="0"
                                value="{{ old('listings_limit', $agent->listings_limit ?? 0) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- API keys --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">API Credentials</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="text" name="agent_api_key" value="{{ old('api_key', $agent->api_key ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Secret</label>
                            <input type="text" name="agent_api_secret" value="{{ old('api_secret', $agent->api_secret ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Accesses (JSON as comma list) --}}
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Accesses</h2>
                    @php
                    $accessesArray = old('accesses_list');
                    if ($accessesArray === null && isset($agent->accesses) && is_array($agent->accesses)) {
                    $accessesArray = implode(', ', $agent->accesses);
                    }
                    @endphp
                    <input type="text" name="agent_accesses_list"
                        value="{{ $accessesArray ?? '' }}"
                        placeholder="e.g. listings.create, listings.edit, agents.view"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Comma separated; will be saved as JSON array.</p>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                        {{ $isEdit ? 'Update Agent' : 'Save Agent' }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Live avatar preview
        const file = document.getElementById('image');
        const wrap = document.getElementById('avatarPreviewWrap');

        file?.addEventListener('change', (e) => {
            const f = e.target.files?.[0];
            if (!f) return;
            const reader = new FileReader();
            reader.onload = ev => {
                wrap.innerHTML = `<img id="avatarPreview" src="${ev.target.result}" class="w-20 h-20 rounded-full object-cover border border-gray-200">`;
            };
            reader.readAsDataURL(f);
        });
    </script>
</body>

</html>