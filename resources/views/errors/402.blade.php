<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>402 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#22c55e;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
canvas{border:2px solid #15803d;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px;text-align:center}
.score{font-size:14px;color:#22c55e;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#15803d;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">402 — PAIEMENT REQUIS</div>
<div class="msg">Gagnez des points au Pong en attendant !</div>
<div class="score">Joueur : <span id="ps">0</span> | CPU : <span id="cs">0</span></div>
<canvas id="c" width="320" height="240"></canvas>
<div class="info">↑ ↓ pour bouger · ESPACE pour lancer</div>
<a href="{{ url('/') }}">← Retour accueil</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d'),W=320,H=240;
const PH=50,PW=8,BR=7;
let py,cy,bx,by,bdx,bdy,ps,cs,launched,loop;

function init(){
  py=H/2-PH/2;cy=H/2-PH/2;
  bx=W/2;by=H/2;bdx=0;bdy=0;
  ps=0;cs=0;launched=false;
  document.getElementById('ps').textContent=0;
  document.getElementById('cs').textContent=0;
}

function launch(){
  if(launched)return;
  launched=true;
  const ang=(Math.random()*0.8-0.4);
  bdx=4*(Math.random()>0.5?1:-1);
  bdy=4*Math.sin(ang);
}

function tick(){
  if(launched){
    bx+=bdx;by+=bdy;
    if(by<=BR||by>=H-BR)bdy=-bdy;
    // player paddle
    if(bdx<0&&bx-BR<=PW+10&&bx-BR>=10&&by>=py&&by<=py+PH){bdx=Math.abs(bdx)+0.3;bdy+=(by-(py+PH/2))*0.08;}
    // cpu paddle
    if(bdx>0&&bx+BR>=W-PW-10&&bx+BR<=W-10&&by>=cy&&by<=cy+PH){bdx=-(Math.abs(bdx)+0.2);bdy+=(by-(cy+PH/2))*0.06;}
    // score
    if(bx<0){cs++;document.getElementById('cs').textContent=cs;launched=false;bx=W/2;by=H/2;}
    if(bx>W){ps++;document.getElementById('ps').textContent=ps;launched=false;bx=W/2;by=H/2;}
    // cpu AI
    if(by<cy+PH/2)cy=Math.max(0,cy-3);
    else cy=Math.min(H-PH,cy+3);
  }
  draw();
}

function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
  // center line
  ctx.setLineDash([6,8]);ctx.strokeStyle='#1e293b';ctx.lineWidth=2;
  ctx.beginPath();ctx.moveTo(W/2,0);ctx.lineTo(W/2,H);ctx.stroke();ctx.setLineDash([]);
  // paddles
  ctx.fillStyle='#22c55e';ctx.beginPath();ctx.roundRect(10,py,PW,PH,4);ctx.fill();
  ctx.fillStyle='#ef4444';ctx.beginPath();ctx.roundRect(W-PW-10,cy,PW,PH,4);ctx.fill();
  // ball
  ctx.fillStyle='#f8fafc';ctx.beginPath();ctx.arc(bx,by,BR,0,Math.PI*2);ctx.fill();
  if(!launched){
    ctx.fillStyle='#475569';ctx.font='12px Segoe UI';ctx.textAlign='center';
    ctx.fillText('ESPACE pour lancer',W/2,H-14);
  }
}

const keys={};
document.addEventListener('keydown',e=>{
  keys[e.key]=true;
  if(e.key===' '){e.preventDefault();launch();}
});
document.addEventListener('keyup',e=>delete keys[e.key]);
setInterval(()=>{
  if(keys['ArrowUp'])py=Math.max(0,py-5);
  if(keys['ArrowDown'])py=Math.min(H-PH,py+5);
},16);
clearInterval(loop);loop=setInterval(tick,16);
init();
</script>
</body>
</html>
