<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\Like;
use Livewire\Component;

class BlogLike extends Component
{
    public int $blogId;
    public bool $liked = false;
    public int $count = 0;

    public function mount(): void
    {
        $this->count = Like::where('likeable_type', Blog::class)
            ->where('likeable_id', $this->blogId)
            ->count();

        if (auth()->check()) {
            $this->liked = Like::where('user_id', auth()->id())
                ->where('likeable_type', Blog::class)
                ->where('likeable_id', $this->blogId)
                ->exists();
        }
    }

    public function toggle(): void
    {
        if (!auth()->check()) {
            $this->dispatch('notify', message: 'Please login to like.');
            return;
        }

        if ($this->liked) {
            Like::where('user_id', auth()->id())
                ->where('likeable_type', Blog::class)
                ->where('likeable_id', $this->blogId)
                ->delete();
            $this->liked = false;
            $this->count--;
            $this->dispatch('notify', message: 'Like removed.');
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_type' => Blog::class,
                'likeable_id' => $this->blogId,
            ]);
            $this->liked = true;
            $this->count++;
            $this->dispatch('notify', message: 'Liked!');
        }
    }

    public function render()
    {
        return view('livewire.blog-like');
    }
}
