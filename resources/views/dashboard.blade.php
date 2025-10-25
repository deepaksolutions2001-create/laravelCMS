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

        .hover-scale {
            transition: transform 0.2s ease;
        }

        .hover-scale:hover {
            transform: scale(1.02);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-lg sm:text-xl font-semibold text-gray-800">CMS Dashboard</h1>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <!-- Left Side - User Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-4 sm:p-6 fade-in">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mb-4">
                            <!-- Display first letter of user name -->
                            DS
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-1">
                            <!-- Display user name -->
                            {{ session('user_name') }}
                        </h2>
                        <p class="text-gray-600 text-sm break-all">
                            <!-- Display user email -->
                            {{ session('user_email') }}
                        </p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Total Pages:</span>
                                <span class="font-semibold text-gray-800">
                                    <!-- Display total pages count -->
                                    {{ count($pages) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 text-sm">Member Since:</span>
                                <span class="font-semibold text-gray-800">
                                    <!-- Display member since date -->
                                    {{ date('M Y', session('user_created_at')) }}

                                </span>
                            </div>
                            <div class="mt-4">
                                <form action="{{ route('logout.user') }}" method="POST">
                                    @csrf
                                    <!-- Add @csrf token here -->
                                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition duration-200 ease-in-out transform hover:scale-105">
                                        Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Pages List -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden fade-in">
                    <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">My Web Pages</h3>
                    </div>

                    <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <table class="w-full min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">Status</th>
                                    <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">Created</th>
                                    <th class="px-3 sm:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">

                                <!-- Laravel Blade Loop: Add foreach loop for pages here -->

                                <!-- Sample Row 1 -->
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <!-- Display page title -->
                                            Home Page
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <!-- Display page description -->
                                            Welcome to our website homepage
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <!-- Check if page status is published -->
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Published
                                        </span>
                                        <!-- Else show Draft status -->
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                                        <!-- Display created date -->
                                        Oct 20, 2025
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                            <a href="/pages/1/edit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="/pages/1" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview
                                            </a>
                                            <form action="/pages/1" method="POST" class="inline">
                                                <!-- Add @csrf token here -->
                                                <!-- Add @method('DELETE') here -->
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this page?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center justify-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Sample Row 2 -->
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">About Us</div>
                                        <div class="text-sm text-gray-500">Learn more about our company</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Draft
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">Oct 22, 2025</td>
                                    <td class="px-3 sm:px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="#" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview
                                            </a>
                                            <form action="#" method="POST" class="inline">
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this page?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Sample Row 3 -->
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">Contact Page</div>
                                        <div class="text-sm text-gray-500">Get in touch with us</div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">Oct 24, 2025</td>
                                    <td class="px-3 sm:px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2">
                                            <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <a href="#" target="_blank" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Preview
                                            </a>
                                            <form action="#" method="POST" class="inline">
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this page?')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg transition duration-200 ease-in-out transform hover:scale-105 inline-flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <!-- End foreach loop here -->

                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State if no pages exist -->
                    <!-- Add if condition for empty pages here -->
                    <!-- <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No pages</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new page.</p>
                    </div> -->
                    <!-- End if condition here -->

                </div>
            </div>

        </div>
    </div>

</body>

</html>