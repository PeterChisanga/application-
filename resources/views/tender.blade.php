@extends('website-layouts.app')

@section('title', 'Tenders')

@section('content')
<style>
    .tenders-page {
        font-family: 'Arial', sans-serif;
        padding: 30px 0;
    }

    .tenders-page h1 {
        font-size: 45px;
        font-weight: 800;
        color: #ff5733;
        margin-bottom: 40px;
    }

    .tender-item {
        margin-bottom: 60px;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .tender-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: #ff5733;
        margin-bottom: 15px;
        border-left: 5px solid #ff5733;
        padding-left: 10px;
    }

    .tender-pdf {
        width: 100%;
        height: 900px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .tender-actions {
        margin-bottom: 15px;
    }

    .tender-actions a {
        display: inline-block;
        padding: 8px 15px;
        background: #28a745;
        color: #fff;
        border-radius: 5px;
        text-decoration: none;
        font-size: 14px;
    }

    .tender-actions a:hover {
        background: #218838;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tender-pdf {
            height: 600px;
        }

        .tenders-page h1 {
            font-size: 28px;
        }
    }
</style>

<section class="tenders-page">
    <div class="container">

        <h1 class="text-center">Our Tenders</h1>

        <!-- Tender 1 -->
        <div class="tender-item">
            <div class="tender-title">Canteen Services Tender</div>
            <div class="tender-actions">
                <a href="{{ asset('tenders/CANTEEN-SERVICES.pdf') }}" download>Download PDF</a>
            </div>
            <embed class="tender-pdf" src="{{ asset('tenders/CANTEEN-SERVICES.pdf') }}">
        </div>

        <!-- Tender 2 -->
        <div class="tender-item">
            <div class="tender-title">Construction Services Tender</div>
            <div class="tender-actions">
                <a href="{{ asset('tenders/CONSTRUCTION-SERVICES.pdf') }}" download>Download PDF</a>
            </div>
            <embed class="tender-pdf" src="{{ asset('tenders/CONSTRUCTION-SERVICES.pdf') }}">
        </div>

        <!-- Tender 3 -->
        <div class="tender-item">
            <div class="tender-title">Transport Services Tender</div>
            <div class="tender-actions">
                <a href="{{ asset('tenders/TRANSPORT-SERVICES.pdf') }}" download>Download PDF</a>
            </div>
            <embed class="tender-pdf" src="{{ asset('tenders/TRANSPORT-SERVICES.pdf') }}">
        </div>

        <!-- Tender 4 -->
        <div class="tender-item">
            <div class="tender-title">Security Services Tender</div>
            <div class="tender-actions">
                <a href="{{ asset('tenders/SECURITY-SERVICES.pdf') }}" download>Download PDF</a>
            </div>
            <embed class="tender-pdf" src="{{ asset('tenders/SECURITY-SERVICES.pdf') }}">
        </div>

        <!-- Tender 5 -->
        <div class="tender-item">
            <div class="tender-title">Equipment & Machinery Tender</div>
            <div class="tender-actions">
                <a href="{{ asset('tenders/EQUIPMENT-MACHINERY.pdf') }}" download>Download PDF</a>
            </div>
            <embed class="tender-pdf" src="{{ asset('tenders/EQUIPMENT-MACHINERY.pdf') }}">
        </div>

    </div>
</section>
@endsection
