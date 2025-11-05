<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>As Sombras de Eldoria — História</title>
	<link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<body class="lore-page">
	<div class="lore-full">
		<div class="lore-bg" aria-hidden="true" style="background-image:url('https://i.pinimg.com/originals/30/96/77/309677604d29c3585324b3afca52c860.gif')"></div>

		<div class="lore-container card">
			<h1 class="lore-title">As Sombras de Eldoria</h1>

			<div class="lore-viewport">
				<div class="lore-text">
					<p>No antigo continente de Eldoria, um mundo outrora pacífico e mágico, uma força obscura começou a emergir das ruínas esquecidas do norte. Durante séculos, a energia mágica fluiu livremente entre os reinos, sustentando a harmonia entre as criaturas e os humanos. Mas agora, criaturas corrompidas pelas trevas surgem das florestas, montanhas e cavernas, atacando vilas e destruindo tudo em seu caminho.</p>

					<p>Você é um jovem guerreiro escolhido pela Ordem dos Guardiões da Luz, a última esperança da humanidade. Seu destino é enfrentar as hordas das sombras, restaurar o equilíbrio e descobrir a origem dessa corrupção.</p>

					<p>A cada inimigo derrotado, você absorve fragmentos da essência sombria (XP), que fortalecem seu corpo e alma. Com isso, você se torna mais forte, mais sábio — e mais próximo do confronto final com o Senhor das Sombras, o ser ancestral que ameaça consumir toda Eldoria em escuridão eterna.</p>
				</div>
			</div>

			<div style="display:flex;gap:10px;justify-content:center;margin-top:12px;">
				<form action="{{ route('create.save') }}" method="POST" style="display:inline;">
					@csrf
					<button type="submit">Confirmar criação</button>
				</form>
				<form action="{{ route('create') }}" method="GET" style="display:inline;">
					<button type="submit" class="ghost">Voltar</button>
				</form>
			</div>
		</div>
	</div>

	<script>
		// reinicia a animação do texto para cada carregamento
		document.addEventListener('DOMContentLoaded', function(){
			const loreText = document.querySelector('.lore-text');
			if(loreText){
				loreText.style.animation = 'none';
				void loreText.offsetWidth;
				loreText.style.animation = null;
			}
		});
	</script>
</body>
</html>
