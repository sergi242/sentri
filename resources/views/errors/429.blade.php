<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>429 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#f97316;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
canvas{border:2px solid #c2410c;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px;text-align:center}
.score{font-size:14px;color:#f97316;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#c2410c;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">429 — TROP DE REQUÊTES</div>
<div class="msg">Évitez les requêtes qui tombent du ciel !</div>
<div class="score">Score : <span id="sc">0</span> | Vies : <span id="vies">3</span></div>
<canvas id="c" width="320" height="280"></canvas>
<div class="info">← → pour esquiver · ESPACE pour recommencer</div>
<a href="{{ url('/') }}">← Retour accueil</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d'),W=320,H=280;
let px,bullets,score,vies,dead,frame,loop;
const PW=44,PH=20,BW=36,BH=16;

function init(){
  px=W/2-PW/2;bullets=[];score=0;vies=3;dead=false;frame=0;
  document.getElementById('sc').textContent=0;
  document.getElementById('vies').textContent=3;
  clearInterval(loop);loop=setInterval(tick,16);
}

function addBullet(){
  bullets.push({x:Math.random()*(W-BW),y:-BH,speed:2+Math.random()*2});
}

function tick(){
  frame++;
  if(!dead){
    if(frame%45===0)addBullet();
    bullets.forEach(b=>b.y+=b.speed);
    // hit check
    bullets=bullets.filter(b=>{
      if(b.y>H){score++;document.getElementById('sc').textContent=score;return false;}
      if(b.x<px+PW&&b.x+BW>px&&b.y+BH>H-30&&b.y<H-10){
        vies--;document.getElementById('vies').textContent=vies;
        if(vies<=0)dead=true;
        return false;
      }
      return true;
    });
  }
  draw();
}

function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
  // bullets (requêtes)
  bullets.forEach(b=>{
    ctx.fillStyle='#f97316';ctx.beginPath();ctx.roundRect(b.x,b.y,BW,BH,4);ctx.fill();
    ctx.fillStyle='#fff';ctx.font='bold 9px monospace';ctx.textAlign='center';
    ctx.fillText('REQUEST',b.x+BW/2,b.y+11);
  });
  // player
  ctx.fillStyle='#3b82f6';ctx.beginPath();ctx.roundRect(px,H-30,PW,PH,6);ctx.fill();
  ctx.font='18px serif';ctx.textAlign='center';ctx.fillText('🛡️',px+PW/2,H-14);
  // ground
  ctx.fillStyle='#1e293b';ctx.fillRect(0,H-5,W,5);
  if(dead){
    ctx.fillStyle='rgba(0,0,0,0.75)';ctx.fillRect(0,0,W,H);
    ctx.fillStyle='#ef4444';ctx.font='bold 20px Segoe UI';ctx.textAlign='center';
    ctx.fillText('SUBMERGÉ !',W/2,H/2-10);
    ctx.fillStyle='#94a3b8';ctx.font='12px Segoe UI';
    ctx.fillText('Score : '+score+' requêtes évitées',W/2,H/2+12);
    ctx.fillText('ESPACE pour recommencer',W/2,H/2+32);
  }
}

const keys={};
document.addEventListener('keydown',e=>{
  keys[e.key]=true;
  if(e.key===' '){e.preventDefault();if(dead)init();}
});
document.addEventListener('keyup',e=>delete keys[e.key]);
setInterval(()=>{
  if(!dead){
    if(keys['ArrowLeft'])px=Math.max(0,px-5);
    if(keys['ArrowRight'])px=Math.min(W-PW,px+5);
  }
},16);
init();
</script>
</body>
</html>
