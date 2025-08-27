<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|min:3')]
    public $name = '';
    
    #[Validate('required|email')]
    public $email = '';
    
    #[Validate('required|min:10')]
    public $message = '';
    
    public $sent = false;
    
    public function submit()
    {
        $this->validate();
        
        // Here you would typically send email or save to database
        // For demo purposes, we'll just set sent to true
        $this->sent = true;
        
        // Reset form
        $this->reset(['name', 'email', 'message']);
        
        // Reset sent status after 3 seconds
        $this->dispatch('contact-sent');
    }
}; ?>

<div>
    @if($sent)
        <div class="bg-green-500 text-white p-4 rounded-lg mb-4 text-center">
            ✅ Message sent successfully! I'll get back to you soon.
        </div>
    @endif
    
    <form wire:submit="submit" class="space-y-4">
        <div>
            <input 
                type="text" 
                wire:model="name" 
                placeholder="Your Name"
                class="w-full p-3 rounded-lg border @error('name') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-portfolio-green"
            >
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <input 
                type="email" 
                wire:model="email" 
                placeholder="Your Email"
                class="w-full p-3 rounded-lg border @error('email') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-portfolio-green"
            >
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <textarea 
                wire:model="message" 
                placeholder="Your Message"
                rows="4"
                class="w-full p-3 rounded-lg border @error('message') border-red-500 @enderror focus:outline-none focus:ring-2 focus:ring-portfolio-green"
            ></textarea>
            @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        
        <button 
            type="submit"
            class="w-full bg-portfolio-green hover:bg-green-600 text-white p-3 rounded-lg transition-colors font-semibold"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Send Message</span>
            <span wire:loading>Sending...</span>
        </button>
    </form>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('contact-sent', () => {
            setTimeout(() => {
                @this.set('sent', false);
            }, 3000);
        });
    });
</script>