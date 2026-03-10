@extends('website-layouts.app')

@section('title', 'About Us')

@section('content')
<style>

    header h1 {
        font-size: 45px;
        color: #ff5733;
        font-weight: 800;
    }

    /* Animations (match other pages) */
    @keyframes floatIn {
        0%{opacity:0;transform:translateY(40px)}
        100%{opacity:1;transform:translateY(0)}
    }
    @keyframes slideInLeft {
        0%{opacity:0;transform:translateX(-40px)}
        100%{opacity:1;transform:translateX(0)}
    }
    @keyframes slideInRight {
        0%{opacity:0;transform:translateX(40px)}
        100%{opacity:1;transform:translateX(0)}
    }

    .animate-float-in{animation:floatIn .8s ease-out forwards;opacity:0}
    .animate-slide-left{animation:slideInLeft .7s ease-out forwards;opacity:0}
    .animate-slide-right{animation:slideInRight .7s ease-out forwards;opacity:0}

    /* Stagger helpers */
    .delay-0{animation-delay:.12s}
    .delay-1{animation-delay:.28s}
    .delay-2{animation-delay:.44s}
    .delay-3{animation-delay:.6s}

    h2,h3 {
        font-size: 1.8rem;
        color:  #ff5733;
        border-bottom: 3px solid #444;
        display: inline-block;
        padding-bottom: 5px;
    }

    .row p {
        font-size: 20px;
    }

    .row ul li {
        font-size: 20px;
    }

    .col-lg-6 img {
        width: 100%;
        /* max-width: 350px; */
        height: auto;
        border-radius: 10px;
        margin-right: 20px;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    @media (max-width: 768px) {
        header h1 {
            font-size: 27px;
            color: #ff5733;
            font-weight: 800;
        }
        .row p {
            font-size: 17px;
        }

        .row ul li {
            font-size: 14px;
        }
    }
</style>
<br>
<section class="about-us">
    <div class="container">
        <header class="text-center animate-float-in delay-0">
            <h1>About Us</h1>
        </header>
        <div class="row">
            <div class="col-lg-6 mb-4 animate-slide-left delay-1">
                <p>
                    <strong>Swarna Metals Zambia Limited</strong> (SMZL) is a purpose-built, greenfield hydrometallurgical copper extraction facility located
                    approximately 25 kilometres from Kitwe in Zambia’s Copperbelt Province. As a subsidiary of PLR Zambia Limited, SMZL is committed to
                    producing high-quality copper products using industry best practices in safety, environmental stewardship and process efficiency,
                    while generating sustainable economic value for local stakeholders.
                </p>
            </div>

            <div class="col-lg-6 mb-4 animate-slide-right delay-1">
                <img src="{{ asset('images/crusher.jpg') }}" alt="Copper Cathodes" class="img-fluid">
            </div>
        </div>

        <div class="row">
            <section class="project-overview mt-4">
                <h3 class="animate-float-in delay-2">Project Overview</h3>
                <ul>
                    <li><strong>Strategic Location:</strong> Zambia’s Copperbelt Province, near Kitwe — an established mining and logistics hub with
                        access to regional infrastructure and skilled workforce.</li>
                    <li><strong>Milling Capacity:</strong> Commissioned initial milling capacity of <strong>1,200 tons/day</strong> (TPD) of copper ore, with planned
                        phased expansion to <strong>2,400 TPD</strong>.</li>
                    <li><strong>Production Targets:</strong> Nameplate annual targets of approximately <strong>12,000 metric tonnes</strong> of copper
                        concentrate and <strong>2,400 metric tonnes</strong> of copper cathode, subject to feed grade and recovery performance.</li>
                    <li><strong>Project Timeline:</strong> Construction commenced in <strong>April 2024</strong>; pre-commissioning and plant trials began in
                        <strong>June 2025</strong>; first commercial copper concentrate was produced in <strong>October 2025</strong>.</li>
                    <li><strong>Commitment:</strong> Delivering reliable product quality while minimising environmental impact and maximising
                        benefits to the local economy.</li>
                </ul>
            </section>
        </div>

        <div class="row mt-5">
            <div class="col-lg-6 mb-4 animate-slide-left delay-1">
                <h2>Our Parent Company – PLR Projects Limited</h2>
                <p>
                    PLR Projects Limited is a diversified infrastructure group with over 40
                    years of global expertise in mining and infrastructure development.
                    PLR’s journey in Zambia began in 2011, with its first footprint established
                    in Pensulo, Serenje District, where the company developed a Ferro Alloy
                    Plant to produce Silico Manganese. This landmark project positioned PLR
                    as a trusted player in Zambia’s mining and industrial sector, exporting
                    products to key international markets.
                    This strong foundation in Pensulo, Serenje paved the way for continued
                    investment and the establishment of Swarna Metals Zambia Ltd,
                    reaffirming PLR’s long-term commitment to Zambia’s mining future
                </p>
            </div>

            <div class="col-lg-6 mb-4 animate-slide-right delay-2">
                <img src="{{ asset('images/swarna-truck.jpeg') }}" alt="Copper Cathodes" class="img-fluid">
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4 animate-slide-left delay-1">
                <h3>Key Contacts</h3>
                <p><strong>Mr. Sri Harsha Vemuri</strong></p>
                <ul>
                    <li><strong>Position:</strong> Director,Swarna Metals Zambia Limited</li>
                </ul>
                <p><strong>Mr. Jayakumar Peddireddy</strong></p>
                <ul>
                    <li><strong>Position:</strong> CEO, Swarna Metals Zambia Limited</li>
                </ul>
                <p><strong>Email:</strong> contact@swarnametals.com</p>
            </div>

            <div class="col-lg-6 mb-4 animate-slide-right delay-2">
                <h3>Parent Company Information</h3>
                <p><strong>PLR Zambia Limited</strong></p>
                <p><strong> Mr. R N Niranjan Reddy</strong></p>
                <ul>
                    <li><strong>Position:</strong> CEO, PLR Zambia Ferro Alloys</li>
                    <li><strong>Address:</strong> J8, Zamsure Apartments, Lusaka, Zambia</li>
                    <li><strong>Website:</strong> <a href="https://plrprojects.com" target="_blank">plrprojects.com</a></li>
                </ul>
            </div>
        </div>

        <div class="row align-items-start">
            <div class="col-lg-6 animate-slide-left delay-1">
                <h3>Our Location</h3>
                <p><strong>Address:</strong> Sabina Mufulira Road, Kitwe - 50100, Copperbelt, Zambia</p>
                <p><strong>Hours:</strong> 8 AM - 5 PM</p>
            </div>

            <div class="col-lg-6 animate-slide-right delay-2">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d174911.3188751776!2d28.05414839521786!3d-12.668204315985065!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m0!4m5!1s0x196ce3002a1d77bf%3A0xa596812ee0acff6b!2sFarm%20No%20F%2F4213%2FA%2C%20Kitwe-Mufilira%2C%20Kitwe!3m2!1d-12.6623824!2d28.155643599999998!5e0!3m2!1sen!2szm!4v1734504385088!5m2!1sen!2szm"
                    width="100%" height="350" style="border:0; border-radius: 8px;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
</section>
@endsection
