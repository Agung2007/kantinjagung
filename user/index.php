<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>


    <script src="https://cdn.tailwindcss.com"></script>
    
</head>

<body>
    <!-- header -->
    <header id="header" class="bg-white fixed top-0 left-0 w-full z-50 shadow-md opacity-0 transform -translate-y-full transition-all duration-500 ease-out">
    <div class="mx-auto max-w-screen-xl px-4 py-2 sm:px-6 sm:py-2 lg:px-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <img src="../assets/images/ifsu.png" alt="Logo" class="h-6 sm:h-8 md:h-10 mr-2">
                <div>
                    <h1 class="text-lg font-bold text-yellow-300 sm:text-xl">DAPOER IFSU</h1>
                    <p class="mt-0 text-xs text-gray-600">Selamat datang di website kantin SMK INFORMATIKA SUMEDANG</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="login.php" class="text-sm px-3 py-2 border border-gray-200 bg-white text-gray-900 transition hover:text-gray-700">
                    Login
                </a>
                <a href="register.php" class="text-sm px-4 py-2 bg-indigo-600 text-white transition hover:bg-indigo-700">
                    Register
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const header = document.getElementById("header");
        setTimeout(() => {
            header.classList.remove("opacity-0", "-translate-y-full");
        }, 300);
    });
</script>


    <!-- bnner -->


    <section id="hero" class="relative opacity-0 translate-y-10 transition-all duration-1000 ease-out">
    <img src="../assets/images/page2.png" alt="Background Image"
        class="absolute inset-0 h-full w-full object-cover" />

    <div class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 sm:to-gray-900/25 sm:bg-gradient-to-r"></div>

    <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
        <div class="max-w-xl text-center sm:text-left opacity-0 translate-y-10 transition-all duration-700 ease-in-out">
            <h1 class="text-3xl font-extrabold text-black sm:text-5xl">
                KANTIN DIGITAL
                <strong class="block font-extrabold text-yellow-300"> ANTI RIBET. </strong>
            </h1>
            <p class="mt-4 max-w-lg text-black sm:text-xl/relaxed">
                Kantin IFSU Berkah adalah platform digital yang memudahkan siswa dan staf SMK Informatika Sumedang
                dalam melihat menu, memesan makanan, dan mengetahui informasi terbaru tentang kantin sekolah.
            </p>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Animasi saat pertama kali membuka website
        const hero = document.getElementById("hero");
        setTimeout(() => {
            hero.classList.remove("opacity-0", "translate-y-10");
        }, 500);

        // Animasi saat scroll
        const scrollElements = document.querySelectorAll(".opacity-0");

        function handleScrollAnimations() {
            scrollElements.forEach((el) => {
                const rect = el.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                if (rect.top < windowHeight * 0.9) { 
                    el.classList.add("opacity-100", "translate-y-0");
                    el.classList.remove("opacity-0", "translate-y-10");
                } 
            });
        }

        // Jalankan animasi saat pertama kali halaman dimuat
        handleScrollAnimations();

        // Tambahkan event listener untuk mendeteksi scroll
        window.addEventListener("scroll", handleScrollAnimations);
    });
</script>

    <!-- PRODUK -->
    <section class="mx-auto max-w-screen-xl px-4 py-12">
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-8 scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
        MENU UNGGULAN
    </h2>

    <span class="relative flex justify-center scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
        <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent bg-gradient-to-r from-transparent via-gray-500 to-transparent opacity-75"></div>
        <span class="relative z-10 bg-white px-6">Menu</span>
    </span>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
            <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/mieayam.png" alt="Mie Ayam">
            <p class="mt-2 text-sm text-gray-700">Mie Ayam</p>
        </div>
        <div class="text-center scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
            <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/cendol.png" alt="Cendol">
            <p class="mt-2 text-sm text-gray-700">Cendol</p>
        </div>
        <div class="text-center scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
            <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/sate.png" alt="Sate">
            <p class="mt-2 text-sm text-gray-700">Sate</p>
        </div>
        <div class="text-center scroll-fade opacity-0 translate-x-10 transition-all duration-700 ease-in-out">
            <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/nasichiken.png" alt="Nasi Chicken">
            <p class="mt-2 text-sm text-gray-700">Nasi Chicken</p>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const scrollElements = document.querySelectorAll(".scroll-fade");

        function handleScrollAnimations() {
            scrollElements.forEach((el) => {
                const rect = el.getBoundingClientRect();
                const windowHeight = window.innerHeight;

                if (rect.top < windowHeight * 0.9 && rect.bottom > 0) { 
                    el.classList.add("opacity-100", "translate-x-0");
                    el.classList.remove("opacity-0", "translate-x-10");
                } else {
                    el.classList.add("opacity-0", "translate-x-10");
                    el.classList.remove("opacity-100", "translate-x-0");
                }
            });
        }

        handleScrollAnimations();
        window.addEventListener("scroll", handleScrollAnimations);
    });
