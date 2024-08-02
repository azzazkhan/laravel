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
    public function pinged() {
        $this->dispatch('pinged', 'Pinged', ['type' => 'info']);
    }

    #[On('echo-private:user.{user.id},Pinged')]
    public function pingedPrivate() {
        $this->dispatch('pinged', 'User pinged', ['type' => 'info']);
    }
}; ?>

<div>
    @script
        <script>
            $wire.on('pinged', ([message, options]) => toast(message, options));
        </script>
    @endscript
</div>

