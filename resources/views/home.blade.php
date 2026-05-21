@use('Illuminate\Support\Facades\Storage')

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/jpg" href="{{ asset('images/logo_public.png') }}">
    <title>PutraDev.</title>
    @vite('resources/css/app.css')

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap"
        rel="stylesheet"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #0d0d0d;
            color: #f5f5f5;
            overflow-x: hidden;
        }

        .bg-glow-tl {
            position: fixed; top: -220px; left: -220px;
            width: 620px; height: 620px; border-radius: 50%;
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .bg-glow-br {
            position: fixed; bottom: -250px; right: -250px;
            width: 720px; height: 720px; border-radius: 50%;
            background: radial-gradient(circle, rgba(52,211,153,0.08) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .bg-glow-center {
            position: fixed; top: 40%; left: 55%;
            width: 500px; height: 500px; border-radius: 50%;
            transform: translate(-50%, -50%);
            background: radial-gradient(circle, rgba(16,185,129,0.05) 0%, transparent 65%);
            pointer-events: none; z-index: 0;
        }

        .glass-nav {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.07);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        }

        .nav-link {
            position: relative; color: rgba(255,255,255,0.55);
            transition: color 0.3s ease; font-size: 0.8125rem; letter-spacing: 0.01em;
        }
        .nav-link::after {
            content: ''; position: absolute; bottom: -4px; left: 50%;
            transform: translateX(-50%); width: 0; height: 1.5px;
            background: linear-gradient(90deg, #10b981, #34d399);
            border-radius: 2px; transition: width 0.35s ease;
        }
        .nav-link:hover { color: #fff; }
        .nav-link:hover::after { width: 100%; }

        .btn-login {
            border: 1px solid rgba(16,185,129,0.45); color: #34d399;
            background: rgba(16,185,129,0.06); transition: all 0.3s ease;
        }
        .btn-login:hover {
            border-color: rgba(16,185,129,0.7); background: rgba(16,185,129,0.12);
            box-shadow: 0 0 20px rgba(16,185,129,0.15);
        }

        .btn-cta {
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            color: #0d0d0d;
            box-shadow: 0 0 20px rgba(16,185,129,0.35), 0 0 50px rgba(16,185,129,0.12);
            transition: box-shadow 0.3s ease, transform 0.25s ease;
        }
        .btn-cta:hover {
            box-shadow: 0 0 30px rgba(16,185,129,0.55), 0 0 70px rgba(16,185,129,0.2);
            transform: translateY(-2px);
        }

        .gradient-text {
            background: linear-gradient(135deg, #34d399 0%, #10b981 50%, #6ee7b7 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .danger-text {
            background: linear-gradient(135deg, #d33434 0%, #b91010 50%, #e76e6e 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }

        .dot-pulse {
            width: 9px; height: 9px; background: #34d399; border-radius: 50%;
            box-shadow: 0 0 6px #34d399, 0 0 16px rgba(52,211,153,0.45);
            animation: pulse-dot 2.4s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 6px #34d399, 0 0 16px rgba(52,211,153,0.35); transform: scale(1); }
            50%       { box-shadow: 0 0 12px #34d399, 0 0 30px rgba(52,211,153,0.6); transform: scale(1.15); }
        }

        .fade-up {
            opacity: 0; transform: translateY(30px);
            animation: fadeUp 0.95s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .delay-1 { animation-delay: 0.12s; }
        .delay-2 { animation-delay: 0.26s; }
        .delay-3 { animation-delay: 0.42s; }
        .delay-4 { animation-delay: 0.58s; }
        .delay-5 { animation-delay: 0.76s; }
        .delay-6 { animation-delay: 0.92s; }
        @keyframes fadeUp { to { opacity: 1; transform: translateY(0); } }

        .slash-deco {
            position: absolute; right: 0; top: 0; width: 38%; height: 100%;
            overflow: hidden; pointer-events: none; z-index: 1;
        }
        .slash-deco::before, .slash-deco::after {
            content: ''; position: absolute; width: 2px; height: 140%;
            transform: rotate(20deg); top: -20%; border-radius: 2px;
        }
        .slash-deco::before { right: 18%; background: linear-gradient(180deg, transparent, rgba(16,185,129,0.18), transparent); }
        .slash-deco::after  { right: 10%; background: linear-gradient(180deg, transparent, rgba(52,211,153,0.10), transparent); }

        .scroll-indicator { animation: bounce 2.4s ease-in-out infinite; }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); opacity: 0.4; }
            50%       { transform: translateY(8px); opacity: 0.8; }
        }

        .stat-divider { width: 1px; height: 40px; background: rgba(255,255,255,0.08); }

        .mobile-menu {
            max-height: 0; overflow: hidden;
            transition: max-height 0.45s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s ease, padding 0.35s ease;
            opacity: 0; padding-top: 0; padding-bottom: 0;
        }
        .mobile-menu.open { max-height: 320px; opacity: 1; padding-top: 1rem; padding-bottom: 1rem; }
        .mobile-menu a {
            display: block; padding: 0.6rem 0; color: rgba(255,255,255,0.55);
            font-size: 0.9rem; font-weight: 500; transition: color 0.25s ease, padding-left 0.25s ease;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .mobile-menu a:last-child { border-bottom: none; }
        .mobile-menu a:hover { color: #34d399; padding-left: 6px; }

        .hamburger-line { transition: all 0.3s ease; transform-origin: center; }
        .hamburger-active .hamburger-line:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .hamburger-active .hamburger-line:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .hamburger-active .hamburger-line:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .noise-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.025;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            background-repeat: repeat; background-size: 180px;
        }

        /* ── Marquee ── */
        .marquee-mask {
            -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
            overflow: hidden;
        }
        .marquee-track {
            display: flex;
            animation: marquee-scroll 150s linear infinite;
            width: max-content;
        }
        @keyframes marquee-scroll {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* ── Section heading centered ── */
        .section-header {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-eyebrow {
            font-size: 0.68rem; font-weight: 600;
            letter-spacing: 0.25em; text-transform: uppercase;
            color: rgba(255,255,255,0.3); margin-bottom: 0.75rem;
        }
        .section-title {
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 800; letter-spacing: -0.02em; color: #fff;
            display: flex; align-items: center; justify-content: center; gap: 0.75rem;
        }

        /* marquee item logo sizing */
        .marquee-item img { width: 20px; height: 20px; object-fit: contain; }

                /* ── Theme Variables ── */
        :root {
            --bg: #0d0d0d;
            --text: #f5f5f5;
            --text-muted: rgba(245,245,245,0.45);
            --border: rgba(255,255,255,0.07);
            --glass: rgba(255,255,255,0.04);
            --card: rgba(255,255,255,0.02);
            --glow-primary: rgba(16,185,129,0.12);
            --glow-secondary: rgba(52,211,153,0.08);
        }
        
        html.light {
            --bg: #f0f0eb;
            --text: #0d0d0d;
            --text-muted: rgba(13,13,13,0.5);
            --border: rgba(0,0,0,0.08);
            --glass: rgba(255,255,255,0.65);
            --card: rgba(255,255,255,0.5);
            --glow-primary: rgba(16,185,129,0.08);
            --glow-secondary: rgba(52,211,153,0.05);
        }
        
        /* Override body */
        body {
            background-color: var(--bg);
            color: var(--text);
            transition: background-color 0.4s ease, color 0.4s ease;
        }
        
        /* Override glass nav */
        .glass-nav {
            background: var(--glass);
            border-color: var(--border);
        }
        
        /* Override nav-link warna di light mode */
        html.light .nav-link { color: rgba(0,0,0,0.5); }
        html.light .nav-link:hover { color: #0d0d0d; }
        
        /* Override cards/sections di light mode */
        html.light .bg-glow-tl,
        html.light .bg-glow-br,
        html.light .bg-glow-center {
            opacity: 0.5;
        }
        
        html.light .noise-overlay { opacity: 0.015; }
        
        /* Card backgrounds */
        html.light [class*="bg-white/[0.02]"] { background: rgba(255,255,255,0.6) !important; }
        html.light [class*="bg-white/[0.03]"] { background: rgba(255,255,255,0.7) !important; }
        html.light [class*="bg-\[#111111\]"]  { background: #ffffff !important; }
        
        /* Borders */
        html.light [class*="border-white/[0.05]"],
        html.light [class*="border-white/[0.06]"],
        html.light [class*="border-white/[0.07]"],
        html.light [class*="border-white/[0.08]"] {
            border-color: rgba(0,0,0,0.08) !important;
        }
        
        /* Text muted */
        html.light [class*="text-white/40"],
        html.light [class*="text-white/45"],
        html.light [class*="text-white/50"],
        html.light [class*="text-white/55"] {
            color: rgba(0,0,0,0.5) !important;
        }
        html.light [class*="text-white/25"],
        html.light [class*="text-white/30"],
        html.light [class*="text-white/35"] {
            color: rgba(0,0,0,0.35) !important;
        }
        html.light [class*="text-white/60"],
        html.light [class*="text-white/70"] {
            color: rgba(0,0,0,0.65) !important;
        }
        html.light .text-white,
        html.light [class*="text-white "] { color: #0d0d0d !important; }
        
        /* Input fields */
        html.light input, html.light textarea {
            background: rgba(0,0,0,0.03) !important;
            border-color: rgba(0,0,0,0.1) !important;
            color: #0d0d0d !important;
        }
        html.light input::placeholder,
        html.light textarea::placeholder { color: rgba(0,0,0,0.3) !important; }
        
        /* Mobile menu */
        html.light .mobile-menu a { color: rgba(0,0,0,0.5); }
        html.light .mobile-menu a:hover { color: #10b981; }
        
        /* Hamburger lines */
        html.light .hamburger-line { background: rgba(0,0,0,0.6) !important; }
        
        /* Section eyebrow */
        html.light .section-eyebrow { color: rgba(0,0,0,0.3); }
        html.light .section-title { color: #0d0d0d; }
        
        /* Footer border */
        html.light footer { border-color: rgba(0,0,0,0.08); }
        
        /* Stat divider */
        html.light .stat-divider { background: rgba(0,0,0,0.1); }
        
        /* Scroll indicator */
        html.light .scroll-indicator svg { color: rgba(0,0,0,0.2) !important; }
        
        /* Login button */
        html.light .btn-login { background: rgba(16,185,129,0.08); }

        html.light [class*="text-white/15"] {
        color: rgba(0,0,0,0.25) !important;
        }   
        .scroll-indicator {
        animation: bounce 2.4s ease-in-out infinite;
        transition: opacity 0.4s ease;
        }
        </style>
    </head>

<body class="relative min-h-screen antialiased">

    <div class="noise-overlay"></div>
    <div class="bg-glow-tl"></div>
    <div class="bg-glow-br"></div>
    <div class="bg-glow-center"></div>

    {{-- ═══════════ NAVBAR ═══════════ --}}
    <header class="fixed top-0 inset-x-0 z-50 flex flex-col items-center pt-5 px-4">
    <nav class="glass-nav rounded-full px-5 md:px-6 py-3 flex items-center justify-between w-full max-w-3xl">

        {{-- Brand --}}
        <a href="#" class="text-white font-extrabold text-lg tracking-tight whitespace-nowrap select-none">
            Putra<span class="gradient-text">Dev.</span>
        </a>

        {{-- Desktop links --}}
        <ul class="hidden md:flex items-center gap-6 lg:gap-7 font-medium">
            <li><a href="#" class="nav-link">Home</a></li>
            <li><a href="#about" class="nav-link">About</a></li>
            <li><a href="#skills" class="nav-link">Skills</a></li>
            <li><a href="#project" class="nav-link">Project</a></li>
            <li><a href="#contact" class="nav-link">Contact</a></li>
        </ul>

        {{-- Right: theme toggle + hamburger --}}
        <div class="flex items-center gap-2">

            {{-- Theme Toggle --}}
            <button id="themeToggle"
                class="flex items-center justify-center w-9 h-9 rounded-full border border-white/10 hover:border-emerald-500/40 bg-white/[0.03] hover:bg-emerald-500/[0.06] transition-all duration-300"
                aria-label="Toggle theme">
                <svg id="iconSun" class="w-4 h-4 text-white/50 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                </svg>
                <svg id="iconMoon" class="w-4 h-4 text-white/50 hidden transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
            </button>

            {{-- Hamburger (mobile only) --}}
            <button id="menuToggle"
                class="md:hidden flex flex-col items-center justify-center gap-[5px] w-9 h-9 rounded-full hover:bg-white/5 transition-colors"
                aria-label="Toggle menu">
                <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
                <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
                <span class="hamburger-line block w-[18px] h-[1.5px] bg-white/70 rounded-full"></span>
            </button>
        </div>
    </nav>

    {{-- Mobile dropdown --}}
    <div id="mobileMenu" class="mobile-menu glass-nav rounded-2xl mt-2 w-full max-w-3xl px-6 md:hidden">
        <a href="#">Home</a>
        <a href="#about">About</a>
        <a href="#skills">Skills</a>
        <a href="#project">Project</a>
        <a href="#contact">Contact</a>
    </div>
</header>

    {{-- ═══════════ HERO ═══════════ --}}
    <main class="relative z-10 min-h-screen flex flex-col justify-center px-6 md:px-16 lg:px-28 pt-32 pb-24">
        <div class="slash-deco hidden lg:block"></div>

        <div class="fade-up delay-1 inline-flex items-center gap-2.5 mb-8 rounded-full px-4 py-2 w-fit">
            <span class="dot-pulse"></span>
            <span class="text-xs font-semibold tracking-[0.18em] uppercase text-white/50">Available for work</span>
        </div>

        <h1 class="fade-up delay-2 font-extrabold leading-[1.06] tracking-tight max-w-4xl">
            <span class="block text-[clamp(2.4rem,6.5vw,3.2rem)] text-white">Building sustainable</span>
            <span class="block text-[clamp(2.4rem,6.5vw,3.2rem)] text-white">and</span>
            <span class="block text-[clamp(2.4rem,6.5vw,3.2rem)]">
                <span class="gradient-text">impactful digital</span>
                <span class="text-white"> solutions.</span>
            </span>
        </h1>

                {{-- ── Sub-headline (Typewriter Loop) ── --}}
        <div class="fade-up delay-3 mt-7 max-w-xl"
             x-data="{
                 sentences: [
                     'Informatics Engineering student and Fullstack Developer focused on building clean, performant, and user-centered web applications.',
                     'I turn complex problems into elegant digital experiences that make a real impact.',
                     'Passionate about crafting scalable backend systems with Laravel and modern frontend interfaces.',
                     'Currently exploring the intersection of clean code, great design, and seamless user experience.'
                 ],
                 sentenceIndex: 0,
                 charIndex: 0,
                 isDeleting: false,
                 text: '',
                 typeSpeed: 20,
                 deleteSpeed: 10,
                 pauseEnd: 2500,
                 pauseStart: 500,

                 init() {
                     this.tick();
                 },

                 tick() {
                     let current = this.sentences[this.sentenceIndex];

                     if (this.isDeleting) {
                         this.text = current.substring(0, this.charIndex - 1);
                         this.charIndex--;
                     } else {
                         this.text = current.substring(0, this.charIndex + 1);
                         this.charIndex++;
                     }

                     let delay = this.isDeleting ? this.deleteSpeed : this.typeSpeed;

                     if (!this.isDeleting && this.charIndex === current.length) {
                         delay = this.pauseEnd;
                         this.isDeleting = true;
                     } else if (this.isDeleting && this.charIndex === 0) {
                         this.isDeleting = false;
                         this.sentenceIndex = (this.sentenceIndex + 1) % this.sentences.length;
                         delay = this.pauseStart;
                     }

                     setTimeout(() => this.tick(), delay);
                 }
             }">

            <p class="text-[clamp(0.88rem,1.5vw,1.05rem)] txt-muted leading-relaxed font-light min-h-[4.5rem]">
                <span x-text="text"></span>
                <span class="inline-block w-[2px] h-[1em] bg-emerald-400/70 align-middle ml-0.5 animate-blink"></span>
            </p>
        </div>

        <div class="fade-up delay-4 flex flex-wrap items-center gap-4 mt-10">
            <a href="#project" class="btn-cta font-bold px-7 py-3.5 rounded-full text-sm tracking-wide">View My Work</a>
            <a href="#about" class="group flex items-center gap-2.5 text-sm font-medium text-white/50 hover:text-white transition-colors duration-300">
                <span class="flex items-center justify-center w-9 h-9 rounded-full border border-white/10 group-hover:border-white/25 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
                Learn more about me
            </a>
        </div>

        {{-- ═══════════ MARQUEE TECH STACK ═══════════ --}}
        <div class="fade-up delay-5 w-full mt-16 py-6 relative">
    <div class="marquee-mask">
        <div class="marquee-track">
            @php
            $marqueeSkills = [
                // Web
                ['name' => 'HTML',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg'],
                ['name' => 'CSS',            'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg'],
                ['name' => 'JavaScript',     'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg'],
                ['name' => 'TypeScript',     'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/typescript/typescript-original.svg'],
                ['name' => 'PHP',            'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg'],
                ['name' => 'Laravel',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg'],
                ['name' => 'Bootstrap',      'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg'],
                ['name' => 'Tailwind',       'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg'],
                ['name' => 'React',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/react/react-original.svg'],
                ['name' => 'Vue.js',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vuejs/vuejs-original.svg'],
                ['name' => 'Angular',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/angularjs/angularjs-original.svg'],
                ['name' => 'Svelte',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/svelte/svelte-original.svg'],
                ['name' => 'Next.js',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nextjs/nextjs-original.svg'],
                ['name' => 'Nuxt.js',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nuxtjs/nuxtjs-original.svg'],

                // Backend & General
                ['name' => 'Python',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/python/python-original.svg'],
                ['name' => 'Java',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/java/java-original.svg'],
                ['name' => 'C',              'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/c/c-original.svg'],
                ['name' => 'C++',            'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cplusplus/cplusplus-original.svg'],
                ['name' => 'C#',             'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/csharp/csharp-original.svg'],
                ['name' => 'Go',             'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/go/go-original-wordmark.svg'],
                ['name' => 'Rust',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/rust/rust-original.svg'],
                ['name' => 'Ruby',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/ruby/ruby-original.svg'],
                ['name' => 'Swift',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/swift/swift-original.svg'],
                ['name' => 'Kotlin',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kotlin/kotlin-original.svg'],
                ['name' => 'Dart',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/dart/dart-original.svg'],
                ['name' => 'Scala',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/scala/scala-original.svg'],
                ['name' => 'Perl',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/perl/perl-original.svg'],
                ['name' => 'Lua',            'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/lua/lua-original.svg'],
                ['name' => 'R',              'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/r/r-original.svg'],
                ['name' => 'MATLAB',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/matlab/matlab-original.svg'],
                ['name' => 'Haskell',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/haskell/haskell-original.svg'],
                ['name' => 'Elixir',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/elixir/elixir-original.svg'],
                ['name' => 'Erlang',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/erlang/erlang-original.svg'],
                ['name' => 'Clojure',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/clojure/clojure-original.svg'],
                ['name' => 'F#',             'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fsharp/fsharp-original.svg'],
                ['name' => 'OCaml',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/ocaml/ocaml-original.svg'],
                ['name' => 'Julia',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/julia/julia-original.svg'],
                ['name' => 'Groovy',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/groovy/groovy-original.svg'],

                // Mobile
                ['name' => 'Flutter',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/flutter/flutter-original.svg'],
                ['name' => 'Objective-C',    'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/objectivec/objectivec-plain.svg'],

                // Runtime & Tools
                ['name' => 'Node.js',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg'],
                ['name' => 'Deno',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/denojs/denojs-original.svg'],
                ['name' => 'GraphQL',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/graphql/graphql-plain.svg'],

                // Database & Query
                ['name' => 'MySQL',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg'],
                ['name' => 'PostgreSQL',     'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/postgresql/postgresql-original.svg'],
                ['name' => 'MongoDB',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mongodb/mongodb-original.svg'],
                ['name' => 'Redis',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/redis/redis-original.svg'],
                ['name' => 'SQLite',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/sqlite/sqlite-original.svg'],

                // Scripting & Shell
                ['name' => 'Bash',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bash/bash-original.svg'],
                ['name' => 'PowerShell',     'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/powershell/powershell-original.svg'],

                // Legacy & Others
                ['name' => 'COBOL',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/cobol/cobol-original.svg'],
                ['name' => 'Fortran',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/fortran/fortran-original.svg'],

                // Config & Markup
                ['name' => 'Terraform',      'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/terraform/terraform-original.svg'],
                ['name' => 'YAML',           'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/yaml/yaml-original.svg'],

                // Tools
                ['name' => 'Figma',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg'],
                ['name' => 'VS Code',        'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg'],
                ['name' => 'Git',            'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/git/git-original.svg'],
                ['name' => 'Docker',         'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/docker/docker-original.svg'],
                ['name' => 'Linux',          'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/linux/linux-original.svg'],
                ['name' => 'Kubernetes',     'img' => 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/kubernetes/kubernetes-plain.svg'],
            ];
            @endphp

            {{-- Set 1 --}}
            <div class="flex items-center gap-4 shrink-0 pr-4">
                @foreach($marqueeSkills as $skill)
                <div class="marquee-item flex items-center gap-3 px-5 py-3 border border-white/[0.06] bg-white/[0.02] rounded-full group cursor-default transition-all hover:border-emerald-500/30 hover:bg-emerald-500/5">
                    <img src="{{ $skill['img'] }}" alt="{{ $skill['name'] }}" class="w-5 h-5" loading="lazy" />
                    <span class="text-sm font-medium text-white/60 group-hover:text-emerald-400 transition-colors whitespace-nowrap">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Set 2 (duplicate for seamless loop) --}}
            <div class="flex items-center gap-4 shrink-0 pr-4">
                @foreach($marqueeSkills as $skill)
                <div class="marquee-item flex items-center gap-3 px-5 py-3 border border-white/[0.06] bg-white/[0.02] rounded-full group cursor-default transition-all hover:border-emerald-500/30 hover:bg-emerald-500/5">
                    <img src="{{ $skill['img'] }}" alt="{{ $skill['name'] }}" class="w-5 h-5" loading="lazy" />
                    <span class="text-sm font-medium text-white/60 group-hover:text-emerald-400 transition-colors whitespace-nowrap">{{ $skill['name'] }}</span>
                </div>
                @endforeach
            </div>
            </div>
        </div>
    </div>

        {{-- ═══════════ ABOUT CARD ═══════════ --}}
        <section id="about"></section>
    <div class="section-header mt-22">
            <p class="section-eyebrow">About me</p>
            <h2 class="section-title">
                Storyteller & Developer
            </h2>
        </div>

        <div class="fade-up delay-6 w-full  p-8 md:p-12 rounded-3xl border border-white/[0.06] bg-white/[0.02] relative overflow-hidden">
            
            <div class="absolute -top-1/2 -left-1/4 w-[600px] h-[600px] bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row-reverse items-center gap-10 md:gap-16">
                <div class="w-full md:w-2/5 relative group">
                    <div class="absolute inset-0 bg-emerald-500/20 blur-2xl rounded-2xl group-hover:bg-emerald-500/30 transition-colors duration-500 scale-90"></div>
                    <img src="{{ asset('images/portofolio.webp') }}" alt="Muhammad Putra"
                         class="relative w-full max-w-xs mx-auto md:max-w-none h-auto rounded-2xl border border-white/10 object-cover shadow-2xl">
                </div>
                <div class="w-full md:w-3/5">
                    <h2 class="text-4xl md:text-3xl font-extrabold tracking-tight mb-10">
                        Haloo, I'm <span class="gradient-text">Muhammad Putra</span>.
                    </h2>
                    <p class="text-white/50 leading-relaxed text-base mb-7">
                        As an Informatics Engineering Student, I bridge the gap between robust backend logic and captivating frontend experiences. I specialize in building clean, performant, and user-centered web applications. I believe that great code tells a story—solving real-world problems while delivering elegant digital experiences.
                    </p>
                     <p class="text-white/50 leading-relaxed text-base mb-7">
                        I believe that great code tells a story—solving real-world problems while delivering elegant digital experiences.
                    </p>
                    <p class="text-white/50 leading-relaxed text-base mb-7">
                        Currently, my go-to tech stack includes modern frameworks like React and Next.js for crafting intuitive user interfaces, backed by Node.js or Laravel to build secure, scalable backend architectures.
                    </p>

                    <div class="flex flex-wrap items-center gap-6 sm:gap-8">
                        <div>
                            <p class="text-3xl font-extrabold text-white"><span class="gradient-text">Full</span>Stack</p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">Developer</p>
                        </div>
                        <div class="stat-divider hidden sm:block"></div>
                        <div>
                            <p class="text-3xl font-extrabold text-white">Indo<span class="danger-text">nesia</span></p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">Banjarmasin</p>
                        </div>
                        <div class="stat-divider hidden sm:block"></div>
                        <div>
                            <p class="text-3xl font-extrabold text-white">2<span class="gradient-text">+</span></p>
                            <p class="text-[0.68rem] text-white/35 mt-1.5 tracking-[0.14em] uppercase font-medium">years experience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ═══════════ skills ═══════════ --}}
    <section id="skills" class="relative z-10 py-22 px-6 md:px-16 lg:px-28" x-data="techStackFilter()">

        <div class="section-header mb-12">
            <p class="section-eyebrow">What I Use</p>
            <h2 class="section-title">
                Tech Stack
            </h2>
        </div>

        <div class="flex flex-wrap justify-center items-center gap-3 mb-10">
            <template x-for="tab in tabs" :key="tab.key">
                <button @click="activeTab = tab.key"
                    :class="activeTab === tab.key ? 'bg-emerald-500 text-[#0d0d0d] border-emerald-500 font-semibold shadow-[0_0_20px_rgba(16,185,129,0.25)]' : 'bg-transparent text-white/40 border-white/10 hover:border-white/20 hover:text-white/60'"
                    class="px-5 py-2 rounded-full text-sm border transition-all duration-300 cursor-pointer"
                    x-text="tab.label"></button>
            </template>
        </div>

        <div class="flex flex-wrap gap-3">
            <template x-for="(skill, i) in filtered" :key="skill.name">
                <div x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     :style="'transition-delay:' + (i * 40) + 'ms'"
                     class="flex items-center justify-between w-full sm:w-[calc(50%-6px)] lg:w-[calc(33.333%-8px)] px-5 py-4 bg-white/[0.03] border border-white/[0.08] rounded-2xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03] hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(16,185,129,0.08)] cursor-default group">
                    <div class="flex items-center gap-3.5">
                        <img :src="skill.img" :alt="skill.name" class="w-7 h-7" loading="lazy" />
                        <span class="text-sm font-semibold text-white/70 group-hover:text-white transition-colors duration-300" x-text="skill.name"></span>
                    </div>
                    <span x-show="skill.level === 'Advanced'" class="text-[0.65rem] font-semibold tracking-wider uppercase px-2.5 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/15" x-text="skill.level"></span>
                    <span x-show="skill.level !== 'Advanced'" x-cloak class="text-[0.65rem] font-semibold tracking-wider uppercase px-2.5 py-1 rounded-full bg-white/[0.05] text-white/40 border border-white/[0.08]" x-text="skill.level"></span>
                </div>
            </template>
        </div>
    </section>

    {{-- ═══════════ PROJECTS ═══════════ --}}
<section id="project" class="relative z-10 py-22 px-6 md:px-16 lg:px-28">

    <div class="section-header mb-14">
        <p class="section-eyebrow">Selected Works</p>
        <h2 class="section-title">Featured <span class="gradient-text">Projects</span></h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($projects as $project)
        <article class="group rounded-3xl bg-[#111111] border border-white/[0.05] overflow-hidden transition-all duration-500 ease-out hover:border-emerald-500/40 hover:shadow-[0_0_40px_rgba(16,185,129,0.10)]">
            <div class="overflow-hidden aspect-[16/10]">
                <img src="{{ $project->image ? Storage::url($project->image) : 'https://picsum.photos/seed/'.$project->id.'/800/500' }}"
                     alt="{{ $project->title }}"
                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105" loading="lazy" />
            </div>
            <div class="p-6 md:p-7">
                <h3 class="text-lg font-bold text-white tracking-tight mb-2 group-hover:text-emerald-300 transition-colors duration-300">
                    {{ $project->title }}
                </h3>
                <p class="text-sm text-white/40 leading-relaxed mb-5 font-light">
                    {{ $project->description }}
                </p>

                {{-- Tags --}}
                @if($project->tags)
                <div class="flex flex-wrap items-center gap-2 mb-5">
                    @foreach($project->tags as $tag)
                    <span class="text-[0.7rem] font-medium tracking-wide uppercase text-white/25 bg-white/[0.04] border border-white/[0.06] rounded-full px-3 py-1">
                        {{ $tag }}
                    </span>
                    @endforeach
                </div>
                @endif

                {{-- Tombol --}}
                <div class="flex items-center gap-4">
                    @if($project->visit_url)
                    <a href="{{ $project->visit_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition-colors duration-300 group/link">
                        <span>Visit Project</span>
                        <svg class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M7 17L17 7M17 7H7M17 7v10"/>
                        </svg>
                    </a>
                    @endif

                    @if($project->github_url)
                    <a href="{{ $project->github_url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-white/40 hover:text-white transition-colors duration-300">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/>
                        </svg>
                        GitHub
                    </a>
                    @endif
                </div>
            </div>
        </article>

        @empty
        <div class="col-span-2 flex flex-col items-center justify-center py-20 text-center">
            <p class="text-sm txt-muted font-light tracking-wide text-center">Belum ada project yang ditambahkan.</p>   
        </div>
        @endforelse
    </div>

</section>

        {{-- ═══════════ CONTACT ═══════════ --}}
        <section id="contact" class="relative z-10 py-22 px-6 md:px-16 lg:px-28">

            <div class="section-header mb-16">
                <p class="section-eyebrow">Contact</p>
                <h2 class="section-title">
                    Let's Talk
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-20">
                <div class="lg:col-span-2 flex flex-col justify-between">
                    <div>
                        <h3 class="text-[clamp(1.5rem,3.5vw,2.4rem)] font-extrabold tracking-tight text-white leading-tight mb-6">
                            Let's Work<br/>Together
                        </h3>
                        <p class="text-sm text-white/40 leading-relaxed font-light mb-10 max-w-sm">
                            Saya selalu terbuka untuk diskusi proyek baru, ide kreatif, atau kesempatan kolaborasi.
                        </p>
                    </div>
                    <div class="flex flex-col gap-3">
                        <a href="mailto:muhammadputra.dev@gmail.com" class="group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/></svg>
                            </span>
                            <div>
                                <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">Email</p>
                                <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">muhammadputra.dev@gmail.com</p>
                            </div>
                        </a>
                        <a href="https://maps.google.com/?q=Kalimantan+Selatan" target="_blank" rel="noopener" class="group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                            </span>
                            <div>
                                <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">Location</p>
                                <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">Kalimantan Selatan, Indonesia</p>
                            </div>
                        </a>
                        <a href="https://wa.me/6282250097049" target="_blank" rel="noopener" class="group flex items-center gap-3.5 px-5 py-3.5 bg-white/[0.03] border border-white/[0.08] rounded-xl transition-all duration-300 hover:border-emerald-500/40 hover:bg-emerald-500/[0.03]">
                            <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-white/[0.04] border border-white/[0.06] group-hover:bg-emerald-500/10 group-hover:border-emerald-500/20 transition-all duration-300">
                                <svg class="w-4 h-4 text-white/40 group-hover:text-emerald-400 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21l1.65-3.8a9 9 0 1 1 3.4 2.9L3 21"/><path d="M9 10a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0v1zm4 0a.5.5 0 0 0 1 0V9a.5.5 0 0 0-1 0v1z"/></svg>
                            </span>
                            <div>
                                <p class="text-[0.65rem] text-white/25 uppercase tracking-wider font-medium mb-0.5">WhatsApp</p>
                                <p class="text-sm text-white/60 group-hover:text-white/90 transition-colors duration-300 font-medium">+62 822 5009 7049</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-3">
                    <form x-data="contactForm()" @submit.prevent="submitForm()"
                        class="p-6 sm:p-8 bg-white/[0.02] border border-white/[0.06] rounded-3xl space-y-5">
                        <div>
                            <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Name</label>
                            <input type="text" x-model="form.name" placeholder="Nama lengkap kamu" required :disabled="isSubmitting"
                                class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 disabled:opacity-40 disabled:cursor-not-allowed" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Email</label>
                            <input type="email" x-model="form.email" placeholder="email@gmail.com" required :disabled="isSubmitting"
                                class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 disabled:opacity-40 disabled:cursor-not-allowed" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-white/40 uppercase tracking-wider mb-2.5">Message</label>
                            <textarea x-model="form.message" rows="5" placeholder="Ceritakan proyek atau ide kamu..." required :disabled="isSubmitting"
                                class="w-full bg-white/[0.02] border border-white/[0.06] rounded-xl px-5 py-4 text-white text-sm placeholder-white/25 outline-none transition-all duration-300 focus:bg-white/[0.04] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/50 resize-none disabled:opacity-40 disabled:cursor-not-allowed"></textarea>
                        </div>
                        <button type="submit" :disabled="isSubmitting"
                            class="w-full bg-emerald-500 text-[#0d0d0d] font-bold text-sm tracking-wide rounded-xl px-8 py-4 transition-all duration-300 flex justify-center items-center gap-2.5 cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed hover:bg-emerald-400 hover:shadow-[0_0_20px_rgba(16,185,129,0.3)] disabled:hover:shadow-none">
                            <svg x-show="isSubmitting" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/></svg>
                            <span x-show="!isSubmitting">Kirim Pesan</span>
                            <span x-show="isSubmitting" x-cloak>Sending...</span>
                        </button>
                        <p class="text-center text-[0.7rem] text-white/20 pt-1">Biasanya saya balas dalam 24 jam.</p>
                    </form>
                </div>
            </div>
        </section>

    {{-- ═══════════ FOOTER ═══════════ --}}
    <footer class="relative z-10 border-t border-white/[0.05] py-10 px-6 md:px-16 lg:px-28">
        <div class="flex flex-col items-center gap-6 md:flex-row md:justify-between">
            <div class="flex flex-col items-center md:items-start gap-1">
                <p class="text-xs text-white/30 font-light">&copy; 2026 Putra<span class="text-emerald-500/60">Dev</span>. All rights reserved.</p>
                <p class="text-[0.65rem] text-white/15 font-light flex items-center gap-1.5">
                    Handcrafted by Muhammmad Putra
                    <svg class="w-2.5 h-2.5 text-emerald-500/50" viewBox="0 0 12 12" fill="currentColor"><path d="M6 1.5C4 1.5 2.5 3 2.5 5c0 3.5 3.5 5.5 3.5 5.5s3.5-2 3.5-5.5C9.5 3 8 1.5 6 1.5z"/></svg>
                </p>
            </div>
            <div class="flex items-center gap-1">
                <a href="https://github.com/settings/profile" target="_blank" rel="noopener" class="group flex items-center justify-center w-9 h-9 rounded-lg transition-all duration-300 hover:-translate-y-1 hover:bg-white/[0.03]" aria-label="GitHub">
                    <svg class="w-[18px] h-[18px] text-white/35 group-hover:text-emerald-500 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"/></svg>
                </a>
                <a href="https://www.tiktok.com/@franns.xo" target="_blank" rel="noopener" class="group flex items-center justify-center w-9 h-9 rounded-lg transition-all duration-300 hover:-translate-y-1 hover:bg-white/[0.03]" aria-label="TikTok">
                    <svg class="w-[18px] h-[18px] text-white/35 group-hover:text-emerald-500 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12a4 4 0 1 0 4 4V4a5 5 0 0 0 5 5" /></svg>
                </a>
                <a href="https://www.instagram.com/b.coenn/" target="_blank" rel="noopener" class="group flex items-center justify-center w-9 h-9 rounded-lg transition-all duration-300 hover:-translate-y-1 hover:bg-white/[0.03]" aria-label="Instagram">
                    <svg class="w-[18px] h-[18px] text-white/35 group-hover:text-emerald-500 transition-colors duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="5"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                </a>
            </div>
        </div>
    </footer>

    <!-- Petunjuk -->
    <div class="scroll-indicator fixed bottom-3 left-1/2 -translate-x-1/2 flex-col items-center gap-0.5 transition-opacity duration-500" style="display:none; opacity:0;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white/12 -mt-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>

    {{-- ═══════════ SCRIPTS ═══════════ --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const toggle = document.getElementById('menuToggle');
            const menu   = document.getElementById('mobileMenu');
            if (!toggle || !menu) return;
            toggle.addEventListener('click', () => {
                const isOpen = menu.classList.toggle('open');
                toggle.classList.toggle('hamburger-active', isOpen);
                toggle.setAttribute('aria-expanded', isOpen);
            });
            menu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    menu.classList.remove('open');
                    toggle.classList.remove('hamburger-active');
                    toggle.setAttribute('aria-expanded', 'false');
                });
            });
            document.addEventListener('click', (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                    toggle.classList.remove('hamburger-active');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <script>
            function techStackFilter() {
    return {
        activeTab: 'all',
        tabs: [
            { key: 'all',       label: 'All Stack' },
            { key: 'frontend',  label: 'Frontend'  },
            { key: 'backend',   label: 'Backend'   },
            { key: 'tools',     label: 'Tools'     },
            { key: 'ai-team',   label: 'Teamwork' }
        ],
        skills: [
            { name: 'HTML',         img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg',         level: 'Advanced',     cat: 'frontend' },
            { name: 'CSS',          img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg',           level: 'Advanced',     cat: 'frontend' },
            { name: 'JavaScript',   img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg',   level: 'Advanced',     cat: 'frontend' },
            { name: 'Tailwind CSS', img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/tailwindcss/tailwindcss-original.svg', level: 'Advanced',     cat: 'frontend' },
            { name: 'Bootstrap',    img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/bootstrap/bootstrap-original.svg',     level: 'Intermediate', cat: 'frontend' },
            { name: 'Alpine.js',    img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/alpinejs/alpinejs-original.svg',       level: 'Intermediate', cat: 'frontend' },
            { name: 'PHP',          img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg',                 level: 'Advanced',     cat: 'backend'  },
            { name: 'Laravel',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/laravel/laravel-original.svg',         level: 'Advanced',     cat: 'backend'  },
            { name: 'MySQL',        img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg',             level: 'Advanced',     cat: 'backend'  },
            { name: 'Node.js',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/nodejs/nodejs-original.svg',           level: 'Intermediate', cat: 'backend'  },
            { name: 'Vite',         img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vitejs/vitejs-original.svg',           level: 'Intermediate', cat: 'tools'    },
            { name: 'Figma',        img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/figma/figma-original.svg',             level: 'Advanced',     cat: 'tools'    },
            
            // FIX GITHUB: Pakai versi github-original.svg tapi ditambahkan filter CSS via Tailwind di HTML jika perlu, 
            // atau pake URL Iconify khusus dark mode yang warnanya putih salju mendelik.
            { name: 'GitHub',       img: 'https://api.iconify.design/bi:github.svg?color=%23ffffff',                                level: 'Advanced',     cat: 'tools'    },
            { name: 'VS Code',      img: 'https://cdn.jsdelivr.net/gh/devicons/devicon/icons/vscode/vscode-original.svg',           level: 'Advanced',     cat: 'tools'    },
            
            // ═══════════ FIX ALL AI ICONS FOR DARK MODE ═══════════
            // GLM-Turbo-5: Pakai icon Robot pintar warna Emerald/Green biar serasi ama tema portofolio lu & ga broken
            { name: 'GLM-Turbo-5',  img: 'https://api.iconify.design/fluent:bot-sparkle-24-filled.svg?color=%2310b981',             level: 'Advanced',     cat: 'ai-team'  },
            // Gemini: Menggunakan Official SVG warna asli (bukan hitam) jadi menyala di dark mode
            { name: 'Gemini 1.5 Pro', img: 'https://api.iconify.design/logos:google-gemini.svg',                                        level: 'Advanced',     cat: 'ai-team'  },
            // Claude Sonnet: Diperbaiki jalurnya ke format ter-update (pasti muncul)
            { name: 'Claude Sonnet', img: 'https://cdn.jsdelivr.net/gh/devicons/devicon@latest/icons/claude/claude-original.svg',    level: 'Advanced',     cat: 'ai-team'  },
            // GPT-4o: Pakai icon OpenAI warna putih terang menderang biar kelihatan di dark mode
            { name: 'GPT-4o',       img: 'https://api.iconify.design/simple-icons:openai.svg?color=%23ffffff',                      level: 'Advanced',     cat: 'ai-team'  }
        ],
        get filtered() {
            return this.activeTab === 'all'
                ? this.skills
                : this.skills.filter(s => s.cat === this.activeTab);
        }
    }
}

        function contactForm() {
            return {
                isSubmitting: false,
                form: { name: '', email: '', message: '' },
                submitForm() {
                    if (!this.form.name || !this.form.email || !this.form.message) return;
                    this.isSubmitting = true;
                    fetch('/contact', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.form)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.form = { name: '', email: '', message: '' };
                            alert('Pesan berhasil dikirim!');
                        }
                    })
                    .catch(() => { alert('Gagal mengirim pesan.'); })
                    .finally(() => { this.isSubmitting = false; });
                }
            }
        }
    </script>
    <script>
    const indicator = document.querySelector('.scroll-indicator');
    const footer = document.querySelector('footer');
    let scrollTimer = null;

    function hideIndicator() {
        indicator.style.opacity = '0';
        setTimeout(() => { indicator.style.display = 'none'; }, 500);
    }

    function showIndicator() {
        indicator.style.display = 'flex';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                indicator.style.opacity = '1';
            });
        });
    }

    if (indicator && footer) {
        // Tampil di awal (desktop only)
        if (window.innerWidth >= 768) {
            showIndicator();
        }

        window.addEventListener('scroll', () => {
            if (window.innerWidth < 768) return;

            const footerVisible = footer.getBoundingClientRect().top < window.innerHeight;

            // Footer keliatan → hilang permanen
            if (footerVisible) {
                clearTimeout(scrollTimer);
                hideIndicator();
                return;
            }

            // Lagi scroll → hilang
            hideIndicator();

            // Berhenti scroll → tampil lagi setelah 1 detik idle
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => {
                const stillFooterVisible = footer.getBoundingClientRect().top < window.innerHeight;
                if (!stillFooterVisible) showIndicator();
            }, 1000);
        });
    }

    // ── Theme toggle ──
    const themeToggle = document.getElementById('themeToggle');
    const iconSun     = document.getElementById('iconSun');
    const iconMoon    = document.getElementById('iconMoon');
    const html        = document.documentElement;

    if (localStorage.getItem('theme') === 'light') {
        html.classList.add('light');
        iconSun.classList.add('hidden');
        iconMoon.classList.remove('hidden');
    }

    themeToggle.addEventListener('click', () => {
        const isLight = html.classList.toggle('light');
        iconSun.classList.toggle('hidden', isLight);
        iconMoon.classList.toggle('hidden', !isLight);
        localStorage.setItem('theme', isLight ? 'light' : 'dark');
    });
</script>
</body>
</html>