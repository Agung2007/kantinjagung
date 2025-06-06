<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dapoer Ifsu | Kantin digital</title>
  <link rel="shortcut icon" href="../assets/images/bahanicon.png">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
  <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
  <!-- AOS CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/typed.js/2.0.12/typed.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
  @keyframes wiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    50% { transform: rotate(5deg); }
    75% { transform: rotate(-3deg); }
  }

  .animate-wiggle {
    animation: wiggle 0.3s ease-in-out;
  }
</style>

</head>

<body>
  <!-- header -->
  <header id="header"
    class="bg-blue-900 fixed top-0 left-0 w-full z-50 shadow-md opacity-0 transform -translate-y-full transition-all duration-500 ease-out">
    <div class="mx-auto max-w-screen-xl px-4 py-2 sm:px-6 sm:py-2 lg:px-8">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <img src="../assets/images/ifsu.png" alt="Logo" class="h-6 sm:h-8 md:h-10 mr-2">
          <div>
            <h1 class="text-lg font-bold text-yellow-300 sm:text-xl">DAPOER IFSU</h1>
            <p class="mt-0 text-xs text-white">Selamat datang di website kantin SMK INFORMATIKA SUMEDANG</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
  <!-- Tombol Login -->
  <a href="login.php"
    class="inline-flex items-center gap-2 px-4 py-2 
           bg-white border border-gray-300 
           text-gray-800 font-semibold rounded-lg 
           hover:shadow-md hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out">
    <!-- Ikon Login -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12H3m0 0l4-4m-4 4l4 4m13-4a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Login
  </a>

  <!-- Tombol Register -->
  <a href="register.php"
    class="inline-flex items-center gap-2 px-4 py-2 
           bg-gradient-to-r from-indigo-600 to-purple-600 
           text-white font-semibold rounded-lg 
           hover:shadow-md hover:scale-105 active:scale-95 
           transition-all duration-300 ease-in-out ring-1 ring-indigo-400">
    <!-- Ikon Register -->
    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
         viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M5 13l4 4L19 7M12 4v16" />
    </svg>
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


  <section id="hero" class="relative" data-aos="fade-up">
    <img src="../assets/images/page2.png" alt="Background Image" class="absolute inset-0 h-full w-full object-cover" />

    <div
      class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 sm:to-gray-900/25 sm:bg-gradient-to-r">
    </div>

    <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
      <div class="max-w-xl text-center sm:text-left opacity-0 translate-y-10 transition-all duration-700 ease-in-out">
        <h1 class="text-3xl font-extrabold text-black sm:text-5xl">
          KANTIN DIGITAL
          <strong class="block font-extrabold text-yellow-300"> ANTI RIBET. </strong>
        </h1>
        <p class="mt-4 max-w-lg text-black sm:text-xl/relaxed">
          DAPOER IFSU Berkah adalah platform digital yang memudahkan siswa dan staf SMK Informatika Sumedang
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
    <h2 class="text-3xl font-bold text-gray-900 text-center mb-8" 
        data-aos="fade-down" data-aos-duration="2500" data-aos-delay="500">
      MENU UNGGULAN
    </h2>

    <span class="relative flex justify-center" 
        data-aos="zoom-in" data-aos-duration="1500" data-aos-delay="700">
      <div class="absolute inset-x-0 top-1/2 h-px -translate-y-1/2 bg-transparent 
                  bg-gradient-to-r from-transparent via-gray-500 to-transparent opacity-75">
      </div>
      <span class="relative z-10 bg-white px-6">Menu</span>
    </span>
</section>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="text-center" data-aos="fade-up" data-aos-duration="2000">
        <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/mieayam.png" alt="Mie Ayam">
        <p class="mt-2 text-sm text-gray-700">Mie Ayam</p>
    </div>
    <div class="text-center" data-aos="fade-down" data-aos-duration="2000">
        <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/cendol.png" alt="Cendol">
        <p class="mt-2 text-sm text-gray-700">Cendol</p>
    </div>
    <div class="text-center" data-aos="zoom-in" data-aos-duration="2000">
        <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/sate.png" alt="Sate">
        <p class="mt-2 text-sm text-gray-700">Sate</p>
    </div>
    <div class="text-center" data-aos="flip-left" data-aos-duration="2000">
        <img class="h-69 w-full object-cover rounded-lg" src="../assets/images/nasichiken.png" alt="Nasi Chicken">
        <p class="mt-2 text-sm text-gray-700">Nasi Chicken</p>
    </div>
