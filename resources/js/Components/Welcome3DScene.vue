<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import * as THREE from 'three';
import gsap from 'gsap';

const containerRef = ref(null);
let renderer, scene, camera, animationFrameId;

let mouseX = 0;
let mouseY = 0;
let targetX = 0;
let targetY = 0;
let windowHalfX = window.innerWidth / 2;
let windowHalfY = window.innerHeight / 2;

const onMouseMove = (event) => {
    mouseX = (event.clientX - windowHalfX) * 0.001;
    mouseY = (event.clientY - windowHalfY) * 0.001;
};

const onResize = () => {
    windowHalfX = window.innerWidth / 2;
    windowHalfY = window.innerHeight / 2;
    camera.aspect = window.innerWidth / window.innerHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(window.innerWidth, window.innerHeight);
};

onMounted(() => {
    // 1. SETUP SCENE
    scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0xffffff, 0.04);
    
    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 0, 12);

    renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    containerRef.value.appendChild(renderer.domElement);

    // 2. SETUP CAHAYA
    scene.add(new THREE.AmbientLight(0xffffff, 1.5));
    
    // Cahaya menggunakan tone khaki kehijauan
    const dirLight = new THREE.DirectionalLight(0x5d6b47, 4); 
    dirLight.position.set(-5, 10, 5);
    scene.add(dirLight);
    
    const pointLight = new THREE.PointLight(0x4a553c, 8, 20); 
    pointLight.position.set(5, 0, 2);
    scene.add(pointLight);

    // 3. OBJEK: INTI DATA (DATA CORE)
    const coreGroup = new THREE.Group();
    
    // Rangka Luar (Wireframe) - Khaki Tua Kehijauan
    const geoCore = new THREE.IcosahedronGeometry(2.5, 1);
    const matCore = new THREE.MeshPhysicalMaterial({
        color: 0x0f172a, wireframe: true, emissive: 0x4a553c, emissiveIntensity: 0.4, transparent: true, opacity: 0.6
    });
    const core = new THREE.Mesh(geoCore, matCore);
    coreGroup.add(core);

    // Bola Menyala di Dalam - Khaki Tua Kehijauan
    const geoInner = new THREE.IcosahedronGeometry(1.2, 0);
    const matInner = new THREE.MeshPhysicalMaterial({
        color: 0x4a553c, metalness: 0.9, roughness: 0.1, emissive: 0x3d4631, emissiveIntensity: 0.5
    });
    const innerCore = new THREE.Mesh(geoInner, matInner);
    coreGroup.add(innerCore);

    // Cincin Data Orbit - Khaki Tua Kehijauan
    const ringGeo = new THREE.RingGeometry(3.5, 3.55, 64);
    const ringMat = new THREE.MeshBasicMaterial({ color: 0x4a553c, side: THREE.DoubleSide, transparent: true, opacity: 0.5 });
    
    const ring1 = new THREE.Mesh(ringGeo, ringMat);
    ring1.rotation.x = Math.PI / 2;
    coreGroup.add(ring1);
    
    const ring2 = new THREE.Mesh(ringGeo, ringMat);
    ring2.rotation.y = Math.PI / 2;
    ring2.scale.set(1.1, 1.1, 1.1);
    coreGroup.add(ring2);

    scene.add(coreGroup);

    // 4. PARTIKEL DATA MELAYANG - Khaki Tua Kehijauan
    const particleGeo = new THREE.BufferGeometry();
    const particleCount = 2000;
    const posArray = new Float32Array(particleCount * 3);
    for(let i = 0; i < particleCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 35;
    }
    particleGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const particleMat = new THREE.PointsMaterial({ size: 0.05, color: 0x5d6b47, transparent: true, opacity: 0.8 });
    const particles = new THREE.Points(particleGeo, particleMat);
    scene.add(particles);

    // 5. ANIMASI MUNCUL
    gsap.fromTo(coreGroup.scale, { x: 0, y: 0, z: 0 }, { x: 1, y: 1, z: 1, duration: 2.5, ease: "elastic.out(1, 0.5)" });

    window.addEventListener('mousemove', onMouseMove);
    window.addEventListener('resize', onResize);

    // 6. LOOP ANIMASI
    const clock = new THREE.Clock();
    const animate = () => {
        const delta = clock.getDelta();
        const elapsed = clock.getElapsedTime();

        // Parallax Kamera
        targetX = mouseX * 2;
        targetY = mouseY * 2;
        camera.position.x += (targetX - camera.position.x) * 0.05;
        camera.position.y += (-targetY - camera.position.y) * 0.05;
        camera.lookAt(0, 0, 0);

        // Putaran Objek
        coreGroup.rotation.y += delta * 0.4;
        coreGroup.rotation.x += delta * 0.2;
        coreGroup.position.y = Math.sin(elapsed * 2) * 0.3; 
        innerCore.rotation.y -= delta * 0.8; 

        particles.rotation.y -= delta * 0.03;

        renderer.render(scene, camera);
        animationFrameId = requestAnimationFrame(animate);
    };
    animate();
});

onBeforeUnmount(() => {
    cancelAnimationFrame(animationFrameId);
    window.removeEventListener('mousemove', onMouseMove);
    window.removeEventListener('resize', onResize);
    if (renderer) renderer.dispose();
});
</script>
    
<template>
    <div class="absolute inset-0 w-full h-full bg-white -z-10 overflow-hidden">
        <div ref="containerRef" class="w-full h-full"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-white via-transparent to-white pointer-events-none opacity-80 z-0"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-white/60 via-transparent to-white pointer-events-none z-0"></div>
    </div>
</template>