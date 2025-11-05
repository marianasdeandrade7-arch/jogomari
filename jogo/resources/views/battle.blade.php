<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Campo de Batalha — Eldoria</title>
	<link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<body class="lore-page">

	<main class="battle-page">
		<header class="card" style="margin-bottom:16px;">
			<h1 style="margin:0;color:var(--accent)">Campo de Batalha — As Sombras de Eldoria</h1>
			<p class="note">Três fases. Vença cada inimigo para avançar.</p>
		</header>

		<section id="battleArea" class="card battle-area">
			<!-- background aplicado via JS -->
			<div class="battle-top">
				<!-- Player à esquerda -->
				<div id="playerBox" class="combat-box player-box">
					<img id="playerImg" src="{{ $character['image'] ?? 'https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg' }}" alt="Herói">
					<h3 id="playerName">{{ $character['name'] ?? 'Herói Anônimo' }}</h3>
					<div class="stat-line">Vida: <span id="playerHPText"></span></div>
					<div class="hp-bar"><div id="playerHPBar" class="hp-fill"></div></div>
					<div class="stat-line">Defesa: <strong id="playerDef">0</strong></div>
					<p>Nível: <span id="playerLevel">1</span></p>
				</div>

				<!-- Inimigo à direita -->
				<div id="enemyBox" class="combat-box enemy-box">
					<img id="enemyImg" src="" alt="Inimigo">
					<h3 id="enemyName"></h3>
					<div class="stat-line">Vida: <span id="enemyHPText"></span></div>
					<div class="hp-bar"><div id="enemyHPBar" class="hp-fill"></div></div>
					<div class="stat-line">Defesa: <strong id="enemyDef">0</strong></div>
				</div>
			</div>

			<div class="battle-controls">
				<button onclick="attack()" id="attackBtn">⚔️ Atacar</button>
				<button onclick="heal()" id="healBtn">✨ Curar</button>
				<button onclick="nextPhase()" id="nextBtn" class="ghost" style="display:none">➡️ Próxima Fase</button>
				<button onclick="restart()" class="ghost">🔁 Reiniciar</button>

				<!-- botão para salvar Eldoria (aparece apenas ao vencer a última fase) -->
				<button id="saveBtn" class="ghost" style="display:none;margin-left:8px" onclick="showVictoryScene()">🕊️ Salvar Eldoria</button>
			</div>

			<p id="battleMessage" class="note" style="margin-top:12px"></p>
		</section>
	</main>

	<script>
		// três cenários (fases) e inimigos conforme fornecido
		const phases = [
			{
				name: 'Floresta Sombria',
				bg: 'https://i.pinimg.com/1200x/14/35/ad/1435ad1ef45c458ce0bef14fe32c4b7a.jpg',
				enemy: { name: 'Travessouro das Sombras', img: 'https://i.pinimg.com/736x/e3/dc/71/e3dc71c95442c20aed53735657a7e475.jpg', hp: 80, def: 5 }
			},
			{
				name: 'Ruínas Antigas',
				bg: 'https://i.pinimg.com/736x/9b/1e/64/9b1e648daf8b2558746d5f7779d3db04.jpg',
				enemy: { name: 'Cavaleiro Corrompido', img: 'https://i.pinimg.com/736x/13/d4/81/13d4816011e5b01cfe9edfea7784d074.jpg', hp: 110, def: 8 }
			},
			{
				name: 'Abismo Congelado',
				bg: 'https://i.pinimg.com/736x/b2/31/28/b23128fea5fba0859605102d53972ea1.jpg',
				enemy: { name: 'Arauto do Vazio', img: 'https://i.pinimg.com/736x/74/3d/a5/743da528a04dd29198cb2f3c46a8c84c.jpg', hp: 150, def: 12 }
			}
		];

		let currentPhase = 0;
		// se houver personagem na session (blade), usar seus valores
		const playerData = @json($character ?? null);
		let playerMax = (playerData && playerData.vida) ? parseInt(playerData.vida,10) : 100;
		let playerDefVal = (playerData && playerData.defesa) ? parseInt(playerData.defesa,10) : 5;
		let playerHP = playerMax;
		// level do jogador (começa com valor do personagem ou 1)
		let playerLevel = (playerData && playerData.level) ? parseInt(playerData.level,10) : 1;
		let playerName = document.getElementById('playerName').textContent || 'Herói';
		let playerImgSrc = document.getElementById('playerImg').src;
		const MAX_HEALS = 3;
		let healsUsed = 0;

		function loadPhase(i){
			if(i < 0 || i >= phases.length) return;
			currentPhase = i;
			const phase = phases[i];
			// background
			const area = document.getElementById('battleArea');
			area.style.backgroundImage = `url('${phase.bg}')`;
			area.style.backgroundSize = 'cover';
			area.style.backgroundPosition = 'center';

			// enemy
			document.getElementById('enemyImg').src = phase.enemy.img;
			document.getElementById('enemyName').textContent = phase.enemy.name;
			// set enemy defense and hp display
			// se for a última fase, reduz a defesa do inimigo uma vez
			if (i === phases.length - 1 && !phase.enemy._reduced) {
				const reduceBy = 4; // ajuste conforme desejar
				phase.enemy.def = Math.max(0, (phase.enemy.def || 0) - reduceBy);
				phase.enemy._reduced = true;
				// nota: mensagem será mostrada abaixo
			}
			document.getElementById('enemyDef').textContent = phase.enemy.def || 0;
			// set numeric HP and bar
			document.getElementById('enemyHPText').textContent = phase.enemy.hp + ' / ' + phase.enemy.hp;
			updateEnemyBar(phase.enemy.hp, phase.enemy.hp);

			// player
			document.getElementById('playerHPText').textContent = playerHP + ' / ' + playerMax;
			updatePlayerBar(playerHP, playerMax);
			// exibe level e defesa atualizados
			document.getElementById('playerLevel').textContent = playerLevel;
			document.getElementById('playerDef').textContent = playerDefVal;
			document.getElementById('playerImg').src = playerImgSrc;

			document.getElementById('battleMessage').textContent = `Fase: ${phase.name} — Enfrente ${phase.enemy.name}.`;
			if (i === phases.length - 1 && phase.enemy._reduced) {
				document.getElementById('battleMessage').textContent += ' A defesa do inimigo foi reduzida nesta fase.';
			}
			document.getElementById('nextBtn').style.display = 'none';
			healsUsed = 0;
		}

		function attack(){
			const phase = phases[currentPhase];
			// ataque do jogador agora varia aleatoriamente entre 10 e 20
			let heroRaw = Math.floor(Math.random() * 11) + 10; // 10-20
			let enemyRaw = Math.floor(Math.random() * 12) + 6; // 6-17
			// aplica defesa: dano efetivo = max(1, raw - defesa)
			const enemyDef = phase.enemy.def || 0;
			const playerDef = playerDefVal || 0;
			let heroDamage = Math.max(1, heroRaw - enemyDef);
			let enemyDamage = Math.max(1, enemyRaw - playerDef);
			phase.enemy.hp -= heroDamage;
			playerHP -= enemyDamage;
			if(phase.enemy.hp < 0) phase.enemy.hp = 0;
			if(playerHP < 0) playerHP = 0;

			// atualiza displays e barras
			document.getElementById('enemyHPText').textContent = phase.enemy.hp + ' / ' + (phases[currentPhase].enemyInitialHP || phase.enemy.hp);
			updateEnemyBar(phase.enemy.hp, phases[currentPhase].enemyInitialHP || phase.enemy.hp);
			document.getElementById('playerHPText').textContent = playerHP + ' / ' + playerMax;
			updatePlayerBar(playerHP, playerMax);

			if(phase.enemy.hp === 0){
				document.getElementById('battleMessage').textContent = `Você derrotou ${phase.enemy.name}!`;
				if(currentPhase < phases.length - 1){
					document.getElementById('nextBtn').style.display = 'inline-block';
				} else {
					// vitória final: mostra botão "Salvar Eldoria"
					document.getElementById('battleMessage').textContent += ' Parabéns — você venceu todas as fases!';
					document.getElementById('saveBtn').style.display = 'inline-block';
					// opcional: desativa atacar/curar para evitar ações após vitória
					disableButtons(false); // mantém habilitado se quiser permitir click em salvar
				}
			} else if (playerHP === 0){
				document.getElementById('battleMessage').textContent = `${playerName} caiu... Reinicie para tentar novamente.`;
				disableButtons(true);
			} else {
				document.getElementById('battleMessage').textContent = `${playerName} causou ${heroDamage} de dano e recebeu ${enemyDamage}.`;
			}
		}

		function heal(){
			// cura aleatória entre 10 e 30
			if(playerHP <= 0) return;
			let healAmount = Math.floor(Math.random() * 21) + 10; // 10-30
			playerHP = Math.min(playerMax, playerHP + healAmount);
			updatePlayerBar(playerHP, playerMax);
			document.getElementById('playerHPText').textContent = playerHP + ' / ' + playerMax;
			healsUsed++;
			document.getElementById('battleMessage').textContent = `${playerName} se curou ${healAmount} pontos. (${healsUsed}/${MAX_HEALS})`;
		}

		function updatePlayerBar(value, max){
			const pct = Math.max(0, Math.min(100, Math.round((value / max) * 100)));
			const bar = document.getElementById('playerHPBar');
			if(bar) bar.style.width = pct + '%';
		}
		function updateEnemyBar(value, max){
			const pct = Math.max(0, Math.min(100, Math.round((value / max) * 100)));
			const bar = document.getElementById('enemyHPBar');
			if(bar) bar.style.width = pct + '%';
		}

		function nextPhase(){
			// restaurar inimigo HP ao carregar próxima
			currentPhase++;
			if(currentPhase < phases.length){
				// ao avançar de fase o personagem ganha +1 nível e +2 de defesa
				playerLevel = (playerLevel || 1) + 1;
				playerDefVal = (playerDefVal || 0) + 2;
				// ao avançar de fase o personagem também ganha 70 de HP (limitado ao máximo)
				playerHP = Math.min(playerMax, playerHP + 70);
				// atualiza displays com o novo level/defesa/vida antes de carregar a fase
				document.getElementById('playerLevel').textContent = playerLevel;
				document.getElementById('playerDef').textContent = playerDefVal;
				document.getElementById('playerHPText').textContent = playerHP + ' / ' + playerMax;
				updatePlayerBar(playerHP, playerMax);
				document.getElementById('battleMessage').textContent = `${playerName} subiu para Nível ${playerLevel} e ganhou +2 de defesa.`;
				// reset player heals count, player keeps HP
				loadPhase(currentPhase);
			}
		}

		function restart(){
			// reset player e fases
			playerHP = playerMax;
			// reset enemy HP to initial defined values
			phases.forEach((p, idx) => {
				if(idx === 0) p.enemy.hp = 80;
				if(idx === 1) p.enemy.hp = 110;
				if(idx === 2) p.enemy.hp = 150;
			});
			loadPhase(0);
			disableButtons(false);
			document.getElementById('battleMessage').textContent = 'Reiniciado.';
		}

		function disableButtons(disable){
			document.getElementById('attackBtn').disabled = disable;
			document.getElementById('healBtn').disabled = disable;
		}

		 // substitui a área principal por uma cena de vitória clara quando o jogador clica em "Salvar Eldoria"
		function showVictoryScene() {
			// mensagem de libertação
			const liberationText = `
				No antigo continente de Eldoria, as trevas finalmente recuaram. 
				Com coragem e sacrifício, ${playerName} restaurou a luz aos reinos. 
				A cidade volta a brilhar, livre da corrupção — um novo tempo de paz e prosperidade começa agora.
			`;
			// cria overlay de vitória
			const overlay = document.createElement('div');
			overlay.className = 'victory-overlay';
			overlay.innerHTML = `
				<div class="victory-bg" aria-hidden="true"></div>
				<div class="victory-panel card">
					<h1 class="victory-title">Eldoria Restaurada</h1>
					<img class="victory-portrait" src="${playerImgSrc}" alt="${playerName}">
					<h2 class="victory-hero">${playerName}</h2>
					<p class="victory-text">${liberationText}</p>
					<div style="margin-top:18px;display:flex;gap:10px;justify-content:center">
						<button onclick="location.href='{{ route('create') }}'">Voltar ao Menu</button>
					</div>
				</div>
			`;
			// limpa body e exibe overlay (mantém CSS)
			document.documentElement.scrollTop = 0;
			document.body.innerHTML = '';
			document.body.appendChild(overlay);
		}

		// inicia primeira fase
		document.addEventListener('DOMContentLoaded', () => {
			// guardar HP inicial para cada inimigo (para barra / display)
			phases.forEach((p, idx) => p.enemyInitialHP = p.enemy.hp);
			loadPhase(0);
		});
	</script>
</body>
</html>
