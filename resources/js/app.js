import './bootstrap';

// Smooth scroll behavior and animations
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add intersection observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
            }
        });
    }, observerOptions);

    // Observe all portfolio cards
    document.querySelectorAll('.portfolio-card, .portfolio-card-colored').forEach(card => {
        observer.observe(card);
    });

    // Mobile menu functionality
    const mobileMenuButtons = document.querySelectorAll('[data-section]');
    mobileMenuButtons.forEach(button => {
        button.addEventListener('click', function() {
            const section = this.getAttribute('data-section');
            const targetElement = document.querySelector(`[data-section-content="${section}"]`);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// PWA-like behavior for mobile
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        // Optional: Register service worker for better mobile experience
        console.log('Ready for PWA enhancements');
    });
}

// Handle orientation changes
window.addEventListener('orientationchange', function() {
    setTimeout(() => {
        window.scrollTo(0, window.scrollY + 1);
        window.scrollTo(0, window.scrollY - 1);
    }, 100);
});