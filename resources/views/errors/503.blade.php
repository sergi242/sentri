<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>503 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#ef4444;letter-spacing:2px;margin-bottom:16px}
.msg{font-size:13px;color:#64748b;margin-bottom:18px}
canvas{border:2px solid #1d4ed8;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px}
.score{font-size:14px;color:#3b82f6;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#1d4ed8;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">503 — SERVEUR INDISPONIBLE</div>
<div class="msg">Connexion au serveur échouée — contacter l'administrateur</div>
<div class="score">Score : <span id="sc">0</span></div>
<canvas id="c" width="320" height="280"></canvas>
<div class="info">Flèches pour jouer · ESPACE pour recommencer</div>
<a href="{{ url('/') }}">↺ Réessayer</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d'),W=320,H=280,S=20;
let snake,dir,food,score,dead,loop;
function init(){
  snake=[{x:8,y:7},{x:7,y:7},{x:6,y:7}];
  dir={x:1,y:0};food=rnd();score=0;dead=false;
  clearInterval(loop);loop=setInterval(tick,130);
}
function rnd(){
  return{x:Math.floor(Math.random()*(W/S)),y:Math.floor(Math.random()*(H/S))};
}
function tick(){
  if(dead)return;
  const h={x:snake[0].x+dir.x,y:snake[0].y+dir.y};
  if(h.x<0||h.x>=W/S||h.y<0||h.y>=H/S||snake.some(s=>s.x===h.x&&s.y===h.y)){
    dead=true;draw();return;
  }
  snake.unshift(h);
  if(h.x===food.x&&h.y===food.y){score++;food=rnd();document.getElementById('sc').textContent=score;}
  else snake.pop();
  draw();
}
function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
  ctx.fillStyle='#1e293b';
  for(let x=0;x<W/S;x++)for(let y=0;y<H/S;y++)if((x+y)%2===0)ctx.fillRect(x*S,y*S,S,S);
  ctx.fillStyle='#ef4444';ctx.beginPath();ctx.arc(food.x*S+S/2,food.y*S+S/2,S/2-2,0,Math.PI*2);ctx.fill();
  snake.forEach((s,i)=>{
    ctx.fillStyle=i===0?'#3b82f6':'#1d4ed8';
    ctx.beginPath();ctx.roundRect(s.x*S+1,s.y*S+1,S-2,S-2,4);ctx.fill();
  });
  if(dead){
    ctx.fillStyle='rgba(0,0,0,0.7)';ctx.fillRect(0,0,W,H);
    ctx.fillStyle='#ef4444';ctx.font='bold 22px Segoe UI';ctx.textAlign='center';ctx.fillText('GAME OVER',W/2,H/2-10);
    ctx.fillStyle='#94a3b8';ctx.font='13px Segoe UI';ctx.fillText('ESPACE pour recommencer',W/2,H/2+15);
  }
}
document.addEventListener('keydown',e=>{
  if(e.key==='ArrowUp'&&dir.y!==1)dir={x:0,y:-1};
  if(e.key==='ArrowDown'&&dir.y!==-1)dir={x:0,y:1};
  if(e.key==='ArrowLeft'&&dir.x!==1)dir={x:-1,y:0};
  if(e.key==='ArrowRight'&&dir.x!==-1)dir={x:1,y:0};
  if(e.key===' '){e.preventDefault();if(dead){document.getElementById('sc').textContent=0;init();}}
});
init();
</script>
</body>
</html>
