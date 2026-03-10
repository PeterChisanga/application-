@extends('website-layouts.app')

@section('title', 'Home')

@section('content')
<style>
    @keyframes floatIn {
        0% {
            opacity: 0;
            transform: translateY(50px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-float-in {
        animation: floatIn 1s ease-out forwards;
        opacity: 0;
    }

    .hero-section {
        background-image: url('{{ asset('images/Ball Mill.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        min-height: 75vh;
        color: #ff5733;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .hero-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.75);
        z-index: 1;
    }

    .hero-section .container {
        position: relative;
        z-index: 2;
        padding: 20px;
    }

    .gallery-img {
        width: 100%;
        height:  400px;
        border-radius: 10px;
        object-fit: cover;
    }

    .our-gallery {
        padding: 40px 0;
    }

    .welcome-section h1 {
        font-size: 45px;
        color: #ff5733;
        font-weight: 800;
    }

    .welcome-section p {
        font-size: 25px;
    }

    @media (max-width: 768px) {
        .hero-section {
            min-height: 60vh;
        }

        .hero-section h1 {
            font-size: 2rem;
        }

        .hero-section p {
            font-size: 1rem;
        }

        .welcome-section h1 {
            font-size: 23px;
        }

        .welcome-section p {
            font-size: 17px;
        }


        .product-info h5 {
            font-size: 1.25rem;
        }

        .images-section .row {
            flex-wrap: wrap;
            margin: 0 20px;
        }

        .gallery-img {
            width: 100%;
            height:  auto;
        }
    }
</style>

<section class="hero-section">
    <div class="container animate-float-in">
        <h1 class="display-3 fw-bold">Swarna Metals</h1>
        <a href="/about" class="btn mb-2 text-white" style="background-color: #510404;">Explore</a>
        <div class="mt-3">
            <p>★★★★★</p>
            <p class="fw-bold">QUALITY, RELIABILITY, INNOVATION, EXCELLENCE</p>
        </div>
    </div>
</section>

<section class="welcome-section py-3 bg-light">
    <div class="container animate-float-in">
        <div class="row">
            <div class="col-md-6">
                <h1 class="fw-bold">Welcome to Swarna Metals</h1>
                <p>
                    Swarna Metals Zambia Limited (SMZL) is a state-of-the-art
                    <span class="fw-bold">hydrometallurgical copper processing plant</span>
                    producing approximately 200 MT of copper cathodes and 1,000 MT of copper concentrates per month.
                    The facility is strategically located 25 kilometers from Kitwe in Zambia’s Copperbelt Province.
                </p>
                <a href="/about" class="btn mb-2 text-white" style="background-color: #510404;">Learn More</a>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('images/crusher-2.jpg') }}" alt="Swarna Plant" class="img-fluid rounded">
            </div>
        </div>
    </div>
</section>

{{-- <section class="our-gallery py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-8">
                <img src="{{ asset('images/swarna-truck.jpeg') }}" alt="Image 1" class="img-fluid gallery-img">
            </div>
            <div class="col-md-4">
                <img src="{{ asset('images/side-image.jpg') }}" alt="Image 2" class="img-fluid gallery-img">
            </div>
        </div>
        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <img src="{{ asset('images/1733738279178.png') }}" alt="Image 3" class="img-fluid gallery-img">
            </div>
            <div class="col-md-8">
                <img src="{{ asset('images/IMG-20241209-WA0101.jpg') }}" alt="Image 4" class="img-fluid gallery-img">
            </div>
        </div>
    </div>
</section> --}}
@endsection
