@extends('website-layouts.app')

@section('title', 'Corporate Social Responsibility - CSR')

@section('content')
<style>
	@keyframes floatIn {
		0% { opacity: 0; transform: translateY(40px); }
		100% { opacity: 1; transform: translateY(0); }
	}
	.animate-float-in { animation: floatIn 1s ease-out forwards; opacity: 0; }


    .csr-hero{
        position:relative;
        min-height:70vh;
    }

    .csr-slide{
        position:relative;
        height:70vh;
        display:flex;
        align-items:center;
        justify-content:center;
    }

    .csr-bg{
        position:absolute;
        width:100%;
        height:100%;
        object-fit:cover;
        top:0;
        left:0;
        z-index:0;
    }

    .csr-slide::after{
        content:"";
        position:absolute;
        inset:0;
        background:rgba(0,0,0,0.3);
        z-index:1;
    }

    .csr-content{
        position:relative;
        z-index:2;
        color:white;
    }

    .csr-content h1{
        font-weight:800;
        font-size:50px;
        color: #ff5733;
    }

    .csr-section h3{
        font-weight:800;
        font-size:30px;
        color: #ff5733;
    }

    .csr-section p{
        font-size:25px;
        color:rgba(19, 18, 18, 0.92);
    }

    .csr-content p{
        max-width:900px;
        margin:auto;
        font-size:25px;
        font-weight: 700;
        color:rgba(255, 255, 255, 0.92);
    }


	@media(min-width:992px){
		.csr-carousel .carousel-item img{height:70vh}
		.csr-carousel .carousel-caption h2{font-size:2.6rem}
	}

	.csr-section{padding:40px 0;border-bottom:1px solid #eef5fb}
	.image-slot{background:#fff;border:1px solid #e0e6ef;min-height:320px; border-radius: 10px; display:flex;align-items:center;justify-content:center;overflow:hidden}
	.image-slot img{width:100%;height:auto;max-height:560px;  object-fit:cover;transition:transform .7s cubic-bezier(.2,.9,.2,1)}
	.image-slot img:hover{transform:scale(1.03)}
	.muted-note{color:#6c7a89;font-size:0.95rem}

	/* Reveal animations */
	.reveal{opacity:0;transform:translateY(20px);transition:opacity .7s cubic-bezier(.2,.9,.2,1),transform .7s cubic-bezier(.2,.9,.2,1)}
	.reveal.in-view{opacity:1;transform:translateY(0)}
	.slide-left{transform:translateX(-30px)}
	.slide-right{transform:translateX(30px)}
	.slide-left.in-view,.slide-right.in-view{transform:translateX(0)}

    /* Responsive: tablets and below */
    @media (max-width: 991.98px) {
        .csr-hero, .csr-slide { min-height:50vh; height:50vh; }
        .csr-content h1{ font-size:36px; }
        .csr-section h3{ font-size:22px; }
        .csr-section p{ font-size:18px; }
        .csr-content p{ font-size:18px; max-width:700px }
        .image-slot{min-height:220px}
        .image-slot img{max-height:360px}
    }

    /* Responsive: mobile phones */
    @media (max-width: 575.98px) {
        .csr-hero, .csr-slide { min-height:40vh; height:40vh; }
        .csr-content h1{ font-size:26px; }
        .csr-section h3{ font-size:18px; }
        .csr-section p{ font-size:15px; }
        .csr-content p{ font-size:15px; font-weight:600; max-width:95% }
        .csr-slide::after{ background:rgba(0,0,0,0.45) }
        .carousel-control-prev, .carousel-control-next{ display:none }
        .image-slot{min-height:160px}
        .image-slot img{max-height:220px}
    }

</style>
    <div id="csrCarousel" class="carousel slide csr-hero" data-bs-ride="carousel">

        <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item active">
                <div class="csr-slide">
                    <img src="{{ asset('images/queens-edited.jpg') }}" class="csr-bg">

                    <div class="container text-center csr-content animate-float-in">
                        <h1>Corporate Social Responsibility</h1>
                        <p>
                            Swarna Metals Zambia Ltd is committed to sustainable development,
                            supporting communities and national growth through responsible operations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <div class="csr-slide">
                    <img src="{{ asset('images/workers-33.jpg') }}" class="csr-bg">

                    <div class="container text-center csr-content">
                        <h1>Supporting Local Employment</h1>
                        <p>
                            Over 300 employees work with Swarna Metals Zambia Ltd,
                            with the majority coming from Kitwe and Mufulira communities.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item">
                <div class="csr-slide">
                    <img src="{{ asset('images/director-edited.jpeg') }}" class="csr-bg">

                    <div class="container text-center csr-content">
                        <h1>Empowering Local Businesses</h1>
                        <p>
                            We partner with local suppliers for materials, services,
                            and logistics to strengthen the Copperbelt economy.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Slide 4 -->
            <div class="carousel-item">
                <div class="csr-slide">
                    <img src="{{ asset('images/responsible-copper-processing.jpg') }}" class="csr-bg">

                    <div class="container text-center csr-content">
                        <h1>Responsible Copper Processing</h1>
                        <p>
                            Our operations support Zambia’s vision of producing
                            3 million tonnes of copper annually by 2031.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#csrCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#csrCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

    </div>

<div class="container">
	<div class="csr-section">
		<div class="row align-items-center">
			<div class="col-lg-7 col-md-6 reveal slide-right">
				<h3>Supporting Local Employment</h3>
				<p>Swarna Metals Zambia Ltd employs over 300 people, with the majority drawn from Kitwe and Mufulira, supporting local jobs, skills development, and economic growth in the Copperbelt.</p>
				<p>Our recruitment and training initiatives prioritise local talent and skills transfer to strengthen community resilience.</p>
			</div>
			<div class="col-lg-5 col-md-6 reveal slide-left">
				<div class="image-slot">
					<img src="{{ asset('images/worker-2.jpg') }}" alt="Employment and local communities image" onerror="this.style.opacity=0.6;this.nextElementSibling.style.display='block'">
					<div style="display:none;text-align:center;color:#9aa7b7">Replace with an image illustrating local employment</div>
				</div>
			</div>
		</div>
	</div>

	<div class="csr-section">
		<div class="row align-items-center">
			<div class="col-lg-7 col-md-6 order-md-2 reveal slide-right">
                <h3>Empowering Local Businesses</h3>
                <p>
                    Swarna Metals Zambia Ltd works closely with local suppliers for copper ore, spare parts,
                    lubricants, groceries, and other essential services. These partnerships strengthen
                    community businesses while promoting inclusive participation in the mining value chain.
                </p>

                <p>
                    Our procurement strategy prioritises transparent, long-term relationships that build
                    local capacity and support sustainable economic growth in the regions where we operate.
                </p>

                <p>
                    In addition, Swarna Metals collaborates with small-scale and artisanal mining partners
                    across the Copperbelt and North-Western Provinces to secure a responsible and sustainable
                    feedstock supply. These partnerships focus on fair commercial terms, technical support,
                    and responsible sourcing practices that improve livelihoods while strengthening the
                    mineral supply chain.
                </p>
            </div>
			<div class="col-lg-5 col-md-6 order-md-1 reveal slide-left">
				<div class="image-slot">
					<img src="{{ asset('images/local.jpeg') }}" alt="Local suppliers image" onerror="this.style.opacity=0.6;this.nextElementSibling.style.display='block'">
					<div style="display:none;text-align:center;color:#9aa7b7">Replace with an image illustrating supplier partnerships</div>
				</div>
			</div>
		</div>
	</div>

	<div class="csr-section">
		<div class="row align-items-center">
			<div class="col-lg-7 col-md-6 reveal slide-right">
				<h3>Sustainable Processing</h3>
				<p>Through responsible mineral processing and capacity expansion, Swarna Metals supports the Government of Zambia’s goal of producing 3 million tonnes of copper by 2031, contributing to national development and export growth.</p>
			</div>
			<div class="col-lg-5 col-md-6 reveal slide-left">
				<div class="image-slot">
					<img src="{{ asset('images/sustainable.jpg') }}" alt="Sustainable processing image" onerror="this.style.opacity=0.6;this.nextElementSibling.style.display='block'">
					<div style="display:none;text-align:center;color:#9aa7b7">Replace with an image illustrating sustainable processing</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	(function(){
		const reveals = Array.from(document.querySelectorAll('.reveal'));
		if(!('IntersectionObserver' in window) || reveals.length===0) return;
		const obs = new IntersectionObserver((entries, o)=>{
			entries.forEach(entry=>{
				if(entry.isIntersecting){
					const el = entry.target;
					// staggered delay
					const idx = reveals.indexOf(el) || 0;
					el.style.transitionDelay = (idx * 120) + 'ms';
					el.classList.add('in-view');
					o.unobserve(el);
				}
			})
		},{threshold:0.12});
		reveals.forEach(r=>obs.observe(r));
	})();
</script>

@endsection
