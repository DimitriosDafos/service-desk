<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $user->name }}</h2>
            <a href="{{ route('tenants.users.edit', $user) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="text-sm text-gray-500">Name</dt><dd class="text-gray-900 dark:text-gray-100">{{ $user->name }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Email</dt><dd class="text-gray-900 dark:text-gray-100">{{ $user->email }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Role</dt><dd class="text-gray-900 dark:text-gray-100">{{ ucfirst($user->role) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Status</dt><dd><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
                        <div><dt class="text-sm text-gray-500">Phone</dt><dd class="text-gray-900 dark:text-gray-100">{{ $user->phone ?? '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Department</dt><dd class="text-gray-900 dark:text-gray-100">{{ $user->department ?? '-' }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
