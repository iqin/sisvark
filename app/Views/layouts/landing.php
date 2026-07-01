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
            display: flex;
            align-items: center;
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
        .carousel-fade .carousel-item {
            opacity: 0;
            transition-duration: 1.2s;
            transition-property: opacity;
        }
        .carousel-fade .carousel-item.active,
        .carousel-fade .carousel-item-next.carousel-item-start,
        .carousel-fade .carousel-item-prev.carousel-item-end {
            opacity: 1;
        }
        .carousel-fade .carousel-item-next,
        .carousel-fade .carousel-item-prev,
        .carousel-fade .carousel-item.active.carousel-item-start,
        .carousel-fade .carousel-item.active.carousel-item-end {
            opacity: 0;
        }
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }
        .carousel-indicators .active {
            background-color: #4e73df;
            border-color: #4e73df;
        }
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
            font-size: 1.1rem;
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