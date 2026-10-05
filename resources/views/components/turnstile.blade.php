<div class="mt-5 w-full">
    <div class="cf-turnstile w-full" data-sitekey="{{ env('TURNSTILE_SITE_KEY', '1x00000000000000000000AA') }}" data-theme="auto" data-size="flexible"></div>
    @error('cf-turnstile-response')
        <p class="text-sm text-destructive mt-1 font-medium">{{ $message }}</p>
    @enderror
</div>
