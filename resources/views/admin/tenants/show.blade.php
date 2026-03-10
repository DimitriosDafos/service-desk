<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $tenant->name }}</h2>
            <a href="{{ route('admin.tenants.edit', $tenant) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('admin_credentials'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                <p class="font-bold">Admin Credentials Created!</p>
                <p>Email: <strong>{{ session('admin_credentials.email') }}</strong></p>
                <p>Password: <strong>{{ session('admin_credentials.password') }}</strong></p>
                <p class="text-sm mt-1">Please save these credentials - they will not be shown again.</p>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="text-sm text-gray-500">Name</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->name }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Slug</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->slug }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Email</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->email ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Phone</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->phone ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Status</dt><dd><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tenant->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $tenant->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
                        <div><dt class="text-sm text-gray-500">Users</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->users_count }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Tickets</dt><dd class="text-gray-900 dark:text-gray-100">{{ $tenant->tickets_count }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
