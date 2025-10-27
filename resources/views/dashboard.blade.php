<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        .nav-item {
            transition: all 0.2s ease;
        }

        .nav-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .nav-item:not(.active):hover {
            background-color: #f3f4f6;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 50;
            overflow-y: auto;
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-backdrop {
            position: fixed;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 0.75rem;
            max-width: 600px;
            width: 90%;
            margin: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Sidebar toggle for mobile */
        .sidebar {
            transition: transform 0.3s ease;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 40;
                height: 100vh;
            }

            .sidebar.open {
                transform: translateX(0);
            }
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm fixed top-0 left-0 right-0 z-30">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left: Menu Button + Title -->
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">CMS Dashboard</h1>
                </div>

                <!-- Right: User Info -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-sm font-medium text-gray-800">{{ session('user_name') }}</p>
                        <p class="text-xs text-gray-500">{{ session('user_email') }}</p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">
                        DS
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Message (if any) -->
    <div id="successMessage" class="hidden fixed top-20 right-4 bg-green-50 border border-green-200 rounded-lg p-4 shadow-lg z-50 fade-in max-w-md">
        <div class="flex items-start">
            <svg class="h-5 w-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Page created successfully!</p>
            </div>
            <button onclick="closeSuccessMessage()" class="ml-auto text-green-500 hover:text-green-700">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>

    <div class="flex pt-16">
        <!-- Left Sidebar Navigation -->
        <aside id="sidebar" class="sidebar w-64 bg-white shadow-lg">
            <div class="p-4 border-b border-gray-200">
                <div class="flex items-center space-x-3 p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-lg">
                        DS
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ session('user_name') }}</p>
                        <p class="text-xs text-gray-600">{{ session('user_email') }}</p>
                    </div>
                </div>
            </div>

            <nav class="p-4 space-y-2">
                <button onclick="showTab('dashboard')" class="nav-item active w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </button>

                <button onclick="showTab('profile')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="font-medium">Profile</span>
                </button>

                <button onclick="showTab('websites')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">My Websites</span>
                </button>

                <button onclick="showTab('properties')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="font-medium">Properties</span>
                </button>

                <button onclick="showTab('agents')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="font-medium">Agents</span>
                </button>

                <button onclick="showTab('subscribers')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Subscribers</span>
                </button>

                <button onclick="showTab('reviews')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                    </svg>
                    <span class="font-medium">Reviews</span>
                </button>

                <button onclick="showTab('formdata')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Form Data</span>
                </button>

                <button onclick="showTab('blog')" class="nav-item w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left text-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span class="font-medium">Blog</span>
                </button>
                <form action="{{ route('logout.user')  }}" method="post">
                    @csrf
                    <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Sign Out</span>
                    </button>
                </form>

            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 min-h-screen">
            <!-- Dashboard Tab -->
            <div id="dashboard" class="tab-content active">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Overview</h2>
                    <p class="text-gray-600 mt-1">Welcome back,
                        {{ session('user_name') }}! Here's what's happening today.
                    </p>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Total Pages</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">24</h3>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Properties</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">156</h3>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Agents</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">42</h3>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 fade-in">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm">Subscribers</p>
                                <h3 class="text-2xl font-bold text-gray-800 mt-1">1,284</h3>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-md p-6 fade-in">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                    <div class="space-y-4">
                        <div class="flex items-center space-x-4 pb-4 border-b border-gray-100">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">New page created</p>
                                <p class="text-xs text-gray-500">Luxury Villa in Sydney - 2 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 pb-4 border-b border-gray-100">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">Property published</p>
                                <p class="text-xs text-gray-500">Modern Apartment Melbourne - 5 hours ago</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">New agent registered</p>
                                <p class="text-xs text-gray-500">Sarah Johnson - Yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Tab -->
            <div id="profile" class="tab-content">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Profile Settings</h2>
                    <p class="text-gray-600 mt-1">Manage your account information and preferences</p>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center space-x-6 mb-6 pb-6 border-b border-gray-200">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            DS
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-800">{{ session('user_name') }}</h3>
                            <p class="text-gray-600">{{ session('user_email') }}</p>
                            <button class="mt-2 text-blue-600 hover:text-blue-700 text-sm font-medium">Change Photo</button>
                        </div>
                    </div>

                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">{{ session('user_name') }}</label>
                                <input type="text" value="Dalbir" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ session('user_email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" value="{{  session('user_phone')}}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Tell us about yourself..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Cancel</button>
                            <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Websites Tab -->
            <div id="websites" class="tab-content">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">My Websites</h2>
                        <p class="text-gray-600 mt-1">Manage all your website pages</p>
                    </div>
                    <button onclick="openModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Page
                    </button>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                                    <!-- ✅ Changed text-left to text-right -->
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($pages as $page)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $page->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $page->meta_description }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $page->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $page->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $page->updated_at->format('d M Y') }}</td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-end gap-3">
                                            <!-- Edit -->
                                            <a href="{{ route('pages.edit', ['id' => $page->id]) }}"
                                                class="text-blue-600 hover:text-blue-800 flex items-center gap-1"
                                                title="Edit Page">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                <span>Edit</span>
                                            </a>

                                            <!-- View -->
                                            <a href="{{ route('pages.preview', ['id' => $page->id]) }}"
                                                class="text-green-600 hover:text-green-800 flex items-center gap-1"
                                                title="View Page">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>View</span>
                                            </a>

                                            <!-- Delete -->
                                            <form action="{{ route('pages.delete', ['id' => $page->id]) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this page?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 flex items-center gap-1"
                                                    title="Delete Page">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    <span>Delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <!-- Properties Tab -->
            <div id="properties" class="tab-content">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Properties</h2>
                        <p class="text-gray-600 mt-1">Manage your real estate listings</p>
                    </div>
                    <a href="{{ route('add.property') }}"> <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Property
                        </button></a>
                </div>

                <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Luxury Villa</h3>
                            <p class="text-gray-600 text-sm mb-3">5 bed • 4 bath • 3500 sq ft</p>
                            <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-blue-600">$1,250,000</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">For Sale</span>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- NEW_STR -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $p)
                    @php
                    $coverPath = ($p->images && count($p->images)) ? $p->images[0] : null;
                    $coverUrl = $coverPath ? Storage::url($coverPath) : null; // public disk URL

                    $facts = trim(
                    ($p->bedrooms ? $p->bedrooms.' bed' : '')
                    .' • '
                    .($p->bathrooms ? $p->bathrooms.' bath' : '')
                    .' • '
                    .($p->land_size ? number_format($p->land_size).' '.($p->land_size_type ?? '') : ''),
                    ' •'
                    );

                    $price = ($p->min_price && $p->max_price)
                    ? '$'.number_format($p->min_price).' - $'.number_format($p->max_price)
                    : ($p->min_price ? '$'.number_format($p->min_price) : '—');
                    @endphp

                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
                        <div class="h-48 {{ $coverUrl ? '' : 'bg-gradient-to-br from-blue-400 to-blue-600' }}">
                            @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="{{ $p->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $p->title }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ $facts ?: '—' }}</p>
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xl font-bold text-blue-600">{{ $price }}</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">{{ $p->contract }}</span>
                            </div>

                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('edit.property',['id'=>$p->id]) }}"
                                    class="px-3 py-1.5 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition">
                                    Edit
                                </a>

                                <form action="{{ route('delete.property',['id'=>$p->id]) }}" method="POST"
                                    onsubmit="return confirm('Delete this property?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $properties->links() }}
            </div>




    </div>

    <!-- Agents Tab -->
    <div id="agents" class="tab-content">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Real Estate Agents</h2>
                <p class="text-gray-600 mt-1">Manage your agent team</p>
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Agent
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">SJ</div>
                <h3 class="text-lg font-semibold text-gray-800">Sarah Johnson</h3>
                <p class="text-gray-600 text-sm mb-3">Senior Agent</p>
                <div class="flex justify-center space-x-4 text-sm text-gray-600 mb-4">
                    <span>23 Properties</span>
                    <span>•</span>
                    <span>4.8 ★</span>
                </div>
                <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">View Profile</button>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">MB</div>
                <h3 class="text-lg font-semibold text-gray-800">Michael Brown</h3>
                <p class="text-gray-600 text-sm mb-3">Property Specialist</p>
                <div class="flex justify-center space-x-4 text-sm text-gray-600 mb-4">
                    <span>18 Properties</span>
                    <span>•</span>
                    <span>4.9 ★</span>
                </div>
                <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">View Profile</button>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 text-center">
                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4">ED</div>
                <h3 class="text-lg font-semibold text-gray-800">Emily Davis</h3>
                <p class="text-gray-600 text-sm mb-3">Luxury Homes Expert</p>
                <div class="flex justify-center space-x-4 text-sm text-gray-600 mb-4">
                    <span>31 Properties</span>
                    <span>•</span>
                    <span>5.0 ★</span>
                </div>
                <button class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition">View Profile</button>
            </div>
        </div>
    </div>

    <!-- Subscribers Tab -->
    <div id="subscribers" class="tab-content">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Email Subscribers</h2>
            <p class="text-gray-600 mt-1">Manage your newsletter subscribers</p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Total Subscribers</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">1,284</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Active</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">1,198</p>
                </div>
                <div class="text-center">
                    <p class="text-gray-500 text-sm">Unsubscribed</p>
                    <p class="text-3xl font-bold text-red-600 mt-2">86</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subscribed Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">john.doe@example.com</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span></td>
                            <td class="px-6 py-4 text-sm text-gray-500">20 Oct 2025</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-red-600 hover:text-red-800">Remove</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">jane.smith@example.com</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Active</span></td>
                            <td class="px-6 py-4 text-sm text-gray-500">18 Oct 2025</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-red-600 hover:text-red-800">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Reviews Tab -->
    <div id="reviews" class="tab-content">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Customer Reviews</h2>
            <p class="text-gray-600 mt-1">Manage property and agent reviews</p>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold">JD</div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gray-800">John Doe</h4>
                            <div class="flex items-center text-yellow-500">
                                <span class="mr-1">★★★★★</span>
                                <span class="text-gray-600 text-sm">5.0</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-2">Amazing property! The location is perfect and the agent was very professional. Highly recommended!</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>Luxury Villa Sydney</span>
                            <span>2 days ago</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold">SK</div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-lg font-semibold text-gray-800">Sarah Kim</h4>
                            <div class="flex items-center text-yellow-500">
                                <span class="mr-1">★★★★☆</span>
                                <span class="text-gray-600 text-sm">4.0</span>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-2">Great experience overall. The apartment is modern and well-maintained. Would definitely recommend to friends.</p>
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <span>Melbourne Apartment</span>
                            <span>1 week ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Data Tab -->
    <div id="formdata" class="tab-content">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Form Submissions</h2>
            <p class="text-gray-600 mt-1">View contact and inquiry forms</p>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Property</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Robert Miller</td>
                            <td class="px-6 py-4 text-sm text-gray-600">robert.m@email.com</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Luxury Villa</td>
                            <td class="px-6 py-4 text-sm text-gray-500">24 Oct 2025</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-blue-600 hover:text-blue-800">View</button>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">Lisa Anderson</td>
                            <td class="px-6 py-4 text-sm text-gray-600">lisa.a@email.com</td>
                            <td class="px-6 py-4 text-sm text-gray-600">Beach House</td>
                            <td class="px-6 py-4 text-sm text-gray-500">23 Oct 2025</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-blue-600 hover:text-blue-800">View</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Blog Tab -->
    <div id="blog" class="tab-content">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Blog Posts</h2>
                <p class="text-gray-600 mt-1">Manage your blog content</p>
            </div>
            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Post
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                <div class="p-6">
                    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                        <span>Real Estate Tips</span>
                        <span>•</span>
                        <span>5 min read</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">10 Tips for First-Time Home Buyers</h3>
                    <p class="text-gray-600 mb-4">Essential advice for navigating your first property purchase in Australia...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">20 Oct 2025</span>
                        <div class="space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Edit</button>
                            <button class="text-green-600 hover:text-green-800">View</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="h-48 bg-gradient-to-br from-green-400 to-green-600"></div>
                <div class="p-6">
                    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                        <span>Market Trends</span>
                        <span>•</span>
                        <span>8 min read</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Sydney Real Estate Market Report 2025</h3>
                    <p class="text-gray-600 mb-4">Comprehensive analysis of current trends and future predictions...</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">18 Oct 2025</span>
                        <div class="space-x-2">
                            <button class="text-blue-600 hover:text-blue-800">Edit</button>
                            <button class="text-green-600 hover:text-green-800">View</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </main>
    </div>

    <!-- Create Page Modal -->
    <div id="createPageModal" class="modal">
        <div class="modal-backdrop" onclick="closeModal()"></div>
        <div class="modal-content">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Create New Page</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form action="{{ route('pages.create') }}" method="post">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Page Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required placeholder="Enter page title" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description <span class="text-red-500">*</span></label>
                        <textarea rows="4" name="meta_description" required placeholder="Enter SEO description (150-160 characters)" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none"></textarea>
                        <p class="mt-1 text-xs text-gray-500">This appears in search results and can be edited later.</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Page
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab switching
        function showTab(tabName) {
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });

            // Show selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to clicked nav item
            event.currentTarget.classList.add('active');

            // Close sidebar on mobile after selection
            if (window.innerWidth < 1024) {
                document.getElementById('sidebar').classList.remove('open');
            }
        }

        // Sidebar toggle for mobile
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
        }

        // Modal functions
        function openModal() {
            document.getElementById('createPageModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('createPageModal').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Success message
        function closeSuccessMessage() {
            document.getElementById('successMessage').classList.add('hidden');
        }

        // Close modal on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            const isClickInsideSidebar = sidebar.contains(e.target);
            const isMenuButton = e.target.closest('button[onclick="toggleSidebar()"]');

            if (!isClickInsideSidebar && !isMenuButton && window.innerWidth < 1024) {
                sidebar.classList.remove('open');
            }
        });
    </script>
</body>

</html>