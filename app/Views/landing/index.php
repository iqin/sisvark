<?= $this->extend('layouts/landing') ?>

<?= $this->section('content') ?>
<div class="row align-items-center pt-4">
    <!-- Bagian Kiri: Logo + Teks -->
    <!-- Ubah text-center menjadi text-start agar rata kiri di semua ukuran -->
    <div class="col-lg-6 text-start mb-5 mb-lg-0">
        
        <!-- LOGO APLIKASI -->
        <!-- Ubah justify-content-center menjadi justify-content-start -->
        <div class="d-flex align-items-center justify-content-start mb-4">
            <img src="<?= base_url('assets/images/logo.png') ?>" 
                 alt="Logo Adaptive Learning System" 
                 class="me-3" style="height: 50px; width: auto; object-fit: contain;">
            <div>
                <!-- Perkecil ukuran font logo di mobile agar proporsional -->
                <h4 class="fw-bold text-primary mb-0" style="letter-spacing: 1px; font-size: 1rem;">
                    ADAPTIVE LEARNING SYSTEM
                </h4>
            </div>
        </div>

        <h5 class="text-primary fw-bold mb-3">SELAMAT DATANG</h5>
        
        <!-- Judul: Ukuran font disesuaikan untuk mobile -->
        <h1 class="fw-bold mb-3" style="color: #2c3e50; line-height: 1.3; font-size: 2rem;">
            Sistem Pembelajaran <span class="text-primary">Adaptif</span> Berbasis Diferensiasi
        </h1>
        
        <p class="text-muted mb-4" style="font-size: 0.95rem;">
            Temukan gaya belajar Anda (Visual, Aural, Read/Write, Kinestetik) dan
            tingkatkan pemahaman konsep Biologi Sel melalui materi yang dipersonalisasi.
        </p>

        <!-- Tombol Login: Hapus w-100 (full width) dan btn-lg agar tidak terlalu besar -->
        <!-- Tombol akan mengikuti lebar teksnya sendiri secara otomatis -->
        <a href="<?= base_url('login') ?>" class="btn btn-primary-custom">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </a>
    </div>

    <!-- Bagian Kanan: Slider 4 Gambar (Tetap di Tengah) -->
    <div class="col-lg-6">
        <div id="landingCarousel" class="carousel slide carousel-fade shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel" data-bs-interval="4000">
            <!-- Indikator -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <!-- Slide -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://img.freepik.com/free-vector/online-learning-concept-illustration_114360-2486.jpg" 
                         class="d-block w-100 carousel-img" 
                         alt="Pembelajaran Adaptif">
                    <div class="carousel-caption d-none d-md-block">
                        <p>📚 Pembelajaran Adaptif</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/hand-drawn-science-education-background_23-2148496866.jpg" 
                         class="d-block w-100 carousel-img" 
                         alt="Materi Biologi Sel">
                    <div class="carousel-caption d-none d-md-block">
                        <p>🔬 Materi Biologi Sel</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/education-concept-illustration_114360-3908.jpg" 
                         class="d-block w-100 carousel-img" 
                         alt="Personalisasi Pembelajaran">
                    <div class="carousel-caption d-none d-md-block">
                        <p>🎯 Belajar Sesuai Gaya Anda</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/digital-education-concept-illustration_114360-2351.jpg" 
                         class="d-block w-100 carousel-img" 
                         alt="Teknologi Pendidikan">
                    <div class="carousel-caption d-none d-md-block">
                        <p>💻 Teknologi Pendidikan Interaktif</p>
                    </div>
                </div>
            </div>

            <!-- Tombol Navigasi -->
            <button class="carousel-control-prev" type="button" data-bs-target="#landingCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#landingCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
</div>

<style>
    /* CSS Khusus untuk tampilan mobile */
    .carousel-img {
        height: 250px;
        object-fit: cover;
    }
    @media (min-width: 576px) {
        .carousel-img {
            height: 350px;
        }
    }
    @media (min-width: 992px) {
        .carousel-img {
            height: 400px;
        }
        /* Kembalikan ukuran font judul ke besar hanya di desktop */
        h1 {
            font-size: 3rem !important;
        }
    }
</style>
<?= $this->endSection() ?>