</script>

<section class="relative animate-section opacity-0 translate-x-10 transition-all duration-[2500ms] delay-700 ease-in-out">
    <img src="../assets/images/model.png" alt="Background Image"
        class="absolute inset-0 h-full w-full object-cover" />

    <div class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 sm:to-gray-900/25 sm:bg-gradient-to-r"></div>

    <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
        <div class="max-w-xl text-center ltr:sm:text-left rtl:sm:text-right">
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".animate-section");

    function handleScrollAnimations() {
        sections.forEach((section) => {
            const rect = section.getBoundingClientRect();
            const windowHeight = window.innerHeight;

            if (rect.top < windowHeight * 0.9 && rect.bottom > 0) {
                setTimeout(() => { // Tambahkan delay lebih lama
                    section.classList.add("opacity-100", "translate-x-0");
                    section.classList.remove("opacity-0", "translate-x-10");
                }, 1000); // Delay 1 detik sebelum animasi dimulai
            } else {
                section.classList.add("opacity-0", "translate-x-10"); // Reset efek agar bisa muncul lagi
                section.classList.remove("opacity-100", "translate-x-0");
            }
        });
    }

    window.addEventListener("scroll", handleScrollAnimations);
    handleScrollAnimations();
});
</script>



    <!-- TESTIMONI -->
    <link href="https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/keen-slider.min.css" rel="stylesheet" />

    <script type="module">
        import KeenSlider from 'https://cdn.jsdelivr.net/npm/keen-slider@6.8.6/+esm'

  const keenSliderActive = document.getElementById('keen-slider-active')
  const keenSliderCount = document.getElementById('keen-slider-count')

  const keenSlider = new KeenSlider(
    '#keen-slider',
    {
      loop: true,
      defaultAnimation: {
        duration: 750,
      },
      slides: {
        origin: 'center',
        perView: 1,
        spacing: 16,
      },
      breakpoints: {
        '(min-width: 640px)': {
          slides: {
            origin: 'center',
            perView: 1.5,
            spacing: 16,
          },
        },
        '(min-width: 768px)': {
          slides: {
            origin: 'center',
            perView: 1.75,
            spacing: 16,
          },
        },
        '(min-width: 1024px)': {
          slides: {
            origin: 'center',
            perView: 3,
            spacing: 16,
          },
        },
      },
      created(slider) {
        slider.slides[slider.track.details.rel].classList.remove('opacity-40')

        keenSliderActive.innerText = slider.track.details.rel + 1
        keenSliderCount.innerText = slider.slides.length
      },
      slideChanged(slider) {
        slider.slides.forEach((slide) => slide.classList.add('opacity-40'))

        slider.slides[slider.track.details.rel].classList.remove('opacity-40')

        keenSliderActive.innerText = slider.track.details.rel + 1
      },
    },
    []
  )

  const keenSliderPrevious = document.getElementById('keen-slider-previous')
  const keenSliderNext = document.getElementById('keen-slider-next')

  keenSliderPrevious.addEventListener('click', () => keenSlider.prev())
  keenSliderNext.addEventListener('click', () => keenSlider.next())
