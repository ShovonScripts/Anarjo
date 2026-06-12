<div>
    @section('header', 'Contact Messages')

    <div class="mb-8">
        <h1 class="text-2xl font-bold tracking-tight">Contact Messages</h1>
        <p class="mt-1 text-sm text-neutral-500">Messages submitted through the contact form.</p>
    </div>

    @if(session('message'))
        <div class="border border-black bg-neutral-50 px-4 py-3 mb-6 text-sm font-medium">{{ session('message') }}</div>
    @endif

    <div x-data="{ showNotification: false }" x-on:reply-sent.window="showNotification = true; setTimeout(() => showNotification = false, 3000)">
        <div x-show="showNotification" class="mb-6 px-4 py-3 bg-black text-white text-sm font-semibold uppercase tracking-wider">Reply sent.</div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- List --}}
            <div class="border border-neutral-200 bg-white overflow-hidden">
                <div class="divide-y divide-neutral-200">
                    @forelse($messages as $msg)
                        <button wire:click="view({{ $msg->id }})" class="w-full text-left px-5 py-4 hover:bg-neutral-50 transition-colors {{ $selectedMessage?->id === $msg->id ? 'bg-neutral-50 border-l-2 border-black' : '' }}">
                            <div class="flex items-center justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold truncate {{ !$msg->read_at ? 'text-black' : 'text-neutral-500' }}">
                                        {{ $msg->name }}
                                        @if(!$msg->read_at)
                                            <span class="ml-2 inline-block w-2 h-2 rounded-full bg-brand-600"></span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-neutral-400 truncate mt-0.5">{{ $msg->subject ?: '(no subject)' }}</p>
                                </div>
                                <span class="text-xs text-neutral-400 shrink-0">{{ $msg->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-neutral-500 mt-1 line-clamp-2">{{ $msg->message }}</p>
                            @if($msg->replied_at)
                                <span class="text-xs text-brand-600 font-semibold mt-1 inline-block">Replied</span>
                            @endif
                        </button>
                    @empty
                        <div class="px-5 py-8 text-center text-neutral-500">No messages yet.</div>
                    @endforelse
                </div>
                @if($messages->hasPages())
                    <div class="border-t border-neutral-200 px-5 py-3">{{ $messages->links() }}</div>
                @endif
            </div>

            {{-- Detail / Reply Panel --}}
            <div class="border border-neutral-200 bg-white">
                @if($selectedMessage)
                    <div class="px-6 py-5 border-b border-neutral-200">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-lg">{{ $selectedMessage->name }}</h3>
                            <button wire:click="closeView" class="text-neutral-400 hover:text-black text-sm">&times;</button>
                        </div>
                        <p class="text-xs text-neutral-400">{{ $selectedMessage->email }}</p>
                        @if($selectedMessage->subject)
                            <p class="text-sm font-medium mt-2">{{ $selectedMessage->subject }}</p>
                        @endif
                        <p class="text-xs text-neutral-400 mt-1">Received {{ $selectedMessage->created_at->format('M d, Y g:i A') }}</p>
                    </div>
                    <div class="px-6 py-5 border-b border-neutral-200">
                        <p class="text-sm text-neutral-700 leading-relaxed whitespace-pre-wrap">{{ $selectedMessage->message }}</p>
                    </div>
                    @if($selectedMessage->admin_reply)
                        <div class="px-6 py-5 border-b border-neutral-200 bg-neutral-50">
                            <p class="text-xs font-semibold uppercase tracking-widest text-brand-600 mb-2">Your Reply</p>
                            <p class="text-sm text-neutral-700 leading-relaxed">{{ $selectedMessage->admin_reply }}</p>
                            <p class="text-xs text-neutral-400 mt-2">Sent {{ $selectedMessage->replied_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                    @if(!$replyModal)
                        <div class="px-6 py-5">
                            <button wire:click="openReply" class="btn-primary btn-sm">Reply</button>
                            <button wire:click="delete({{ $selectedMessage->id }})" wire:confirm="Delete this message?" class="btn-outline btn-sm ml-2">Delete</button>
                        </div>
                    @else
                        <div class="px-6 py-5">
                            <label class="block text-xs font-semibold uppercase tracking-widest mb-3">Write Reply</label>
                            <form wire:submit="sendReply">
                                <textarea wire:model="reply" rows="5" class="input-field w-full" placeholder="Type your reply..."></textarea>
                                @error('reply') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                                <div class="flex items-center gap-3 mt-4">
                                    <button type="submit" class="btn-primary btn-sm" wire:loading.attr="disabled">Send Reply</button>
                                    <button type="button" wire:click="$set('replyModal', false)" class="btn-outline btn-sm">Cancel</button>
                                    <div wire:loading wire:target="sendReply" class="text-sm text-neutral-500">Sending...</div>
                                </div>
                            </form>
                        </div>
                    @endif
                @else
                    <div class="px-6 py-20 text-center text-neutral-500">Select a message to view and reply.</div>
                @endif
            </div>
        </div>
    </div>
</div>
