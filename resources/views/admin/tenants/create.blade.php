<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Tenant</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('admin.tenants.store') }}" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="name" style="display:block; margin-bottom: 0.5rem; font-weight: 500;">Tenant Name *</label>
                        <input type="text" name="name" id="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
                    </div>
                    <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                        <a href="{{ route('admin.tenants.index') }}" style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none;">Cancel</a>
                        <button type="submit" style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">Create Tenant</button>
                    </div>
                </form>
            </div>
            <p style="margin-top: 1rem; color: #6b7280; font-size: 0.875rem;">An admin user will be automatically created (admin@tenant-name.com)</p>
        </div>
    </div>
</x-app-layout>
