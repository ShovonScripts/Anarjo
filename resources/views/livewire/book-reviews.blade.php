<div class="mt-16 border-t border-neutral-200 pt-12">
    <h2 class="section-heading mb-10">Reviews</h2>

    {{-- Review Form --}}
    @auth
        @if(!$userReview || $userReview->status === 'rejected')
            <div class="border border-neutral-200 p-6 mb-10">
                <h3 class="text-sm font-semibold uppercase tracking-widest mb-4">{{ $userReview ? 'Submit a new review' : 'Write a Review' }}</h3>
                <form wire:submit="submit" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Rating</label>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" wire:click="$set('rating', {{ $i }})" class="p-1 transition-colors">
                                    <svg class="w-6 h-6 {{ $i <= $this->rating ? 'text-black' : 'text-neutral-300' }}" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                </button>
                            @endfor
                        </div>
                        @error('rating') <span class="text-xs text-brand-600">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-widest mb-2">Your Review</label>
                        <textarea wire:model="body" rows="4" class="input-field" placeholder="Share your thoughts about this book..."></textarea>
                        @error('body') <span class="text-xs text-brand-600 mt-1">{{ $message }}</span> @enderror
                    </div>
                    <button type="submit" wire:loading.attr="disabled" class="btn-primary btn-sm">
                        <span wire:loading.remove wire:target="submit">Submit Review</span>
                        <span wire:loading wire:target="submit" class="inline-flex items-center gap-2">
                            <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            Submitting…
                        </span>
                    </button>
                </form>
            </div>
        @elseif($userReview && $userReview->status === 'pending')
            <p class="text-sm text-neutral-500 mb-10 italic">Your review is pending approval.</p>
        @endif
    @else
        <p class="text-sm text-neutral-500 mb-10"><a href="{{ route('login') }}" class="text-brand-600 underline">Login</a> to write a review.</p>
    @endauth

    {{-- Reviews List --}}
    @if($reviews->count())
        <div class="divide-y divide-neutral-200">
            @foreach($reviews as $review)
                <div class="py-6 first:pt-0">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 bg-neutral-200 flex items-center justify-center text-xs font-bold">{{ substr($review->user->name, 0, 1) }}</span>
                        <div>
                            <p class="text-sm font-semibold">{{ $review->user->name }}</p>
                            <div class="flex gap-0.5">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-black' : 'text-neutral-300' }}" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                        <span class="ml-auto text-xs text-neutral-400">{{ $review->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-neutral-700 leading-relaxed">{{ $review->review }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-sm text-neutral-500">No reviews yet.</p>
    @endif
</div>
