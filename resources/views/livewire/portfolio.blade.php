<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Skill;
use App\Models\Project;

new class extends Component
{
    public $activeSection = 'about';
    public $projects = [];
    public $technologies = [];
    public $darkMode = false;
    public $showLogin = false;
    public $showLoginCount = 0;
    public $showContactForm = false;
    public $contactForm = [
        'name' => '',
        'email' => '',
        'subject' => '',
        'message' => ''
    ];

    public function mount()
    {

        $this->loadTechnologies();
        $this->loadProjects();

    }


    private function getRandomColor()
    {
        $colors = [
            'bg-red-400 dark:bg-red-500',
            'bg-blue-400 dark:bg-blue-500',
            'bg-green-400 dark:bg-green-500',
            'bg-yellow-400 dark:bg-yellow-500',
            'bg-purple-400 dark:bg-purple-500',
            'bg-pink-400 dark:bg-pink-500',
            'bg-indigo-400 dark:bg-indigo-500',
            'bg-orange-400 dark:bg-orange-500',
            'bg-teal-400 dark:bg-teal-500',
            'bg-cyan-400 dark:bg-cyan-500',
            'bg-lime-400 dark:bg-lime-500',
            'bg-emerald-400 dark:bg-emerald-500',
        ];

        return $colors[array_rand($colors)];
    }


    public function loadTechnologies()
    {
        $skills = Skill::where('status', 'active')->get(['name', 'icon_path as icon']);
        
        $this->technologies = $skills->map(function($skill) {
            return [
                'id' => $skill->id,
                'name' => $skill->name,
                'icon' => $skill->icon ?? '',
                'color' => $this->getRandomColor()
            ];
        })->toArray();
    }


    public function loadProjects()
    {
        $projects = Project::where('status', 'active')->get();
        
        $this->projects = $projects->map(function($project) {
            return [
                'id' => $project->id,
                'acronym' => $project->acronym,
                'title' => $project->title,
                'description' => $project->description,
                'link' => $project->link,
                'image' => $project->image,
                'project_status' => $project->project_status,
                'made_of' => json_decode($project->made_of) ?? [],
            ];
        })->toArray();
    }


    public function randomizeColors()
    {
        // Re-assign random colors to existing technologies
        $this->technologies = collect($this->technologies)->map(function($tech) {
            $tech['color'] = $this->getRandomColor();
            return $tech;
        })->toArray();
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
    }

    public function toggleDarkMode()
    {
        $this->darkMode = !$this->darkMode;
        $this->dispatch('toggle-dark-mode');
    }

    public function toggleLoginButton()
    {
        if ($this->showLogin){
            redirect('/login');
        }


        if ($this->showLoginCount < 1) {
            $this->showLoginCount++;
            
        } else {
            $this->showLoginCount++;
            if($this->showLoginCount == 3) {
                $this->showLogin = true;
            }
        }

        
    }

    public function toggleContactForm()
    {
        $this->showContactForm = !$this->showContactForm;
    }

    public function submitContactForm()
    {
        $this->validate([
            'contactForm.name' => 'required|min:2',
            'contactForm.email' => 'required|email',
            'contactForm.subject' => 'required|min:5',
            'contactForm.message' => 'required|min:10'
        ]);

        // Here you would typically send an email or save to database
        session()->flash('message', 'Thank you for your message! I\'ll get back to you soon.');
        
        $this->contactForm = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        $this->showContactForm = false;
    }
}; ?>

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-300 p-2 sm:p-4">
    <!-- Dark Mode Toggle -->
    <button 
        wire:click="toggleDarkMode"
        class="dark-mode-toggle group"
        title="Toggle Dark Mode"
    >
        <span class="block dark:hidden text-gray-700 group-hover:text-yellow-500 transition-colors">🌙</span>
        <span class="hidden dark:block text-yellow-400 group-hover:text-yellow-300 transition-colors">☀️</span>
    </button>

    <div class="max-w-7xl mx-auto">
        <!-- Portfolio Grid Layout -->
        <div class="mobile-grid">
            
            <!-- Main Hero Section -->
            <div class="lg:row-span-1 lg:row-start-1 sm:col-span-2 lg:col-span-2">
                <div class="portfolio-card-colored bg-portfolio-dark dark:bg-gray-800 text-white h-full min-h-[300px] sm:min-h-[400px] relative overflow-hidden">
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <h1 class="mobile-hero-title font-bold mb-2 sm:mb-4 leading-tight">
                            JAY-AR <span class="text-portfolio-yellow dark:text-yellow-400">REVIS</span>
                        </h1>
                        <p class="text-lg sm:text-xl lg:text-2xl mb-4 sm:mb-6 text-blue-200 dark:text-blue-300">
                            Full Stack Web Developer 
                        </p>
                        <p class="text-gray-300 dark:text-gray-400 leading-relaxed mobile-text max-w-2xl">
                            Passionate full-stack developer with expertise in modern web technologies. 
                            I create robust, scalable applications using Laravel, Livewire, and cutting-edge 
                            frontend frameworks. Always eager to learn and implement the latest industry standards.
                        </p>
                        
                        <!-- Mobile CTA Buttons -->
                        <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                            <button class="bg-portfolio-yellow hover:bg-yellow-500 text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all hover:scale-105">
                                View Projects
                            </button>
                            <button class="border border-portfolio-yellow text-portfolio-yellow hover:bg-portfolio-yellow hover:text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all">
                                Contact Me
                            </button>
                            <button class="{{ $showLogin ? 'text-portfolio-yellow hover:bg-portfolio-yellow' : 'cursor-help' }}  hover:text-gray-900 px-6 py-3 rounded-lg font-semibold transition-all" wire:click="toggleLoginButton">
                                {{ $showLogin ? 'Log In' : '' }}
                            </button>
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-portfolio-yellow opacity-10 rounded-full transform translate-x-8 sm:translate-x-16 -translate-y-8 sm:-translate-y-16"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 sm:w-24 sm:h-24 bg-portfolio-blue opacity-20 rounded-full transform -translate-x-6 sm:-translate-x-12 translate-y-6 sm:translate-y-12"></div>
                </div>
            </div>

            <!-- About Me Section -->
            <div 
                x-data="{
                    current: 0, 
                    isTransitioning: false,
                    sections: [
                        { key: 'journey', label: 'Professional Journey' },
                        { key: 'expertise', label: 'Core Expertise' },
                        { key: 'values', label: 'What Drives Me' }
                    ],
                    intervalId: null,
                    
                    startAutoRotate() {
                        this.intervalId = setInterval(() => {
                            this.goToNext()
                        }, 6000)
                    },
                    
                    stopAutoRotate() {
                        if (this.intervalId) {
                            clearInterval(this.intervalId)
                            this.intervalId = null
                        }
                    },
                    
                    goToNext() {
                        this.goToSection((this.current + 1) % this.sections.length)
                    },
                    
                    goToPrev() {
                        this.goToSection((this.current - 1 + this.sections.length) % this.sections.length)
                    },
                    
                    goToSection(index) {
                        if (this.isTransitioning || index === this.current) return
                        
                        this.isTransitioning = true
                        this.current = index
                        
                        setTimeout(() => {
                            this.isTransitioning = false
                        }, 500)
                    }
                }" 
                x-init="startAutoRotate()"
                @mouseenter="stopAutoRotate()" 
                @mouseleave="startAutoRotate()"
                class="relative lg:row-span-1 lg:row-start-1 lg:row-end-3 portfolio-card-colored bg-portfolio-blue dark:bg-blue-600 text-white min-h-[300px] sm:min-h-[400px] flex items-center justify-center"
            >
                <div class="h-full flex flex-col p-4 sm:p-6 relative w-full">
                    <div class="text-center mb-6">
                        <h2 class="text-3xl sm:text-4xl font-bold mb-2">ABOUT ME</h2>
                        <div class="w-16 h-1 bg-blue-200 mx-auto rounded-full"></div>
                    </div>

                    <!-- Content Container with Fixed Height -->
                    <div class="flex-1 relative mb-20">
                        <!-- Professional Journey -->
                        <div 
                            x-show="sections[current].key === 'journey'" 
                            x-transition.opacity.duration.500ms
                            class="absolute inset-0"
                        >
                            <h3 class="text-xl font-semibold mb-3 flex items-center gap-2">
                                <span class="text-2xl">👨‍💻</span>
                                Professional Journey
                            </h3>
                            <p class="text-blue-100 dark:text-blue-200 leading-relaxed text-sm sm:text-base wrap-break-word">
                                With over 5 years of experience in web development, I specialize in creating dynamic, 
                                user-friendly applications that solve real-world problems. My passion lies in crafting 
                                clean, maintainable code and delivering exceptional user experiences.
                            </p>
                        </div>

                        <!-- Core Expertise -->
                        <div 
                            x-show="sections[current].key === 'expertise'" 
                            x-transition.opacity.duration.500ms
                            class="absolute inset-0"
                        >
                            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                <span class="text-2xl">🚀</span>
                                Core Expertise
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-2 gap-3">
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg">🔧</span>
                                        <span class="font-semibold">Backend Development</span>
                                    </div>
                                    <p class="text-blue-200 text-sm">PHP, Laravel, MySQL, API Development</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg">🎨</span>
                                        <span class="font-semibold">Frontend Development</span>
                                    </div>
                                    <p class="text-blue-200 text-sm">Livewire, JavaScript, Tailwind CSS, Alpine.js</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg">☁️</span>
                                        <span class="font-semibold">Cloud & DevOps</span>
                                    </div>
                                    <p class="text-blue-200 text-sm">AWS, Docker, Git, CI/CD Pipelines</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-lg">📱</span>
                                        <span class="font-semibold">Modern Development</span>
                                    </div>
                                    <p class="text-blue-200 text-sm">Responsive Design, PWAs, Performance Optimization</p>
                                </div>
                            </div>
                        </div>

                        <!-- What Drives Me -->
                        <div 
                            x-show="sections[current].key === 'values'" 
                            x-transition.opacity.duration.500ms
                            class="absolute inset-0"
                        >
                            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                <span class="text-2xl">💡</span>
                                What Drives Me
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <span class="text-yellow-300 mt-1">⭐</span>
                                    <div>
                                        <span class="font-medium">Clean Code Advocate</span>
                                        <p class="text-blue-200 text-sm">Writing readable, maintainable, and scalable code</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-yellow-300 mt-1">⭐</span>
                                    <div>
                                        <span class="font-medium">Continuous Learner</span>
                                        <p class="text-blue-200 text-sm">Always exploring new technologies and best practices</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-yellow-300 mt-1">⭐</span>
                                    <div>
                                        <span class="font-medium">Problem Solver</span>
                                        <p class="text-blue-200 text-sm">Finding creative solutions to complex challenges</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Controls -->
                    <div class="absolute bottom-4 left-0 right-0 flex justify-between items-center px-6 gap-4">
                        <!-- Previous Button -->
                        <button 
                            @click="goToPrev()"
                            :disabled="isTransitioning"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-400 disabled:opacity-50 disabled:cursor-not-allowed rounded-full text-sm/6 shadow-lg text-nowrap capitalize transition-all duration-200"
                        >
                            ← <span x-text="sections[(current - 1 + sections.length) % sections.length].key"></span>
                        </button>

                        <!-- Next Button -->
                        <button 
                            @click="goToNext()"
                            :disabled="isTransitioning"
                            class="px-4 py-2 bg-blue-500 hover:bg-blue-400 disabled:opacity-50 disabled:cursor-not-allowed rounded-full text-sm/6 shadow-lg text-nowrap capitalize transition-all duration-200"
                        >
                            <span x-text="sections[(current + 1) % sections.length].key"></span> →
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Technology Stack -->
            <div class="sm:col-span-2 lg:col-span-2 lg:row-span-1 lg:row-start-2">
                <div class="carousel-container relative overflow-hidden">
                    <!-- Navigation Buttons -->
                    <button class="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full transition-all duration-300 z-10" id="prevBtn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    
                    <button class="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black bg-opacity-30 hover:bg-opacity-50 text-white p-2 rounded-full transition-all duration-300 z-10" id="nextBtn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Continuous rotating carousel -->
                    <div class="carousel-track" id="carouselTrack">
                        <div class="carousel-wrapper">
                            @foreach($technologies as $tech)
                            <div class="portfolio-card-colored {{ $tech['color'] }} text-white carousel-item">
                                <div class="tech-icon mx-auto">
                                    <span class="text-base sm:text-xl lg:text-2xl font-bold"><img src="{{ asset($tech['icon']) }}"/></span>
                                </div>
                                <p class="text-center mt-2 sm:mt-3 font-semibold text-xs sm:text-sm lg:text-base">{{ $tech['name'] }}</p>
                            </div>
                            @endforeach
                            
                            <!-- Duplicate items for seamless loop -->
                            @foreach($technologies as $tech)
                            <div class="portfolio-card-colored {{ $tech['color'] }} text-white carousel-item">
                                <div class="tech-icon mx-auto">
                                    <span class="text-base sm:text-xl lg:text-2xl font-bold"><img src="{{ asset($tech['icon']) }}"/></span>
                                </div>
                                <p class="text-center mt-2 sm:mt-3 font-semibold text-xs sm:text-sm lg:text-base">{{ $tech['name'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Dots Navigation -->
                    <div class="flex justify-center mt-4 space-x-2" id="dotsContainer">
                        <!-- Dots will be generated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Contact Section -->
            <div class=" lg:row-start-3 lg:row-end-5 portfolio-card-colored bg-portfolio-green dark:bg-green-600 text-white min-h-[250px] sm:min-h-[300px] flex items-center justify-center">
                <div class="h-full flex flex-col p-4 sm:p-6">
                    <div class="text-center mb-6">
                        <h3 class="text-3xl sm:text-4xl font-bold mb-2">LET'S CONNECT</h3>
                        <div class="w-16 h-1 bg-green-200 mx-auto rounded-full"></div>
                        <p class="text-green-100 mt-3">Ready to bring your ideas to life?</p>
                    </div>

                    @if(session('message'))
                        <div class="bg-green-700 border border-green-500 text-green-100 px-4 py-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    @if(!$showContactForm)
                        <!-- Contact Information -->
                        <div class="space-y-6 mb-6">
                            <!-- Primary Contact -->
                            <div class="bg-green-700 dark:bg-green-800 bg-opacity-50 rounded-lg p-4">
                                <h4 class="font-semibold mb-3 flex items-center gap-2">
                                    <span class="text-xl">📬</span>
                                    Get In Touch
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📧</span>
                                        <div>
                                            <div class="font-medium">Email</div>
                                            <a href="mailto:jrevis029@gmail.com" class="text-green-200 hover:text-white transition-colors text-sm">
                                                jrevis029@gmail.com
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📱</span>
                                        <div>
                                            <div class="font-medium">Phone</div>
                                            <a href="tel:+639761598467" class="text-green-200 hover:text-white transition-colors text-sm">
                                                +63 976 159 8467
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-lg">📍</span>
                                        <div>
                                            <div class="font-medium">Location</div>
                                            <span class="text-green-200 text-sm">Philippines (Remote Available)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form Toggle Button -->
                        <button 
                            wire:click="toggleContactForm"
                            class="w-full bg-green-800 dark:bg-green-900 hover:bg-green-900 dark:hover:bg-green-800 text-white py-3 px-4 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2"
                        >
                            <span class="text-lg">✉️</span>
                            Send Message
                        </button>
                    @else
                        <!-- Contact Form -->
                        <form wire:submit.prevent="submitContactForm" class="space-y-4 flex-1">
                            <div>
                                <label class="block text-green-100 text-sm font-medium mb-1">Name *</label>
                                <input 
                                    type="text" 
                                    wire:model="contactForm.name"
                                    class="w-full px-3 py-2 bg-green-800 border border-green-600 rounded text-white placeholder-green-300 focus:outline-none focus:border-green-400"
                                    placeholder="Your Name"
                                >
                                @error('contactForm.name') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-green-100 text-sm font-medium mb-1">Email *</label>
                                <input 
                                    type="email" 
                                    wire:model="contactForm.email"
                                    class="w-full px-3 py-2 bg-green-800 border border-green-600 rounded text-white placeholder-green-300 focus:outline-none focus:border-green-400"
                                    placeholder="your@email.com"
                                >
                                @error('contactForm.email') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-green-100 text-sm font-medium mb-1">Subject *</label>
                                <input 
                                    type="text" 
                                    wire:model="contactForm.subject"
                                    class="w-full px-3 py-2 bg-green-800 border border-green-600 rounded text-white placeholder-green-300 focus:outline-none focus:border-green-400"
                                    placeholder="Project Discussion"
                                >
                                @error('contactForm.subject') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-green-100 text-sm font-medium mb-1">Message *</label>
                                <textarea 
                                    wire:model="contactForm.message"
                                    rows="4"
                                    class="w-full px-3 py-2 bg-green-800 border border-green-600 rounded text-white placeholder-green-300 focus:outline-none focus:border-green-400 resize-none"
                                    placeholder="Tell me about your project..."
                                ></textarea>
                                @error('contactForm.message') <span class="text-red-300 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="flex gap-3">
                                <button 
                                    type="submit"
                                    class="flex-1 bg-green-800 dark:bg-green-900 hover:bg-green-900 dark:hover:bg-green-800 text-white py-2 px-4 rounded font-medium transition-all flex items-center justify-center gap-2"
                                >
                                    <span>Send Message</span>
                                    <span class="text-sm">🚀</span>
                                </button>
                                <button 
                                    type="button"
                                    wire:click="toggleContactForm"
                                    class="bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded font-medium transition-all"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Projects Section -->
            <div class="lg:row-start-3 lg:row-end-4 sm:col-span-2 lg:col-span-2 portfolio-card-colored bg-portfolio-red dark:bg-red-600 text-white min-h-[300px]">
                <div class="h-full flex flex-col">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8 text-center">PROJECTS</h3>
                    
                    <div class="flex-1 space-y-4 sm:space-y-6 overflow-y-auto max-h-60 sm:max-h-80">
                        @foreach($projects as $index => $project)
                        <div class="bg-red-600 dark:bg-red-700 bg-opacity-50 dark:bg-opacity-50 rounded-lg p-3 sm:p-4 hover:bg-opacity-70 dark:hover:bg-opacity-70 transition-all cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <span class=""><img class="w-[50px] h-[50px]" src="{{ asset($project['image'] ? $project['image'] : 'default_logo.png') }}" /></span>
                                    <h4 class="font-bold text-sm sm:text-base lg:text-lg">{{ $project['title'] }} ({{ $project['acronym'] }})</h4>
                                </div>
                                <span class="text-xs bg-red-800 dark:bg-red-900 px-2 py-1 rounded flex-shrink-0 capitalize">{{ $project['project_status'] }}</span>
                            </div>
                            <p class="text-red-100 dark:text-red-200 text-xs sm:text-sm mb-3">{{ $project['description'] }}</p>
                            <div class="flex flex-wrap gap-1 sm:gap-2">
                                @foreach($project['made_of'] as $techItem)
                                <span class="text-xs capitalize bg-red-800 dark:bg-red-900 text-red-100 dark:text-red-200 px-2 py-1 rounded">{{ $techItem }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if (count($projects) >= 2)
                        <button class="mt-4 bg-red-800 dark:bg-red-900 hover:bg-red-900 dark:hover:bg-red-800 text-white px-4 sm:px-6 py-2 rounded-lg transition-colors text-sm sm:text-base">
                            View All Projects →
                        </button>
                    @endif
                </div>
            </div>

            <!-- Social Links -->
            <div class="lg:col-span-2 lg:row-start-4 lg:row-end-5 portfolio-card-colored bg-portfolio-yellow dark:bg-yellow-500 text-gray-800 dark:text-gray-900 mb-8 lg:mb-0">
                <div class="text-center">
                    <h3 class="text-xl sm:text-2xl font-bold mb-6 sm:mb-8 text-gray-700 dark:text-gray-800">Connect With Me</h3>
                    
                    <!-- Social Icons Grid for Mobile -->
                    <div class="grid grid-cols-3 gap-4 sm:flex sm:justify-center sm:space-x-6 mb-6 sm:mb-8">
                        <a href="https://github.com" class="social-icon hover:text-gray-600 dark:hover:text-gray-700 flex flex-col items-center gap-1" title="GitHub">
                            <span class="text-2xl sm:text-3xl">🐙</span>
                            <span class="text-xs sm:hidden">GitHub</span>
                        </a>
                        <a href="https://linkedin.com" class="social-icon hover:text-blue-600 dark:hover:text-blue-700 flex flex-col items-center gap-1" title="LinkedIn">
                            <span class="text-2xl sm:text-3xl">💼</span>
                            <span class="text-xs sm:hidden">LinkedIn</span>
                        </a>
                        <a href="https://facebook.com" class="social-icon hover:text-blue-500 dark:hover:text-blue-600 flex flex-col items-center gap-1" title="Facebook">
                            <span class="text-2xl sm:text-3xl">📘</span>
                            <span class="text-xs sm:hidden">Facebook</span>
                        </a>
                        <a href="https://instagram.com" class="social-icon hover:text-pink-500 dark:hover:text-pink-600 flex flex-col items-center gap-1 sm:block" title="Instagram">
                            <span class="text-2xl sm:text-3xl">📷</span>
                            <span class="text-xs sm:hidden">Instagram</span>
                        </a>
                        <a href="https://twitter.com" class="social-icon hover:text-blue-400 dark:hover:text-blue-500 flex flex-col items-center gap-1 sm:block" title="Twitter">
                            <span class="text-2xl sm:text-3xl">🐦</span>
                            <span class="text-xs sm:hidden">Twitter</span>
                        </a>
                        <a href="mailto:jay.revis@email.com" class="social-icon hover:text-red-500 dark:hover:text-red-600 flex flex-col items-center gap-1 sm:block" title="Email">
                            <span class="text-2xl sm:text-3xl">✉️</span>
                            <span class="text-xs sm:hidden">Email</span>
                        </a>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-700 mb-2">Currently Available For</div>
                        <div class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 sm:px-4 py-2 rounded-full text-xs sm:text-sm font-semibold animate-bounce-gentle">
                            ✨ Freelance Projects
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Bar (Sticky Bottom) -->
        <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-40 sm:hidden">
            <div class="grid grid-cols-4 py-2">
                <button wire:click="setActiveSection('home')" class="flex flex-col items-center py-2 px-1 text-gray-600 dark:text-gray-400 hover:text-portfolio-blue dark:hover:text-blue-400 transition-colors">
                    <span class="text-lg">🏠</span>
                    <span class="text-xs">Home</span>
                </button>
                <button wire:click="setActiveSection('about')" class="flex flex-col items-center py-2 px-1 text-gray-600 dark:text-gray-400 hover:text-portfolio-blue dark:hover:text-blue-400 transition-colors">
                    <span class="text-lg">👨‍💻</span>
                    <span class="text-xs">About</span>
                </button>
                <button wire:click="setActiveSection('projects')" class="flex flex-col items-center py-2 px-1 text-gray-600 dark:text-gray-400 hover:text-portfolio-blue dark:hover:text-blue-400 transition-colors">
                    <span class="text-lg">🚀</span>
                    <span class="text-xs">Projects</span>
                </button>
                <button wire:click="setActiveSection('contact')" class="flex flex-col items-center py-2 px-1 text-gray-600 dark:text-gray-400 hover:text-portfolio-blue dark:hover:text-blue-400 transition-colors">
                    <span class="text-lg">📧</span>
                    <span class="text-xs">Contact</span>
                </button>
            </div>
        </div>

        <!-- Floating Action Button (Hidden on Small Screens) -->
        <div class="hidden sm:block fixed bottom-8 right-8 z-50">
            <button 
                class="bg-portfolio-blue dark:bg-blue-600 hover:bg-blue-600 dark:hover:bg-blue-700 text-white p-3 sm:p-4 rounded-full shadow-lg transition-all hover:scale-110 group"
                title="Scroll to top"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            >
                <span class="text-lg sm:text-xl group-hover:animate-bounce-gentle">↑</span>
            </button>
        </div>
        
        <!-- Add padding bottom for mobile navigation -->
        <div class="h-16 sm:hidden"></div>
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('toggle-dark-mode', () => {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('carouselTrack');
        const wrapper = track.querySelector('.carousel-wrapper');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const dotsContainer = document.getElementById('dotsContainer');
        
        let currentSlide = 0;
        let isManualControl = false;
        let manualTimeout;
        const originalItems = wrapper.children.length / 5; // Since we duplicate items
        
        // Create navigation dots based on original items
        function createDots() {
            dotsContainer.innerHTML = '';
            
            for (let i = 0; i < originalItems; i++) {
                const dot = document.createElement('div');
                dot.className = `carousel-dot ${i === 0 ? 'active' : ''}`;
                dot.addEventListener('click', () => goToSlide(i));
                dotsContainer.appendChild(dot);
            }

        }
        
        // Update active dot
        function updateDots() {
            const dots = dotsContainer.querySelectorAll('.carousel-dot');
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }
        
        // Manual navigation to specific slide
        function goToSlide(slideIndex) {
            currentSlide = slideIndex;
            stopAutoScroll();
            
            const itemWidth = wrapper.children[0].offsetWidth + 16; // width + gap
            const translateX = -(slideIndex * itemWidth);
            
            wrapper.style.transform = `translateX(${translateX}px)`;
            updateDots();
            
            // Resume auto scroll after 3 seconds
            resumeAutoScroll();
        }
        
        // Next slide
        function nextSlide() {
            currentSlide = (currentSlide + 1) % originalItems;
            goToSlide(currentSlide);
        }
        
        // Previous slide
        function prevSlide() {
            currentSlide = currentSlide === 0 ? originalItems - 1 : currentSlide - 1;
            goToSlide(currentSlide);
        }
        
        // Stop auto scroll for manual control
        function stopAutoScroll() {
            isManualControl = true;
            wrapper.classList.add('smooth-scroll');
            wrapper.style.animation = 'none';
            
            clearTimeout(manualTimeout);
        }
        
        // Resume auto scroll
        function resumeAutoScroll() {
            manualTimeout = setTimeout(() => {
                isManualControl = false;
                wrapper.classList.remove('smooth-scroll');
                wrapper.style.animation = '';
                wrapper.style.transform = '';
                currentSlide = 0;
                updateDots();
            }, 4000);
        }
        
        // Track current slide based on scroll position (for dots update during auto scroll)
        function trackAutoScroll() {
            if (!isManualControl) {
                const scrollProgress = (Date.now() % 20000) / 20000; // 20s animation duration
                const newSlide = Math.floor(scrollProgress * originalItems) % originalItems;
                
                if (newSlide !== currentSlide) {
                    currentSlide = newSlide;
                    updateDots();
                }
            }
            
            requestAnimationFrame(trackAutoScroll);
        }
        
        // Event listeners
        nextBtn.addEventListener('click', nextSlide);
        prevBtn.addEventListener('click', prevSlide);
        
        // Pause on hover, resume on leave
        wrapper.addEventListener('mouseenter', () => {
            if (!isManualControl) {
                wrapper.style.animationPlayState = 'paused';
            }
        });
        
        wrapper.addEventListener('mouseleave', () => {
            if (!isManualControl) {
                wrapper.style.animationPlayState = 'running';
            }
        });
        
        // Initialize
        createDots();
        trackAutoScroll();
    });
</script>