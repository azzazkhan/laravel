<?php

use App\Events\Pinged;

use function Livewire\Volt\{state};

state(['message' => '']);

$ping = function () {
    broadcast(new Pinged(auth()->user(), $this->pull('message')))->toOthers();

    $this->dispatch('pinged')->self();
};

?>

<div>
    <form wire:submit="ping" class="space-y-6">
        <div>
            <x-input-label for="message" :value="__('Message')" />
            <x-text-input wire:model.live="message" id="message" name="message" type="text" class="block w-full mt-1" autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('message')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="pinged">
                {{ __('Users pinged!') }}
            </x-action-message>
        </div>
    </form>
</div>
