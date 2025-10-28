<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>As Sombras de Eldoria</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>🌙 As Sombras de Eldoria</h1>
        <p>No antigo continente de Eldoria, uma força obscura emerge. Escolha seu herói e enfrente as trevas!</p>
    </header>

    <main>
        <!-- Seção de Personagens -->
        <section id="characters">
            <h2>Escolha seu Personagem</h2>
            <div class="heroes">
                <button class="hero" onclick="chooseHero('Lyra')">
                    <img src="https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg" alt="Kael">
                    <p>Lyra⚔️</p>
                </button>
                <button class="hero" onclick="chooseHero('Kael')">
                    <img src="https://i.pinimg.com/736x/3c/fa/36/3cfa36141945e396d9909288205c8755.jpg" alt="Lyra">
                    <p>Kael⚔️</p>
                </button>
                <button class="hero" onclick="chooseHero('Thorne')">
                    <img src="fundo.png" alt="Thorne">
                    <p>Thorne 🔥</p>
                </button>
            </div>
        </section>

        <!-- Seção de Batalha -->
        <section id="battle" class="hidden">
            <h2>Campo de Batalha</h2>
            <div class="battle-area">
                <div id="player">
                    <img id="heroImg" src="" alt="Herói">
                    <h3 id="heroName"></h3>
                    <p>HP: <span id="playerHP">100</span></p>
                </div>

                <div id="enemy">
                    <img id="enemyImg" src="https://i.pinimg.com/736x/23/40/1f/23401f27f7536ddb6daab001aa9f9689.jpg" alt="Inimigo">
                    <h3 id="enemyName">Cavaleiro Caído</h3>
                    <p>HP: <span id="enemyHP">100</span></p>
                </div>
            </div>

            <div class="actions">
                <button onclick="attack()">⚔️ Atacar</button>
                <button onclick="heal()">✨ Curar</button>
                <button onclick="restart()">🔁 Reiniciar</button>
            </div>

            <p id="message"></p>
        </section>
    </main>

    <footer>
        <p>© 2025 - As Sombras de Eldoria | Desenvolvido no WAMP</p>
    </footer>

    <script>
        let hero = "";
        let playerHP = 100;
        let enemyHP = 100;

        // Limite de curas por fase
        const MAX_HEALS_PER_PHASE = 3;
        let healsThisPhase = 0;

        // Contador de renascimentos
        let resurrectionCount = 0;

        // Array de inimigos por ordem de dificuldade
        const enemies = [
            {name: "Goblin das Trevas", hp: 50, img: "https://i.pinimg.com/1200x/b8/88/a5/b888a50be8d56eca081ac207e876fe7d.jpg"},
            {name: "Cavaleiro Caído", hp: 100, img: "https://i.pinimg.com/736x/23/40/1f/23401f27f7536ddb6daab001aa9f9689.jpg"},
            {name: "Arauto do Vazio", hp: 150, img: "https://i.pinimg.com/736x/74/3d/a5/743da528a04dd29198cb2f3c46a8c84c.jpg"}
        ];
        let currentEnemy = 0;

        // Personagens e suas imagens
        const heroImages = {
            "Lyra": "https://i.pinimg.com/736x/7b/bf/ee/7bbfee1f9ef36762eb90e7805077060c.jpg",
            "Kael": "https://i.pinimg.com/736x/3c/fa/36/3cfa36141945e396d9909288205c8755.jpg",
            "Thorne": "https://i.pinimg.com/736x/77/6c/a6/776ca6345b8aa6c3683981da61dec649.jpg"
        };

        function chooseHero(name) {
            hero = name;
            document.getElementById("heroName").textContent = hero;
            document.getElementById("heroImg").src = heroImages[hero];

            document.getElementById("characters").classList.add("hidden");
            document.getElementById("battle").classList.remove("hidden");

            // Configura o primeiro inimigo
            enemyHP = enemies[currentEnemy].hp;
            document.getElementById("enemyHP").textContent = enemyHP;
            document.getElementById("enemyName").textContent = enemies[currentEnemy].name;
            document.getElementById("enemyImg").src = enemies[currentEnemy].img;

            // reseta contador de curas ao começar a fase
            healsThisPhase = 0;

            document.getElementById("message").textContent = `${hero} entra em batalha contra ${enemies[currentEnemy].name}!`;
        }

        function attack() {
            if (playerHP <= 0) return;

            let heroDamage = Math.floor(Math.random() * 20) + 10;
            let enemyDamage = Math.floor(Math.random() * 15) + 5;

            enemyHP -= heroDamage;
            playerHP -= enemyDamage;

            if (enemyHP < 0) enemyHP = 0;
            if (playerHP < 0) playerHP = 0;

            document.getElementById("playerHP").textContent = playerHP;
            document.getElementById("enemyHP").textContent = enemyHP;

            if (enemyHP === 0) {
                document.getElementById("message").textContent = `Você derrotou ${enemies[currentEnemy].name}! XP ganho! 🌟`;
                currentEnemy++;
                if (currentEnemy < enemies.length) {
                    // Próximo inimigo — reseta contador de curas para a nova fase
                    enemyHP = enemies[currentEnemy].hp;
                    document.getElementById("enemyHP").textContent = enemyHP;
                    document.getElementById("enemyName").textContent = enemies[currentEnemy].name;
                    document.getElementById("enemyImg").src = enemies[currentEnemy].img;
                    healsThisPhase = 0;
                    document.getElementById("message").textContent += ` Prepare-se para enfrentar ${enemies[currentEnemy].name}!`;
                } else {
                    document.getElementById("message").textContent += " Você derrotou todos os inimigos! Eldoria está livre!";
                }
            } else if (playerHP === 0) {
                if (resurrectionCount === 0) {
                    resurrect();
                } else {
                    document.getElementById("message").textContent = `${hero} caiu nas sombras pela segunda vez... O jogo será reiniciado. 💀`;
                    restart();
                }
            } else {
                document.getElementById("message").textContent = `${hero} causou ${heroDamage} de dano, mas recebeu ${enemyDamage}!`;
            }
        }

        function resurrect() {
            resurrectionCount++;
            playerHP = Math.floor(Math.random() * 50) + 50; // Vida aleatória entre 50 e 99
            healsThisPhase += 2; // Adiciona 2 curas extras
            document.getElementById("playerHP").textContent = playerHP;
            document.getElementById("message").textContent = `${hero} renasceu das cinzas com ${playerHP} de vida e 2 curas extras! 🌟`;
        }

        function heal() {
            if (playerHP <= 0 || enemyHP <= 0) return;

            if (healsThisPhase >= MAX_HEALS_PER_PHASE) {
                document.getElementById("message").textContent = `${hero} não pode se curar mais nesta fase.`;
                return;
            }

            let healAmount = Math.floor(Math.random() * 20) + 10;
            const beforeHP = playerHP;
            playerHP += healAmount;
            if (playerHP > 100) playerHP = 100;
            const actualHealed = playerHP - beforeHP;

            healsThisPhase++;
            document.getElementById("playerHP").textContent = playerHP;
            document.getElementById("message").textContent = `${hero} se curou em ${actualHealed} pontos! ✨ (${healsThisPhase}/${MAX_HEALS_PER_PHASE})`;
        }

        function restart() {
            playerHP = 100;
            enemyHP = enemies[0].hp;
            currentEnemy = 0;
            healsThisPhase = 0;
            resurrectionCount = 0; // Reseta o contador de renascimentos

            document.getElementById("playerHP").textContent = playerHP;
            document.getElementById("enemyHP").textContent = enemyHP;
            document.getElementById("enemyName").textContent = enemies[currentEnemy].name;
            document.getElementById("enemyImg").src = enemies[currentEnemy].img;

            document.getElementById("message").textContent = `${hero} se prepara novamente para a batalha.`;
        }
    </script>

</body>
</html>