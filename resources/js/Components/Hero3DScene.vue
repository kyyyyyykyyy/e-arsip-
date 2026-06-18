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
    // 1. SETUP SCENE & CAMERA (Tema Terang Putih)
    scene = new THREE.Scene();
    scene.fog = new THREE.FogExp2(0xffffff, 0.04); 
    
    camera = new THREE.PerspectiveCamera(45, window.innerWidth / window.innerHeight, 0.1, 1000);
    camera.position.set(0, 2, 8);

    // 2. SETUP RENDERER
    renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(window.innerWidth, window.innerHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    containerRef.value.appendChild(renderer.domElement);

    // 3. SETUP CAHAYA (Khaki Tua Kehijauan & Abu-Abu)
    scene.add(new THREE.AmbientLight(0xffffff, 1.2));
    
    const dirLight = new THREE.DirectionalLight(0x5d6b47, 3);
    dirLight.position.set(5, 10, 5);
    scene.add(dirLight);
    
    const pointLight1 = new THREE.PointLight(0x4a553c, 6, 20);
    pointLight1.position.set(-5, 0, -5);
    scene.add(pointLight1);
    
    const pointLight2 = new THREE.PointLight(0x606e4e, 4, 15);
    pointLight2.position.set(0, -5, 0);
    scene.add(pointLight2);

    // 4. OBJEK: LEMARI ARSIP PUSAT (Abu-abu Rokok Solid)
    const cabinetGeo = new THREE.BoxGeometry(2, 3, 2);
    const cabinetMat = new THREE.MeshPhysicalMaterial({
        color: 0x9ca3af, metalness: 0.7, roughness: 0.2,
        clearcoat: 1.0, clearcoatRoughness: 0.1,
        emissive: 0x4a553c, emissiveIntensity: 0.15 // Sedikit nyala Khaki
    });
    const cabinet = new THREE.Mesh(cabinetGeo, cabinetMat);
    scene.add(cabinet);

    // 5. OBJEK: FOLDER HOLOGRAM MELAYANG (Khaki Kehijauan)
    const foldersGroup = new THREE.Group();
    const folderGeo = new THREE.BoxGeometry(1.2, 0.8, 0.05);
    const folderMat = new THREE.MeshPhysicalMaterial({
        color: 0x5d6b47, transparent: true, opacity: 0.4,
        roughness: 0.1, transmission: 0.9, thickness: 0.5,
        emissive: 0x4a553c, emissiveIntensity: 0.3, wireframe: false
    });

    for(let i = 0; i < 12; i++) {
        const angle = (i / 12) * Math.PI * 2;
        const radius = 3.5 + Math.random();
        const folder = new THREE.Mesh(folderGeo, folderMat);
        if (i % 3 === 0) folder.material = folderMat.clone();
        if (i % 3 === 0) folder.material.wireframe = true;
        
        folder.position.set(Math.cos(angle) * radius, (Math.random() - 0.5) * 4, Math.sin(angle) * radius);
        folder.rotation.y = -angle;
        foldersGroup.add(folder);
    }
    scene.add(foldersGroup);

    // 6. OBJEK: PARTIKEL CAHAYA (Khaki Tua)
    const particleGeo = new THREE.BufferGeometry();
    const particleCount = 2000;
    const posArray = new Float32Array(particleCount * 3);
    for(let i = 0; i < particleCount * 3; i++) {
        posArray[i] = (Math.random() - 0.5) * 40; 
    }
    particleGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const particleMat = new THREE.PointsMaterial({ size: 0.05, color: 0x4a553c, transparent: true, opacity: 0.8 });
    const particles = new THREE.Points(particleGeo, particleMat);
    scene.add(particles);

    // 7. ANIMASI MASUK (GSAP)
    gsap.fromTo(cabinet.scale, { x: 0, y: 0, z: 0 }, { x: 1, y: 1, z: 1, duration: 2, ease: "expo.out" });
    gsap.fromTo(foldersGroup.position, { y: -10 }, { y: 0, duration: 2.5, ease: "power3.out", delay: 0.5 });

    // 8. EVENT LISTENER MOUSE & RESIZE
    window.addEventListener('mousemove', onMouseMove);
    window.addEventListener('resize', onResize);

    // 9. LOOP ANIMASI UTAMA
    const clock = new THREE.Clock();
    const animate = () => {
        const delta = clock.getDelta();
        const elapsed = clock.getElapsedTime();

        // Parallax Kamera
        targetX = mouseX * 2;
        targetY = mouseY * 2;
        camera.position.x += (targetX - camera.position.x) * 0.05;
        camera.position.y += (-targetY + 2 - camera.position.y) * 0.05;
        camera.lookAt(0, 0, 0);

        // Putaran Lemari & Efek Melayang
        cabinet.rotation.y += delta * 0.2;
        cabinet.position.y = Math.sin(elapsed * 1.5) * 0.2;

        // Putaran Folder & Partikel
        foldersGroup.rotation.y -= delta * 0.1;
        particles.rotation.y += delta * 0.02;

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
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-white pointer-events-none z-0"></div>
    </div>
</template>