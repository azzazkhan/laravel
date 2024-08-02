<?php

use App\Events\Pinged;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Volt\Component;

new class extends Component {
    public User $user;

    public function mount() {
        $this->user = auth()->user();
    }

    #[On('echo:public,Pinged')]
    public function pinged($payload) {
        $this->dispatch('pinged', user: $payload['user'], message: $payload['message'], type: 'info');
    }
}; ?>

<div>
    @script
        <script>
            $wire.on('pinged', ({user, message, type = 'default'}) => {
                toast(`${user ? `User <strong>${user}</strong>` : 'System'} ${message ? 'messaged' : 'pinged'}`, {
                    type,
                    description: message,
                });
            });
        </script>
    @endscript
</div>
