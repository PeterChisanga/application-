<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Payslip</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .payslip {
            padding: 20px;
            margin: 20px auto;
            max-width: 700px;
        }

        .center-text {
            text-align: center;
        }

        .logo {
            display: block;
            margin: 0 auto;
            max-width: 60px;
            height: auto;
        }

        .company-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .company-header h1 {
            margin: 0;
            color: #510404;
            font-size: 20px;
            font-weight: bold;
        }

        .payslip table {
            width: 100%;
            /* border: 1px solid black; */
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .payslip th, .payslip td {
            padding: 2px;
            border: 1px solid black;
            text-align: left;
        }

        .payslip .bold {
            font-weight: bold;
        }

        .payslip .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="payslip">
        <div class="center-text">
            <img src="{{ public_path('images/logo_circle.jpg') }}" alt="Swarna Metals Logo" class="logo">
        </div>

        <div class="company-header">
            <h1>SWARNA METALS ZAMBIA LIMITED</h1>
        </div>

        <div class="title">
            <p><strong>Pay Slip</strong></p>
            <p><strong>Date Issued:</strong> {{ now()->format('d-m-Y') }}</p>
        </div>

        <table class="table">
            <tr>
                <th>Employee Name:</th>
                <td>{{ $employee->name }}</td>
                <th>Employee ID:</th>
                <td>{{ $employee->id_number }}</td>
            </tr>
            <tr>
                <th>Designation:</th>
                <td>{{ $employee->position }}</td>
                <th>Department/Team:</th>
                <td>{{ $employee->team }}</td>
            </tr>
            <tr>
                <th>Pay Period:</th>
                <td colspan="3">{{ now()->format('F Y') }}</td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Basic Salary (Monthly)</td>
                    <td>{{ $basicPay = number_format((float) $employee->basic_salary, 2) }} ZMW</td>
                </tr>
                <tr>
                    <td> <strong>Allowances (Monthly)</strong></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Housing Allowance (30%)</td>
                    <td>{{ $hra = number_format((float) $employee->basic_salary*0.3, 2) }} ZMW</td>
                </tr>
                <tr>
                    <td>Transportation Allowance </td>
                    <td>{{ $conveyance = number_format((float) $employee->transport_allowance, 2) }} ZMW</td>
                </tr>
                <tr>
                    <td>Lunch Allowance </td>
                    <td>{{ $lunch_allowance = number_format((float) $employee->lunch_allowance , 2) }} ZMW</td>
                </tr>
                <tr>
                    <td>Other Allowances</td>
                    <td>{{ $otherAllowances = number_format((float) $employee->other_allowances, 2) }} ZMW</td>
                </tr>
                <?php
                    $grossEarnings = (float) $employee->basic_salary + (float) $employee->housing_allowance + (float) $employee->transport_allowance + (float) $employee->other_allowances + (float) $employee->lunch_allowance;
                    $totalDeductions = (float) $employee->pf + (float) $employee->tax + (float) $employee->loan_recovery + (float) $employee->other_deductions;

                    $netPay = $grossEarnings - $totalDeductions;

                    $formattedGrossEarnings = number_format($grossEarnings, 2);
                    $formattedTotalDeductions = number_format($totalDeductions, 2);
                    $formattedNetPay = number_format($netPay, 2);
                    $formattedAnnualGrossSalary = number_format($grossEarnings * 12, 2);
                    $napsa = number_format($grossEarnings * 0.05, 2);

                    if ($grossEarnings > 9200) {
                        $zra = number_format((5100 * 0) + (2000 * 0.2) + (2100 * 0.3) + (($grossEarnings - 9200) * 0.37), 2);
                    } elseif ($grossEarnings > 7100) {
                        $zra = number_format((5100 * 0) + (2000 * 0.2) + (($grossEarnings - 7100) * 0.3), 2);
                    } elseif ($grossEarnings > 5100) {
                        $zra = number_format((5100 * 0) + (($grossEarnings - 5100) * 0.2), 2);
                    } else {
                        $zra = number_format(0, 2);
                    }

                    $nhima = number_format( $employee->basic_salary * 0.01, 2);
                ?>
                <tr>
                    <th><strong>Monthly Gross Salary</strong></th>
                    <th>{{ $formattedGrossEarnings }} ZMW</th>
                </tr>

                <tr>
                    <td><strong>Deductions (Standard)</strong></td>
                    <td></td>
                </tr>

                <tr>
                    <td>NAPSA (5% on Gross)</td>
                    <td>{{ $napsa }} ZMW</td>
                </tr>

                <tr>
                    <td>PAYE (as per ZRA norms)</td>
                    <td>{{ $zra }} ZMW</td>
                </tr>

                <tr>
                    <td>Loan Recovery</td>
                    <td>{{ number_format((float) $employee->loan_recovery, 2) }} ZMW</td>
                </tr>

                <tr>
                    <td>NHIMA (1% on Basic)</td>
                    <td>{{$nhima }} ZMW</td>
                </tr>

                <tr>
                    <th><strong>Net Salary (Monthly Credited)</strong></th>
                    <th>{{ $formattedNetPay }} ZMW</th>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>


<div class="auth-buttons">
    @if(Auth::check())
        <a href="{{ route('users.logout') }}" class="btn btn-logout">Logout</a>
    @else
        <a href="{{ route('users.login') }}" class="btn btn-login">Login</a>
    @endif
</div>


<header class="main-header">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <div class="header-logo d-flex align-items-center">
                <img src="{{ asset('images/logo_circle.jpg') }}" alt="Swarna Metals Logo" class="logo" style="width: 80px; height: auto; margin-right: 10px;">
            </div>
            <!-- Navbar Toggle (for smaller screens) -->
            <button class="navbar-toggler d-md-none" type="button" onclick="toggleMenu()">
                <span class="navbar-toggler-icon">&#9776;</span>
            </button>
            <nav id="navbarNav" class="nav-collapse d-md-block">
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/products-and-services">Products and Services</a></li>
                <li><a href="/jobs">Jobs</a></li>
                <li><a href="/about-plr-zambia">About PLR Zambia</a></li>
            </ul>
        </nav>
        </div>

        <!-- Navigation Links -->

    </div>
</header>


@extends('layouts.app')

@section('title', 'About PLR Zambia')

@section('content')
<style>
    table {
    width: 90%;
    margin-bottom: 30px;
    border-collapse: collapse;
    font-family: Arial, sans-serif;
    font-size: 18px;
    color: #333;
    }
    th {
    padding: 12px;
    text-align: left;
    border-bottom: 2px solid #444;
    background-color: #f4f4f4;
    }
    td {
    padding: 10px 15px;
    border-bottom: 1px solid #ddd;
    }
    tr:hover {
    background-color: #f9f9f9;
    }
    caption {
    font-size: 22px;
    font-weight: bold;
    margin-bottom: 15px;
    }
    .grade-header {
    font-weight: bold;
    background-color: #f2f2f2;
    }
    @media screen and (max-width: 768px) {
    table {
        font-size: 14px;
    }
    th, td {
        padding: 8px;
    }
    caption {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 5px;
        }
    }
</style>
<div class="container">
    <h1>About PLR Zambia</h1>

    <section>
        <h2>PLR Projects' Journey into Zambia</h2>
        <p>
            In 2011, M/S PLR conducted surveys in Tanzania and Zambia to identify investment opportunities in mining and allied industries.
            This initiative led to the incorporation of PLR Projects Zambia Limited, marking the company’s entry into Zambia’s mining sector.
            The initial focus was on establishing a ferroalloy business, which laid the foundation for the company’s growth.
        </p>
    </section>

    <section>
        <h2>Strategic Location and Logistics</h2>
        <p>
            Zambia, located in southern-central Africa, shares borders with Tanzania, the Democratic Republic of the Congo, Angola, Zimbabwe, Malawi, Namibia, and Mozambique.
            The nearest port facility is Dar es Salaam in Tanzania, approximately 1,900 kilometers by road from Mansa in Zambia’s Luapula Province, where the mining site is situated.
            The capital city, Lusaka, is about 800 kilometers from Mansa, with Serenje located along the route.
        </p>
    </section>

    <section>
        <h2>Ferro Alloy Plant</h2>
        <p>
            PLR Projects Zambia Limited planned to establish a Ferro Alloy Plant with an initial capacity of <strong>2 x 9 MVA</strong>, expandable up to <strong>54 MVA</strong>, in Serenje, Central Province.
            Land for the plant was acquired in Pensulo village, approximately 440 kilometers from Lusaka.
            Key raw materials such as manganese, quartz, dolomite, and coal are locally available, while coke is imported from neighboring Zimbabwe.
        </p>
        <p>
            The first phase of the <strong>9 MVA</strong> PLR Ferro Alloy Plant was completed, and commercial production began in May 2017.
            The plant is designed to produce ferro silicon, ferro manganese, and silico manganese. Initially, production focused on silico manganese.
            The product is exported to markets including Dubai, Japan, South Africa, Kenya, and Tanzania.
        </p>
        <p>
            Construction of the second phase of the Ferro Alloy Plant, with an additional <strong> 10 MVA </strong> capacity, was initiated and was expected to commence commercial production during 2019.
            This expansion underscores PLR Projects’ commitment to strengthening its presence in Zambia’s mining and industrial sectors.
        </p>
    </section>

    <section>
        <h2>Silico Manganese Specifications</h2>
        <p>
            Silico Manganese (SiMn) is a vital alloy used in steelmaking and foundry industries, offering strength, durability, and improved wear resistance.
            Below are the specifications of our standard grades to help meet your industrial requirements.
        </p>
    </section>
    <h4 style="text-align:center;">Standard Grades</h4>
    <section>
        <table>
            <thead>
            <tr>
                <th>Specification</th>
                <th>Details</th>
            </tr>
            </thead>
            <tbody>
            <tr class="grade-header">
                <td colspan="2">Grade: SiMn 60/14</td>
            </tr>
            <tr>
                <td>Manganese (Mn)</td>
                <td>60% minimum</td>
            </tr>
            <tr>
                <td>Silicon (Si)</td>
                <td>14% minimum</td>
            </tr>
            <tr>
                <td>Carbon (C)</td>
                <td>2.0% maximum</td>
            </tr>
            <tr>
                <td>Phosphorus (P)</td>
                <td>0.3% maximum</td>
            </tr>
            <tr>
                <td>Sulfur (S)</td>
                <td>0.03% maximum</td>
            </tr>

            <tr class="grade-header">
                <td colspan="2">Grade: SiMn 65/17</td>
            </tr>
            <tr>
                <td>Manganese (Mn)</td>
                <td>65% minimum</td>
            </tr>
            <tr>
                <td>Silicon (Si)</td>
                <td>17% minimum</td>
            </tr>
            <tr>
                <td>Carbon (C)</td>
                <td>2.0% maximum</td>
            </tr>
            <tr>
                <td>Phosphorus (P)</td>
                <td>0.3% maximum</td>
            </tr>
            <tr>
                <td>Sulfur (S)</td>
                <td>0.03% maximum</td>
            </tr>

            <tr class="grade-header">
                <td colspan="2">Grade: SiMn 68/18</td>
            </tr>
            <tr>
                <td>Manganese (Mn)</td>
                <td>68% minimum</td>
            </tr>
            <tr>
                <td>Silicon (Si)</td>
                <td>18% minimum</td>
            </tr>
            <tr>
                <td>Carbon (C)</td>
                <td>2.0% maximum</td>
            </tr>
            <tr>
                <td>Phosphorus (P)</td>
                <td>0.3% maximum</td>
            </tr>
            <tr>
                <td>Sulfur (S)</td>
                <td>0.03% maximum</td>
            </tr>
            </tbody>
        </table>
    </section>
    <h4 style="text-align:center;">Balance: Iron (Fe)</h4>
    <section>
        <table>
        <thead>
            <tr>
            <th colspan="2">Physical Properties</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>Appearance</td>
            <td>Metallic lumps with a steel-gray color</td>
            </tr>
            <tr>
            <td>Density</td>
            <td>~6.1 g/cm³</td>
            </tr>
            <tr>
            <td>Melting Range</td>
            <td>1060°C to 1350°C</td>
            </tr>
        </tbody>
        </table>

        <h2>Applications</h2>
        <h2>Silico Manganese is essential For</h2>
        <p><strong>1. Steel Making</strong></p>
        <ul>
            <li>Acts as a deoxidizer to remove oxygen impurities, producing cleaner, stronger steel.</li>
            <li>Serves as an alloying agent to enhance strength, hardness, and wear resistance.</li>
        </ul>

        <p><strong>2. Foundry Applications:</strong></p>
        <ul>
            <li>Improves elasticity, magnetic properties, and thermal resistance of metal castings.</li>
        </ul>
    </section>

    <section>
        <h2>Copper Business</h2>
        <p>
            In 2022, PLR Projects expanded its operations by establishing a new copper business under its subsidiary, Swarna Metals Zambia Limited (SMZL).
            Located on Sabina Mufulira Road, approximately 25 kilometers from Kitwe in Zambia’s Copperbelt Province, SMZL is a state-of-the-art greenfield hydrometallurgical copper extraction plant.
            The facility is designed to produce high-quality copper cathodes and copper concentrates, catering to the increasing demand in the global copper market.
        </p>
    </section>

    <section>
        <h2>Commitment to Zambia's Growth and Sustainability</h2>
        <p>
            SMZL integrates cutting-edge technology, sustainability, and operational excellence, positioning itself as a key player in Zambia’s copper processing industry.
            With a milling capacity of <strong>1,200 tons per day</strong>, expandable to <strong>2,400 tons per day</strong>, the plant reflects PLR’s commitment to value addition across the copper value chain.
            The vision of SMZL aligns with Zambia’s industrial growth strategy, fostering innovation and sustainable development in the Copperbelt Province.
        </p>
        <p>
            By diversifying its portfolio, PLR Projects has reinforced its role as a significant contributor to Zambia’s industrial and economic progress.
        </p>
    </section>

    <section>
        <h2>Why Choose Us</h2>
        <ul>
            <li><strong>Consistent Quality:</strong> We adhere to strict quality standards to ensure high-grade alloys.</li>
            <li><strong>Customized Solutions:</strong> Tailored specifications available to meet unique industrial needs.</li>
            <li><strong>Reliable Supply:</strong> Efficient production and logistics ensure timely delivery.</li>
        </ul>
        <p style="font-style: italic;">
            "Contact us today to learn more about our products and how they can support your operations."
        </p>
    </section>
</div>
@endsection


@extends('layouts.app')

@section('title', 'Products and Services')

@section('content')
<style>
    .gallery-img {
        width: 100%;
        height: 500px;
        /* aspect-ratio: 1 / 2; */
        border-radius: 10px;
        object-fit: cover;
    }

    .images-section .row {
        display: flex;
        justify-content: space-between;
    }
</style>

<section class="products-services mt-5">
    <div class="container">
        <h1 class="text-center">Our Products and Services</h1>
        <div class="content">
            <h2>Copper Cathodes</h2>
            <ul>
                <li><strong>Description:</strong> High-purity cathodes with 99.99% Cu content, suitable for industrial applications.</li>
                <li><strong>Applications:</strong> Electrical manufacturing, construction, and industrial machinery.</li>
                <li><strong>Key Features:</strong> Precision-crafted using advanced hydrometallurgical techniques.</li>
            </ul>

            <h2>Copper Concentrates</h2>
            <ul>
                <li><strong>Description:</strong> Premium-grade concentrates with 25% copper content, ideal for smelting and refining processes.</li>
                <li><strong>Applications:</strong> Base material for high-value copper products.</li>
                <li><strong>Key Features:</strong> Sustainably sourced from sulphide ores.</li>
            </ul>

            <h2>Future Goals</h2>
            <ul>
                <li>Expand milling capacity to 2,400 TPD by 2026.</li>
                <li>Establish global partnerships for copper exports.</li>
                <li>Contribute significantly to Zambia’s industrial growth through innovation and sustainability.</li>
            </ul>
        </div>

        <div class="images-section">
            <div class="row text-center">
                <div class="col-md-3">
                    <img src="{{ asset('images/cathode.jpeg') }}" alt="Image 1" class="img-fluid gallery-img">
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('images/IMG-20241209-WA0101.jpg') }}" alt="Image 2" class="img-fluid gallery-img">
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('images/1733738279178.png') }}" alt="Image 3" class="img-fluid gallery-img">
                </div>
                <div class="col-md-3">
                    <img src="{{ asset('images/IMG-20241209-WA0108_cropped.jpg') }}" alt="Image 4" class="img-fluid gallery-img">
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<section class="about-us">
    <div class="container">
        <div class="row">
            <!-- Left Column -->
            <h1 style="text-align: center; margin-bottom:10px;">About Us</h1>
            <div class="col-md-6">
                <h2>Our Commitment to Quality</h2>
                <p>
                    <strong>Swarna Metals Zambia Limited</strong> (SMZL) is a state-of-the-art greenfield hydrometallurgical
                    copper extraction plant, strategically located 25 kilometers from Kitwe, in Zambia's Copperbelt Province.
                    As a subsidiary of PLR Zambia Limited, SMZL is dedicated to producing high-quality copper cathodes
                    and copper concentrates to meet the growing demand in the global copper market.
                </p>
                <h3>Key Highlights</h3>
                <h4>Strategic Location</h4>
                <p>SMZL is located in Zambia’s Copperbelt Province, a region abundant in copper ore reserves, facilitating efficient operations and logistics.</p>
                <h4>Initial Milling Capacity</h4>
                <p>The plant has a starting capacity of 1,200 tons per day (TPD), with plans to expand to 2,400 TPD in the near future.</p>
                <h4>Production Targets (Phase 1)</h4>
                <ul>
                    <li><strong>Copper Concentrate:</strong> 12,000 metric tons annually.</li>
                    <li><strong>Copper Cathode:</strong> 2,400 metric tons annually with a purity of 99.99%.</li>
                </ul>
                <h4>Timeline</h4>
                <p>Commissioning is scheduled for Q1 2025, initiating full-scale production and positioning SMZL as a key contributor to Zambia's industrial advancement.</p>

                <h3>Key Contacts</h3>
                <h4>Mr. Jayakumar Peddireddy</h4>
                <ul>
                    <li><strong>Position:</strong>CEO, Swarna Metals Zambia</li>
                    <li><strong>Physical Address:</strong> J5, Zamsure Apartments, Lusaka, Zambia</li>
                </ul>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <h2>Innovative Mining Solutions</h2>
                <p>
                    With a focus on innovation, sustainability, and operational excellence, SMZL is poised to become a leading player in Zambia's thriving copper processing industry. The company's vision aligns with the country's industrial growth, ensuring value addition at every stage of the copper value chain.
                </p>

                <h3>Group Company Information</h3>
                <h4>Group Company Name: PLR Zambia Limited</h4>
                <h4>Contact Details:</h4>
                <h4>Mr. R N Niranjan Reddy</h4>
                <ul>
                    <li><strong>Position:</strong> CEO, PLR Zambia Ferro Alloys</li>
                    <li><strong>Physical Address:</strong> J8, Zamsure Apartments, Lusaka, Zambia</li>
                    <li><strong>Website:</strong> <a href="https://plrprojects.com" target="_blank">plrprojects.com</a></li>
                </ul>
            </div>
        </div>
        <div class="row">
            <h1 style="text-align: left; margin-bottom:10px;">Our Location</h1>
            <div class="col-md-6">
                <p>
                    Swarna Metals is located in Kitwe, Zambia, a hub for metal and mining industries, ensuring easy access to resources and markets.
                </p>
                <h2>Address</h2>
                <p>Address Sabina Mufulira Road, Kitwe - 50100, Copperbelt, Zambia</p>
                <h3>Hours</h3>
                <p>8 AM - 5 PM</p>
            </div>

            <div class="col-md-6">
                <iframe src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d174911.3188751776!2d28.05414839521786!3d-12.668204315985065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x196ce3002a1d77bf%3A0xa596812ee0acff6b!2sFarm%20No%20F%2F4213%2FA%2C%20Kitwe-Mufilira%2C%20Kitwe!3m2!1d-12.6623824!2d28.155643599999998!5e0!3m2!1sen!2szm!4v1734504385088!5m2!1sen!2szm" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
