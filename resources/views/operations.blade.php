@extends('website-layouts.app')

@section('title', 'Operations')

@section('content')
<style>
	/* Animations */
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

	/* Header with background image */
	.operations header{position:relative;padding:76px 0 40px;overflow:hidden}
	.operations header::before{content:"";position:absolute;inset:0;background-image:url('{{ asset("images/crusher.jpg") }}');background-size:cover;background-position:center;filter:brightness(.55);z-index:0}
	.operations header::after{content:"";position:absolute;inset:0;background:rgba(0,0,0,0.23);z-index:1}
	.operations header h1{position:relative;z-index:2;color:#ff5733;font-weight:800;font-size:45px;text-shadow:0 2px 8px rgba(0,0,0,.6)}

	h2,h3{
		font-size:1.8rem;
		color:#ff5733;
		border-bottom:3px solid #444;
		display:inline-block;
		padding-bottom:5px;
	}

	.row p{font-size:25px}

	.row ul li{font-size:20px}

	.col-lg-6 img{width:100%;height:auto;border-radius:10px;margin-right:20px;transition:transform .35s,box-shadow .35s}
	.col-lg-6 img:hover{transform:scale(1.03);box-shadow:0 10px 30px rgba(0,0,0,.18)}

	@media (max-width:768px){
		.row p{font-size:17px}
		.row ul li{font-size:14px}
		.operations header{padding:36px 0}
		.operations header h1{font-size:1.6rem}
	}

	/* keep animations paused until element is visible */
	.animate-float-in, .animate-slide-left, .animate-slide-right{animation-play-state:paused}
</style>

<section class="operations">
    <header class="text-center mb-5">
        <h1 class="animate-float-in">Our Operations</h1>
    </header>
	<div class="container">
        <div class="row mb-4">
            <p>
                Swarna Metals operates a concentrator and a leach–SX–EW (solvent extraction–electrowinning) circuit. SMZL produces approximately
                <strong>200 MT of copper cathodes</strong> and <strong>1,000 MT of copper concentrates per month</strong>, supporting consistent
                delivery to international markets.
            </p>
        </div>
		<div class="row mb-4">
			<div class="col-lg-6 mb-4 animate-slide-right">
				<h2>Concentrator</h2>
				<p>
					Our concentrator treats sulphide ores through a multi-stage crushing, grinding and flotation process to produce
					copper concentrate. The concentrator is engineered for throughput, metallurgical flexibility and consistent concentrate quality
					to meet smelter specifications.
				</p>
			</div>
            <div class="col-lg-6 mb-4">
				<img src="{{ asset('images/flotation.jpg') }}" alt="Operations Plant" class="img-fluid animate-slide-left">
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-lg-6 mb-4 animate-slide-right">
				<img src="{{ asset('images/leach-plant.jpeg') }}" alt="Leach SX-EW" class="img-fluid">
			</div>

			<div class="col-lg-6 mb-4 animate-slide-left">
				<h2>Leach – SX – EW Circuit</h2>
				<p>
					The leach-SX-EW plant complements our concentrator by treating oxide and mixed ores to produce 99.99% copper cathodes via
					Electrowinning. The circuit minimises environmental impact while enabling the production of saleable cathode
					material with controlled impurity profiles.
				</p>
			</div>
		</div>
	</div>
</section>

<script>
	(function(){
		const els = Array.from(document.querySelectorAll('.animate-float-in, .animate-slide-left, .animate-slide-right'));
		if(!('IntersectionObserver' in window) || els.length===0) return;
		const obs = new IntersectionObserver((entries, o)=>{
			entries.forEach(entry=>{
				if(entry.isIntersecting){
					const el = entry.target;
					const idx = els.indexOf(el) || 0;
					el.style.animationDelay = (idx * 90) + 'ms';
					el.style.animationPlayState = 'running';
					o.unobserve(el);
				}
			});
		},{threshold:0.12});
		els.forEach(e=>obs.observe(e));
	})();
</script>

@endsection
