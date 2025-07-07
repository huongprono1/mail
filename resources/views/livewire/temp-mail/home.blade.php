<div class="">
    <x-filament::section :compact="true" class="mb-6">
        <form wire:submit="saveCustomMail">
            <div class="w-full flex flex-col lg:flex-row gap-2">
                <x-filament::input.wrapper class="flex-grow">
                    <x-filament::input
                        type="text"
                        wire:model="customMail"
                        placeholder="{{__('Input custom mail')}}"
                    />
                    <x-slot name="suffix">
                        <x-filament::input.select wire:model="domain">
                            @foreach($domains as $domain)
                                <option value="{{$domain->name}}">{{$domain->name}}</option>
                            @endforeach
                        </x-filament::input.select>
                    </x-slot>
                </x-filament::input.wrapper>

                <x-filament::button
                    icon="heroicon-o-plus-circle"
                    type="submit"
                    tooltip="{{__('Create')}}"
                >
                    {{__('Create')}}
                </x-filament::button>
                    <x-filament::button x-on:click="$wire.dispatch('refreshMail');"
                                        icon="heroicon-s-arrow-path"
                                        :disabled="!$this->selectedMail"
                    >
                        {{__('Refresh')}}
                    </x-filament::button>


                    <x-filament::button wire:click="createRandom"
                                        icon="heroicon-s-arrow-path-rounded-square">
                        {{__('Create random')}}
                    </x-filament::button>

                <x-filament::button wire:click="removeMail"
                                    icon="heroicon-o-trash"
                                    color="danger"
                                    :disabled="!$this->selectedMail"
                >
                        {{__('Delete')}}
                    </x-filament::button>
                <x-filament::dropdown max-height="400px" teleport="true">
                    <x-slot name="trigger"
                            :disabled="$this->countMail == 0">
                            <x-filament::button color="warning" icon="heroicon-o-list-bullet"></x-filament::button>
                        </x-slot>
                        <x-filament::dropdown.list>
                            @foreach($this->mails as $idx => $item)
                                <x-filament::dropdown.list.item
                                    wire:click="selectMail({{$item}})"
                                    x-on:click="close()">
                                    {{$item->email}}
                                </x-filament::dropdown.list.item>
                            @endforeach
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
            </div>
        </form>

    </x-filament::section>

    @if($countMail)
        @livewire('temp-mail.inbox')
    @endif
</div>
