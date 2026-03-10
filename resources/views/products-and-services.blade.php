@extends('website-layouts.app')

@section('title', 'Products')

@section('content')
<style>
    .products-services {
        font-family: 'Arial', sans-serif;
    }

    .products-services h1,
    .products-services h2 {
        font-weight: bold;
        margin-bottom: 20px;
    }

    .products-services h1 {
        font-size: 45px;
        color: #ff5733;
        font-weight: 800;
    }

    .products-services h2 {
        font-size: 1.8rem;
        color:  #ff5733;
        border-bottom: 3px solid #444 ;
        display: inline-block;
        padding-bottom: 5px;
    }

    .products-services ul {
        margin: 15px 0;
        padding: 0;
        list-style: none;
    }

    .products-services ul li {
        font-size: 1.1rem;
        margin-bottom: 10px;
    }

    .products-services ul li strong {
        color: #ff5733;
    }

    .product-row {
        display: flex;
        align-items: center;
        margin-bottom: 40px;
    }

    .product-row img {
        width: 100%;
        max-width: 350px;
        height: auto;
        border-radius: 10px;
        margin-right: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .product-row img:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .product-row  ul li {
        font-size: 23px;
    }

    .products-services .container {
        padding: 20px;
        border-radius: 10px;
    }

    /* Media Queries for Responsiveness */
    @media (max-width: 768px) {
        .product-row {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-row img {
            margin-right: 0;
            margin-bottom: 15px;
        }

        .products-services h1 {
            font-size: 2rem;
        }

        .products-services h2 {
            font-size: 1.5rem;
        }

        .products-services h4 {
            font-size: 18px;
        }

        .products-services  ul li {
            font-size: 1rem;
        }

        .products-row  ul li {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .products-services h1 {
            font-size: 20px;
        }

        .products-services h2 {
            font-size: 1.3rem;
        }

        .products-services h4 {
            font-size: 18px;
        }

        .products-services ul li {
            font-size: 0.9rem;
        }
    }
</style>
<br>
<section class="products-services ">
    <div class="container">
        <h1 class="text-center">Our Products</h1>

        <div class="content mt-4">
            <!-- Copper Cathodes Section -->
            <div class="product-row">
                <img src="{{ asset('images/cathode.jpeg') }}" alt="Copper Cathodes" class="img-fluid">
                <div>
                    <h2>Copper Cathodes</h2>
                    <ul>
                        <li><strong>Description:</strong> High-purity cathodes with 99.99% Cu content, suitable for industrial applications.</li>
                        <li><strong>Applications:</strong> Electrical manufacturing, construction, and industrial machinery.</li>
                        <li><strong>Key Features:</strong> Precision-crafted using advanced hydrometallurgical techniques.</li>
                    </ul>
                </div>
            </div>

            <!-- Copper Concentrates Section -->
            <div class="product-row">
                <img src="{{ asset('images/concetrate-plant.jpg') }}" alt="Copper Concentrates" class="img-fluid">
                <div>
                    <h2>Copper Concentrates</h2>
                    <ul>
                        <li><strong>Description:</strong> Premium-grade concentrates between 20% and 35% copper content, ideal for smelting and refining processes.</li>
                        <li><strong>Applications:</strong> Base material for high-value copper products.</li>
                        <li><strong>Key Features:</strong> Sustainably sourced from sulphide ores.</li>
                    </ul>
                </div>
            </div>
            <h4>For queries on our products please send an email to sales@swarnametals.com</h4>
        </div>
    </div>
</section>
@endsection
