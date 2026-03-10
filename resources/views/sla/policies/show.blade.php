<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $policy->name }}</h2>
            <a href="{{ route('sla.policies.edit', $policy) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Edit</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><dt class="text-sm text-gray-500">Priority</dt><dd class="text-gray-900 dark:text-gray-100">{{ ucfirst($policy->priority) }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Status</dt><dd><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $policy->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">{{ $policy->is_active ? 'Active' : 'Inactive' }}</span></dd></div>
                        <div><dt class="text-sm text-gray-500">Response Time</dt><dd class="text-gray-900 dark:text-gray-100">{{ $policy->response_time_minutes ? $policy->response_time_minutes . ' minutes' : '-' }}</dd></div>
                        <div><dt class="text-sm text-gray-500">Resolution Time</dt><dd class="text-gray-900 dark:text-gray-100">{{ $policy->resolution_time_minutes ? $policy->resolution_time_minutes . ' minutes' : '-' }}</dd></div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
