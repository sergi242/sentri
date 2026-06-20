<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>500 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#ef4444;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
#board{position:relative;width:320px;height:240px;background:#0f172a;border:2px solid #7f1d1d;border-radius:8px;overflow:hidden}
.bug{position:absolute;font-size:28px;cursor:pointer;user-select:none;transition:transform 0.05s}
.bug:hover{transform:scale(1.2)}
.info{font-size:11px;color:#334155;margin-top:10px}
.score{font-size:14px;color:#ef4444;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#7f1d1d;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
#overlay{position:absolute;inset:0;background:rgba(0,0,0,0.8);display:flex;flex-direction:column;align-items:center;justify-content:center;font-size:18px;font-weight:700}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">500 — ERREUR SERVEUR</div>
<div class="msg">Des bugs ont envahi le serveur ! Écrasez-les !</div>
<div class="score">Score : <span id="sc">0</span> | Temps : <span id="tm">30</span>s</div>
<div id="board"><div id="overlay" style="display:none"></div></div>
<div class="info">Cliquez sur les bugs 🐛 pour les écraser</div>
<a href="{{ url('/') }}">↺ Réessayer</a>
<script>
const board=document.getElementById('board'),overlay=document.getElementById('overlay');
const BUGS=['🐛','🦗','🪲','🦟','🐜'];
let score=0,timeLeft=30,bugs=[],spawnI,timerI,active=false;

function start(){
  score=0;timeLeft=30;bugs=[];active=true;
  board.querySelectorAll('.bug').forEach(b=>b.remove());
  overlay.style.display='none';
  document.getElementById('sc').textContent=0;
  document.getElementById('tm').textContent=30;
  clearInterval(spawnI);clearInterval(timerI);
  spawnI=setInterval(spawnBug,700);
  timerI=setInterval(()=>{
    timeLeft--;document.getElementById('tm').textContent=timeLeft;
    if(timeLeft<=0){clearInterval(spawnI);clearInterval(timerI);active=false;endGame();}
  },1000);
}

function spawnBug(){
  if(!active)return;
  const bug=document.createElement('div');
  bug.className='bug';
  bug.textContent=BUGS[Math.floor(Math.random()*BUGS.length)];
  bug.style.left=Math.random()*270+'px';
  bug.style.top=Math.random()*200+'px';
  bug.addEventListener('click',()=>{
    if(!active)return;
    score+=10;document.getElementById('sc').textContent=score;
    const splat=document.createElement('div');
    splat.style.cssText='position:absolute;font-size:22px;pointer-events:none';
    splat.style.left=bug.style.left;splat.style.top=bug.style.top;
    splat.textContent='💥';board.appendChild(splat);
    setTimeout(()=>splat.remove(),400);
    bug.remove();
  });
  board.appendChild(bug);
  setTimeout(()=>bug.remove(),2500);
}

function endGame(){
  overlay.style.display='flex';
  overlay.innerHTML=`<div style="color:#ef4444">TEMPS ÉCOULÉ</div>
    <div style="font-size:14px;color:#94a3b8;margin:8px 0">Score final : ${score}</div>
    <button onclick="start()" style="padding:8px 20px;background:#7f1d1d;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px">Rejouer</button>`;
}

board.addEventListener('click',e=>{if(e.target===board&&!active)start();});
start();
</script>
</body>
</html>
