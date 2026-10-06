<div class="mt-5 w-full">
    <div class="cf-turnstile w-full" data-sitekey="{{ config('services.turnstile.site_key') }}" data-theme="auto" data-size="flexible"></div>
    @error('cf-turnstile-response')
        <p class="text-sm text-destructive mt-1 font-medium">{{ $message }}</p>
    @enderror
</div>
