<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Adaptif VARK' ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        
        /* Terapkan flexbox hanya untuk layar desktop ke atas */
        @media (min-width: 992px) {
            body {
                display: flex;
                align-items: center;
            }
        }

        .card-shadow {
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: none;
            border-radius: 15px;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-primary-custom:hover {
            transform: scale(1.03);
            color: white;
        }
        /* ... (biarkan sisa style carousel Anda tetap sama) ... */
        .carousel-caption {
            background: rgba(0,0,0,0.4);
            border-radius: 8px;
            padding: 8px 16px;
            display: inline-block;
            width: auto;
        }
        .carousel-caption p {
            margin: 0;
            font-weight: 600;
            font-size: 1rem; /* Sedikit dikecilkan untuk mobile */
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <?= $this->renderSection('content') ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>