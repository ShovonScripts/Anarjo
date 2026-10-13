<div class="mt-16 border-t border-neutral-200 pt-12">
    <h2 class="section-heading mb-8">Comments ({{ $comments->count() }})</h2>

    @if($comments->count())
        <div class="space-y-6 mb-10">
            @foreach($comments as $comment)
                <div class="border border-neutral-200 p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-8 h-8 rounded-full bg-neutral-200 flex items-center justify-center text-xs font-bold uppercase">
                            {{ substr($comment->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold">{{ $comment->user->name }}</p>
                            <p class="text-xs text-neutral-400">{{ $comment->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <p class="text-neutral-600 leading-relaxed">{{ $comment->body }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-neutral-400 text-sm mb-10">No comments yet. Be the first to comment!</p>
    @endif

    @auth
        <form wire:submit="addComment" class="space-y-4">
            <textarea wire:model="body" rows="3" placeholder="Write a comment..." class="w-full border border-neutral-300 px-4 py-3 text-sm focus:outline-none focus:border-black transition-colors resize-none"></textarea>
            @error('body') <p class="text-red-600 text-xs">{{ $message }}</p> @enderror
            <div class="flex justify-end">
                <button type="submit" class="btn-primary btn-sm text-xs tracking-wider uppercase">Post Comment</button>
            </div>
            <p class="text-xs text-neutral-400">Your comment will appear once approved by a moderator.</p>
        </form>
    @else
        <p class="text-sm text-neutral-500">
            <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-semibold underline">Login</a> to leave a comment.
        </p>
    @endauth
</div>
