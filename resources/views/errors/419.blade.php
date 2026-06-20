<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>419 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#06b6d4;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
canvas{border:2px solid #0e7490;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px;text-align:center}
.score{font-size:14px;color:#06b6d4;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#0e7490;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">419 — SESSION EXPIRÉE</div>
<div class="msg">Le temps a expiré ! Comme ce bird, continuez à voler !</div>
<div class="score">Score : <span id="sc">0</span></div>
<canvas id="c" width="320" height="280"></canvas>
<div class="info">ESPACE ou clic pour voler</div>
<a href="{{ url('/') }}">↺ Reconnecter</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d'),W=320,H=280;
let bird,pipes,score,dead,frame;

function init(){
  bird={x:60,y:H/2,vy:0};pipes=[];score=0;dead=false;frame=0;
  clearInterval(window._loop);
  window._loop=setInterval(tick,16);
  document.getElementById('sc').textContent=0;
}

function addPipe(){
  const gap=75,top=30+Math.random()*(H-gap-60);
  pipes.push({x:W,top,bot:top+gap});
}

function tick(){
  frame++;
  if(!dead){
    bird.vy+=0.45;bird.y+=bird.vy;
    if(frame%90===0)addPipe();
    pipes.forEach(p=>p.x-=2.5);
    pipes=pipes.filter(p=>p.x>-40);
    // collisions
    if(bird.y<8||bird.y>H-8){dead=true;}
    pipes.forEach(p=>{
      if(bird.x+10>p.x&&bird.x-10<p.x+40&&(bird.y-10<p.top||bird.y+10>p.bot))dead=true;
      if(p.x+40===bird.x)score++;
    });
    document.getElementById('sc').textContent=score;
  }
  draw();
}

function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
  // pipes
  pipes.forEach(p=>{
    ctx.fillStyle='#0e7490';
    ctx.fillRect(p.x,0,40,p.top);
    ctx.fillRect(p.x,p.bot,40,H-p.bot);
    ctx.fillStyle='#06b6d4';
    ctx.fillRect(p.x-4,p.top-14,48,14);
    ctx.fillRect(p.x-4,p.bot,48,14);
  });
  // bird
  ctx.save();ctx.translate(bird.x,bird.y);
  ctx.rotate(Math.min(Math.max(bird.vy*0.06,-0.4),0.6));
  ctx.font='22px serif';ctx.textAlign='center';ctx.textBaseline='middle';
  ctx.fillText('🐦',0,0);
  ctx.restore();
  if(dead){
    ctx.fillStyle='rgba(0,0,0,0.72)';ctx.fillRect(0,0,W,H);
    ctx.fillStyle='#ef4444';ctx.font='bold 20px Segoe UI';ctx.textAlign='center';
    ctx.fillText('GAME OVER',W/2,H/2-12);
    ctx.fillStyle='#94a3b8';ctx.font='13px Segoe UI';
    ctx.fillText('Score : '+score,W/2,H/2+12);
    ctx.fillText('ESPACE pour rejouer',W/2,H/2+32);
  }
}

document.addEventListener('keydown',e=>{
  if(e.key===' '){e.preventDefault();if(dead)init();else{bird.vy=-7;}}
});
c.addEventListener('click',()=>{if(dead)init();else bird.vy=-7;});
init();
</script>
</body>
</html>
