<x-filament-panels::page>
    <x-filament::section :compact="true" class="print-section">
        @if(!user_has_feature('no_ads'))
            {!! setting('ads.before_message_content') !!}
        @endif
        <x-slot name="heading">
            <div class="flex justify-between">
                <x-filament::button icon="heroicon-o-arrow-small-left" size="sm" color="gray" tag="a"
                                    href="{{\App\Filament\App\Pages\Home::getUrl()}}"
                                    :spa-mode="true"
                >{{__('Back to inbox')}}</x-filament::button>
                <div>
                    <x-filament::button icon="heroicon-o-printer" size="sm" color="gray"
                                        onclick="window.print()"
                    >{{__('Print')}}</x-filament::button>
                </div>
            </div>
            <h2 class="text-2xl py-6">{{$message->subject}}</h2>
        </x-slot>
        <x-slot name="description">
            <div class="flex justify-between items-center">
                <div>
                    {{$message->sender_name}}
                    <div class="text-sm text-gray-400">
                        {{ __('From').': '. $message->from}}
                    </div>
                </div>
                <div class="justify-end text-sm">
                    {{$message->created_at->diffForHumans()}}
                    <div class="text-sm text-gray-400">
                        {{__('To').': '.$message->to}}
                    </div>
                </div>
            </div>
        </x-slot>
        @if(!user_has_feature('no_ads'))
            {!! setting('ads.before_message_body') !!}
        @endif
        <div class="print-section">
            <div class="">
                <iframe
                    class="w-full flex flex-grow min-h-[calc(100vh_-_480px)] md:min-h-[calc(100vh)] dark:border-gray-700"
                    srcdoc="{!! $mailBody !!}"></iframe>
            </div>
        </div>
        @if(!user_has_feature('no_ads'))
            {!! setting('ads.after_message_body') !!}
        @endif
    </x-filament::section>
</x-filament-panels::page>
