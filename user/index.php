<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kantin Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <header class="bg-orange-500 p-4 text-white flex justify-between items-center">
        <div class="flex items-center">
            <img src="../assets/images/ifsu.png" alt="Icon Kantin" class="h-10 w-10 mr-2 animate-bounce">
            <h1 class="text-xl font-bold">Kantin Digital</h1>
        </div>
        <a href="login.php" class="bg-white text-orange-500 px-4 py-2 rounded-md shadow-md hover:bg-orange-100 transition duration-300">Login</a>
    </header>
    
    <section class="p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Produk Kami</h2>
        <div class="relative max-w-md mx-auto overflow-hidden">
            <button id="prev" class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-orange-500 text-white p-2 rounded-full z-10">❮</button>
            <div id="carousel" class="flex transition-transform duration-500 ease-in-out">
                <img src="../assets/images/cireng.jpeg" class="w-full h-64 object-cover rounded-lg shadow-md" alt="Produk 1">
                <img src="../assets/images/popice.jpeg" class="w-full h-64 object-cover rounded-lg shadow-md hidden" alt="Produk 2">
                <img src="../assets/images/susu.jpeg" class="w-full h-64 object-cover rounded-lg shadow-md hidden" alt="Produk 3">
            </div>
            <button id="next" class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-orange-500 text-white p-2 rounded-full z-10">❯</button>
        </div>
    </section>
    
    <section class="p-6 bg-white shadow-md text-center mt-6 animate-fadeIn">
        <h2 class="text-2xl font-bold">Tentang Kantin</h2>
        <p class="mt-2 text-gray-700">Kantin digital menyediakan berbagai macam makanan dan minuman dengan pemesanan yang praktis dan cepat.</p>
    </section>
    
    <section class="p-6 bg-white shadow-md text-center mt-6 animate-fadeIn">
        <h2 class="text-2xl font-bold">Jadwal Kantin</h2>
        <p class="mt-2 text-gray-700">Senin - Jumat: 08:00 - 18:00</p>
        <p class="mt-1 text-gray-700">Sabtu - Minggu: 09:00 - 15:00</p>
    </section>
    
    <footer class="bg-orange-500 text-white text-center p-4 mt-6">
        <p>&copy; 2025 Kantin Digital. Semua Hak Dilindungi.</p>
    </footer>
    
    <script>
        const images = document.querySelectorAll('#carousel img');
        let currentIndex = 0;
        
        document.getElementById('next').addEventListener('click', () => {
            images[currentIndex].classList.add('hidden');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.remove('hidden');
        });
        
        document.getElementById('prev').addEventListener('click', () => {
            images[currentIndex].classList.add('hidden');
            currentIndex = (currentIndex - 1 + images.length) % images.length;
            images[currentIndex].classList.remove('hidden');
        });
    </script>
</body>
</html>
