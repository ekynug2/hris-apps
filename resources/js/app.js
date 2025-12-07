import './bootstrap';
import * as THREE from 'three';

// Landing Page Animations & 3D Background
document.addEventListener('DOMContentLoaded', () => {
    // Only run on landing page if container exists
    const container = document.getElementById('canvas-container');
    if (!container) return;

    // Reveal Animations
    const elements = document.querySelectorAll('.reveal-text');
    elements.forEach(el => el.classList.add('visible'));

    // Three.js Logic
    const initThreeJS = () => {
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
});
