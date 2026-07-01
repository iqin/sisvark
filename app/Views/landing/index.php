<?= $this->extend('layouts/landing') ?>

<?= $this->section('content') ?>
<div class="row align-items-center min-vh-75" style="min-height: 70vh;">
    <!-- Bagian Kiri: Logo + Teks -->
    <div class="col-lg-6">
        <!-- LOGO APLIKASI -->
        <div class="d-flex align-items-center mb-4">
            <img src="<?= base_url('assets/images/logo.png') ?>" 
                 alt="Logo Adaptive Learning System" 
                 style="height: 70px; width: auto; object-fit: contain; margin-right: 15px;">
            <div>
                <h4 class="fw-bold text-primary mb-0" style="letter-spacing: 2px;">ADAPTIVE</h4>
                <h4 class="fw-bold text-primary mb-0" style="letter-spacing: 2px;">LEARNING SYSTEM</h4>
            </div>
        </div>

        <h5 class="text-primary fw-bold mb-3">SELAMAT DATANG</h5>
        <h1 class="display-4 fw-bold mb-4" style="color: #2c3e50;">
            Sistem Pembelajaran <br> <span class="text-primary">Adaptif</span> Berbasis Diferensiasi
        </h1>
        <p class="lead text-muted mb-4" style="font-size: 1.1rem;">
            Temukan gaya belajar Anda (Visual, Aural, Read/Write, Kinestetik) dan
            tingkatkan pemahaman konsep Biologi Sel melalui materi yang dipersonalisasi.
        </p>
        <a href="<?= base_url('login') ?>" class="btn btn-primary-custom btn-lg">
            <i class="fas fa-sign-in-alt me-2"></i> Login
        </a>
    </div>

    <!-- Bagian Kanan: Slider 4 Gambar -->
    <div class="col-lg-6 text-center">
        <div id="landingCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000">
            <!-- Indikator -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#landingCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>

            <!-- Slide -->
            <div class="carousel-inner rounded-4 shadow-sm">
                <!-- Slide 1: Pembelajaran Adaptif -->
                <div class="carousel-item active">
                    <img src="https://img.freepik.com/free-vector/online-learning-concept-illustration_114360-2486.jpg" 
                         class="d-block w-100" 
                         alt="Pembelajaran Adaptif"
                         style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <p>📚 Pembelajaran Adaptif</p>
                    </div>
                </div>

                <!-- Slide 2: Biologi Sel -->
                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/hand-drawn-science-education-background_23-2148496866.jpg" 
                         class="d-block w-100" 
                         alt="Materi Biologi Sel"
                         style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <p>🔬 Materi Biologi Sel</p>
                    </div>
                </div>

                <!-- Slide 3: Personalisasi Pembelajaran -->
                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/education-concept-illustration_114360-3908.jpg" 
                         class="d-block w-100" 
                         alt="Personalisasi Pembelajaran"
                         style="height: 400px; object-fit: cover;">
                    <div class="carousel-caption d-none d-md-block">
                        <p>🎯 Belajar Sesuai Gaya Anda</p>
                    </div>
                </div>

                <!-- Slide 4: Teknologi Pendidikan Interaktif -->
                <div class="carousel-item">
                    <img src="https://img.freepik.com/free-vector/digital-education-concept-illustration_114360-2351.jpg" 
                         class="d-block w-100" 
                         alt="Teknologi Pendidikan"
                         style="height: 400px; object-fit: cover;">
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
<?= $this->endSection() ?>