</div>
  </section>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
      AOS.init({ duration: 500, easing: "ease-in-out" });
      
      new Typed("#typed-text", {
        strings: [
          "Nasi Timbel adalah hidangan khas Sunda yang terdiri dari nasi putih yang dibungkus daun pisang, sehingga memberikan aroma khas yang menggugah selera.",
          "Biasanya disajikan dengan lauk seperti ayam goreng, ikan asin, tahu, tempe, sambal, dan lalapan segar.",
          "Cocok dinikmati saat makan siang atau malam, Nasi Timbel menghadirkan cita rasa autentik yang sederhana namun kaya akan kelezatan khas Nusantara."
        ],
        typeSpeed: 30,
        backSpeed: 20,
        backDelay: 1000,
        startDelay: 500,
        loop: false,
        showCursor: true,
      });
    });
  </script>

  <section id="hero" class="relative" data-aos="zoom-in">
    <img src="../assets/images/orange2.png" alt="Background Image" class="absolute inset-0 h-full w-full object-cover" data-aos="fade-left" data-aos-duration="2500" />
    <img src="../assets/images/timbel.png" alt="Center Yellow" class="absolute right-16 bottom-10 h-2/3 opacity-100" data-aos="fade-right" data-aos-duration="2500" />

    <div class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 sm:to-gray-900/25 sm:bg-gradient-to-r">
    </div>

    <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8">
      <div class="max-w-xl text-center sm:text-left opacity 100 translate-y-10 transition-all duration-1000 ease-in-out" data-aos="fade-up">
        <p id="typed-text" class="mt-4 max-w-lg text-gray 100 sm:text-xl/relaxed"></p>
      </div>
    </div>
  </section>

  

<section id="hero" class="relative" data-aos="fade-up">
  <img src="../assets/images/merah.png" alt="Background Image" class="absolute inset-0 h-full w-full object-cover" data-aos="zoom-out" data-aos-duration="2500" />
  
  <div class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 sm:to-gray-900/25 sm:bg-gradient-to-r">
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 lg:flex lg:h-screen lg:items-center lg:px-8 lg:flex-row-reverse">
    
<!-- Teks di kanan -->
<div class="lg:w-1/2 text-center sm:text-left opacity-0 translate-y-10 transition-all duration-700 ease-in-out" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200" data-aos-once="true">
  <p class="mt-4 max-w-lg text-gray 100 sm:text-xl/relaxed">
    Cireng SMK Informatika Sumedang adalah salah satu makanan favorit yang tersedia di kantin sekolah. Cireng ini dibuat dengan bahan berkualitas, menghasilkan tekstur yang renyah di luar dan kenyal di dalam. Disajikan dengan berbagai pilihan saus, mulai dari pedas hingga manis, menjadikannya camilan yang cocok untuk menemani waktu istirahat siswa dan staf. Selain enak, cireng ini juga menjadi ikon khas kantin sekolah karena cita rasanya yang unik dan selalu segar setiap hari.      
  </p>
</div>

    <!-- Gambar di kiri -->
    <div class="lg:w-1/2 flex justify-center">
      <img src="../assets/images/cireng2.png" alt="Cireng Image" class="h-2/3 opacity-100" data-aos="flip-left" data-aos-duration="2500" />
    </div>

  </div>
</section>


  <section class="relative opacity-0 translate-y-10 transition-all duration-[2500ms] delay-700 ease-in-out"
  data-aos="fade-up" data-aos-duration="2000" data-aos-easing="ease-out-cubic">

  <img src="../assets/images/model.png" alt="Background Image" 
    class="absolute inset-0 h-full w-full object-cover transform scale-105 transition-transform duration-[3000ms] ease-in-out group-hover:scale-110"
    data-aos="zoom-in-up" data-aos-duration="2500" />

  <div class="absolute inset-0 bg-gray-900/75 sm:bg-transparent sm:from-gray-900/95 
              sm:to-gray-900/25 sm:bg-gradient-to-r"
    data-aos="fade" data-aos-duration="2000">
  </div>

  <div class="relative mx-auto max-w-screen-xl px-4 py-32 sm:px-6 
              lg:flex lg:h-screen lg:items-center lg:px-8"
    data-aos="fade-up" data-aos-delay="1000" data-aos-duration="2000">
  </div>
</section>


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
  <div class="mx-auto max-w-screen-xl px-4 py-12 sm:px-6 lg:px-8 lg:py-16"
    data-aos="fade-up" data-aos-duration="2000" data-aos-easing="ease-out-cubic">
    
    <h2 class="text-center text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl"
      data-aos="zoom-in" data-aos-duration="1500">
      PELANGGAN TERKENAL
    </h2>
    
  </div>