</section>

@endsection



@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <style>
            /* Smooth fade-in and float-in animation */
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

    /* Apply animation to specific sections */
    .animate-float-in {
        animation: floatIn 1s ease-out forwards;
        opacity: 0; /* Ensures the animation starts invisible */
    }

    /* Full-width background image for hero section */
    .hero-section {
        background-image: url('{{ asset('images/home-background-image.jpg')}}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        min-height: 75vh;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    /* Optional: Add a dark overlay for better text visibility */
    .hero-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6); /* Black overlay with transparency */
        z-index: 1; /* Ensures it sits above the background */
    }

    .hero-section .container {
        position: relative;
        z-index: 2; /* Content appears above the overlay */
    }

    .gallery-img {
        width: 100%;
        height: 300px;
        border-radius: 10px;
        object-fit: cover;
    }

    .images-section .row {
        display: flex;
        justify-content: space-between;
        margin: 0px 90px
    }

    .product-info {
        padding-top: 10px;
        text-align: left;
        font-size: 0.9em;
        color: #000000;
    }

    .product-info h5 {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .product-info p {
        margin: 5px 0;
        font-size: 17px;
    }

    .product-info p strong {
        font-weight: bold;
    }

    .our-gallery {
        padding: 40px 0;
    }

    .gallery-container .row {
        margin-bottom: 30px;
    }

    .gallery-img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    .our-gallery h2 {
        font-size: 2em;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
    }

    .welcome-section .row {
        align-items: start;
    }

    </style>

    <section class="hero-section">
        <div class="container animate-float-in">
            <h1 class="display-3 fw-bold">Swarna Metals</h1>
            <p class="lead">Discover our products and services today.</p>
            <a href="/about" class="btn btn-primary px-4 py-2">Explore</a>
            <div class="mt-3">
                <p>★★★★★</p>
                <p class="fw-bold">QUALITY, RELIABILITY, INNOVATION, EXCELLENCE</p>
            </div>
        </div>
    </section>

    <section class="welcome-section py-5 bg-light">
        <div class="container animate-float-in">
            <div class="row align-items-start">
                <div class="col-md-6">
                    <h1 class="fw-bold" style="font-size: 48px;">Welcome to Swarna Metals</h1>
                    <p style="font-size: 20px;">
                        Swarna Metals Zambia Limited (SMZL) is a state-of-the-art
                        <span class="fw-bold">greenfield hydrometallurgical copper extraction plant</span>,
                        strategically located 25 kilometers from Kitwe, in Zambia's Copperbelt Province.
                    </p>
                    <a href="/about" class="btn btn-primary">Learn More</a>
                </div>
                <div class="col-md-6">
                    <img src="{{ asset('images/side-image.jpg')}}" alt="Swarna Plant" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </section>

    <section style="background-color:#a8dadc;">
        <div class="images-section" style=" padding-top:40px; padding-bottom:40px;">
           <h1 class="display-3 fw-bold" style="text-align: center; margin: 30px 0px;">Our Products and Services</h1>
            <div class="row text-center">
                <div class="col-md-4">
                    <img src="{{ asset('images/cathode.jpeg') }}" alt="Copper Cathodes" class="img-fluid gallery-img">
                    <div class="product-info">
                        <h5>Copper Cathodes</h5>
                        <p><strong>Description:</strong> High-purity cathodes with 99.99% Cu content, suitable for industrial applications.</p>
                        <p><strong>Applications:</strong> Electrical manufacturing, construction, and industrial machinery.</p>
                        <p><strong>Key Features:</strong> Precision-crafted using advanced hydrometallurgical techniques.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/1733738279178.png') }}" alt="Copper Concentrates" class="img-fluid gallery-img">
                    <div class="product-info">
                        <h5>Copper Concentrates</h5>
                        <p><strong>Description:</strong> Premium-grade concentrates with 25% copper content, ideal for smelting and refining processes.</p>
                        <p><strong>Applications:</strong> Base material for high-value copper products.</p>
                        <p><strong>Key Features:</strong> Sustainably sourced from sulphide ores.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/IMG-20241209-WA0101.jpg') }}" alt="Future Goals" class="img-fluid gallery-img">
                    <div class="product-info">
                        <h5>Future Goals</h5>
                        <p><strong>Expansion:</strong> Expand milling capacity to 2,400 TPD by 2026.</p>
                        <p><strong>Partnerships:</strong> Establish global partnerships for copper exports.</p>
                        <p><strong>Contributions:</strong> Contribute significantly to Zambia’s industrial growth through innovation and sustainability.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="our-gallery" style="width: 80%; margin: 0 auto;">
        <h2 class="text-center">Our Gallery</h2>
        <p class="text-center">Explore our products and services.</p>
        <div class="gallery-container">
            <div class="row">
                <div class="col-md-8">
                    <img src="{{ asset('images/swarna-truck.jpeg') }}" alt="Image 1" class="img-fluid gallery-img">
                </div>
                <div class="col-md-4">
                    <img src="{{ asset('images/side-image.jpg') }}" alt="Image 2" class="img-fluid gallery-img">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('images/1733738279178.png') }}" alt="Image 3" class="img-fluid gallery-img">
                </div>
                <div class="col-md-8">
                    <img src="{{ asset('images/IMG-20241209-WA0101.jpg') }}" alt="Image 4" class="img-fluid gallery-img">
                </div>
            </div>
        </div>
    </section>

