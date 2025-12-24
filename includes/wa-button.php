<?php
// WhatsApp Configuration
$wa_number = '6282112568941'; // Format: 62xxx tanpa + atau spasi
$wa_message = 'Halo Ensskin Beauty Glow, saya ingin bertanya tentang treatment yang tersedia.';
?>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/<?php echo $wa_number; ?>?text=<?php echo urlencode($wa_message); ?>" 
   target="_blank"
   id="waButton"
   class="fixed bottom-6 right-6 z-50 w-16 h-16 bg-green-500 rounded-full shadow-2xl flex items-center justify-center hover:bg-green-600 transition-all transform hover:scale-110 group">
    <i class="fab fa-whatsapp text-white text-3xl"></i>
    
    <!-- Tooltip -->
    <div class="absolute right-full mr-4 bg-white text-gray-800 px-4 py-2 rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all whitespace-nowrap">
        <span class="font-semibold">Chat dengan Admin</span>
        <div class="absolute top-1/2 -right-2 -translate-y-1/2 w-0 h-0 border-t-8 border-t-transparent border-b-8 border-b-transparent border-l-8 border-l-white"></div>
    </div>
    
    <!-- Pulse Animation -->
    <span class="absolute inset-0 rounded-full bg-green-400 animate-ping opacity-75"></span>
</a>

<style>
@keyframes ping {
    75%, 100% {
        transform: scale(1.5);
        opacity: 0;
    }
}

.animate-ping {
    animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}

/* Smooth entrance animation */
#waButton {
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateY(100px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Hide on mobile when scrolling down */
@media (max-width: 768px) {
    #waButton.hidden-mobile {
        transform: translateY(100px);
        opacity: 0;
    }
}
</style>

<script>
// Optional: Hide WA button on mobile when scrolling down
let lastScrollTop = 0;
const waButton = document.getElementById('waButton');

window.addEventListener('scroll', function() {
    if (window.innerWidth <= 768) {
        let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            waButton.classList.add('hidden-mobile');
        } else {
            // Scrolling up
            waButton.classList.remove('hidden-mobile');
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
    }
}, false);

// Track WhatsApp button clicks (optional analytics)
waButton.addEventListener('click', function() {
    // Bisa ditambahkan tracking ke Google Analytics atau database
    console.log('WhatsApp button clicked');
    
    // Simpan ke localStorage untuk tracking
    let clicks = localStorage.getItem('wa_clicks') || 0;
    localStorage.setItem('wa_clicks', parseInt(clicks) + 1);
});
</script>