</script>

    <section class="bg-white">
        <div class="mx-auto max-w-screen-xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16">
            <h2 class="text-center text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                PELANGGAN TERKENAL
            </h2>

            <div class="mt-8">
                <div id="keen-slider" class="keen-slider">
                    <div class="keen-slider__slide opacity-40 transition-opacity duration-500">
                        <blockquote class="rounded-lg bg-gray-50 p-6 shadow-xs sm:p-8">
                            <div class="flex items-center gap-4">
                                <img src="../assets/images/ronaldo.jpeg" alt="Testimoni"
                                    class="size-14 rounded-full object-cover mx-auto">

                                <div>
                                    <div class="flex justify-center gap-0.5 text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>

                                    <p class="mt-0.5 text-lg font-medium text-gray-900">Cristiano Ronaldo</p>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-700">
                                Ya makanan nya sangat enak sekali keluarga saya di portugal semua nya suka </p>
                        </blockquote>
                    </div>

                    <div class="keen-slider__slide opacity-40 transition-opacity duration-500">
                        <blockquote class="rounded-lg bg-gray-50 p-6 shadow-xs sm:p-8">
                            <div class="flex items-center gap-4">
                                <img src="../assets/images/agung.jpg" alt="Testimoni"
                                    class="size-14 rounded-full object-cover mx-auto">
                                <div>
                                    <div class="flex justify-center gap-0.5 text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>

                                    <p class="mt-0.5 text-lg font-medium text-gray-900">Agung Ganteng</p>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-700">
                                Saya beli disini tidak pernah kecewa, pegawai ramah </p>
                        </blockquote>
                    </div>

                    <div class="keen-slider__slide opacity-40 transition-opacity duration-500">
                        <blockquote class="rounded-lg bg-gray-50 p-6 shadow-xs sm:p-8">
                            <div class="flex items-center gap-4">
                                <img src="../assets/images/boykun.webp" alt="Testimoni"
                                    class="size-14 rounded-full object-cover mx-auto">


                                <div>
                                    <div class="flex justify-center gap-0.5 text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>

                                    <p class="mt-0.5 text-lg font-medium text-gray-900">Tanboy kun</p>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-700">
                                Saya suka mukbang cireng di sini </p>
                        </blockquote>
                    </div>

                    <div class="keen-slider__slide opacity-40 transition-opacity duration-500">
                        <blockquote class="rounded-lg bg-gray-50 p-6 shadow-xs sm:p-8">
                            <div class="flex items-center gap-4">
                                <img src="../assets/images/messi.jpeg" alt="Testimoni"
                                    class="size-14 rounded-full object-cover mx-auto">


                                <div>
                                    <div class="flex justify-center gap-0.5 text-green-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>

                                    <p class="mt-0.5 text-lg font-medium text-gray-900">Lionel Messi</p>
                                </div>
                            </div>

                            <p class="mt-4 text-gray-700">
                                Mantap cocok rasa nya cocok untuk lidah orang Argentina </p>
                        </blockquote>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-center gap-4">
                    <button aria-label="Previous slide" id="keen-slider-previous"
                        class="text-gray-600 transition-colors hover:text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </button>

                    <p class="w-16 text-center text-sm text-gray-700">
                        <span id="keen-slider-active"></span>
                        /
                        <span id="keen-slider-count"></span>
                    </p>

                    <button aria-label="Next slide" id="keen-slider-next"
                        class="text-gray-600 transition-colors hover:text-gray-900">
                        <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 5l7 7-7 7" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="space-y-3 max-w-md mx-auto">
  <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden" open>
    <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
      <h2 class="font-medium text-sm">Jam berapa kantin tutup?</h2>
      <span class="relative size-4 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </span>
    </summary>
    <p class="mt-2 text-sm text-gray-700">
      Kantin Buka setiap hari, Buka Pukul 08.00 dan Tutup 16.00
    </p>
  </details>

  <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden">
    <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
      <h2 class="font-medium text-sm">Kapan website bisa di gunakan?</h2>
      <span class="relative size-4 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </span>
    </summary>
    <p class="mt-2 text-sm text-gray-700">
      Website dapat di gunakan hanya satu jam sebelum istirahat.
    </p>
  </details>

  <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden">
    <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
      <h2 class="font-medium text-sm">Siapa yang membuat website ini?</h2>
      <span class="relative size-4 shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </span>
    </summary>
    <p class="mt-2 text-sm text-gray-700">
      Website ini di buat oleh Agung ganteng.
    </p>
  </details>

</div>

</body>

</html>