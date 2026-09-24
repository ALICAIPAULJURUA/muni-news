@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-[#24AAE1] focus:ring-[#24AAE1] rounded-sm shadow-sm']) }} style="border-width:2px; border-radius:2px;">
