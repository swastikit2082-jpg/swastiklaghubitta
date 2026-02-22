// Common JavaScript for Swastik Microfinance Website

// Date and Time Display Function
function updateDateTime() {
    const now = new Date();
    
    // Get Nepali date (Bikram Sambat approximation)
    const bsYear = now.getFullYear() + 57; // BS is roughly AD + 57
    const bsMonths = ['बैशाख', 'जेठ', 'असार', 'श्रावण', 'भदौ', 'अशोज', 'कार्तिक', 'मंसिर', 'पौष', 'माघ', 'फाल्गुन', 'चैत'];
    const bsMonth = bsMonths[now.getMonth()];
    const bsDay = now.getDate();
    const bsDate = `${bsDay} ${bsMonth} ${bsYear}`;
    
    // Get English date
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const enDate = now.toLocaleDateString('en-US', options);
    
    // Get time
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const time = `${hours}:${minutes}:${seconds}`;
    
    // Display in both formats
    const datetimeDisplay = document.getElementById('datetime-display');
    if (datetimeDisplay) {
        datetimeDisplay.innerHTML = `
            <div style="display: flex; flex-direction: column; align-items: center; line-height: 1.3;">
                <span style="font-size: 0.75rem;">${bsDate}</span>
                <span style="font-size: 0.85rem; font-weight: 700;">${time}</span>
            </div>
        `;
    }
}

// Update time every second
setInterval(updateDateTime, 1000);
// Initial call
updateDateTime();

// Preloader
window.addEventListener('load', function() {
    setTimeout(function() {
        const preloader = document.getElementById('preloader');
        if (preloader) {
            preloader.classList.add('fade-out');
        }
    }, 1500);
});

// Mobile menu toggle
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');
if (hamburger && navLinks) {
    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('active');
    });
}

// Header scroll effect
window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
});

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Animated Counter
function animateCounters() {
    const counters = document.querySelectorAll('.stat-number, .hero-stat-number');
    
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;
        
        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current).toLocaleString();
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target.toLocaleString() + (target >= 1000 ? '+' : '');
            }
        };
        
        updateCounter();
    });
}

// Start counter animation when stats section is visible
const statsObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            animateCounters();
            statsObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.5 });

const statsSection = document.querySelector('.stats-section');
if (statsSection) {
    statsObserver.observe(statsSection);
}

// FAQ Accordion
document.querySelectorAll('.faq-question').forEach(button => {
    button.addEventListener('click', () => {
        const faqItem = button.parentElement;
        faqItem.classList.toggle('active');
    });
});

// Fade in animation on scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('.fade-in').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(30px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
});

// Loan Application Form Submission
async function submitApplication(e) {
    e.preventDefault();
    
    const formData = {
        name: document.getElementById('name').value,
        phone: document.getElementById('phone').value,
        loan_type: document.getElementById('loan_type').value,
        amount: document.getElementById('amount').value
    };

    try {
        const response = await fetch('loan.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData).toString()
        });

        const result = await response.text();
        
        alert('🎉 आवेदन सफलतापूर्वक पेश भयो!\n\nहाम्रो टिम २४ घण्टाभित्र तपाईंसँग सम्पर्क गर्नेछ।\n\nधन्यवाद!');
        e.target.reset();
    } catch (error) {
        console.error('Error:', error);
        alert('❌ त्रुटि भयो। कृपया पुनः प्रयास गर्नुहोस्।');
    }
}

// ========================================
// DATE AND TIME DISPLAY - Live Clock
// ========================================
function updateDateTime() {
    const datetimeDisplay = document.getElementById('datetime-display');
    if (!datetimeDisplay) return;
    
    const now = new Date();
    
    // Time in 12-hour format with Nepali/English
    let hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, '0');
    const seconds = now.getSeconds().toString().padStart(2, '0');
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    const timeStr = `${hours}:${minutes}:${seconds} ${ampm}`;
    
    // English Date
    const englishDate = now.toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
    
    // Nepali Date (BS Calendar - Approximate conversion)
    const nepaliMonths = ['बैशाख', 'जेठ', 'असार', 'श्रावण', 'भदौ', 'अश्विन', 'कार्तिक', 'मंसिर', 'पुष', 'माघ', 'फाल्गुन', 'चैत'];
    const nepaliDays = ['आइतबार', 'सोमबार', 'मंगलबार', 'बुधबार', 'बिहिबार', 'शुक्रबार', 'शनिबार'];
    
    // Get approximate BS year (current year - 56-58 years for Nepali calendar)
    const bsYear = now.getFullYear() - 57;
    const bsMonth = nepaliMonths[now.getMonth()];
    const bsDay = now.getDate();
    const bsWeekday = nepaliDays[now.getDay()];
    
    const nepaliDate = `${bsWeekday}, ${bsDay} ${bsMonth} ${bsYear}`;
    
    // Update the display
    datetimeDisplay.innerHTML = `
        <div style="display: flex; flex-direction: column; align-items: center; gap: 2px;">
            <div style="font-size: 0.9rem; font-weight: 700; color: #fbbf24;">
                🕐 ${timeStr}
            </div>
            <div style="font-size: 0.7rem; color: rgba(255,255,255,0.85);">
                ${englishDate}
            </div>
            <div style="font-size: 0.7rem; color: #fbbf24;">
                📅 ${nepaliDate}
            </div>
        </div>
    `;
}

