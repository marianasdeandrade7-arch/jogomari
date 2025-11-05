<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Criar Personagem — As Sombras de Eldoria</title>

	<!-- Usa CSS externo em public/css/create.css -->
	<link rel="stylesheet" href="{{ asset('css/create.css') }}">

</head>
<body>
	<main class="container">
		<section class="card form-panel">
			<h1>Criar Novo Personagem</h1>
			<p class="note">Forje um herói nas sombras de Eldoria. Preencha os atributos e clique em Criar.</p>

			<!-- Adicionado: opções rápidas para escolher 3 personagens com foto -->
			<div class="hero-choices">
				<h3>Escolher personagem</h3>
				<div class="heroes" role="list">
					<button type="button" class="hero-btn" data-key="Lyra" role="button" aria-pressed="false" tabindex="0" onclick="selectPreset('Lyra')" title="Lyra">
						<img class="hero-thumb" src="https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg" alt="Lyra">
						<span>Lyra</span>
					</button>
					<button type="button" class="hero-btn" data-key="Kael" role="button" aria-pressed="false" tabindex="0" onclick="selectPreset('Kael')" title="Kael">
						<img class="hero-thumb" src="https://i.pinimg.com/736x/3c/fa/36/3cfa36141945e396d9909288205c8755.jpg" alt="Kael">
						<span>Kael</span>
					</button>
					<button type="button" class="hero-btn" data-key="Thorne" role="button" aria-pressed="false" tabindex="0" onclick="selectPreset('Thorne')" title="Thorne">
						<img class="hero-thumb" src="https://i.pinimg.com/736x/77/6c/a6/776ca6345b8aa6c3683981da61dec649.jpg" alt="Thorne">
						<span>Thorne</span>
					</button>
				</div>
			</div>

			<form action="/create" method="POST" id="createForm">
				@csrf

				<div class="field">
					<label for="name">Nome</label>
					<input id="name" name="name" type="text" placeholder="Ex: Lyra" required>
				</div>

				<div class="row">
					<div class="field">
						<label for="level">Nível</label>
						<!-- Nível fixo em 1 no momento da criação -->
						<input id="level" name="level" type="number" min="1" value="1" required disabled>
					</div>
					<div class="field">
						<label for="xp">XP</label>
						<input id="xp" name="xp" type="number" min="0" value="0">
					</div>
				</div>

				<!-- Indicador de pontos para distribuir entre atributos -->
				<div class="field">
					<p id="pointsInfo" class="note">Pontos disponíveis: <strong id="pointsRemaining">45</strong></p>
				</div>

				<div class="row">
					<div class="field">
						<label for="vida">Vida (HP)</label>
						<input id="vida" name="vida" type="number" min="1" value="100" required>
					</div>
					<div class="field">
						<label for="poder">Poder</label>
						<input id="poder" name="poder" type="number" min="0" value="10" required>
					</div>
				</div>

				<div class="row">
					<div class="field">
						<label for="ataque">Ataque</label>
						<input id="ataque" name="ataque" type="number" min="0" value="10" required>
					</div>
					<div class="field">
						<label for="defesa">Defesa</label>
						<input id="defesa" name="defesa" type="number" min="0" value="5" required>
					</div>
				</div>

				<div class="field">
					<label for="image">URL da Imagem (opcional)</label>
					<input id="image" name="image" type="url" placeholder="https://...">
				</div>

				<div class="buttons">
						<button id="createBtn" type="submit">Criar Personagem</button>
						<button type="button" class="ghost" onclick="document.getElementById('createForm').reset(); updatePreview();">Limpar</button>
					</div>

				<p class="note">Nota: ajuste a rota POST (/create) no backend para salvar no banco.</p>
			</form>

			<div class="footer">© 2025 — As Sombras de Eldoria</div>
		</section>

		<aside class="card preview" aria-label="Preview do personagem">
			<div id="portrait" class="portrait" style="background-image:url('https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg')"></div>
			<h2 id="previewName">Herói Anônimo</h2>
			<div class="stat-list">
				<div class="stat"><span>Nível</span><strong id="previewLevel">1</strong></div>
				<div class="stat"><span>XP</span><strong id="previewXP">0</strong></div>
				<div class="stat"><span>Vida</span><strong id="previewVida">100</strong></div>
				<div class="stat"><span>Poder</span><strong id="previewPoder">10</strong></div>
				<div class="stat"><span>Ataque</span><strong id="previewAtaque">10</strong></div>
				<div class="stat"><span>Defesa</span><strong id="previewDefesa">5</strong></div>
			</div>
			<p class="note">Preview em tempo real enquanto editas os campos.</p>
		</aside>
	</main>

	<script>
			// Pool e bases para distribuição (usuário tem 45 pontos extras para distribuir)
		const POINTS_POOL = 45;
		const base = { vida: 100, poder: 10, ataque: 10, defesa: 5 };
		const fields = ['name','level','xp','vida','poder','ataque','defesa','image'];
		
		function updatePreview(){
			const values = {};
			fields.forEach(f => {
				const el = document.getElementById(f);
				values[f] = el ? el.value : '';
			});
			document.getElementById('previewName').textContent = values.name || 'Herói Anônimo';
			// nível sempre 1 no momento da criação
			document.getElementById('previewLevel').textContent = '1';
			document.getElementById('previewXP').textContent = values.xp || '0';
			document.getElementById('previewVida').textContent = values.vida || base.vida;
			document.getElementById('previewPoder').textContent = values.poder || base.poder;
			document.getElementById('previewAtaque').textContent = values.ataque || base.ataque;
			document.getElementById('previewDefesa').textContent = values.defesa || base.defesa;
			const imgUrl = values.image && values.image.trim() ? values.image.trim() : 'https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg';
			document.getElementById('portrait').style.backgroundImage = `url('${imgUrl}')`;
		}

		// controle de pontos: impede ultrapassar POINTS_POOL distribuindo entre atributos
		function getAllocated() {
			const vida = parseInt(document.getElementById('vida').value || base.vida, 10);
			const poder = parseInt(document.getElementById('poder').value || base.poder, 10);
			const ataque = parseInt(document.getElementById('ataque').value || base.ataque, 10);
			const defesa = parseInt(document.getElementById('defesa').value || base.defesa, 10);
			const extra = Math.max(0, vida - base.vida) + Math.max(0, poder - base.poder) + Math.max(0, ataque - base.ataque) + Math.max(0, defesa - base.defesa);
			return extra;
		}

		function updatePointsDisplay() {
			const allocated = getAllocated();
			const remaining = Math.max(0, POINTS_POOL - allocated);
			document.getElementById('pointsRemaining').textContent = remaining;
		}

		// chamada quando um dos inputs de atributo muda; garante não ultrapassar o pool
		function onAttributeInput(e) {
			const id = e.target.id;
			if(!['vida','poder','ataque','defesa'].includes(id)) return;
			// converte e clamp
			const el = e.target;
			const val = parseInt(el.value || 0, 10);
			const min = base[id];
			// soma dos extras sem o campo atual
			let othersExtra = 0;
			['vida','poder','ataque','defesa'].forEach(k=>{
				if(k === id) return;
				const v = parseInt(document.getElementById(k).value || base[k], 10);
				othersExtra += Math.max(0, v - base[k]);
			});
			const allowedForThis = Math.max(0, POINTS_POOL - othersExtra);
			const maxThis = base[id] + allowedForThis;
			// ajusta se passou do limite (também garante não ficar abaixo da base)
			let newVal = Math.max(min, val);
			if(newVal > maxThis) newVal = maxThis;
			el.value = newVal;
			updatePreview();
			updatePointsDisplay();
		}

		// hookup de eventos
		['vida','poder','ataque','defesa','image','name','xp'].forEach(f=>{
			const el = document.getElementById(f);
			if(!el) return;
			if(['vida','poder','ataque','defesa'].includes(f)) {
				// força min / max para cada campo baseado no base + pool
				el.min = base[f];
				el.max = base[f] + POINTS_POOL;
				el.addEventListener('input', onAttributeInput);
			} else {
				el.addEventListener('input', updatePreview);
			}
		});

		// inicializa preview e contadores
		updatePreview();
		updatePointsDisplay();

		// Presets: nivel fixo = 1; distribuição de pontos somando até 45 (extras sobre os base)
		const presets = {
			"Lyra":   { name:'Lyra', level:1, xp:0, vida: base.vida + 10, poder: base.poder + 15, ataque: base.ataque + 15, defesa: base.defesa + 5, image:'https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg' },
			"Kael":   { name:'Kael', level:1, xp:0, vida: base.vida + 15, poder: base.poder + 10, ataque: base.ataque + 12, defesa: base.defesa + 8, image:'https://i.pinimg.com/736x/3c/fa/36/3cfa36141945e396d9909288205c8755.jpg' },
			"Thorne": { name:'Thorne', level:1, xp:0, vida: base.vida + 20, poder: base.poder + 5, ataque: base.ataque + 15, defesa: base.defesa + 5, image:'https://i.pinimg.com/736x/77/6c/a6/776ca6345b8aa6c3683981da61dec649.jpg' }
		};

		function selectPreset(key){
			const p = presets[key];
			if(!p) return;
			// preenche inputs (se existirem)
			if(document.getElementById('name')) document.getElementById('name').value = p.name;
			// level fixo em criação (campo está disabled)
			if(document.getElementById('xp')) document.getElementById('xp').value = p.xp;
			if(document.getElementById('vida')) document.getElementById('vida').value = p.vida;
			if(document.getElementById('poder')) document.getElementById('poder').value = p.poder;
			if(document.getElementById('ataque')) document.getElementById('ataque').value = p.ataque;
			if(document.getElementById('defesa')) document.getElementById('defesa').value = p.defesa;
			if(document.getElementById('image')) document.getElementById('image').value = p.image;
			// atualiza preview e pontos
			updatePreview();
			updatePointsDisplay();

			// destaca botão selecionado e ajusta aria-pressed
			document.querySelectorAll('.hero-btn').forEach(b=>{
				const isTarget = b.dataset.key === key;
				b.classList.toggle('selected', isTarget);
				b.setAttribute('aria-pressed', isTarget ? 'true' : 'false');
			});
		}

		// adiciona suporte a teclado (Enter / Space) para os botões de personagem
		document.addEventListener('DOMContentLoaded', function(){
			document.querySelectorAll('.hero-btn').forEach(btn=>{
				btn.addEventListener('keydown', function(e){
					if(e.key === 'Enter' || e.key === ' '){
						e.preventDefault();
						btn.click();
					}
				});
			});
		});
	</script>
</body>
</html>