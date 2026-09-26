@php
    $likableType = get_class($likable);
    $likesCount = $likable->likes->count();
    $userLiked = $likable->likes->contains(fn ($l) => $l->user_id === auth()->id());
@endphp

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('likeButton', () => ({
        liked: {{ $userLiked ? 'true' : 'false' }},
        count: {{ $likesCount }},
        busy: false,
        async toggle() {
            if (this.busy) return;
            this.busy = true;
            try {
                const resp = await fetch('{{ route('like.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        likeable_type: '{{ addslashes($likableType) }}',
                        likeable_id: {{ $likable->id }}
                    })
                });
                if (resp.status === 401 || resp.status === 403) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }
                const data = await resp.json();
                if (resp.ok) {
                    this.liked = data.liked;
                    this.count = data.count;
                }
            } catch (e) {
                // silent
            } finally {
                this.busy = false;
            }
        }
    }));
});
</script>

<div x-data="likeButton" class="flex items-center gap-2">
    <button type="button" @click="toggle()" :disabled="busy"
            class="inline-flex items-center gap-2 rounded-sm border-2 px-4 py-2 text-sm font-bold uppercase tracking-wider transition hover:opacity-90 disabled:opacity-50"
            style="border-color: var(--muni-red); color: var(--muni-red); min-height:44px;"
            aria-label="Like this post">
        <i x-bind:class="liked ? 'fa-solid fa-heart' : 'fa-regular fa-heart'" style="color: var(--muni-red);"></i>
        <span class="normal-case"><span x-text="count"></span> Likes</span>
    </button>
</div>