// Initialize date/time when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initial call
    updateDateTime();
    // Update every second
    setInterval(updateDateTime, 1000);
});

// ========================================
// ENHANCED ANIMATIONS
// ========================================

// Scroll Animation - Fade In Up
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.service-card, .stat-box, .testimonial-card, .news-card, .mv-card, .why-card, .chart-card, .progress-card');
    
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(40px)';
        el.style.transition = `all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) ${index * 0.1}s`;
    });

    const animationObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                animationObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    animatedElements.forEach(el => animationObserver.observe(el));
}

// 3D Card Tilt Effect
function initCardTilt() {
    const cards = document.querySelectorAll('.service-card, .team-card, .news-card');
    
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            const centerX = rect.width / 2;
            const centerY = rect.height / 2;
            const rotateX = (y - centerY) / 10;
            const rotateY = (centerX - x) / 10;
            
            card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`;
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)';
        });
    });
}

// Progress Bar Animation
function initProgressBars() {
    const progressBars = document.querySelectorAll('.progress-fill');
    
    const progressObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const bar = entry.target;
                const targetWidth = bar.getAttribute('data-width');
                bar.style.width = targetWidth + '%';
                progressObserver.unobserve(bar);
            }
        });
    }, { threshold: 0.5 });

    progressBars.forEach(bar => progressObserver.observe(bar));
}

// Hero Particles Animation
function createParticles() {
    const hero = document.querySelector('.hero');
    if (!hero) return;
    
    const particleContainer = document.createElement('div');
    particleContainer.className = 'particles-container';
    hero.appendChild(particleContainer);
    
    for (let i = 0; i < 20; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 10 + 's';
        particle.style.animationDuration = (10 + Math.random() * 10) + 's';
        particleContainer.appendChild(particle);
    }
}

// Enhanced Counter Animation with Easing
function animateCounter(element, target, duration = 2000) {
    const start = 0;
    const startTime = performance.now();
    
    function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easeOutQuart(progress);
        const current = Math.floor(start + (target - start) * easedProgress);
        
        element.textContent = current.toLocaleString() + (target >= 1000 ? '+' : '');
        
        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            element.textContent = target.toLocaleString() + (target >= 1000 ? '+' : '');
        }
    }
    
    requestAnimationFrame(update);
}

// Initialize all enhanced animations
document.addEventListener('DOMContentLoaded', function() {
    initScrollAnimations();
    initCardTilt();
    initProgressBars();
    createParticles();
    
    // Enhanced counter animation
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counters = entry.target.querySelectorAll('.stat-number, .hero-stat-number');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    animateCounter(counter, target, 2500);
                });
                counterObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3 });
    
    document.querySelectorAll('.stats-section, .hero').forEach(section => {
        counterObserver.observe(section);
    });
});

// Theme Toggle Animation
function initThemeToggle() {
    const themeToggle = document.querySelector('.theme-toggle');
    if (!themeToggle) return;
    
    themeToggle.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        
        // Animate the toggle
        themeToggle.style.transform = 'scale(0.9) rotate(180deg)';
        setTimeout(() => {
            themeToggle.style.transform = 'scale(1) rotate(0deg)';
        }, 300);
    });
}

// Add hover sound effect (optional - commented out)
// function playHoverSound() {
//     const hoverSound = new Audio('hover.mp3');
//     hoverSound.volume = 0.1;
//     hoverSound.play().catch(() => {});
// }

// Button Ripple Effect
function initRippleEffect() {
    const buttons = document.querySelectorAll('.btn, .cta-btn, .submit-btn, .sidebar-btn');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const rect = this.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.4);
                border-radius: 50%;
                pointer-events: none;
                width: 20px;
                height: 20px;
                left: ${x}px;
                top: ${y}px;
                transform: translate(-50%, -50%) scale(0);
                animation: ripple 0.6s ease-out;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
}

// Initialize additional effects
document.addEventListener('DOMContentLoaded', function() {
    initThemeToggle();
    initRippleEffect();
});

// Add CSS for ripple animation
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: translate(-50%, -50%) scale(10);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// ========================================
// ENHANCED CHARTS FUNCTIONALITY
// ========================================

// Chart Navigation and Filtering
function initChartNavigation() {
    const navButtons = document.querySelectorAll('.chart-nav-btn');
    const chartItems = document.querySelectorAll('.chart-item');

    if (!navButtons.length || !chartItems.length) return;

    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            navButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            const filter = button.getAttribute('data-chart');

            chartItems.forEach(item => {
                if (filter === 'all') {
                    item.classList.remove('hidden');
                    item.classList.remove('fade-out');
                } else {
                    const category = item.getAttribute('data-category');
                    if (category === filter) {
                        item.classList.remove('hidden');
                        item.classList.remove('fade-out');
                    } else {
                        item.classList.add('fade-out');
                        setTimeout(() => item.classList.add('hidden'), 500);
                    }
                }
            });
        });
    });
}

// Chart Expand Functionality
function expandChart(chartId) {
    const modal = document.getElementById('chartModal');
    if (!modal) return;
    
    const modalChart = document.getElementById('modalChart');
    if (!modalChart) return;

    const chartConfig = Chart.getChart(chartId);

    if (chartConfig) {
        new Chart(modalChart, chartConfig.config);
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeChartModal() {
    const modal = document.getElementById('chartModal');
    if (!modal) return;
    
    modal.classList.remove('show');
    document.body.style.overflow = '';

    const modalChart = document.getElementById('modalChart');
    if (!modalChart) return;
    
    const chartInstance = Chart.getChart(modalChart);
    if (chartInstance) {
        chartInstance.destroy();
    }
}

// Animated Chart Counters
function animateChartCounters() {
    const counters = document.querySelectorAll('.chart-stat-value[data-count]');
    
    if (!counters.length) return;

    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-count'));
        const duration = 2000;
        const increment = target / (duration / 16);
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                counter.textContent = Math.floor(current);
                requestAnimationFrame(updateCounter);
            } else {
                counter.textContent = target;
            }
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateCounter();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        observer.observe(counter);
    });
}

// Enhanced Chart Animations
function initChartAnimations() {
    const chartCards = document.querySelectorAll('.chart-card');
    
    if (!chartCards.length) return;

    chartCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200);

        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Chart Tooltip Enhancement
function initChartTooltips() {
    const charts = document.querySelectorAll('.chart-container canvas');
    
    if (!charts.length) return;

    charts.forEach(canvas => {
        canvas.style.cursor = 'pointer';
    });
}

// Initialize all chart enhancements
document.addEventListener('DOMContentLoaded', function() {
    initChartNavigation();
    animateChartCounters();
    initChartAnimations();
    initChartTooltips();

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeChartModal();
        }
    });

    const chartModal = document.getElementById('chartModal');
    if (chartModal) {
        chartModal.addEventListener('click', (e) => {
            if (e.target.id === 'chartModal') {
                closeChartModal();
            }
        });
    }
});

// CEO Section Scroll Animation
function initCEOAnimation() {
    const ceoSection = document.querySelector('.ceo-section');
    if (!ceoSection) return;
    
    const ceoImage = ceoSection.querySelector('.ceo-image');
    const ceoInfo = ceoSection.querySelector('.ceo-info');
    
    // Create intersection observer for CEO section
    const ceoObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add animation classes with delay
                if (ceoImage) {
                    setTimeout(() => {
                        ceoImage.classList.add('ceo-animate');
                    }, 200);
                }
                if (ceoInfo) {
                    setTimeout(() => {
                        ceoInfo.classList.add('ceo-animate');
                    }, 400);
                }
                ceoObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.3, rootMargin: '0px 0px -100px 0px' });
    
    ceoObserver.observe(ceoSection);
}

// Initialize CEO animation
document.addEventListener('DOMContentLoaded', function() {
    initCEOAnimation();
});
