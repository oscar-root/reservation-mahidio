<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salle Mahidio | Excellence & Prestige</title>

    <!-- Google Fonts Premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0a0a0a; color: #e8e8e8; overflow-x: hidden; }
        .font-serif-luxury { font-family: 'Cormorant Garamond', serif; }

        .glass-nav {
            background: rgba(10, 10, 10, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(212, 175, 55, 0.15);
        }

        .custom-cursor {
            width: 30px; height: 30px; border: 1px solid #d4af37;
            border-radius: 50%; position: fixed; pointer-events: none; z-index: 9999;
            transition: transform 0.1s ease-out; transform: translate(-50%, -50%);
        }

        .btn-luxury {
            background: linear-gradient(135deg, #d4af37 0%, #f5e7a3 50%, #b8960c 100%);
            color: #0a0a0a; transition: all 0.4s ease;
        }
        .btn-luxury:hover { transform: scale(1.05); box-shadow: 0 0 30px rgba(212, 175, 55, 0.4); }

        .luxury-card {
            background: rgba(20, 20, 20, 0.6);
            border: 1px solid rgba(212, 175, 55, 0.1); border-radius: 40px;
            transition: all 0.5s ease;
        }
        .luxury-card:hover { border-color: #d4af37; background: rgba(30, 30, 30, 0.8); transform: translateY(-10px); }

        .gradient-text {
            background: linear-gradient(135deg, #d4af37 0%, #f5e7a3 50%, #d4af37 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }

        .section-reveal { opacity: 0; transform: translateY(40px); transition: 1s ease-out; }
        .section-reveal.revealed { opacity: 1; transform: translateY(0); }
        
        /* Footer Gradient Border */
        .footer-border { height: 2px; background: linear-gradient(90deg, transparent, #d4af37, transparent); }
    </style>
</head>
<body class="antialiased">

    <div class="custom-cursor hidden lg:block" id="cursor"></div>

    <!-- Navigation -->
    <nav class="fixed w-full z-50 glass-nav" id="mainNav">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 flex justify-between h-20 items-center">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#d4af37] to-[#b8960c] rounded-xl flex items-center justify-center">
                    <span class="text-black font-bold text-xl font-serif-luxury">M</span>
                </div>
                <span class="text-xl font-serif-luxury font-bold tracking-widest uppercase">Salle <span class="gradient-text">Mahidio</span></span>
            </div>
            
            <div class="hidden md:flex items-center space-x-12 text-[10px] font-bold tracking-[0.3em] uppercase">
                <a href="#accueil" class="hover:text-[#d4af37] transition">Accueil</a>
                <a href="#services" class="hover:text-[#d4af37] transition">Services</a>
                <a href="#apropos" class="hover:text-[#d4af37] transition">À propos</a>
            </div>

            <div class="flex items-center gap-6">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2 btn-luxury rounded-full font-bold text-[10px] uppercase tracking-widest">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline text-[10px] font-bold uppercase tracking-widest hover:text-[#d4af37]">Connexion</a>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 btn-luxury rounded-full font-bold text-[10px] uppercase tracking-widest shadow-lg">Réserver</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO SECTION CENTRÉE -->
    <section id="accueil" class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden text-center">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-black/60 z-10"></div>
            <img src="https://images.unsplash.com/photo-1519167758481-83f550bb49b3?auto=format&fit=crop&q=80" class="w-full h-full object-cover opacity-40" alt="Hero">
        </div>

        <div class="max-w-5xl mx-auto px-6 relative z-20 section-reveal">
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white/5 border border-[#d4af37]/20 mb-10">
                <span class="w-1.5 h-1.5 rounded-full bg-[#d4af37] animate-ping"></span>
                <span class="text-[9px] font-black uppercase tracking-[0.4em] text-[#f5e7a3]">L'excellence à Kamina</span>
            </div>
            <h1 class="text-6xl md:text-8xl lg:text-9xl font-serif-luxury font-bold leading-tight mb-10">
                L'Art de <span class="gradient-text italic">Célébrer</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-12 leading-relaxed font-light tracking-wide">
                Bienvenue au Lycée Mahidio. Un écrin de prestige où chaque détail est orchestré numériquement pour magnifier vos événements les plus précieux.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-12 py-5 btn-luxury rounded-2xl font-black text-xs uppercase tracking-[0.2em]" wire: navigate>Commencer l'expérience</a>
                <a href="#services" class="w-full sm:w-auto px-12 py-5 border border-[#d4af37]/30 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-white/5 transition">Nos Services</a>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section id="services" class="py-32 bg-black relative">
        <div class="max-w-7xl mx-auto px-6 text-center mb-24 section-reveal">
            <h2 class="text-4xl md:text-6xl font-serif-luxury font-bold">Services <span class="gradient-text italic">Premium</span></h2>
            <p class="text-gray-500 mt-4 uppercase tracking-[0.3em] text-[10px]">L'innovation au service du sacré</p>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid md:grid-cols-3 gap-10">
            <div class="luxury-card p-12 section-reveal">
                <div class="w-16 h-16 bg-[#d4af37]/10 rounded-2xl flex items-center justify-center mb-10 border border-[#d4af37]/20">
                    <i class="fas fa-calendar-check text-[#d4af37] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4 tracking-tight">Disponibilité Temps-Réel</h3>
                <p class="text-gray-400 text-sm leading-loose">Consultez et réservez instantanément via notre plateforme sécurisée. Zéro doublon, 100% de fiabilité.</p>
            </div>
            <div class="luxury-card p-12 section-reveal">
                <div class="w-16 h-16 bg-[#d4af37]/10 rounded-2xl flex items-center justify-center mb-10 border border-[#d4af37]/20">
                    <i class="fas fa-file-invoice-dollar text-[#d4af37] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4 tracking-tight">Transparence Totale</h3>
                <p class="text-gray-400 text-sm leading-loose">Tarification automatisée et génération de reçus numériques officiels pour chaque réservation.</p>
            </div>
            <div class="luxury-card p-12 section-reveal">
                <div class="w-16 h-16 bg-[#d4af37]/10 rounded-2xl flex items-center justify-center mb-10 border border-[#d4af37]/20">
                    <i class="fas fa-gem text-[#d4af37] text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4 tracking-tight">Cadre de Prestige</h3>
                <p class="text-gray-400 text-sm leading-loose">Un espace architectural moderne conçu pour sublimer vos mariages et cérémonies religieuses.</p>
            </div>
        </div>
    </section>

    <!-- SECTION À PROPOS -->
    <section id="apropos" class="py-32 bg-gradient-to-b from-black to-[#050505]">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 grid lg:grid-cols-2 gap-20 items-center">
            <div class="section-reveal">
                <h2 class="text-4xl md:text-6xl font-serif-luxury font-bold mb-8">Notre <span class="gradient-text italic">Héritage</span></h2>
                <p class="text-gray-400 leading-loose text-lg mb-8">
                    Située au sein de la congrégation religieuse du Lycée Mahidio à Kamina, notre salle de cérémonie est bien plus qu'un espace : c'est un engagement vers la modernité et la traçabilité.
                </p>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <p class="text-3xl font-bold gradient-text">500+</p>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 mt-2">Événements réussis</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold gradient-text">100%</p>
                        <p class="text-[10px] uppercase tracking-widest text-gray-500 mt-2">Digitalisé</p>
                    </div>
                </div>
            </div>
            <div class="rounded-[40px] overflow-hidden border border-[#d4af37]/20 shadow-2xl section-reveal">
                <img src="https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&q=80" class="w-full h-[500px] object-cover opacity-70" alt="About">
            </div>
        </div>
    </section>

    <!-- FOOTER PERSONNALISÉ -->
    <footer class="bg-black pt-24 pb-12">
        <div class="footer-border mb-20"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="grid md:grid-cols-4 gap-16 mb-20">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-gradient-to-r from-[#d4af37] to-[#b8960c] rounded-xl flex items-center justify-center">
                            <span class="text-black font-bold text-xl">M</span>
                        </div>
                        <span class="text-xl font-serif-luxury font-bold tracking-widest uppercase">Salle <span class="gradient-text">Mahidio</span></span>
                    </div>
                    <p class="text-gray-500 text-sm leading-relaxed max-w-sm">
                        La plateforme officielle de gestion et de suivi des réservations pour le Lycée Mahidio. Élégance, traçabilité et prestige à Kamina.
                    </p>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Navigation</h4>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li><a href="#accueil" class="hover:text-[#d4af37] transition">Accueil</a></li>
                        <li><a href="#services" class="hover:text-[#d4af37] transition">Nos Services</a></li>
                        <li><a href="#apropos" class="hover:text-[#d4af37] transition">À propos</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-[#d4af37] transition" wire:navigate>Espace Membre</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-white mb-8">Contact</h4>
                    <ul class="space-y-4 text-sm text-gray-500">
                        <li class="flex items-start gap-3"><i class="fas fa-map-marker-alt text-[#d4af37]"></i> Kamina, Haut-Lomami, RDC</li>
                        <li class="flex items-center gap-3"><i class="fas fa-envelope text-[#d4af37]"></i> contact@mahidio.cd</li>
                        <li class="flex items-center gap-3"><i class="fas fa-phone-alt text-[#d4af37]"></i> +243 000 000 000</li>
                    </ul>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-between items-center pt-12 border-t border-white/5 gap-6">
                <p class="text-[10px] uppercase tracking-widest text-gray-600">© 2026 Salle Mahidio. Tous droits réservés.</p>
                <div class="flex gap-6">
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#d4af37] hover:text-black transition text-gray-500"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#d4af37] hover:text-black transition text-gray-500"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script>
        const cursor = document.getElementById('cursor');
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
        });

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('revealed');
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.section-reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>