{{-- <a class="btn btn-success mt-4" href="{{ route('applications.create')}}">Apply now</a>
<br>
<a class="btn btn-info mt-4" href="{{ route('applications.track')}}">Track your application</a> --}}
@endsection

<!-- pd template  -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Payslip</title>
        <style>
            body {
                font-family: sans-serif;
                background-image: url('{{ public_path('images/logo_circle.jpg') }}');
                background-repeat: no-repeat;
                background-position: center;
                background-size: 500px;
                opacity: 1;
            }

            .payslip {
                padding: 10px;
                margin: 10px auto;
                max-width: 700px;
                background: rgba(255, 255, 255, 0.78);
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .center-text {
                text-align: center;
            }

            .logo {
                display: block;
                margin: 0 auto 10px;
                max-width: 80px;
                height: auto;
            }

            .company-header {
                text-align: center;
                margin-bottom: 15px;
            }

            .company-header h1 {
                margin: 0;
                color: #510404;
                font-size: 30px;
                font-weight: bold;
            }

            .payslip table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .payslip th, .payslip td {
                padding: 4px;
                border: 1px solid #000000;
                font-size: 12px;
                text-align: left;
            }

            .payslip .bold {
                font-weight: bold;
            }

            .payslip .right {
                text-align: right;
            }

            .payslip p {
                font-size: 11px;
            }

            .payslip h4 {
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div class="payslip">
            <div class="center-text">
                <img src="{{ public_path('images/logo_circle.jpg') }}" alt="Swarna Metals Logo" class="logo">
            </div>

            <div class="company-header">
                <h1>SWARNA METALS ZAMBIA LIMITED</h1>
            </div>

            <div class="title">
                <h4><strong>Pay Slip</strong></h4>
            </div>
            <table>
                <tr>
                    <th>Employee Name:</th>
                    <td>{{ $employee->name }}</td>
                    <th>Employee ID:</th>
                    <td>{{ $employee->id_number }}</td>
                </tr>
                <tr>
                    <th>Designation:</th>
                    <td>{{ $employee->position }}</td>
                    <th>Grade:</th>
                    <td>{{ $employee->grade }}</td>
                </tr>
                <tr>
                    <th>Department/Team:</th>
                    <td colspan="3">{{ $employee->team }}</td>
                </tr>
            </table>

            <h4>Year-to-Date Summary</h4>
            <table>
                <tr>
                    <th>Gross Pay YTD:</th>
                    <td>{{ number_format($payslipData['gross_pay_ytd'], 2) }} ZMW</td>
                    <th>Tax (ZRA) Paid YTD:</th>
                    <td>{{ number_format($payslipData['tax_paid_ytd'], 2) }} ZMW</td>
                </tr>
                <tr>
                    <th>NAPSA Contribution YTD:</th>
                    <td>{{ number_format($payslipData['napsa_ytd'], 2) }} ZMW</td>
                    <th>Pension Contribution YTD:</th>
                    <td>{{ number_format($payslipData['pension_ytd'], 2) }} ZMW</td>
                </tr>
                <tr>
                    <th>Leave Balance (Days):</th>
                    <td>-</td>
                    <td colspan="2"></td>
                </tr>
            </table>

            <h4>Earnings</h4>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Shift/Hrs</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>E01</td>
                        <td>Basic Pay</td>
                        <td></td>
                        <td>{{ $employee->basic_salary }}</td>
                    </tr>
                    <tr>
                        <td>E02</td>
                        <td>Housing Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['housing_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>E03</td>
                        <td>Lunch Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['lunch_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>E04</td>
                        <td>Transport Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['transport_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>E05</td>
                        <td>Overtime Pay</td>
                        <td>{{ number_format($payslipData['overtime_hours'], 2) }}</td>
                        <td>{{ number_format($payslipData['overtime_pay'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>E06</td>
                        <td>Other Allowances</td>
                        <td></td>
                        <td>{{ number_format($payslipData['other_allowances'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="3">Total Earnings</th>
                        <th>{{ number_format($payslipData['total_earnings'], 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <h4>Deductions</h4>
            <table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>D01</td>
                        <td>NAPSA Contribution</td>
                        <td>{{ number_format($payslipData['napsa'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>D02</td>
                        <td>Health Insurance (NHIMA)</td>
                        <td>{{ number_format($payslipData['health_insurance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>D03</td>
                        <td>Loan Recovery</td>
                        <td>{{ number_format($payslipData['loan_recovery'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>D04</td>
                        <td>Other Deductions</td>
                        <td>{{ number_format($payslipData['other_deductions'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>D05</td>
                        <td>Tax Deduction (ZRA)</td>
                        <td>{{ number_format($payslipData['tax_paid_ytd'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Total Deductions</th>
                        <th>{{ number_format($payslipData['total_deductions'], 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <p><strong>Net Pay:</strong> {{ number_format($payslipData['net_pay'], 2) }} ZMW</p>
            <p><strong>Payment Method:</strong> {{ $employee->payment_method }}</p>

            <h4>Additional Information</h4>
            <p style="display: inline-block; margin-right: 20px;">
                <strong>Social Security Number:</strong> {{ $employee->social_security_number }}
            </p>
            <p style="display: inline-block;">
                <strong>Bank Name:</strong> {{ $employee->bank_name }}
            </p>
            <p style="display: inline-block; margin-right: 20px;">
                <strong>Branch Name:</strong> {{ $employee->branch_name }}
            </p>
            <p style="display: inline-block;">
                <strong>Bank Account Number:</strong> {{ $employee->bank_account_number }}
            </p>
            <p><strong>Prepared By:</strong>............................................................................................................................   <strong>Date:</strong>.............................................</p>
        </div>
    </body>
</html>

<div class="qr-code">
            <img src="{{ public_path('images/qr-code-swarna.png') }}" alt="QR Code">
        </div>


<!-- --------------------------Admin Dashboard------------------------ -->
@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
@include('partials.sidebar')
            <h2 class="mb-4 text-center">Swarna Metals Admin Dashboard</h2>
            <!-- Key Metrics -->
            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-success">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-users"></i> Active Employees
                            </h5>
                            <p class="card-text h4">{{ $employeesTotal }}</p>
                            <a href="{{ route('employees.index') }}" class="btn btn-light btn-sm">Manage Employees</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-primary">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-file-alt"></i> Total Applications
                            </h5>
                            <p class="card-text h4">{{ $applicationsTotal }}</p>
                            <a href="{{ route('applications.index') }}" class="btn btn-light btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-chart-line"></i> Production Rate
                            </h5>
                            <p class="card-text h4">85%</p>
                            <a href="{{ route('not-implemented-yet') }}" class="btn btn-light btn-sm">View Reports</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-warehouse"></i> Inventory
                            </h5>
                            <p class="card-text h4">1345</p>
                            <a href="{{ route('not-implemented-yet') }}" class="btn btn-light btn-sm">Track Inventory</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Monthly Production (Tons)</h5>
                            <canvas id="productionChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Production Breakdown</h5>
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Add ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Production (Tons)',
                data: [120, 150, 170, 130, 180, 200, 210],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.2)',
                fill: true
            }]
        },
        options: { responsive: true }
    });

    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'pie',
        data: {
            labels: ['Copper Cathodes', 'Copper Concentrate'],
            datasets: [{
                label: 'Revenue',
                data: [60, 40],
                backgroundColor: ['#ffcd56', '#4bc0c0'],
            }]
        },
        options: { responsive: true }
    });
</script>
@endsection

<!-- -----------------------------application store method ------------------------------------------------------------ -->
public function store(Request $request) {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email',
            'phone' => 'required|string|max:20',
            'nrc_number' => 'required|string|max:20',
            'position_applied_for' => 'required|string|max:255',
            'years_of_experience' => 'required|integer|min:0',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'certificates' => 'nullable|array',
            'certificates.*' => 'file|mimes:pdf,doc,docx|max:10240',
        ]);

        try {
            $application = Application::create(
                $request->except(['certificates', 'resume_path', 'cover_letter_path'])
            );

            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
                $application->resume_path = $resumePath;
            }

            if ($request->hasFile('cover_letter')) {
                $coverLetterPath = $request->file('cover_letter')->store('cover_letters', 'public');
                $application->cover_letter_path = $coverLetterPath;
            }

            if ($request->has('certificates')) {
                foreach ($request->file('certificates') as $file) {
                    $certificatePath = $file->store('certificates', 'public');
                    Certificate::create([
                        'application_id' => $application->id,
                        'file_path' => $certificatePath,
                    ]);
                }
            }

            $application->save();

            return redirect()->route('applications.create')->with('success', $request->first_name.' '.  $request->last_name.' '.'Your Application was submitted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'There was an error processing your application. Please try again.'])->withInput();
        }
    }


public function store(Request $request) {
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|string|email',
        'phone' => 'required|string|max:20',
        'nrc_number' => 'required|string|max:20',
        'position_applied_for' => 'required|string|max:255',
        'years_of_experience' => 'required|integer|min:0',
        'resume' => 'required|file|mimes:pdf,doc,docx|max:10240',
        'cover_letter' => 'required|file|mimes:pdf,doc,docx|max:10240',
        'certificates' => 'nullable|array',
        'certificates.*' => 'file|mimes:pdf,doc,docx|max:10240',
    ]);

    try {
        // Check for duplicate applications
        $existingApplication = Application::where('first_name', $validatedData['first_name'])
            ->where('last_name', $validatedData['last_name'])
            ->where('nrc_number', $validatedData['nrc_number'])
            ->where('position_applied_for', $validatedData['position_applied_for'])
            ->first();

        if ($existingApplication) {
            return back()->withErrors([
                'warning' => 'You have already submitted an application for this position.',
            ])->withInput();
        }

        // Create a new application
        $application = Application::create(
            $request->except(['certificates', 'resume_path', 'cover_letter_path'])
        );

        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            $application->resume_path = $resumePath;
        }

        if ($request->hasFile('cover_letter')) {
            $coverLetterPath = $request->file('cover_letter')->store('cover_letters', 'public');
            $application->cover_letter_path = $coverLetterPath;
        }

        if ($request->has('certificates')) {
            foreach ($request->file('certificates') as $file) {
                $certificatePath = $file->store('certificates', 'public');
                Certificate::create([
                    'application_id' => $application->id,
                    'file_path' => $certificatePath,
                ]);
            }
        }

        $application->save();

        return redirect()->route('applications.create')
            ->with('success', $request->first_name . ' ' . $request->last_name . ' Your application was submitted successfully.');
    } catch (\Exception $e) {
        return back()->withErrors([
            'error' => 'There was an error processing your application. Please try again.',
        ])->withInput();
    }
}


<!-- -----------------------------Index Blade Equipments------------------------------------- -->
@extends('layouts.app')

@section('title', 'Vehicles')

@section('content')
<div class="container">
    <a href="{{ route('dashboards.admin') }}" class="btn btn-secondary mb-4"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 class="mb-4">Vehicles List</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

   <a href="{{ route('equipments.create') }}" class="btn mb-3" style="background-color:#510404; color: #fff;">
        <i class="fas fa-truck"></i> Register Vehicle
    </a>
    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#reportModal">
        <i class="fas fa-file-alt"></i> Generate Report For All Vehicles
    </button>

    <!-- Search Form Later Update-->
    {{-- <form action="{{ route('vehicles.index') }}" method="GET" class="mb-3">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Reg Number, Type, or Driver" value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            <div class="col-12 col-md-2">
                <a href="{{ route('vehicles.index') }}" class="btn btn-secondary w-100">Reset</a>
            </div>
        </div>
    </form> --}}

    <!-- Dropdown to select vehicle -->
    <div class="mb-3">
        <label for="vehicleSelect" class="form-label">Select a Vehicle to Register a Trip:</label>
        <select id="vehicleSelect" class="form-select">
            <option value="">Select a Vehicle to Register a Trip</option>
            @foreach($equipments as $equipment)
                <option value="{{ $equipment->id }}">{{ $equipment->registration_number }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Registration Number</th>
                    <th>Mileage (Km)</th>
                    <th>Vehicle Type</th>
                    <th>Driver</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipments as $equipment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $equipment->registration_number }}</td>
                    <td>-</td>
                    <td>{{ $equipment->type }}</td>
                    <td>{{ $equipment->driver }}</td>
                    <td>
                        <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-info btn-sm">View</a>
                        <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Vehicle Log Form -->
{{-- <div class="modal fade" id="logTripModal" tabindex="-1" aria-labelledby="logTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logTripModalLabel">Register a Trip</h5>
                <button type="button" class="btn-close" id="btn_close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle_logs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicle_id" id="selectedVehicleId">

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="return_date" class="form-label">Return Date</label>
                            <input type="date" name="return_date" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="start_kilometers" class="form-label">Start Kilometers<span class="text-danger">*</span></label>
                            <input type="number" name="start_kilometers" class="form-control" placeholder="example: 54666" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="end_kilometers" class="form-label">Close Kilometers</label>
                            <input type="number" name="end_kilometers" class="form-control" placeholder="example: 54777">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="location" class="form-label">Location<span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" placeholder="example: Kasempa, Serenje, Ndoa, Solwezi..." required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="material_delivered" class="form-label">Material Delivered</label>
                            <input type="text" name="material_delivered" class="form-control" placeholder="example: copper ore, quary, blocks...">
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity (tonnes)</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" placeholder="example: 60, 25 ...">
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4">Fuel Information</h4>

                    <div class="mb-3">
                        <label for="litres_added" class="form-label">Litres Added (Litres) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="litres_added" class="form-control" placeholder="example: 60" required>
                    </div>

                    <div class="mb-3">
                        <label for="refuel_location" class="form-label">Refuel Location</label>
                        <input type="text" name="refuel_location" class="form-control" placeholder="example: SITE, KALULUSHI STATION, CHIMWEMWE STATION ...">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn_close" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Vehicle Trip & Fuel Log</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> --}}

<!-- Modal for Vehicle Log Form -->
<div class="modal fade" id="logTripModal" tabindex="-1" aria-labelledby="logTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logTripModalLabel">Register a Trip</h5>
                <button type="button" class="btn-close" id="btn_close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vehicle_logs.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="vehicle_id" id="selectedVehicleId">

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" class="form-control" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="return_date" class="form-label">Return Date</label>
                            <input type="date" name="return_date" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="start_kilometers" class="form-label">Start Kilometers<span class="text-danger">*</span></label>
                            <input type="number" name="start_kilometers" id="start_kilometers" class="form-control" placeholder="example: 54666" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="end_kilometers" class="form-label">Close Kilometers</label>
                            <input type="number" name="end_kilometers" class="form-control" placeholder="example: 54777">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="location" class="form-label">Location<span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control" placeholder="example: Kasempa, Serenje, Ndoa, Solwezi..." required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="material_delivered" class="form-label">Material Delivered</label>
                            <input type="text" name="material_delivered" class="form-control" placeholder="example: copper ore, quary, blocks...">
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                            </div>
                            <div class="col-md-6">
                                <label for="quantity" class="form-label">Quantity (tonnes)</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" placeholder="example: 60, 25 ...">
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4">Fuel Information</h4>

                    <div class="mb-3">
                        <label for="litres_added" class="form-label">Litres Added (Litres) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="litres_added" class="form-control" placeholder="example: 60" required>
                    </div>

                    <div class="mb-3">
                        <label for="refuel_location" class="form-label">Refuel Location</label>
                        <input type="text" name="refuel_location" class="form-control" placeholder="example: SITE, KALULUSHI STATION, CHIMWEMWE STATION ...">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn_close" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Vehicle Trip & Fuel Log</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Generate Report For all Vehicles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reports.generate_all') }}" method="POST">
                    @csrf
                    <input type="hidden" id="vehicle_id" name="vehicle_id" value="{{ $vehicle->id ?? '' }}">
                    <input type="hidden" id="format" name="format" value="csv">

                    <div class="mb-3">
                        <label for="month" class="form-label">Select Month</label>
                        <select class="form-control" id="month" name="month" required>
                            <option value="">Select Month</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Select Year</label>
                        <select class="form-control" id="year" name="year" required>
                            <option value="">Select Year</option>
                            @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                <option value="{{ $y }}">{{ $y }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Generate Report For all Vehicles</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var vehicleSelect = document.getElementById('vehicleSelect');
    var vehicleIdField = document.getElementById('selectedVehicleId');
    var modalElement = document.getElementById('logTripModal');
    var startKilometersField = document.getElementById('start_kilometers');

    if (vehicleSelect && vehicleIdField && modalElement && startKilometersField) {
        vehicleSelect.addEventListener('change', function() {
            if (this.value) {
                vehicleIdField.value = this.value;

                // Fetch the last trip's end_kilometers for the selected vehicle
                fetch(`/vehicle-logs/last-trip/${this.value}`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the start_kilometers field
                        if(data.end_kilometers != null) {
                            startKilometersField.value = data.end_kilometers;

                        } else {
                            startKilometersField.value = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching last trip details:', error);
                    });

                // Show the modal
                var modal = new bootstrap.Modal(modalElement);
                modal.show();
            }
        });

        document.getElementById('btn_close').addEventListener('click', function () {
            var modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }
});
</script>

{{-- <script>
    document.addEventListener("DOMContentLoaded", function() {
        var vehicleSelect = document.getElementById('vehicleSelect');
        var vehicleIdField = document.getElementById('selectedVehicleId');
        var modalElement = document.getElementById('logTripModal');

        if (vehicleSelect && vehicleIdField && modalElement) {
            vehicleSelect.addEventListener('change', function() {
                if (this.value) {
                    vehicleIdField.value = this.value;
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            });

            document.getElementById('btn_close').addEventListener('click', function () {
                var modalInstance = bootstrap.Modal.getInstance(modalElement);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });
        }
    });
</script> --}}

@endsection

<!-- -----------------------Equipment Show Blade -------------------------------- -->
@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div id="successMessage" class="alert alert-success" style="display: none;"></div>

    <h2 class="mb-4">
        @if ($equipment->registration_number)
            Registration Number: {{ $equipment->registration_number }} |
        @else
            Asset Code: {{ $equipment->asset_code ?? 'N/A' }} |
        @endif
        Type: {{ $equipment->type }} |
        Name: {{ $equipment->equipment_name }}
    </h2>

    <div class="d-flex flex-column flex-md-row gap-2 mb-3">
        <a href="{{ route('trips.create', $equipment->id) }}" class="btn" style="background-color:#510404; color: #fff;">
            <i class="fas fa-plus-circle"></i> Add Trip
        </a>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportModal">
            <i class="fas fa-file-alt"></i> Generate Equipment Report
        </button>
        <a href="{{ route('equipments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <h2 class="mt-4">Equipment Trips</h2>

    {{-- //This should be well implemented as a later update. Solve the conflict between the pagination and search functionality. --}}
    {{-- <form action="{{ route('equipments.show', $equipment->id) }}" method="GET" class="mb-4">
        <div class="row g-3">
            <!-- Month Filter -->
            <div class="col-12 col-md-4">
                <label for="month" class="form-label">Filter by Month</label>
                <select class="form-control" id="month" name="month" onchange="this.form.submit()">
                    <option value="">All Months</option>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Year Filter -->
            <div class="col-12 col-md-4">
                <label for="year" class="form-label">Filter by Year</label>
                <select class="form-control" id="year" name="year" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                        <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            <!-- Submit Button for Non-JS Users -->
            <div class="col-12 col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form> --}}

    @if ($equipment->trips->isEmpty())
        <div class="alert alert-warning">No trips available for this equipment.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Departure Date</th>
                        <th>Return Date</th>
                        <th>Start Km</th>
                        <th>Close Km</th>
                        <th>Distance Travelled</th>
                        <th>Location</th>
                        <th>Driver</th>
                        <th>Material Delivered</th>
                        <th>Material Qty (tonnes)</th>
                        <th>Fuel Records</th>
                        <th>Total Fuel Used</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($equipment->trips as $trip)
                        <tr>
                            <td>{{ $trip->departure_date->format('Y-m-d') }}</td>
                            <td>{{ $trip->return_date ? $trip->return_date->format('Y-m-d') : '-' }}</td>
                            <td>{{ number_format($trip->start_kilometers) }}</td>
                            <td>{{ $trip->end_kilometers ? number_format($trip->end_kilometers) : '-' }}</td>
                            <td>{{ $trip->end_kilometers && $trip->start_kilometers ? number_format($trip->end_kilometers - $trip->start_kilometers) : '-' }} km</td>
                            <td>{{ $trip->location }}</td>
                            <td>{{ $trip->driver->employee_full_name ?? '-' }}</td>
                            <td>{{ $trip->material_delivered ?? '-' }}</td>
                            <td>{{ $trip->quantity ? number_format($trip->quantity, 2) : '-' }}</td>
                            <td>
                                @if ($trip->fuels->isEmpty())
                                    <span class="text-muted">No fuel data</span>
                                @else
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($trip->fuels as $fuel)
                                            <li>{{ number_format($fuel->litres_added, 2) }} Litres at {{ $fuel->refuel_location ?? '-' }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>{{ number_format($trip->fuels->sum('litres_added'), 2) }} Litres</td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <button class="btn btn-sm btn-warning updateTripBtn" data-bs-toggle="modal" data-bs-target="#updateTripModal" data-trip-id="{{ $trip->id }}">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Update Trip Modal -->
<!-- Update Trip Modal -->
<div class="modal fade" id="updateTripModal" tabindex="-1" aria-labelledby="updateTripModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateTripModalLabel">Edit Trip Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <small>
                        <strong>Note:</strong> If the driver is not listed in the dropdown below, please ensure they are registered as an employee with the designation "Driver" in the employee management section.
                    </small>
                </div>
                <form id="updateTripForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="equipment_id" id="updateEquipmentId">
                    <input type="hidden" name="trip_id" id="updateTripId">

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                            <select name="driver_id" id="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                                <option value="">Select Driver</option>
                                @foreach (\App\Models\Employee::where('designation', 'driver')->get() as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->employee_full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                            <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                                   value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                            <input type="date" name="departure_date" id="departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                                   value="{{ old('departure_date') }}" required>
                            @error('departure_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="return_date" class="form-label">Return Date</label>
                            <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                   value="{{ old('return_date') }}">
                            @error('return_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                            <input type="number" name="start_kilometers" id="start_kilometers"
                                   class="form-control @error('start_kilometers') is-invalid @enderror"
                                   value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                            @error('start_kilometers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="end_kilometers" class="form-label">Closing Kilometers</label>
                            <input type="number" name="end_kilometers" id="end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                                   value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                            @error('end_kilometers')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12 col-md-6">
                            <label for="material_delivered" class="form-label">Material Delivered</label>
                            <input type="text" name="material_delivered" id="material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
                                   value="{{ old('material_delivered') }}" placeholder="example: copper ore, quarry, blocks...">
                            @error('material_delivered')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="quantity" class="form-label">Quantity (tonnes)</label>
                            <input type="number" step="0.01" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity') }}" placeholder="example: 60, 25 ...">
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <h4 class="mt-4">Fuel Information</h4>
                    <div id="fuel-entries">
                        <div class="fuel-entry row mb-3">
                            <div class="col-12 col-md-5">
                                <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="fuels[0][litres_added]" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                       value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                @error('fuels.0.litres_added')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-5">
                                <label for="refuel_location[]" class="form-label">Refuel Location</label>
                                <input type="text" name="fuels[0][refuel_location]" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                       value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                @error('fuels.0.refuel_location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-fuel-entry" disabled><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary mb-3" id="add-fuel-entry"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Trip & Fuel</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Equipment Report Modal -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">Generate Equipment Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reports.generate') }}" method="POST">
                    @csrf
                    <input type="hidden" id="equipment_id" name="equipment_id" value="{{ $equipment->id }}">
                    <input type="hidden" id="format" name="format" value="csv">

                    <div class="mb-3">
                        <label for="month" class="form-label">Select Month</label>
                        <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                            <option value="">Select Month</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endfor
                        </select>
                        @error('month')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Select Year</label>
                        <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                            <option value="">Select Year</option>
                            @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                        @error('year')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Generate Report</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for dynamic fuel entries and AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    var fuelEntryCount = 1;

    // Populate form and existing fuel entries on modal open
    $('.updateTripBtn').on('click', function () {
        let tripId = $(this).data('trip-id');
        $('#updateTripId').val(tripId);

        // Fetch trip data via AJAX
        $.ajax({
            url: `/trips/${tripId}/edit`,
            type: "GET",
            success: function (response) {
                $('#driver_id').val(response.driver_id);
                $('#location').val(response.location);
                $('#departure_date').val(response.departure_date);
                $('#return_date').val(response.return_date);
                $('#start_kilometers').val(response.start_kilometers);
                $('#end_kilometers').val(response.end_kilometers);
                $('#material_delivered').val(response.material_delivered);
                $('#quantity').val(response.quantity);
                $('#updateEquipmentId').val(response.equipment_id);

                // Populate existing fuel entries
                $('#fuel-entries').empty();
                fuelEntryCount = 0;
                response.fuels.forEach(function(fuel) {
                    addFuelEntry(fuel.litres_added, fuel.refuel_location, fuel.id, fuelEntryCount);
                    fuelEntryCount++;
                });
                updateRemoveButtons();
            },
            error: function () {
                $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                    .text('Failed to fetch trip data.').fadeIn();
            }
        });
    });

    // Add new fuel entry
    $('#add-fuel-entry').on('click', function() {
        addFuelEntry('', '', null, fuelEntryCount);
        fuelEntryCount++;
        updateRemoveButtons();
    });

    // Remove fuel entry
    $('#fuel-entries').on('click', '.remove-fuel-entry', function() {
        $(this).closest('.fuel-entry').remove();
        updateRemoveButtons();
    });

    function addFuelEntry(litresAdded, refuelLocation, fuelId, index) {
        var fuelEntryHtml = `
            <div class="fuel-entry row mb-3">
                <div class="col-12 col-md-5">
                    <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="fuels[${index}][litres_added]" class="form-control" value="${litresAdded || ''}" placeholder="example: 60" required>
                </div>
                <div class="col-12 col-md-5">
                    <label for="refuel_location[]" class="form-label">Refuel Location</label>
                    <input type="text" name="fuels[${index}][refuel_location]" class="form-control" value="${refuelLocation || ''}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                </div>
                <div class="col-12 col-md-2 d-flex align-items-end">
                    <input type="hidden" name="fuels[${index}][id]" value="${fuelId || ''}">
                    <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                </div>
            </div>
        `;
        $('#fuel-entries').append(fuelEntryHtml);
    }

    function updateRemoveButtons() {
        var entries = $('#fuel-entries .fuel-entry');
        var removeButtons = $('#fuel-entries .remove-fuel-entry');
        removeButtons.prop('disabled', entries.length === 1);
    }

    // Form submission
    $('#updateTripForm').on('submit', function (e) {
        e.preventDefault();
        let tripId = $('#updateTripId').val();
        let formData = $(this).serializeArray();
        let jsonData = {};

        $.each(formData, function () {
            if (this.name.includes('[')) {
                let [mainKey, index, subKey] = this.name.match(/([^[\]]+)/g);
                if (!jsonData[mainKey]) jsonData[mainKey] = [];
                if (!jsonData[mainKey][index]) jsonData[mainKey][index] = {};
                jsonData[mainKey][index][subKey] = this.value || null;
            } else {
                jsonData[this.name] = this.value || null;
            }
        });

        $.ajax({
            url: `/trips/${tripId}`,
            type: "PUT",
            data: JSON.stringify(jsonData),
            contentType: "application/json",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#successMessage').text(response.message).fadeIn().delay(3000).fadeOut();
                    $('#updateTripModal').modal('hide');
                    setTimeout(() => location.reload(), 3000);
                } else {
                    $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                        .text('Failed to update trip.').fadeIn();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '<ul>';
                    $.each(errors, function (key, messages) {
                        errorMessage += `<li>${messages[0]}</li>`;
                    });
                    errorMessage += '</ul>';
                    $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                        .html(errorMessage).fadeIn();
                } else {
                    $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                        .text('An error occurred. Please try again.').fadeIn();
                }
            }
        });
    });
});
</script>
@endsection






---------------------Equipment Show blade --------------------
@extends('layouts.app')

@section('title', 'Equipment Details')

@section('content')
<div class="container">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div id="successMessage" class="alert alert-success" style="display: none;"></div>
    <!-- Equipment Header -->
    <h2 class="mb-4">
        Equipment Details:
        @if ($equipment->registration_number)
            Registration Number: {{ $equipment->registration_number }}
        @else
            Asset Code: {{ $equipment->asset_code ?? 'N/A' }}
        @endif
    </h2>

    <!-- Action Buttons -->
    <div class="d-flex flex-column flex-md-row gap-2 mb-4">
        <a href="{{ route('equipments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Equipments
        </a>
        <button type="button" class="btn add-trip-btn" style="background-color:#510404; color: #fff;" data-bs-toggle="modal" data-bs-target="#addTripModal" data-equipment-id="{{ $equipment->id }}">
            <i class="fas fa-plus-circle"></i> Add Trip
        </button>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#reportModal">
            <i class="fas fa-file-alt"></i> Generate Equipment Report
        </button>
    </div>

    <!-- Tabs for Equipment Details -->
    <ul class="nav nav-tabs" id="equipmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab" aria-controls="details" aria-selected="true">Details</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="trips-tab" data-bs-toggle="tab" data-bs-target="#trips" type="button" role="tab" aria-controls="trips" aria-selected="false">Trips</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="spares-tab" data-bs-toggle="tab" data-bs-target="#spares" type="button" role="tab" aria-controls="spares" aria-selected="false">Spares</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="insurances-tab" data-bs-toggle="tab" data-bs-target="#insurances" type="button" role="tab" aria-controls="insurances" aria-selected="false">Insurances</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="taxes-tab" data-bs-toggle="tab" data-bs-target="#taxes" type="button" role="tab" aria-controls="taxes" aria-selected="false">Taxes</button>
        </li>
    </ul>

    <div class="tab-content" id="equipmentTabsContent">
        <!-- Equipment Details Tab -->
        <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Equipment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Asset Code:</strong> {{ $equipment->asset_code ?? 'N/A' }}</p>
                            <p><strong>Registration Number:</strong> {{ $equipment->registration_number ?? 'N/A' }}</p>
                            <p><strong>Chassis Number:</strong> {{ $equipment->chasis_number ?? 'N/A' }}</p>
                            <p><strong>Engine Number:</strong> {{ $equipment->engine_number ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Type:</strong> {{ $equipment->type }}</p>
                            <p><strong>Equipment Name:</strong> {{ $equipment->equipment_name }}</p>
                            <p><strong>Ownership:</strong> {{ $equipment->ownership }}</p>
                            <p><strong>Year Purchased:</strong> {{ $equipment->date_purchased->format('Y-m-d') }}</p>
                            <p><strong>Value (USD):</strong> {{ number_format($equipment->value, 2) }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <h6>Pictures</h6>
                        @php
                            $pictures = is_array($equipment->pictures) ? $equipment->pictures : json_decode($equipment->pictures, true);
                        @endphp
                        @if (!empty($pictures) && is_array($pictures))
                            <div class="row">
                                @foreach ($pictures as $picture)
                                    <div class="col-md-3 mb-3">
                                        <img src="{{ asset('storage/' . $picture) }}" alt="Equipment Picture" class="img-thumbnail" style="max-width: 100%; height: auto;">
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No pictures available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Trips Tab -->
        <div class="tab-pane fade" id="trips" role="tabpanel" aria-labelledby="trips-tab">
            <div class="card mt-3">
                <div class="card-header " style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Trips</h5>
                </div>
                <div class="card-body">
                    @if ($equipment->trips->isEmpty())
                        <div class="alert alert-warning">No trips available for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Departure Date</th>
                                        <th>Return Date</th>
                                        <th>Start Km</th>
                                        <th>Close Km</th>
                                        <th>Distance Travelled</th>
                                        <th>Location</th>
                                        <th>Driver</th>
                                        <th>Material Delivered</th>
                                        <th>Qty (tonnes)</th>
                                        <th>Fuel Records</th>
                                        <th>Total Fuel Used</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->trips as $trip)
                                        <tr>
                                            <td>{{ $trip->departure_date->format('Y-m-d') }}</td>
                                            <td>{{ $trip->return_date ? $trip->return_date->format('Y-m-d') : '-' }}</td>
                                            <td>{{ number_format($trip->start_kilometers) }}</td>
                                            <td>{{ $trip->end_kilometers ? number_format($trip->end_kilometers) : '-' }}</td>
                                            <td>{{ $trip->end_kilometers && $trip->start_kilometers ? number_format($trip->end_kilometers - $trip->start_kilometers) : '-' }} km</td>
                                            <td>{{ $trip->location }}</td>
                                            <td>{{ $trip->driver->employee_full_name ?? '-' }}</td>
                                            <td>{{ $trip->material_delivered ?? '-' }}</td>
                                            <td>{{ $trip->quantity ? number_format($trip->quantity, 2) : '-' }}</td>
                                            <td>
                                                @if ($trip->fuels->isEmpty())
                                                    <span class="text-muted">No fuel data</span>
                                                @else
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach ($trip->fuels as $fuel)
                                                            <li>{{ number_format($fuel->litres_added, 2) }} Litres at {{ $fuel->refuel_location ?? '-' }}</li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </td>
                                            <td>{{ number_format($trip->fuels->sum('litres_added'), 2) }} Litres</td>
                                            <td>
                                                <button class="btn btn-sm btn-warning updateTripBtn" data-bs-toggle="modal" data-bs-target="#updateTripModal" data-trip-id="{{ $trip->id }}">
                                                    <i class="fas fa-edit"></i> Update
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Spares Tab -->
        <div class="tab-pane fade" id="spares" role="tabpanel" aria-labelledby="spares-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Spare Parts</h5>
                </div>
                <div class="card-body">
                    @if ($equipment->spares->isEmpty())
                        <div class="alert alert-warning">No spare parts recorded for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Price (USD)</th>
                                        <th>Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->spares as $spare)
                                        <tr>
                                            <td>{{ $spare->name }}</td>
                                            <td>{{ number_format($spare->price, 2) }}</td>
                                            <td>{{ number_format($spare->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Insurances Tab -->
        <div class="tab-pane fade" id="insurances" role="tabpanel" aria-labelledby="insurances-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Insurances</h5>
                </div>
                <div class="card-body">
                    @if ($equipment->equipmentInsurances->isEmpty())
                        <div class="alert alert-warning">No insurance records for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Insurance Company</th>
                                        <th>Premium (USD)</th>
                                        <th>Phone Number</th>
                                        <th>Address</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->equipmentInsurances as $insurance)
                                        <tr>
                                            <td>{{ $insurance->insurance_company }}</td>
                                            <td>{{ number_format($insurance->premium, 2) }}</td>
                                            <td>{{ $insurance->phone_number ?? 'N/A' }}</td>
                                            <td>{{ $insurance->address ?? 'N/A' }}</td>
                                            <td>{{ $insurance->expiry_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Taxes Tab -->
        <div class="tab-pane fade" id="taxes" role="tabpanel" aria-labelledby="taxes-tab">
            <div class="card mt-3">
                <div class="card-header" style="background-color:#510404; color: #fff;">
                    <h5 class="mb-0">Taxes</h5>
                </div>
                <div class="card-body">
                    @if ($equipment->equipmentTaxes->isEmpty())
                        <div class="alert alert-warning">No tax records for this equipment.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Cost (USD)</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($equipment->equipmentTaxes as $tax)
                                        <tr>
                                            <td>{{ $tax->name }}</td>
                                            <td>{{ number_format($tax->cost, 2) }}</td>
                                            <td>{{ $tax->expiry_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

     <!-- Add Trip Modal -->
    <div class="modal fade" id="addTripModal" tabindex="-1" aria-labelledby="addTripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTripModalLabel">Add New Trip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <small>
                            <strong>Note:</strong> If the driver is not listed in the dropdown below, please ensure they are registered as an employee with the designation "Driver" in the employee management section.
                        </small>
                    </div>
                    <form id="addTripForm" action="{{ route('trips.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" id="addEquipmentId" value="{{ $equipment->id }}">

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                <select name="driver_id" id="add_driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\Employee::where('designation', 'driver')->get() as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->employee_full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="add_location" class="form-control @error('location') is-invalid @enderror"
                                       value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                                <input type="date" name="departure_date" id="add_departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                                       value="{{ old('departure_date') }}" required>
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_return_date" class="form-label">Return Date</label>
                                <input type="date" name="return_date" id="add_return_date" class="form-control @error('return_date') is-invalid @enderror"
                                       value="{{ old('return_date') }}">
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                                <input type="number" name="start_kilometers" id="add_start_kilometers"
                                       class="form-control @error('start_kilometers') is-invalid @enderror"
                                       value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                                @error('start_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_end_kilometers" class="form-label">Closing Kilometers</label>
                                <input type="number" name="end_kilometers" id="add_end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                                       value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                                @error('end_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="add_material_delivered" class="form-label">Material Delivered</label>
                                <input type="text" name="material_delivered" id="add_material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
                                       value="{{ old('material_delivered') }}" placeholder="example: copper ore, quarry, blocks...">
                                @error('material_delivered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="add_quantity" class="form-label">Quantity (tonnes)</label>
                                <input type="number" step="0.01" name="quantity" id="add_quantity" class="form-control @error('quantity') is-invalid @enderror"
                                       value="{{ old('quantity') }}" placeholder="example: 60, 25 ...">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="add-fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="add_litres_added_0" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" id="add_litres_added_0" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                           value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="add_refuel_location_0" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" id="add_refuel_location_0" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                           value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                    @error('fuels.0.refuel_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-fuel-entry" disabled><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" id="add-fuel-entry"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Trip & Fuel</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Trip Modal -->
    <div class="modal fade" id="updateTripModal" tabindex="-1" aria-labelledby="updateTripModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTripModalLabel">Edit Trip Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning mb-3">
                        <small>
                            <strong>Note:</strong> If the driver is not listed in the dropdown below, please ensure they are registered as an employee with the designation "Driver" in the employee management section.
                        </small>
                    </div>
                    <form id="updateTripForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="equipment_id" id="updateEquipmentId">
                        <input type="hidden" name="trip_id" id="updateTripId">

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="driver_id" class="form-label">Driver <span class="text-danger">*</span></label>
                                <select name="driver_id" id="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                                    <option value="">Select Driver</option>
                                    @foreach (\App\Models\Employee::where('designation', 'driver')->get() as $driver)
                                        <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                            {{ $driver->employee_full_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('driver_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="example: Kasempa, Serenje, Ndola, Solwezi..." required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="departure_date" class="form-label">Departure Date <span class="text-danger">*</span></label>
                                <input type="date" name="departure_date" id="departure_date" class="form-control @error('departure_date') is-invalid @enderror"
                                    value="{{ old('departure_date') }}" required>
                                @error('departure_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="return_date" class="form-label">Return Date</label>
                                <input type="date" name="return_date" id="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date') }}">
                                @error('return_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="start_kilometers" class="form-label">Start Kilometers <span class="text-danger">*</span></label>
                                <input type="number" name="start_kilometers" id="start_kilometers"
                                    class="form-control @error('start_kilometers') is-invalid @enderror"
                                    value="{{ old('start_kilometers') }}" placeholder="example: 54666" required>
                                @error('start_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="end_kilometers" class="form-label">Closing Kilometers</label>
                                <input type="number" name="end_kilometers" id="end_kilometers" class="form-control @error('end_kilometers') is-invalid @enderror"
                                    value="{{ old('end_kilometers') }}" placeholder="example: 54777">
                                @error('end_kilometers')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12 col-md-6">
                                <label for="material_delivered" class="form-label">Material Delivered</label>
                                <input type="text" name="material_delivered" id="material_delivered" class="form-control @error('material_delivered') is-invalid @enderror"
                                    value="{{ old('material_delivered') }}" placeholder="example: copper ore, quarry, blocks...">
                                @error('material_delivered')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="quantity" class="form-label">Quantity (tonnes)</label>
                                <input type="number" step="0.01" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" placeholder="example: 60, 25 ...">
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <h4 class="mt-4">Fuel Information</h4>
                        <div id="fuel-entries">
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="litres_added[]" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[0][litres_added]" class="form-control @error('fuels.0.litres_added') is-invalid @enderror"
                                        value="{{ old('fuels.0.litres_added') }}" placeholder="example: 60" required>
                                    @error('fuels.0.litres_added')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="refuel_location[]" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[0][refuel_location]" class="form-control @error('fuels.0.refuel_location') is-invalid @enderror"
                                        value="{{ old('fuels.0.refuel_location') }}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                    @error('fuels.0.refuel_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <button type="button" class="btn btn-danger remove-fuel-entry" disabled><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mb-3" id="add-fuel-entry"><i class="fas fa-plus"></i> Add Another Fuel Entry</button>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Update Trip & Fuel</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Equipment Report Modal -->
    {{-- <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generate Equipment Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" id="equipment_id" name="equipment_id" value="{{ $equipment->id }}">
                        <input type="hidden" id="format" name="format" value="csv">

                        <div class="mb-3">
                            <label for="month" class="form-label">Select Month</label>
                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                <option value="">Select Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Select Year</label>
                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Select Year</option>
                                @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                    <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Generate Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Equipment Report Modal -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reportModalLabel">Generate Equipment Report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm" action="{{ route('reports.generate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="equipment_id" value="{{ $equipment->id }}">

                        <div class="mb-3">
                            <label for="month" class="form-label">Select Month <span class="text-danger">*</span></label>
                            <select class="form-control @error('month') is-invalid @enderror" id="month" name="month" required>
                                <option value="">Select Month</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ old('month') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endfor
                            </select>
                            @error('month')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="year" class="form-label">Select Year <span class="text-danger">*</span></label>
                            <select class="form-control @error('year') is-invalid @enderror" id="year" name="year" required>
                                <option value="">Select Year</option>
                                @for ($y = date('Y'); $y >= date('Y') - 10; $y--)
                                    <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                            @error('year')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="format" class="form-label">Report Format <span class="text-danger">*</span></label>
                            <select class="form-control @error('format') is-invalid @enderror" id="format" name="format" required>
                                <option value="">Select Format</option>
                                <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                                {{-- later update pdf format --}}
                                {{-- <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option> --}}
                            </select>
                            @error('format')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fas fa-download"></i> Generate Report</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for AJAX and dynamic fuel entries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    var fuelEntryCount = 1; // Global scope

    document.addEventListener("DOMContentLoaded", function() {
        var addTripButton = document.querySelector('.add-trip-btn');
        var equipmentIdField = document.getElementById('addEquipmentId');
        var modalElement = document.getElementById('addTripModal');
        var startKilometersField = document.getElementById('add_start_kilometers');
        var fuelEntriesContainer = document.getElementById('add-fuel-entries');
        var addFuelEntryButton = document.getElementById('add-fuel-entry');

        if (addTripButton && equipmentIdField && modalElement && startKilometersField) {
            addTripButton.addEventListener('click', function() {
                var equipmentId = this.getAttribute('data-equipment-id');
                equipmentIdField.value = equipmentId;

                fetch(`/trips/last-trip/${equipmentId}`)
                    .then(response => response.json())
                    .then(data => {
                        startKilometersField.value = data.end_kilometers !== null ? data.end_kilometers : (data.start_kilometers || 0);
                    })
                    .catch(error => {
                        console.error('Error fetching last trip details:', error);
                        startKilometersField.value = 0;
                    });
            });
        }

        addFuelEntryButton.addEventListener('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-5">
                        <label for="add_litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="add_litres_added_${fuelEntryCount}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="add_refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="add_refuel_location_${fuelEntryCount}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            fuelEntriesContainer.insertAdjacentHTML('beforeend', newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        fuelEntriesContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-fuel-entry') || e.target.parentElement.classList.contains('remove-fuel-entry')) {
                e.target.closest('.fuel-entry').remove();
                updateRemoveButtons();
            }
        });

        function updateRemoveButtons() {
            var entries = fuelEntriesContainer.getElementsByClassName('fuel-entry');
            var removeButtons = fuelEntriesContainer.getElementsByClassName('remove-fuel-entry');
            for (var i = 0; i < removeButtons.length; i++) {
                removeButtons[i].disabled = (entries.length === 1);
            }
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.updateTripBtn', function() {
            let tripId = $(this).data('trip-id');
            $('#updateTripId').val(tripId);

            console.log('Fetching trip data for ID:', tripId);

            $.ajax({
                url: `/trips/${tripId}/edit`,
                type: "GET",
                success: function(response) {
                    console.log('AJAX Success:', response);
                    $('#driver_id').val(response.driver_id);
                    $('#location').val(response.location);
                    $('#departure_date').val(response.departure_date);
                    $('#return_date').val(response.return_date);
                    $('#start_kilometers').val(response.start_kilometers);
                    $('#end_kilometers').val(response.end_kilometers);
                    $('#material_delivered').val(response.material_delivered);
                    $('#quantity').val(response.quantity);
                    $('#updateEquipmentId').val(response.equipment_id);

                    $('#fuel-entries').empty();
                    fuelEntryCount = 0;
                    response.fuels.forEach(function(fuel) {
                        var newEntry = `
                            <div class="fuel-entry row mb-3">
                                <div class="col-12 col-md-5">
                                    <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" value="${fuel.litres_added || ''}" placeholder="example: 60" required>
                                </div>
                                <div class="col-12 col-md-5">
                                    <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                                    <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" value="${fuel.refuel_location || ''}" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                                </div>
                                <div class="col-12 col-md-2 d-flex align-items-end">
                                    <input type="hidden" name="fuels[${fuelEntryCount}][id]" value="${fuel.id || ''}">
                                    <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                        $('#fuel-entries').append(newEntry);
                        fuelEntryCount++;
                    });
                    updateRemoveButtons();
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.status, xhr.responseText);
                    $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                        .text('Failed to fetch trip data.').fadeIn();
                }
            });
        });

        $('#add-fuel-entry').on('click', function() {
            var newEntry = `
                <div class="fuel-entry row mb-3">
                    <div class="col-12 col-md-5">
                        <label for="litres_added_${fuelEntryCount}" class="form-label">Litres Added <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="fuels[${fuelEntryCount}][litres_added]" id="litres_added_${fuelEntryCount}" class="form-control" placeholder="example: 60" required>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="refuel_location_${fuelEntryCount}" class="form-label">Refuel Location</label>
                        <input type="text" name="fuels[${fuelEntryCount}][refuel_location]" id="refuel_location_${fuelEntryCount}" class="form-control" placeholder="example: Site, Chimwemwe Meru Station, Kalulushi Meru Station">
                    </div>
                    <div class="col-12 col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger remove-fuel-entry"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            `;
            $('#fuel-entries').append(newEntry);
            fuelEntryCount++;
            updateRemoveButtons();
        });

        $('#fuel-entries').on('click', '.remove-fuel-entry', function() {
            $(this).closest('.fuel-entry').remove();
            updateRemoveButtons();
        });

        function updateRemoveButtons() {
            var entries = $('#fuel-entries .fuel-entry');
            var removeButtons = $('#fuel-entries .remove-fuel-entry');
            removeButtons.prop('disabled', entries.length === 1);
        }

        $('#updateTripForm').on('submit', function(e) {
            e.preventDefault();
            let tripId = $('#updateTripId').val();
            let formData = $(this).serializeArray();
            let jsonData = {};

            $.each(formData, function() {
                if (this.name.includes('[')) {
                    let [mainKey, index, subKey] = this.name.match(/([^[\]]+)/g);
                    if (!jsonData[mainKey]) jsonData[mainKey] = [];
                    if (!jsonData[mainKey][index]) jsonData[mainKey][index] = {};
                    jsonData[mainKey][index][subKey] = this.value || null;
                } else {
                    jsonData[this.name] = this.value || null;
                }
            });

            $.ajax({
                url: `/trips/${tripId}`,
                type: "PUT",
                data: JSON.stringify(jsonData),
                contentType: "application/json",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('#successMessage').text(response.message).fadeIn().delay(3000).fadeOut();
                        $('#updateTripModal').modal('hide');
                        setTimeout(() => location.reload(), 3000);
                    } else {
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .text('Failed to update trip.').fadeIn();
                    }
                },
                error: function(xhr) {
                    console.error('Submit Error:', xhr.status, xhr.responseText);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMessage = '<ul>';
                        $.each(errors, function(key, messages) {
                            errorMessage += `<li>${messages[0]}</li>`;
                        });
                        errorMessage += '</ul>';
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .html(errorMessage).fadeIn();
                    } else {
                        $('#successMessage').removeClass('alert-success').addClass('alert-danger')
                            .text('An error occurred. Please try again.').fadeIn();
                    }
                }
            });
        });
    });
</script>

@endsection



<!-- ------------------------------Payslip Structure------------------------------ -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Employee Payslip</title>
        <style>
            body {
                font-family: sans-serif;
                background-image: url('{{ public_path('images/logo_circle.jpg') }}');
                background-repeat: no-repeat;
                background-position: center;
                background-size: 500px;
                opacity: 1;
            }

            .payslip {
                padding: 10px;
                margin: 10px auto;
                max-width: 700px;
                background: rgba(255, 255, 255, 0.78);
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .center-text {
                text-align: center;
            }

            .logo {
                display: block;
                margin: 0 auto 10px;
                max-width: 80px;
                height: auto;
            }

            .company-header {
                text-align: center;
                margin-bottom: 15px;
            }

            .company-header h1 {
                margin: 0;
                color: #510404;
                font-size: 30px;
                font-weight: bold;
            }

            .payslip table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 10px;
            }

            .payslip th, .payslip td {
                padding: 4px;
                border: 1px solid #000000;
                font-size: 12px;
                text-align: left;
            }

            .payslip .bold {
                font-weight: bold;
            }

            .payslip .right {
                text-align: right;
            }

            .payslip p {
                font-size: 11px;
            }

            .payslip h4 {
                margin-bottom: 0px;
            }
        </style>
    </head>
    <body>
        <div class="payslip">
            <div class="center-text">
                <img src="{{ public_path('images/logo_circle.jpg') }}" alt="Swarna Metals Logo" class="logo">
            </div>

            <div class="company-header">
                <h1>SWARNA METALS ZAMBIA LIMITED</h1>
            </div>

            <div class="title">
                <h4><strong>Pay Slip</strong></h4>
            </div>

            <!-- Employee Information -->
            <table>
                <tr>
                    <th>EMP NR:</th>
                    <td>{{ $employee->employee_id }}</td>
                    <th>PAY POINT:</th>
                    <td>KITWE</td>
                </tr>
                <tr>
                    <th>EMP NAME:</th>
                    <td>{{ $employee->employee_full_name }}</td>
                    <th>PAY GRADE:</th>
                    <td>{{ $employee->grade }}</td>
                </tr>
                <tr>
                    <th>KNOWN AS:</th>
                    <td>{{ $employee->known_as }}</td>
                    <th>BASIC RATE:</th>
                    <td>{{ number_format($employee->basic_salary, 2) }} ZMW</td>
                </tr>
                <tr>
                    <th>DATE ENGAGED:</th>
                    <td>{{ $employee->date_of_joining }}</td>
                    <th>PLANT SITE:</th>
                    <td>SALAMANO</td>
                </tr>
                <tr>
                    <th>ACCOUNT NO:</th>
                    <td>{{ $employee->bank_account_number }}</td>
                    <th>TERM DATE:</th>
                    <td>{{ $employee->term_date ?? '-' }}</td>
                </tr>
            </table>

            <!-- Earnings and Deductions -->
            <h4>Earnings</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Units</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Basic Pay</td>
                        <td>23</td>
                        <td>{{ number_format($employee->basic_salary, 2) }}</td>
                    </tr>
                    <tr>
                        <td>Leave Days</td>
                        <td>2</td>
                        <td>{{ number_format($payslipData['leave_value'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Housing Allowance</td>
                        <td></td>
                        <td>{{ number_format($payslipData['housing_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Overtime</td>
                        <td>16</td>
                        <td>{{ number_format($payslipData['overtime_pay'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Transport</td>
                        <td></td>
                        <td>{{ number_format($payslipData['transport_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Lunch</td>
                        <td></td>
                        <td>{{ number_format($payslipData['lunch_allowance'], 2) }}</td>
                    </tr>
                    <tr>
                        <th colspan="2">Total Earnings</th>
                        <th>{{ number_format($payslipData['total_earnings'], 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <h4>Deductions</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>TAX RATE</td>
                        <td>{{ number_format($payslipData['tax_rate'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>NAPSA</td>
                        <td>{{ number_format($payslipData['napsa'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>NHIMA</td>
                        <td>{{ number_format($payslipData['nhima'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>ADVANCE</td>
                        <td>{{ number_format($payslipData['advance'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>UMUZ FEE</td>
                        <td>{{ number_format($payslipData['umuz_fee'], 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Deductions</th>
                        <th>{{ number_format($payslipData['total_deductions'], 2) }}</th>
                    </tr>
                </tbody>
            </table>

            <!-- Net Pay -->
            <p><strong>Net Pay:</strong> {{ number_format($payslipData['net_pay'], 2) }} ZMW</p>

            <!-- Year-to-Date Totals -->
            <h4>Year-to-Date Totals</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount (ZMW)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Tax Paid</td>
                        <td>{{ number_format($payslipData['tax_paid_ytd'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Taxable Earnings</td>
                        <td>{{ number_format($payslipData['taxable_earnings_ytd'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Annual Leave Due</td>
                        <td>{{ number_format($payslipData['annual_leave_due'], 2) }}</td>
                    </tr>
                    <tr>
                        <td>Leave Value</td>
                        <td>{{ number_format($payslipData['leave_value_ytd'], 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Additional Information -->
            <h4>Additional Information</h4>
            <p><strong>Prepared By:</strong> ............................................................................................................................   <strong>Date:</strong> .............................................</p>
        </div>
    </body>
</html>

<!-- --------------------------Equipment index table ----------------------------------------- -->
<div class="table-responsive">
        <table class="table table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    {{-- <th>Asset Code</th> --}}
                    <th>Registration Number</th>
                    <th>Equipment Name</th>
                    <th>Type</th>
                    {{-- <th>Value (USD)</th> --}}
                    <th>Mileage (Km) /Hours</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($equipments as $equipment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    {{-- <td>{{ $equipment->asset_code ?? 'N/A' }}</td> --}}
                    <td>{{ $equipment->registration_number ?? 'N/A' }}</td>
                    <td>{{ $equipment->equipment_name }}</td>
                    <td>{{ $equipment->type }}</td>
                    {{-- <td>{{ number_format($equipment->value, 2) }}</td> --}}
                    <td>
                        @if($equipment->trips->last())
                            {{ number_format($equipment->trips->last()->end_kilometers ?? $equipment->trips->last()->start_kilometers, 0, '.', ',') }} Km
                        @elseif ($equipment->machineryUsages->last())
                            {{ number_format($equipment->machineryUsages->last()->closing_hours ?? $equipment->machineryUsages->last()->start_hours, 0, '.', ',') }} Hours
                        @else
                         -
                        @endif
                    </td>
                    <td>
                        @if ($equipment->status == 'Running')
                            <div class="btn btn-sm" style="background-color: #28a745; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                        @elseif ($equipment->status == 'Under Maintenance')
                            <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                        @elseif ($equipment->status == 'Broken Down')
                            <div class="btn btn-sm" style="background-color: #ffc107; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                        @elseif ($equipment->status == 'Accident')
                            <div class="btn btn-sm" style="background-color: #dc3545; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status }}</div>
                        @else
                            <div class="btn btn-sm" style="background-color: #6c757d; color: white; border-radius: 4px; padding: 0.25rem 0.5rem;">{{ $equipment->status ?? 'N/A' }}</div>
                        @endif
                    </td>
                    <td class="text-nowrap">
                        <div class="d-flex flex-column flex-md-row gap-1">
                            <a href="{{ route('equipments.show', $equipment) }}" class="btn btn-info btn-sm mr-1">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('equipments.edit', $equipment) }}" class="btn btn-warning btn-sm mt-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">No Equipment found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>


<!-- ----------------------------------Equipment Report PD------------------------------------
 -->
 <!DOCTYPE html>
<html>
<head>
    <title>Equipment Report</title>
</head>
<body>
    <h1>{{ $equipment->type }} Report for {{ $equipment->equipment_name }}</h1>
    <h3>Period: {{ $startDate }} to {{ $endDate }}</h3>

    @if ($dataType === 'machinery')
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Start Hours</th>
                    <th>Closing Hours</th>
                    <th>Hours Used</th>
                    <th>Location</th>
                    <th>Operator</th>
                    <th>Fuel Logs</th>
                    <th>Total Fuel Used</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->date->format('Y-m-d') }}</td>
                        <td>{{ $item->start_hours ?? '-' }}</td>
                        <td>{{ $item->closing_hours ?? '-' }}</td>
                        <td>{{ $item->closing_hours && $item->start_hours ? number_format($item->closing_hours - $item->start_hours, 2) : '-' }}</td>
                        <td>{{ $item->location }}</td>
                        <td>{{ $item->operator->employee_full_name ?? '-' }}</td>
                        <td>{{ $item->fuels->map(fn($f) => "{$f->litres_added}L at {$f->refuel_location}")->implode(' | ') ?: 'No fuel data' }}</td>
                        <td>{{ number_format($item->fuels->sum('litres_added'), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <!-- Existing trip table structure -->
        <table border="1">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Departure Date</th>
                    <th>Return Date</th>
                    <th>Start Km</th>
                    <th>Close Km</th>
                    <th>Distance</th>
                    <th>Location</th>
                    <th>Driver</th>
                    <th>Material</th>
                    <th>Supplier</th>
                    <th>Gross Wt</th>
                    <th>Tare Wt</th>
                    <th>Net Wt</th>
                    <th>Loading</th>
                    <th>Council</th>
                    <th>Weighbridge</th>
                    <th>Toll Gate</th>
                    <th>Other Exp</th>
                    <th>Fuel Logs</th>
                    <th>Total Fuel</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->departure_date->format('Y-m-d') }}</td>
                        <td>{{ $item->return_date ? $item->return_date->format('Y-m-d') : '-' }}</td>
                        <td>{{ number_format($item->start_kilometers) }}</td>
                        <td>{{ $item->end_kilometers ? number_format($item->end_kilometers) : '-' }}</td>
                        <td>{{ $item->end_kilometers && $item->start_kilometers ? number_format($item->end_kilometers - $item->start_kilometers) : '-' }}</td>
                        <td>{{ $item->location }}</td>
                        <td>{{ $item->driver->employee_full_name ?? '-' }}</td>
                        <td>{{ $item->material_delivered ?? '-' }}</td>
                        <td>{{ $item->supplier_name ?? '-' }}</td>
                        <td>{{ $item->gross_weight ? number_format($item->gross_weight, 2) : '-' }}</td>
                        <td>{{ $item->tare_weight ? number_format($item->tare_weight, 2) : '-' }}</td>
                        <td>{{ $item->net_weight ? number_format($item->net_weight, 2) : '-' }}</td>
                        <td>{{ $item->loading ? number_format($item->loading, 2) : '-' }}</td>
                        <td>{{ $item->council_fee ? number_format($item->council_fee, 2) : '-' }}</td>
                        <td>{{ $item->weighbridge ? number_format($item->weighbridge, 2) : '-' }}</td>
                        <td>{{ $item->toll_gate ? number_format($item->toll_gate, 2) : '-' }}</td>
                        <td>{{ $item->other_expenses ? number_format($item->other_expenses, 2) : '-' }}</td>
                        <td>{{ $item->fuels->map(fn($f) => "{$f->litres_added}L at {$f->refuel_location}")->implode(' | ') ?: 'No fuel data' }}</td>
                        <td>{{ number_format($item->fuels->sum('litres_added'), 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <!-- Associated costs and summary sections can remain largely the same, adjusted for $dataType -->
</body>
</html>

//you can watch a tutorial on youtube the basics and how to connect it to the database (MySQL)
