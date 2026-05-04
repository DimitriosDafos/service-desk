<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h-semibold text-xl2 class="font text-gray-800 dark:text-gray-200 leading-tight">
                {{ $ticket->ticket_number }}
            </h2>
            @if(auth()->user()->canManageTickets())
            <a href="{{ route('tickets.edit', $ticket) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Edit
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $ticket->title }}</h1>
                                    <div class="flex gap-2 mt-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @switch($ticket->status?->value)
                                                @case('new') bg-blue-100 text-blue-800 @break
                                                @case('triaged') bg-yellow-100 text-yellow-800 @break
                                                @case('in_progress') bg-orange-100 text-orange-800 @break
                                                @case('pending') bg-gray-100 text-gray-800 @break
                                                @case('resolved') bg-green-100 text-green-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ $ticket->status?->label() ?? $ticket->status }}
                                        </span>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @switch($ticket->priority?->value)
                                                @case('critical') bg-red-100 text-red-800 @break
                                                @case('high') bg-orange-100 text-orange-800 @break
                                                @case('medium') bg-yellow-100 text-yellow-800 @break
                                                @case('low') bg-green-100 text-green-800 @break
                                                @default bg-gray-100 text-gray-800
                                            @endswitch">
                                            {{ $ticket->priority?->label() ?? $ticket->priority }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="prose dark:prose-invert max-w-none">
                                <p>{{ $ticket->description }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Comments</h3>
                            
                            @forelse($ticket->comments as $comment)
                            <div class="mb-4 pb-4 border-b dark:border-gray-700 last:border-0">
                                <div class="flex justify-between items-start">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $comment->user?->name ?? 'Unknown' }}
                                        @if($comment->is_internal)
                                        <span class="ml-2 px-2 py-0.5 text-xs bg-yellow-100 text-yellow-800 rounded">Internal</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="mt-2 text-gray-700 dark:text-gray-300">{{ $comment->content }}</div>
                            </div>
                            @empty
                            <p class="text-gray-500">No comments yet.</p>
                            @endforelse

                            @if(auth()->user()->canManageTickets() || auth()->user()->id === $ticket->requester_id)
                            <form method="POST" action="{{ route('tickets.comments', $ticket) }}" class="mt-6">
                                @csrf
                                <div class="mb-4">
                                    <textarea name="content" rows="3" required
                                        class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900"
                                        placeholder="Add a comment..."></textarea>
                                </div>
                                @if(auth()->user()->canManageTickets())
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300">
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Internal Note</span>
                                    </label>
                                </div>
                                @endif
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Add Comment
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>

                <div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Details</h3>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm text-gray-500">Requester</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->requester?->name ?? 'N/A' }}</dd>
                                </div>
                                @if($ticket->assignedAgent)
                                <div>
                                    <dt class="text-sm text-gray-500">Assigned To</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->assignedAgent->name }}</dd>
                                </div>
                                @endif
                                @if($ticket->assignedGroup)
                                <div>
                                    <dt class="text-sm text-gray-500">Assigned Group</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->assignedGroup->name }}</dd>
                                </div>
                                @endif
                                @if($ticket->queue)
                                <div>
                                    <dt class="text-sm text-gray-500">Queue</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->queue->name }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm text-gray-500">Created</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->created_at->diffForHumans() }}</dd>
                                </div>
                                @if($ticket->first_response_at)
                                <div>
                                    <dt class="text-sm text-gray-500">First Response</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->first_response_at->diffForHumans() }}</dd>
                                </div>
                                @endif
                                @if($ticket->resolved_at)
                                <div>
                                    <dt class="text-sm text-gray-500">Resolved</dt>
                                    <dd class="text-gray-900 dark:text-gray-100">{{ $ticket->resolved_at->diffForHumans() }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