</section>

      <div class="mt-8">
        <div id="keen-slider" class="keen-slider">
          <div class="keen-slider__slide opacity-40 transition-opacity duration-500">
            <blockquote class="rounded-lg bg-gray-50 p-6 shadow-xs sm:p-8">
              <div class="flex items-center gap-4">
                <img src="../assets/images/ronaldo.jpeg" alt="Testimoni"
                  class="size-14 rounded-full object-cover mx-auto">

                <div>
                  <div class="flex justify-center gap-0.5 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
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
                <img src="../assets/images/agung.jpg" alt="Testimoni" class="size-14 rounded-full object-cover mx-auto">
                <div>
                  <div class="flex justify-center gap-0.5 text-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
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
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
                      <path
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-5" viewBox="0 0 20 20" fill="currentColor">
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
    <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden" open data-aos="fade-up">
      <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
        <h2 class="font-medium text-sm">Apa itu website kantin?</h2>
        <span class="relative size-4 shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
      </summary>
      <p class="mt-2 text-sm text-gray-700">
      Website kantin adalah platform online yang memungkinkan pengguna untuk melihat menu, memesan makanan, dan melakukan pembayaran secara langsung melalui internet. Website ini biasanya digunakan oleh kantin sekolah, kantor, atau tempat makan lainnya untuk memberikan kemudahan bagi pengunjung dalam memesan makanan.
      </p>
    </details>

    <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden" data-aos="fade-up"
      data-aos-delay="100">
      <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
        <h2 class="font-medium text-sm">Bagaimana cara memesan makanan di website kantin?</h2>
        <span class="relative size-4 shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
      </summary>
      <p class="mt-2 text-sm text-gray-700">
      Pengguna dapat memesan makanan melalui menu yang tersedia di halaman utama website, memilih item makanan atau minuman yang diinginkan, menambahkannya ke keranjang belanja, dan melanjutkan ke halaman pembayaran untuk menyelesaikan transaksi.
</p>
    </details>

    <details class="group rounded-lg bg-gray-50 p-4 [&_summary::-webkit-details-marker]:hidden" data-aos="fade-up"
      data-aos-delay="100">
      <summary class="flex cursor-pointer items-center justify-between gap-1 text-gray-900">
        <h2 class="font-medium text-sm"> Apakah website kantin bisa menunjukkan stok makanan?</h2>
        <span class="relative size-4 shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-100 group-open:opacity-0"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <svg xmlns="http://www.w3.org/2000/svg" class="absolute inset-0 size-4 opacity-0 group-open:opacity-100"
            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </span>
      </summary>
      <p class="mt-2 text-sm text-gray-700">
      Ya, website kantin dapat dilengkapi dengan fitur stok yang menampilkan jumlah persediaan setiap menu. Jika stok suatu item habis, menu tersebut bisa diberi label "Stok Habis" atau dihapus sementara dari daftar pilihan.
</p>
    </details>
  </div>

  </div>
  <footer class="p-4 bg-white md:p-8 lg:p-10 dark:bg-gray-800" data-aos="fade-up" data-aos-duration="800">
    <div class="mx-auto max-w-screen-xl text-center">
      <a href="https://wa.me/08586270297"
        class="flex justify-center items-center text-2xl font-semibold text-gray-900 dark:text-white">
        <img src="../assets/images/ifsu.png" alt="Logo" class="mr-2 h-8">
        SMK INFORMATIKA SUMEDANG
      </a>
      <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
        © 2025-2026 <a href="#" class="hover:underline">agung ganteng</a>. dapoer ifsu.
      </span>
    </div>
  </footer>

</body>
<!-- AOS JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
    AOS.init({
        once: false // Animasi akan diputar ulang saat elemen masuk kembali ke viewport
    });
</script>

<a href="https://wa.me/08586270297" target="_blank"
  class="fixed bottom-6 right-6 bg-green-500 text-white p-4 rounded-full shadow-lg flex items-center space-x-2 
         transition hover:scale-110 hover:bg-green-600 
         animate-pulse hover:animate-none"
  onmouseover="this.classList.add('animate-wiggle')"
  onmouseout="this.classList.remove('animate-wiggle')">
  <img src="https://cdn-icons-png.flaticon.com/512/733/733585.png" alt="WhatsApp" class="w-6 h-6">
  <span class="hidden md:inline font-semibold">Hubungi Admin</span>
</a>




</html>