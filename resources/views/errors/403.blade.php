<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>403 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#ec4899;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
canvas{border:2px solid #9d174d;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px;text-align:center}
.score{font-size:14px;color:#ec4899;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#9d174d;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">403 — ACCÈS INTERDIT</div>
<div class="msg">Zone sécurisée. En attendant, cassez des briques !</div>
<div class="score">Score : <span id="sc">0</span> | Vies : <span id="vies">3</span></div>
<canvas id="c" width="320" height="260"></canvas>
<div class="info">← → pour bouger · ESPACE pour lancer · R pour recommencer</div>
<a href="{{ url('/') }}">← Retour accueil</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d'),W=320,H=260;
let bx,by,bdx,bdy,px,pw=60,ph=10,br=7,launched,score,vies,bricks,dead,won;
const BW=8,BH=4,BPW=36,BPH=14,BPAD=4,BOX=10,BOY=30;
const COLORS=['#ec4899','#f43f5e','#a855f7','#6366f1','#3b82f6'];

function init(){
  px=W/2-pw/2;bx=W/2;by=H-40;bdx=3;bdy=-3;launched=false;
  score=0;vies=3;dead=false;won=false;
  bricks=[];
  for(let r=0;r<BH;r++)for(col=0;col<BW;col++)
    bricks.push({x:BOX+col*(BPW+BPAD),y:BOY+r*(BPH+BPAD),alive:true,color:COLORS[r%COLORS.length]});
  update();
}

function update(){
  document.getElementById('sc').textContent=score;
  document.getElementById('vies').textContent=vies;
}

function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,W,H);
  bricks.forEach(b=>{
    if(!b.alive)return;
    ctx.fillStyle=b.color;ctx.beginPath();ctx.roundRect(b.x,b.y,BPW,BPH,3);ctx.fill();
  });
  ctx.fillStyle='#3b82f6';ctx.beginPath();ctx.roundRect(px,H-20,pw,ph,4);ctx.fill();
  ctx.fillStyle='#ec4899';ctx.beginPath();ctx.arc(bx,by,br,0,Math.PI*2);ctx.fill();
  if(dead||won){
    ctx.fillStyle='rgba(0,0,0,0.78)';ctx.fillRect(0,0,W,H);
    ctx.textAlign='center';
    ctx.fillStyle=won?'#22c55e':'#ef4444';ctx.font='bold 20px Segoe UI';
    ctx.fillText(won?'VICTOIRE !':'GAME OVER',W/2,H/2-10);
    ctx.fillStyle='#94a3b8';ctx.font='12px Segoe UI';
    ctx.fillText('R pour recommencer',W/2,H/2+14);
  }
}

let loop;
function gameLoop(){
  if(!dead&&!won){
    if(launched){
      bx+=bdx;by+=bdy;
      if(bx<=br||bx>=W-br)bdx=-bdx;
      if(by<=br)bdy=-bdy;
      if(by>=H-20-br&&bx>=px&&bx<=px+pw)bdy=-Math.abs(bdy);
      if(by>H){vies--;update();if(vies<=0){dead=true;}else{bx=W/2;by=H-40;launched=false;}}
      bricks.forEach(b=>{
        if(!b.alive)return;
        if(bx+br>b.x&&bx-br<b.x+BPW&&by+br>b.y&&by-br<b.y+BPH){
          b.alive=false;score+=10;update();bdy=-bdy;
        }
      });
      if(bricks.every(b=>!b.alive))won=true;
    }else{bx=px+pw/2;}
  }
  draw();
}

document.addEventListener('keydown',e=>{
  if(e.key==='ArrowLeft')px=Math.max(0,px-18);
  if(e.key==='ArrowRight')px=Math.min(W-pw,px+18);
  if(e.key===' '){e.preventDefault();if(dead||won)init();else launched=true;}
  if(e.key==='r'||e.key==='R')init();
});

clearInterval(loop);loop=setInterval(gameLoop,16);
init();
</script>
</body>
</html>
