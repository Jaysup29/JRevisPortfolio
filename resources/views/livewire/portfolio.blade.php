<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $activeSection = 'about';
    public $projects = [];
    public $technologies = [];
    public $darkMode = false;
    
    public function mount()
    {
        $this->technologies = [
            ['name' => 'JS', 'color' => 'bg-yellow-400 dark:bg-yellow-500', 'icon' => 'JS'],
            ['name' => 'Livewire', 'color' => 'bg-blue-400 dark:bg-blue-500', 'icon' => '⚡'],
            ['name' => 'Figma', 'color' => 'bg-purple-400 dark:bg-purple-500', 'icon' => '🎨'],
            ['name' => 'PHP', 'color' => 'bg-indigo-400 dark:bg-indigo-500', 'icon' => 'php'],
            ['name' => 'Bootstrap', 'color' => 'bg-purple-600 dark:bg-purple-700', 'icon' => 'B'],
            ['name' => 'Laravel', 'color' => 'bg-red-400 dark:bg-red-500', 'icon' => '📦'],
        ];
        
        $this->projects = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'Full-stack e-commerce solution with Laravel and Livewire',
                'tech' => ['Laravel', 'Livewire', 'MySQL', 'Tailwind CSS'],
                'status' => 'Completed',
                'image' => '🛒'
            ],
            [
                'title' => 'Real-time Chat Application',
                'description' => 'WebSocket-based chat app with real-time messaging',
                'tech' => ['PHP', 'WebSockets', 'JavaScript', 'Redis'],
                'status' => 'In Progress',
                'image' => '💬'
            ],
            [
                'title' => 'CMS Dashboard',
                'description' => 'Content management system with advanced admin panel',
                'tech' => ['Laravel', 'Volt', 'Alpine.js', 'MySQL'],
                'status' => 'Completed',
                'image' => '📊'
            ]
        ];
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
            <div class="sm:col-span-2 lg:col-span-2">
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
                        </div>
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute top-0 right-0 w-24 h-24 sm:w-32 sm:h-32 bg-portfolio-yellow opacity-10 rounded-full transform translate-x-8 sm:translate-x-16 -translate-y-8 sm:-translate-y-16"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 sm:w-24 sm:h-24 bg-portfolio-blue opacity-20 rounded-full transform -translate-x-6 sm:-translate-x-12 translate-y-6 sm:translate-y-12"></div>
                </div>
            </div>

            <!-- About Me Section -->
            <div class="portfolio-card-colored bg-portfolio-blue dark:bg-blue-600 text-white min-h-[300px] sm:min-h-[400px] flex items-center justify-center">
                <div class="text-center px-2">
                    <h2 class="mobile-title font-bold mb-6 sm:mb-8">ABOUT ME</h2>
                    <div class="space-y-3 sm:space-y-4 text-blue-100 dark:text-blue-200">
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-xl sm:text-2xl">🚀</span>
                            <span class="mobile-text">5+ years of web development</span>
                        </div>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-xl sm:text-2xl">💻</span>
                            <span class="mobile-text">Laravel & PHP specialist</span>
                        </div>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-xl sm:text-2xl">⚡</span>
                            <span class="mobile-text">Livewire enthusiast</span>
                        </div>
                        <div class="flex items-center justify-center gap-3">
                            <span class="text-xl sm:text-2xl">🎯</span>
                            <span class="mobile-text">Clean code advocate</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technology Stack -->
            {{-- <div class="sm:col-span-2 lg:col-span-3">
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 sm:gap-4">
                    @foreach($technologies as $tech)
                    <div class="portfolio-card-colored {{ $tech['color'] }} text-white">
                        <div class="tech-icon mx-auto">
                            <span class="text-base sm:text-xl lg:text-2xl font-bold">{{ $tech['icon'] }}</span>
                        </div>
                        <p class="text-center mt-2 sm:mt-3 font-semibold text-xs sm:text-sm lg:text-base">{{ $tech['name'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div> --}}

            <div class="sm:col-span-2 lg:col-span-3">
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
                                    <span class="text-base sm:text-xl lg:text-2xl font-bold">{{ $tech['icon'] }}</span>
                                </div>
                                <p class="text-center mt-2 sm:mt-3 font-semibold text-xs sm:text-sm lg:text-base">{{ $tech['name'] }}</p>
                            </div>
                            @endforeach
                            
                            <!-- Duplicate items for seamless loop -->
                            @foreach($technologies as $tech)
                            <div class="portfolio-card-colored {{ $tech['color'] }} text-white carousel-item">
                                <div class="tech-icon mx-auto">
                                    <span class="text-base sm:text-xl lg:text-2xl font-bold">{{ $tech['icon'] }}</span>
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
            <div class="portfolio-card-colored bg-portfolio-green dark:bg-green-600 text-white min-h-[250px] sm:min-h-[300px] flex items-center justify-center">
                <div class="text-center px-2">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8">CONTACT</h3>
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3">
                            <span class="text-xl sm:text-2xl">📧</span>
                            <span class="mobile-text break-all">jay.revis@email.com</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3">
                            <span class="text-xl sm:text-2xl">📱</span>
                            <span class="mobile-text">+63 912 345 6789</span>
                        </div>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-2 sm:gap-3">
                            <span class="text-xl sm:text-2xl">📍</span>
                            <span class="mobile-text">Philippines</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="sm:col-span-2 lg:col-span-2 portfolio-card-colored bg-portfolio-red dark:bg-red-600 text-white min-h-[300px]">
                <div class="h-full flex flex-col">
                    <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6 sm:mb-8 text-center">PROJECTS</h3>
                    
                    <div class="flex-1 space-y-4 sm:space-y-6 overflow-y-auto max-h-60 sm:max-h-80">
                        @foreach($projects as $index => $project)
                        <div class="bg-red-600 dark:bg-red-700 bg-opacity-50 dark:bg-opacity-50 rounded-lg p-3 sm:p-4 hover:bg-opacity-70 dark:hover:bg-opacity-70 transition-all cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-3">
                                    <span class="text-xl sm:text-2xl">{{ $project['image'] }}</span>
                                    <h4 class="font-bold text-sm sm:text-base lg:text-lg">{{ $project['title'] }}</h4>
                                </div>
                                <span class="text-xs bg-red-800 dark:bg-red-900 px-2 py-1 rounded flex-shrink-0">{{ $project['status'] }}</span>
                            </div>
                            <p class="text-red-100 dark:text-red-200 text-xs sm:text-sm mb-3">{{ $project['description'] }}</p>
                            <div class="flex flex-wrap gap-1 sm:gap-2">
                                @foreach($project['tech'] as $techItem)
                                <span class="text-xs bg-red-800 dark:bg-red-900 text-red-100 dark:text-red-200 px-2 py-1 rounded">{{ $techItem }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <button class="mt-4 bg-red-800 dark:bg-red-900 hover:bg-red-900 dark:hover:bg-red-800 text-white px-4 sm:px-6 py-2 rounded-lg transition-colors text-sm sm:text-base">
                        View All Projects →
                    </button>
                </div>
            </div>

            <!-- Social Links -->
            <div class="portfolio-card-colored bg-portfolio-yellow dark:bg-yellow-500 text-gray-800 dark:text-gray-900">
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
        const originalItems = wrapper.children.length / 2; // Since we duplicate items
        
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
            }, 3000);
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