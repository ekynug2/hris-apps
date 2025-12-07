<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'HRIS Apps') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #0f172a; color: #fff; overflow-x: hidden; }
        #canvas-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; }
        .content-layer { position: relative; z-index: 10; }
        
        /* Text Animations */
        .reveal-text { opacity: 0; transform: translateY(20px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
        .reveal-text.visible { opacity: 1; transform: translateY(0); }
        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        .delay-400 { transition-delay: 400ms; }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: transform 0.3s ease, border-color 0.3s ease;
        }
        .glass-card:hover {
            transform: translateY(-5px);
            border-color: rgba(245, 158, 11, 0.5);
            background: rgba(255, 255, 255, 0.07);
        }
    </style>
    
    <!-- Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
</head>
<body class="antialiased selection:bg-amber-500 selection:text-white">

    <!-- 3D Background -->
    <div id="canvas-container"></div>

    <div class="relative min-h-screen flex flex-col content-layer">
        <!-- Navbar -->
        <nav class="w-full py-6 px-4 sm:px-8 flex justify-between items-center max-w-7xl mx-auto reveal-text visible">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-amber-500/20">
                    H
                </div>
                <span class="text-2xl font-bold tracking-tight text-white">HRIS Apps</span>
            </div>
            <div>
               <a href="/hris/login" class="px-6 py-2.5 rounded-full bg-white/10 text-white font-medium hover:bg-white/20 transition-all duration-300 backdrop-blur-sm border border-white/10 hover:border-amber-500/50">
                    Access Portal
               </a>
            </div>
        </nav>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center justify-center px-4 sm:px-8 mb-20 mt-10">
            <div class="max-w-5xl w-full text-center space-y-8">
                
                <div class="reveal-text delay-100">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-500/10 text-amber-400 text-sm font-semibold border border-amber-500/20 backdrop-blur-md">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Next Generation HR Management
                    </div>
                </div>
                
                <h1 class="reveal-text delay-200 text-5xl md:text-8xl font-extrabold tracking-tight leading-tight text-white">
                    Future-Ready <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 via-orange-500 to-amber-600">
                        Workforce Systems
                    </span>
                </h1>
                
                <p class="reveal-text delay-300 text-xl md:text-2xl text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    Experience the synergy of AI statistics and ADMS real-time attendance in one unified platform.
                </p>
                
                <div class="reveal-text delay-400 flex flex-col sm:flex-row items-center justify-center gap-5 pt-6">
                    <a href="/hris/login" class="group relative w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-lg hover:scale-105 transform transition-all duration-300 shadow-xl shadow-amber-500/25 overflow-hidden">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Login to Dashboard
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </span>
                    </a>
                    <a href="https://github.com/ekynug2/hris-apps" target="_blank" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-white/5 text-slate-300 font-semibold text-lg border border-white/10 hover:bg-white/10 hover:text-white transition-all duration-300 backdrop-blur-sm">
                        Documentation
                    </a>
                </div>
                
                <!-- Stats / Features Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-20 text-left reveal-text delay-400">
                    <div class="glass-card p-8 rounded-2xl group">
                        <div class="w-14 h-14 bg-blue-500/10 rounded-xl flex items-center justify-center text-blue-400 mb-6 group-hover:scale-110 transition-transform duration-300 border border-blue-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-2">Centralized Data</h3>
                        <p class="text-slate-400 leading-relaxed">Secure, comprehensive employee records accessible from anywhere.</p>
                    </div>
                    <div class="glass-card p-8 rounded-2xl group">
                        <div class="w-14 h-14 bg-emerald-500/10 rounded-xl flex items-center justify-center text-emerald-400 mb-6 group-hover:scale-110 transition-transform duration-300 border border-emerald-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-2">ADMS Push Sync</h3>
                        <p class="text-slate-400 leading-relaxed">Real-time attendance synchronization with ZKTeco bio-metric devices.</p>
                    </div>
                    <div class="glass-card p-8 rounded-2xl group">
                        <div class="w-14 h-14 bg-purple-500/10 rounded-xl flex items-center justify-center text-purple-400 mb-6 group-hover:scale-110 transition-transform duration-300 border border-purple-500/20">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="font-bold text-white text-xl mb-2">AI Insights</h3>
                        <p class="text-slate-400 leading-relaxed">Smart algorithms to detect burnout risks and attendance anomalies.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full py-8 text-center text-slate-600 text-sm reveal-text delay-400">
            &copy; {{ date('Y') }} HRIS Apps by kykytnh. Built with Laravel v{{ Illuminate\Foundation\Application::VERSION }}
        </footer>
    </div>

    <!-- Animation & 3D Script -->
    <script>
        // Trigger generic reveal animations on load
        document.addEventListener('DOMContentLoaded', () => {
            const elements = document.querySelectorAll('.reveal-text');
            elements.forEach(el => el.classList.add('visible'));
        });

        // Three.js Background
        const initThreeJS = () => {
            const container = document.getElementById('canvas-container');
            const scene = new THREE.Scene();
            
            // Fog for depth
            scene.fog = new THREE.FogExp2(0x0f172a, 0.002);

            const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 1, 1000);
            camera.position.z = 500;

            const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(window.devicePixelRatio);
            container.appendChild(renderer.domElement);

            // Particles
            const geometry = new THREE.BufferGeometry();
            const particlesCount = 700;
            const positions = new Float32Array(particlesCount * 3);
            const colors = new Float32Array(particlesCount * 3);
            
            const color1 = new THREE.Color(0xf59e0b); // Amber
            const color2 = new THREE.Color(0xea580c); // Orange/Red

            for (let i = 0; i < particlesCount; i++) {
                positions[i * 3] = (Math.random() - 0.5) * 1500; // x
                positions[i * 3 + 1] = (Math.random() - 0.5) * 1500; // y
                positions[i * 3 + 2] = (Math.random() - 0.5) * 1500; // z
                
                // Mix colors
                const mixedColor = i % 2 === 0 ? color1 : color2;
                colors[i * 3] = mixedColor.r;
                colors[i * 3 + 1] = mixedColor.g;
                colors[i * 3 + 2] = mixedColor.b;
            }

            geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            geometry.setAttribute('color', new THREE.BufferAttribute(colors, 3));

            // Material
            const material = new THREE.PointsMaterial({
                size: 3,
                vertexColors: true,
                transparent: true,
                opacity: 0.8,
                sizeAttenuation: true
            });

            const particles = new THREE.Points(geometry, material);
            scene.add(particles);

            // Mouse interaction
            let mouseX = 0;
            let mouseY = 0;
            
            document.addEventListener('mousemove', (event) => {
                mouseX = (event.clientX - window.innerWidth / 2) * 0.5;
                mouseY = (event.clientY - window.innerHeight / 2) * 0.5;
            });

            // Animation Loop
            const animate = () => {
                requestAnimationFrame(animate);

                // Gentle rotation
                particles.rotation.x += 0.0005;
                particles.rotation.y += 0.0005;

                // Mouse interaction parallax
                camera.position.x += (mouseX - camera.position.x) * 0.05;
                camera.position.y += (-mouseY - camera.position.y) * 0.05;
                camera.lookAt(scene.position);

                renderer.render(scene, camera);
            };

            animate();

            // Handle Resize
            window.addEventListener('resize', () => {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
        };

        initThreeJS();
    </script>
</body>
</html>