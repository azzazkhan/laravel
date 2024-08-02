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
        ['user' => $user, 'message' => $message] = $payload;

        $this->dispatch('pinged', user: $user, message: $message, type: 'info');
    }
}; ?>

<div>
    @script
        <script>
            $wire.on('pinged', ({user, message, type = 'default'}) => {
                let sender = `<strong>${user || 'System'}</strong>`;
                let title = ` pinged`


                toast(message ? `Message from ${sender}` : `${sender} pinged`, {
                    type,
                    description: message,
                });
            });
        </script>
    @endscript
</div>
