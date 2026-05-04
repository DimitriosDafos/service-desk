<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit User</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tenants.users.update', $user) }}" class="p-6">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                        <input type="text" name="name" id="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('name', $user->name) }}">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                        <input type="email" name="email" id="email" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('email', $user->email) }}">
                    </div>
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Password (leave blank to keep)</label>
                        <input type="password" name="password" id="password" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
                        <select name="role" id="role" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            @foreach(['tenant_admin','agent','requester','auditor'] as $role)
                            <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                        <input type="text" name="phone" id="phone" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('phone', $user->phone) }}">
                    </div>
                    <div class="mb-4">
                        <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                        <input type="text" name="department" id="department" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('department', $user->department) }}">
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                        </label>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 1.5rem;">
                        <button type="submit" style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">Save Changes</button>
                        <a href="{{ route('tenants.users.index') }}" style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none;">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
