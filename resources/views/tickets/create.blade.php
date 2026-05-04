<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('tickets.store') }}" class="p-6">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Title *
                        </label>
                        <input type="text" name="title" id="title" required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                            value="{{ old('title') }}">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="queue_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Queue
                            </label>
                            <select name="queue_id" id="queue_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Select Queue</option>
                                @foreach($queues as $queue)
                                <option value="{{ $queue->id }}">{{ $queue->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Priority *
                            </label>
                            <select name="priority" id="priority" required
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                @foreach($priorities as $priority)
                                <option value="{{ $priority }}">{{ ucfirst($priority) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(!auth()->user()->isRequester())
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="assigned_group_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assign to Group
                            </label>
                            <select name="assigned_group_id" id="assigned_group_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Select Group</option>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sla_policy_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                SLA Policy
                            </label>
                            <select name="sla_policy_id" id="sla_policy_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                                <option value="">Select SLA Policy</option>
                                @foreach($slaPolicies as $policy)
                                <option value="{{ $policy->id }}">{{ $policy->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('tickets.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                            Cancel
                        </a>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
