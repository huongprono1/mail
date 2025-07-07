<div>
    @if(!user_has_feature('no_ads'))
        {!! setting('ads.below_form_header') !!}
    @endif
    <div class="mt-8">
        {{ $this->table }}
    </div>
</div>
