<style>
    .loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: white;
        z-index: 9999;
        opacity: 1;
        visibility: visible;
        transition: opacity 1.5s ease, visibility 1.5s ease;
        pointer-events: none;
    }

    .loading-overlay.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .logo-spinner {
        width: 120px;
        height: 120px;
        animation:
            bounce-in 2s cubic-bezier(0.68, -0.55, 0.27, 1.55) forwards,
            spin 2s linear infinite;
        transform-origin: center;
    }

    @keyframes bounce-in {
        0% {
            opacity: 0;
            transform: scale(0.1) translateY(100px) rotate(0deg);
        }

        50% {
            opacity: 1;
            transform: scale(1.2) translateY(-20px) rotate(180deg);
        }

        70% {
            transform: scale(0.9) translateY(10px) rotate(270deg);
        }

        85% {
            transform: scale(1.05) translateY(-5px) rotate(315deg);
        }

        100% {
            opacity: 1;
            transform: scale(1) translateY(0) rotate(360deg);
        }
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg) scale(1);
        }

        50% {
            transform: rotate(180deg) scale(1.05);
        }

        100% {
            transform: rotate(360deg) scale(1);
        }
    }
</style>

<body>
    <div id="loading-overlay" class="loading-overlay">
        <img src="images/silver.PNG" alt="Loading" class="logo-spinner">
    </div>
    <script>
        window.addEventListener('load', function() {
            const loadingOverlay = document.getElementById('loading-overlay');

            // Sembunyikan overlay setelah 2 detik halaman selesai dimuat
            setTimeout(() => {
                loadingOverlay.classList.add('hidden');
            }, 2000);

            // Setelah animasi fade-out selesai, sembunyikan display-nya
            loadingOverlay.addEventListener('transitionend', function() {
                if (loadingOverlay.classList.contains('hidden')) {
                    loadingOverlay.style.display = 'none';
                }
            });

            // Tampilkan overlay saat submit form
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    loadingOverlay.style.display = 'flex';
                    loadingOverlay.classList.remove('hidden');
                });
            });

            // Tampilkan overlay sebelum reload atau pindah halaman
            window.addEventListener('beforeunload', function() {
                loadingOverlay.style.display = 'flex';
                loadingOverlay.classList.remove('hidden');
            });
        });
    </script>
</body>
