/**
 * Ensskin Beauty Glow - Main JavaScript
 * Handle interactivity dan UX enhancements
 */

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    
    // Sticky Header on Scroll
    initStickyHeader();
    
    // Smooth Scroll untuk anchor links
    initSmoothScroll();
    
    // Lazy Loading Images
    initLazyLoading();
    
    // Form Validation
    initFormValidation();
    
    // Animation on Scroll
    initScrollAnimations();
    
    // Show/Hide Alert Messages
    initAlertMessages();
});

/**
 * Sticky Header Effect
 */
function initStickyHeader() {
    const header = document.querySelector('header');
    if (!header) return;
    
    let lastScroll = 0;
    
    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
        
        // Hide header on scroll down, show on scroll up (optional)
        if (currentScroll > lastScroll && currentScroll > 500) {
            header.style.transform = 'translateY(-100%)';
        } else {
            header.style.transform = 'translateY(0)';
        }
        
        lastScroll = currentScroll;
    });
}

/**
 * Smooth Scroll untuk Internal Links
 */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Ignore jika href hanya '#'
            if (href === '#') return;
            
            e.preventDefault();
            
            const target = document.querySelector(href);
            if (target) {
                const headerHeight = document.querySelector('header')?.offsetHeight || 0;
                const targetPosition = target.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Lazy Loading Images
 */
function initLazyLoading() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback untuk browser lama
        images.forEach(img => img.classList.add('loaded'));
    }
}

/**
 * Form Validation untuk Booking
 */
function initFormValidation() {
    const bookingForm = document.getElementById('bookingForm');
    if (!bookingForm) return;
    
    bookingForm.addEventListener('submit', function(e) {
        // Reset previous errors
        clearFormErrors();
        
        let isValid = true;
        const errors = [];
        
        // Validate Name
        const name = this.customer_name.value.trim();
        if (name.length < 3) {
            errors.push('Nama minimal 3 karakter');
            markFieldError('customer_name');
            isValid = false;
        }
        
        // Validate Phone
        const phone = this.customer_phone.value.trim();
        if (!/^[0-9]{10,13}$/.test(phone)) {
            errors.push('No. WhatsApp harus 10-13 digit angka');
            markFieldError('customer_phone');
            isValid = false;
        }
        
        // Validate Email (optional)
        const email = this.customer_email.value.trim();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push('Format email tidak valid');
            markFieldError('customer_email');
            isValid = false;
        }
        
        // Validate Date
        const bookingDate = this.booking_date.value;
        const today = new Date().toISOString().split('T')[0];
        if (bookingDate < today) {
            errors.push('Tanggal tidak boleh di masa lalu');
            markFieldError('booking_date');
            isValid = false;
        }
        
        // Show errors
        if (!isValid) {
            e.preventDefault();
            showFormErrors(errors);
            return false;
        }
        
        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
    });
}

function markFieldError(fieldName) {
    const field = document.querySelector(`[name="${fieldName}"]`);
    if (field) {
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-200');
    }
}

function clearFormErrors() {
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.classList.remove('border-red-500');
        field.classList.add('border-gray-200');
    });
    
    const errorDiv = document.getElementById('formErrors');
    if (errorDiv) errorDiv.remove();
}

function showFormErrors(errors) {
    const form = document.getElementById('bookingForm');
    if (!form) return;
    
    const errorDiv = document.createElement('div');
    errorDiv.id = 'formErrors';
    errorDiv.className = 'mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg';
    
    const errorList = errors.map(error => `<li>${error}</li>`).join('');
    errorDiv.innerHTML = `
        <div class="flex items-start">
            <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-1"></i>
            <div>
                <p class="font-semibold text-red-700 mb-2">Periksa kembali form Anda:</p>
                <ul class="text-red-600 text-sm list-disc list-inside">${errorList}</ul>
            </div>
        </div>
    `;
    
    form.insertBefore(errorDiv, form.firstChild);
    
    // Scroll to error
    errorDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * Animation on Scroll
 */
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('.fade-in-up, .card-hover');
    
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });
        
        animatedElements.forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    }
}

/**
 * Auto-hide Alert Messages
 */
function initAlertMessages() {
    const alerts = document.querySelectorAll('.alert-success, .alert-error');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });
}

/**
 * Price Formatter
 */
function formatPrice(price) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(price);
}

/**
 * Date Formatter
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

/**
 * Copy to Clipboard
 */
function copyToClipboard(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Berhasil disalin!', 'success');
        });
    } else {
        // Fallback untuk browser lama
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        showToast('Berhasil disalin!', 'success');
    }
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-6 py-3 rounded-lg shadow-xl text-white transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    toast.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Debounce Function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Check if element is in viewport
 */
function isInViewport(element) {
    const rect = element.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Export functions untuk digunakan di file lain
window.EnsskinUtils = {
    formatPrice,
    formatDate,
    copyToClipboard,
    showToast,
    debounce,
    isInViewport
};