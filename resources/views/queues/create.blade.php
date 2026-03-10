<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Queue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tenants.queues.store') }}" class="p-6">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                        <input type="text" name="name" id="name" required class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                    </div>

                    <div class="mb-4">
                        <label for="group_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Group</label>
                        <select name="group_id" id="group_id" class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            <option value="">Select Group</option>
                            @foreach($groups as $group)
                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="auto_assign" value="1" class="rounded border-gray-300">
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Auto-assign tickets</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('tenants.queues.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Cancel</a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Queue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
