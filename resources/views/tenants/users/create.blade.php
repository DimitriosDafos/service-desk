<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Create User') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tenants.users.store') }}" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                        <input type="text" name="name" id="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                        <input type="email" name="email" id="email" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password *</label>
                        <input type="password" name="password" id="password" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
                        <select name="role" id="role" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="tenant_admin">Tenant Admin</option>
                            <option value="agent">Agent</option>
                            <option value="requester">Requester</option>
                            <option value="auditor">Auditor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div class="mb-4">
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <input type="text" name="department" id="department" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                        <a href="{{ route('tenants.users.index') }}" style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none;">Cancel</a>
                        <button type="submit" style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
