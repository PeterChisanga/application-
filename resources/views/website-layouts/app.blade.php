<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset('images/logo_circle.jpg') }}" type="image/x-icon">
  <title>@yield('title', 'Swarna Metals Zambia Limited (SMZL)')</title>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

  {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}

<style>
    body, html {
        height: 100%;
        margin: 0;
        padding-top: 55px;
    }

    .main-header {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
    }

    .logo {
        width: 80px;
        height: auto;
        margin-right: 10px;
    }

    /* Top contact bar above main nav */
    .top-contact-bar {
        background: #510404;
        color: #ffffff;
        font-size: 0.95rem;
        padding: 6px 0;
    }

    .top-contact-bar a {
        color: #ffffff;
        text-decoration: none;
        margin-left: 8px;
    }

    .top-contact-bar .contact-left,
    .top-contact-bar .contact-right {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .top-contact-bar i { color: #ffddc9; }

    @media (max-width: 768px) {
        .top-contact-bar { font-size: 0.85rem; padding: 6px 10px; }
        .top-contact-bar .container { flex-direction: column; align-items: center; gap: 6px; }
        .top-contact-bar .contact-left,
        .top-contact-bar .contact-right {
            display: flex;
            width: 100%;
            justify-content: center;
            margin: 2px 0;
        }
        .logo {
            width: 40px;
            height: auto;
            margin-right: 4px;
        }
        /* ensure main content clears the fixed header on small screens */
        body, html { padding-top: 50px; }
    }

    @media (max-width: 576px) {
        /* slightly larger padding for very small screens where header stacks taller */
        body, html { padding-top: 50px; }
    }

    .main-header nav {
        background-color: rgb(255, 255, 255);
        color: rgb(1, 1, 1);
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 50px;
    }

    .logo {
        font-size: 1.5rem;
    }

    .main-header ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .main-header ul li {
        margin-right: 20px;
    }

    .main-header ul li a {
        color: rgb(0, 0, 0);
        text-decoration: none;
        transition: color 0.3s;
        /* font-weight: bold; */
        font-size: 20px;
    }

    .main-header ul li a:hover {
        color: #510404;
        text-decoration: underline;
    }

    .main-header .checkbtn {
        font-size: 30px;
        color: #510404;
        cursor: pointer;
        display: none;
    }

    #check {
        display: none;
    }

    @media (max-width: 768px) {
        .top-contact-bar {
            font-size: 12px;
        }

        .main-header .nav {
            padding: 10px 0px;
        }

        .main-header .checkbtn {
            display: block;
            order: 1;
            margin-right: 20px;
        }

        .main-header ul {
            position: fixed;
            top: 80px;
            right: -100%;
            background-color: rgb(255, 255, 255);
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: all 0.3s;
        }

        .main-header ul li {
        margin: 20px 0;
        }

        .main-header ul li a {
        font-size: 20px;
        }

        #check:checked ~ ul {
        right: 0;
        }
    }

    .main-footer {
        background-color: #510404;
        color: white;
        margin-top: 40px;
        padding: 10px 0 30px 0;
        width: 100%;
    }

    .main-footer h5 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
        color: #ffffff;
    }

    .footer-link {
        color: #ffffff;
        text-decoration: none;
        transition: color 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .footer-link:hover {
        color: #ff5733;
    }

    .main-footer p, .main-footer ul {
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .main-footer ul {
        list-style: none;
        padding-left: 0;
    }

    .main-footer .text-center {
        font-size: 0.9rem;
        margin-top: 20px;
        color: #ffffff;
    }

    .main-footer i {
        font-size: 1.1rem;
        margin-right: 8px;
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .main-footer h5 {
            font-size: 1.25rem;
            text-align: center;
        }

        .main-footer .row {
            text-align: center;
        }

        .main-footer p {
            font-size: 0.95rem;
        }

        .main-footer p, .main-footer ul {
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .footer-link {
            font-size: 0.95rem;
        }
    }

    @media (max-width: 576px) {
        .main-footer h5 {
            font-size: 1.1rem;
        }

        .main-footer p, .footer-link {
            font-size: 0.85rem;
        }

        .footer-link i {
            font-size: 1rem;
        }
    }
</style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  @include('website-layouts.header')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @yield('content')

  @include('website-layouts.footer')

<!-- Bootstrap JS (Make sure this is included) -->
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleMenu() {
        const nav = document.getElementById('navbarNav');
        nav.classList.toggle('show');
    }
</script>

</body>
</html>




