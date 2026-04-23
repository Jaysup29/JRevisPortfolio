<?php

use Livewire\Volt\Component;
use Illuminate\Support\Collection;
use App\Models\Skill;
use App\Models\Project;
use App\Mail\ContactFormMail;

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

        Mail::To('jrevis029@gmail.com')->send(new ContactFormMail($this->contactForm));
        
        // Here you would typically send an email or save to database
        session()->flash('message', 'Thank you for your message! I\'ll get back to you soon.');
        
        $this->contactForm = ['name' => '', 'email' => '', 'subject' => '', 'message' => ''];
        $this->showContactForm = false;
    }
}; ?>

<div class="min-h-screen transition-colors duration-300 p-2 sm:p-4">
    <!-- Splash Screen -->
    <div class="splash-overlay" id="splashOverlay">
        <div class="ripple-container" id="rippleContainer"></div>
        <div class="splash-flash" id="splashFlash"></div>
        <!-- Greeting text: absolutely centered at ripple origin -->
        <div class="splash-greet" id="splashGreet" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(0.3);z-index:2;width:100%;"></div>
        <!-- Name + CTA: also centered but below the greeting -->
        <div class="splash-name" id="splashName" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);z-index:2;text-align:center;width:100%;">
            <h1>I'm Jay-ar<span>.</span></h1>
            <div class="splash-title" id="splashTyped"></div>
            <div class="splash-cta" id="splashCta">
                <button onclick="enterPortfolio()">
                    <span>Get to know me &rarr;</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Dark Mode Toggle -->
    <!-- Dark Mode Toggle -->
    <button
        id="darkModeToggle"
        class="dark-mode-toggle group"
        title="Toggle Dark Mode"
        wire:ignore
    >
        <span class="block dark:hidden text-gray-700 group-hover:text-yellow-500 transition-colors">🌙</span>
        <span class="hidden dark:block text-yellow-400 group-hover:text-yellow-300 transition-colors">☀️</span>
    </button>
    <script>
        document.getElementById('darkModeToggle').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
            }
            var nowDark = document.documentElement.classList.contains('dark');
            if (window.__bgStyle) {
                window.__bgStyle.textContent = 'html, body { background-color: ' + (nowDark ? '#0a0a14' : '#f0f1f5') + ' !important; }';
            }
        });
    </script>

    <div class="max-w-7xl mx-auto">
        <!-- Portfolio Grid Layout -->
        <div class="mobile-grid">
            
            <!-- Main Hero Section -->
            <div id="section-home" class="lg:row-span-1 lg:row-start-1 md:col-span-2 lg:col-span-2 scroll-reveal tile-enter-hidden" data-reveal-delay="0">
                <div class="portfolio-card-colored bg-portfolio-dark dark:bg-gray-800 text-white h-full min-h-[300px] sm:min-h-[400px] relative overflow-hidden">
                    <div class="tile-glow" id="glow-hero"></div>
                    <div class="relative z-10 h-full flex flex-col justify-center">
                        <h1 class="mobile-hero-title font-bold mb-2 sm:mb-4 leading-tight">
                            JAY-AR <span class="text-portfolio-yellow dark:text-yellow-400">REVIS</span>
                        </h1>
                        <p class="text-lg sm:text-xl lg:text-2xl mb-4 sm:mb-6 text-blue-200 dark:text-blue-300" id="heroTyped"></p>
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
                id="section-about"
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
                class="relative lg:row-span-1 lg:row-start-1 lg:row-end-3 portfolio-card-colored bg-portfolio-blue dark:bg-blue-600 text-white min-h-[480px] sm:min-h-[400px] lg:min-h-[500px] flex items-center justify-center scroll-reveal tile-enter-hidden"
                data-reveal-delay="100"
            >
                <div class="tile-glow tile-glow-white" id="glow-about"></div>
                <div class="h-full flex flex-col p-4 sm:p-6 relative w-full">
                    <div class="text-center mb-6">
                        <h2 class="text-3xl sm:text-4xl font-bold mb-2">ABOUT ME</h2>
                        <div class="w-16 h-1 bg-blue-200 mx-auto rounded-full"></div>
                    </div>

                    <!-- Content Container with Fixed Height -->
                    <div class="flex-1 relative pb-14 sm:pb-16 min-h-[200px] sm:min-h-[280px] lg:min-h-[340px]">
                        <!-- Professional Journey -->
                        <div
                            class="absolute inset-0 pb-14 sm:pb-16 transition-opacity duration-300 ease-in-out"
                            :class="sections[current].key === 'journey' ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
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
                            class="absolute inset-0 pb-14 sm:pb-16 transition-opacity duration-300 ease-in-out"
                            :class="sections[current].key === 'expertise' ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
                        >
                            <h3 class="text-xl font-semibold mb-4 flex items-center gap-2">
                                <span class="text-2xl">🚀</span>
                                Core Expertise
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2 gap-2 sm:gap-4 lg:gap-2 xl:gap-4">
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-2 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <span class="text-lg">🔧</span>
                                        <span class="font-semibold text-sm sm:text-base">Backend Development</span>
                                    </div>
                                    <p class="text-blue-200 text-xs sm:text-sm">PHP, Laravel, MySQL, API Development</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-2 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <span class="text-lg">🎨</span>
                                        <span class="font-semibold text-sm sm:text-base">Frontend Development</span>
                                    </div>
                                    <p class="text-blue-200 text-xs sm:text-sm">Livewire, JavaScript, Tailwind CSS, Alpine.js</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-2 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <span class="text-lg">☁️</span>
                                        <span class="font-semibold text-sm sm:text-base">Cloud & DevOps</span>
                                    </div>
                                    <p class="text-blue-200 text-xs sm:text-sm">AWS, Docker, Git, CI/CD Pipelines</p>
                                </div>
                                <div class="bg-blue-700 dark:bg-blue-800 bg-opacity-50 rounded-lg p-2 sm:p-4">
                                    <div class="flex items-center gap-2 mb-1 sm:mb-2">
                                        <span class="text-lg">📱</span>
                                        <span class="font-semibold text-sm sm:text-base">Modern Development</span>
                                    </div>
                                    <p class="text-blue-200 text-xs sm:text-sm">Responsive Design, PWAs, Performance Optimization</p>
                                </div>
                            </div>
                        </div>

                        <!-- What Drives Me -->
                        <div
                            class="absolute inset-0 pb-14 sm:pb-16 transition-opacity duration-300 ease-in-out"
                            :class="sections[current].key === 'values' ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
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
                    <div class="absolute bottom-2 sm:bottom-4 left-0 right-0 flex justify-between items-center px-3 sm:px-6 gap-2 sm:gap-4 z-20">
                        <!-- Previous Button -->
                        <button
                            @click="goToPrev()"
                            :disabled="isTransitioning"
                            class="px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-500 hover:bg-blue-400 disabled:opacity-50 disabled:cursor-not-allowed rounded-full text-xs sm:text-sm shadow-lg text-nowrap capitalize transition-all duration-200"
                        >
                            ← <span x-text="sections[(current - 1 + sections.length) % sections.length].key"></span>
                        </button>

                        <!-- Next Button -->
                        <button
                            @click="goToNext()"
                            :disabled="isTransitioning"
                            class="px-3 sm:px-4 py-1.5 sm:py-2 bg-blue-500 hover:bg-blue-400 disabled:opacity-50 disabled:cursor-not-allowed rounded-full text-xs sm:text-sm shadow-lg text-nowrap capitalize transition-all duration-200"
                        >
                            <span x-text="sections[(current + 1) % sections.length].key"></span> →
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Technology Stack -->
            <div id="section-carousel" class="md:col-span-2 lg:col-span-2 lg:row-span-1 lg:row-start-2 overflow-hidden rounded-2xl scroll-reveal tile-enter-hidden" data-reveal-delay="200">
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
            <div id="section-contact" class=" lg:row-start-3 lg:row-end-5 portfolio-card-colored bg-portfolio-green dark:bg-green-600 text-white min-h-[250px] sm:min-h-[300px] flex items-center justify-center scroll-reveal tile-enter-hidden" data-reveal-delay="300">
                <div class="tile-glow tile-glow-white" id="glow-contact"></div>
                <div class="h-full flex flex-col p-4 sm:p-6">
                    <!-- Status pill: always-on availability signal -->
                    <div class="flex justify-center mb-4">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-900/60 border border-green-400/40 text-green-100 text-xs sm:text-sm font-semibold">
                            <span class="w-2 h-2 rounded-full bg-green-300 animate-pulse"></span>
                            Available for freelance projects
                        </span>
                    </div>

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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H6.911a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661z" />
                                    </svg>
                                    Get In Touch
                                </h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Email</div>
                                            <a href="mailto:jrevis029@gmail.com" class="text-green-200 hover:text-white transition-colors text-sm">
                                                jrevis029@gmail.com
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Phone</div>
                                            <a href="tel:+639761598467" class="text-green-200 hover:text-white transition-colors text-sm">
                                                +63 976 159 8467
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        <div>
                                            <div class="font-medium">Location</div>
                                            <span class="text-green-200 text-sm">Tondo, Manila · Remote Available</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Form Toggle Button -->
                        <button
                            wire:click="toggleContactForm"
                            class="w-full py-3 px-4 rounded-lg font-semibold transition-all hover:scale-105 flex items-center justify-center gap-2 shadow-lg"
                            style="background-color: var(--cta-amber); color: #1a1a2e;"
                            onmouseover="this.style.backgroundColor='var(--cta-amber-hover)'"
                            onmouseout="this.style.backgroundColor='var(--cta-amber)'"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.125A59.769 59.769 0 0121.485 12 59.768 59.768 0 013.27 20.875L5.999 12zm0 0h7.5" />
                            </svg>
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

                    <!-- Connect elsewhere — social brand icons -->
                    <div class="mt-6 pt-4 border-t border-green-500/40">
                        <div class="text-center text-xs uppercase tracking-wider text-green-200 mb-3">Connect elsewhere</div>
                        <div class="flex items-center justify-center gap-4">
                            <!-- GitHub -->
                            <a href="https://github.com/Jaysup29" target="_blank" rel="noopener" aria-label="GitHub"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 .5C5.73.5.5 5.73.5 12c0 5.08 3.29 9.39 7.86 10.91.58.11.79-.25.79-.56v-2.17c-3.2.7-3.87-1.37-3.87-1.37-.52-1.33-1.28-1.69-1.28-1.69-1.05-.71.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.72-1.55-2.55-.29-5.24-1.28-5.24-5.69 0-1.26.45-2.29 1.18-3.1-.12-.29-.51-1.47.11-3.06 0 0 .97-.31 3.18 1.18.92-.26 1.91-.39 2.89-.39.98 0 1.97.13 2.89.39 2.2-1.49 3.17-1.18 3.17-1.18.63 1.59.24 2.77.12 3.06.74.81 1.18 1.84 1.18 3.1 0 4.42-2.69 5.4-5.25 5.68.41.36.78 1.06.78 2.13v3.16c0 .31.21.68.8.56A11.52 11.52 0 0023.5 12C23.5 5.73 18.27.5 12 .5z"/>
                                </svg>
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://www.linkedin.com/in/jayarrevis/" target="_blank" rel="noopener" aria-label="LinkedIn"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.063 2.063 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <!-- Facebook -->
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener" aria-label="Facebook"
                               class="text-white hover:text-green-200 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div id="section-projects" class="lg:row-start-3 lg:row-end-4 md:col-span-2 lg:col-span-2 portfolio-card-colored bg-portfolio-red dark:bg-red-600 text-white min-h-[520px] scroll-reveal tile-enter-hidden" data-reveal-delay="400">
                <div class="tile-glow tile-glow-white" id="glow-projects"></div>
                <div class="h-full flex flex-col">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8 text-center">PROJECTS</h3>
                    
                    <div class="flex-1 space-y-4 sm:space-y-6 overflow-y-auto max-h-72 sm:max-h-80">
                        @foreach($projects as $index => $project)
                        <div class="bg-red-600 dark:bg-red-700 bg-opacity-50 dark:bg-opacity-50 rounded-lg p-3 sm:p-4 hover:bg-opacity-70 dark:hover:bg-opacity-70 transition-all cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <span class=""><img class="w-8 h-8 sm:w-[50px] sm:h-[50px]" src="{{ asset($project['image'] ? $project['image'] : 'default_logo.png') }}" /></span>
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

        </div>

        <!-- Mobile Navigation Bar (Sticky Bottom) -->
        <div
            x-data="{
                activeNav: 'home',
                scrollTo(section) {
                    this.activeNav = section;
                    const el = document.getElementById('section-' + section);
                    if (el) {
                        const offset = el.getBoundingClientRect().top + window.scrollY - 16;
                        window.scrollTo({ top: offset, behavior: 'smooth' });
                    }
                }
            }"
            class="fixed bottom-0 left-0 right-0 z-40 sm:hidden"
        >
            <div class="mx-3 mb-2 bg-gray-900/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-2xl shadow-2xl border border-gray-700/50 dark:border-gray-600/50">
                <div class="grid grid-cols-4 py-1.5 px-2">
                    <button @click="scrollTo('home')" class="group flex flex-col items-center py-2 px-1 rounded-xl transition-all duration-200" :class="activeNav === 'home' ? 'bg-portfolio-blue/20' : ''">
                        <span class="text-lg transition-transform duration-200 group-active:scale-90" :class="activeNav === 'home' ? 'scale-110' : ''">🏠</span>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors" :class="activeNav === 'home' ? 'text-portfolio-blue dark:text-blue-400' : 'text-gray-400'">Home</span>
                    </button>
                    <button @click="scrollTo('about')" class="group flex flex-col items-center py-2 px-1 rounded-xl transition-all duration-200" :class="activeNav === 'about' ? 'bg-portfolio-blue/20' : ''">
                        <span class="text-lg transition-transform duration-200 group-active:scale-90" :class="activeNav === 'about' ? 'scale-110' : ''">👨‍💻</span>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors" :class="activeNav === 'about' ? 'text-portfolio-blue dark:text-blue-400' : 'text-gray-400'">About</span>
                    </button>
                    <button @click="scrollTo('projects')" class="group flex flex-col items-center py-2 px-1 rounded-xl transition-all duration-200" :class="activeNav === 'projects' ? 'bg-portfolio-blue/20' : ''">
                        <span class="text-lg transition-transform duration-200 group-active:scale-90" :class="activeNav === 'projects' ? 'scale-110' : ''">🚀</span>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors" :class="activeNav === 'projects' ? 'text-portfolio-blue dark:text-blue-400' : 'text-gray-400'">Projects</span>
                    </button>
                    <button @click="scrollTo('contact')" class="group flex flex-col items-center py-2 px-1 rounded-xl transition-all duration-200" :class="activeNav === 'contact' ? 'bg-portfolio-blue/20' : ''">
                        <span class="text-lg transition-transform duration-200 group-active:scale-90" :class="activeNav === 'contact' ? 'scale-110' : ''">📧</span>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors" :class="activeNav === 'contact' ? 'text-portfolio-blue dark:text-blue-400' : 'text-gray-400'">Contact</span>
                    </button>
                </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const track = document.getElementById('carouselTrack');
        if (!track) return;
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

    // Inner glow follows cursor inside tile
    document.querySelectorAll('.portfolio-card-colored').forEach(tile => {
        const glow = tile.querySelector('.tile-glow');
        if (!glow) return;

        tile.addEventListener('mousemove', (e) => {
            const rect = tile.getBoundingClientRect();
            glow.style.left = (e.clientX - rect.left) + 'px';
            glow.style.top = (e.clientY - rect.top) + 'px';

            // 3D tilt
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const rotateX = (y - rect.height / 2) / rect.height * -6;
            const rotateY = (x - rect.width / 2) / rect.width * 6;
            tile.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-2px)`;
        });

        tile.addEventListener('mouseleave', () => {
            tile.style.transform = 'perspective(800px) rotateX(0) rotateY(0) translateY(0)';
        });
    });

    // Scroll reveal with stagger — only for elements NOT handled by splash entrance
    function initScrollReveal() {
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const delay = parseInt(entry.target.dataset.revealDelay || '0');
                    setTimeout(() => {
                        entry.target.classList.add('revealed');
                    }, delay);
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.scroll-reveal:not(.tile-enter-hidden)').forEach(el => {
            revealObserver.observe(el);
        });
    }

    // ===== SPLASH SCREEN =====
    const greetingsData = [
        { word: 'Kumusta', color: '#f4a261' },
        { word: 'Hello', color: '#4361ee' },
        { word: 'Hola', color: '#e63946' },
        { word: 'Bonjour', color: '#2ec4b6' },
        { word: '\u3053\u3093\u306b\u3061\u306f', color: '#a78bfa' },
        { word: '\uc548\ub155\ud558\uc138\uc694', color: '#f472b6' },
        { word: 'Ciao', color: '#34d399' },
        { word: 'Hallo', color: '#fbbf24' },
        { word: 'Ol\u00e1', color: '#60a5fa' },
        { word: 'Merhaba', color: '#fb923c' },
        { word: '\u041f\u0440\u0438\u0432\u0435\u0442', color: '#a78bfa' },
        { word: '\u4f60\u597d', color: '#4361ee' },
        { word: 'Namaste', color: '#2ec4b6' },
        { word: 'Aloha', color: '#f4a261' },
    ];

    function initSplash() {
        if (sessionStorage.getItem('splashSeen')) {
            const overlay = document.getElementById('splashOverlay');
            if (overlay) overlay.classList.add('hidden');
            document.body.style.overflow = 'auto';
            revealTilesInstantly();
            return;
        }

        document.body.style.overflow = 'hidden';
        const greetEl = document.getElementById('splashGreet');
        const rippleContainer = document.getElementById('rippleContainer');
        const flashEl = document.getElementById('splashFlash');
        if (!greetEl || !rippleContainer || !flashEl) return;

        let cycleIndex = 0;

        function spawnRipple(color) {
            const size = 600 + Math.random() * 400;
            const ripple = document.createElement('div');
            ripple.className = 'ripple-ring';
            ripple.style.width = size + 'px';
            ripple.style.height = size + 'px';
            ripple.style.color = color;
            rippleContainer.appendChild(ripple);

            flashEl.classList.remove('flash');
            void flashEl.offsetWidth;
            var isDarkMode = document.documentElement.classList.contains('dark');
            var flashAlpha = isDarkMode ? '11' : '30';
            flashEl.style.background = 'radial-gradient(circle at center, ' + color + flashAlpha + ' 0%, transparent 60%)';
            flashEl.classList.add('flash');

            setTimeout(() => ripple.remove(), 1200);
        }

        function showGreet(index) {
            greetEl.textContent = greetingsData[index].word;
            greetEl.style.color = greetingsData[index].color;
            greetEl.classList.remove('pop-out');
            void greetEl.offsetWidth;
            greetEl.classList.add('pop-in');
            spawnRipple(greetingsData[index].color);
        }

        function hideGreet() {
            greetEl.classList.remove('pop-in');
            greetEl.classList.add('pop-out');
        }

        showGreet(0);

        const interval = setInterval(() => {
            hideGreet();
            setTimeout(() => {
                cycleIndex++;
                if (cycleIndex >= greetingsData.length) {
                    clearInterval(interval);
                    greetEl.classList.remove('pop-out');
                    greetEl.textContent = 'Hello';
                    greetEl.style.color = '#4361ee';
                    void greetEl.offsetWidth;
                    greetEl.classList.add('pop-in');
                    spawnRipple('#4361ee');
                    setTimeout(() => spawnRipple('#4361ee'), 200);
                    setTimeout(() => spawnRipple('#4361ee'), 400);
                    setTimeout(showNameReveal, 1200);
                    return;
                }
                showGreet(cycleIndex);
            }, 200);
        }, 350);
    }

    function showNameReveal() {
        const greetEl = document.getElementById('splashGreet');
        greetEl.classList.remove('pop-in');
        greetEl.classList.add('pop-out');

        setTimeout(() => {
            greetEl.style.display = 'none';
            document.getElementById('splashName').classList.add('visible');
            setTimeout(startSplashTyping, 500);
        }, 300);
    }

    function startSplashTyping() {
        const text = "I build things for the web.";
        const el = document.getElementById('splashTyped');
        let i = 0;
        function typeChar() {
            if (i < text.length) {
                el.innerHTML = text.substring(0, i + 1) + '<span class="typing-cursor"></span>';
                i++;
                setTimeout(typeChar, 55);
            } else {
                el.innerHTML = text + '<span class="typing-cursor"></span>';
                setTimeout(() => document.getElementById('splashCta').classList.add('visible'), 300);
            }
        }
        typeChar();
    }

    function enterPortfolio() {
        sessionStorage.setItem('splashSeen', 'true');
        document.getElementById('splashOverlay').classList.add('hidden');
        document.body.style.overflow = 'auto';

        setTimeout(() => {
            // Remove hidden state from ALL tiles first
            document.querySelectorAll('.tile-enter-hidden').forEach(el => {
                el.classList.remove('tile-enter-hidden', 'scroll-reveal');
            });

            // Apply choreographed entrance to specific tiles
            const entries = [
                { id: 'section-home', cls: 'tile-enter-hero' },
                { id: 'section-about', cls: 'tile-enter-side' },
                { id: 'section-carousel', cls: 'tile-enter-stretch' },
                { id: 'section-contact', cls: 'tile-enter-bottom-1' },
                { id: 'section-projects', cls: 'tile-enter-bottom-2' },
            ];
            entries.forEach(t => {
                const el = document.getElementById(t.id);
                if (el) el.classList.add(t.cls);
            });

            // Start hero typing after tiles animate in
            setTimeout(startHeroTyping, 1500);
        }, 400);
    }

    function revealTilesInstantly() {
        document.querySelectorAll('.tile-enter-hidden').forEach(el => {
            el.classList.remove('tile-enter-hidden', 'scroll-reveal');
            el.classList.add('revealed');
            el.style.opacity = '1';
            el.style.pointerEvents = 'auto';
        });
        startHeroTyping();
    }

    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(initSplash, 300);
    });


    function startHeroTyping() {
        const text = "Full Stack Web Developer";
        const el = document.getElementById('heroTyped');
        if (!el || el.dataset.typed) return;
        el.dataset.typed = 'true';
        let i = 0;
        function typeChar() {
            if (i < text.length) {
                el.innerHTML = text.substring(0, i + 1) + '<span class="typing-cursor"></span>';
                i++;
                setTimeout(typeChar, 80);
            } else {
                el.innerHTML = text + '<span class="typing-cursor"></span>';
            }
        }
        typeChar();
    }
</script>