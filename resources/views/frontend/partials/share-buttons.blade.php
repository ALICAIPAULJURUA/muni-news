@php
    $shareUrl = $shareUrl ?? url()->current();
    $shareTitle = $shareTitle ?? '';
    $shareLink = urlencode($shareUrl);
    $shareText = urlencode($shareTitle);
@endphp
<div class="flex flex-wrap gap-2">
    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareLink }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#1877F2; min-width:44px; min-height:44px;" aria-label="Share on Facebook"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="https://twitter.com/intent/tweet?url={{ $shareLink }}&text={{ $shareText }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#000; min-width:44px; min-height:44px;" aria-label="Share on X"><i class="fa-brands fa-x-twitter"></i></a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareLink }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#0A66C2; min-width:44px; min-height:44px;" aria-label="Share on LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
    <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareLink }}" target="_blank" rel="noopener" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background:#25D366; min-width:44px; min-height:44px;" aria-label="Share on WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
    <a href="mailto:?subject={{ $shareText }}&body={{ $shareLink }}" class="w-10 h-10 rounded-sm flex items-center justify-center text-white hover:opacity-90" style="background: var(--muni-red); min-width:44px; min-height:44px;" aria-label="Share via Email"><i class="fa-solid fa-envelope"></i></a>
</div>