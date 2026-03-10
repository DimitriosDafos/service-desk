<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Edit SLA Policy') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('sla.policies.update', $policy) }}" class="p-6">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                        <input type="text" name="name" id="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('name', $policy->name) }}">
                    </div>
                    <div class="mb-4">
                        <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority *</label>
                        <select name="priority" id="priority" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            @foreach(['critical','high','medium','low'] as $p)
                            <option value="{{ $p }}" {{ $policy->priority == $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="response_time_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Response Time (minutes)</label>
                            <input type="number" name="response_time_minutes" id="response_time_minutes" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('response_time_minutes', $policy->response_time_minutes) }}">
                        </div>
                        <div>
                            <label for="resolution_time_minutes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Resolution Time (minutes)</label>
                            <input type="number" name="resolution_time_minutes" id="resolution_time_minutes" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900" value="{{ old('resolution_time_minutes', $policy->resolution_time_minutes) }}">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ $policy->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Active</span>
                        </label>
                    </div>
                    <div class="flex justify-end gap-4">
                        <a href="{{ route('sla.policies.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
