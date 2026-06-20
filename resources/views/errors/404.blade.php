<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>404 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#f59e0b;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
canvas{border:2px solid #d97706;border-radius:8px;display:block}
.info{font-size:11px;color:#334155;margin-top:10px}
.score{font-size:14px;color:#f59e0b;margin-bottom:8px}
a{display:inline-block;margin-top:14px;padding:8px 22px;background:#d97706;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">404 — PAGE INTROUVABLE</div>
<div class="msg">Vous êtes perdu ? Trouvez la sortie !</div>
<div class="score">Temps : <span id="sc">0</span>s</div>
<canvas id="c" width="300" height="300"></canvas>
<div class="info">Flèches pour naviguer · ESPACE nouvelle carte</div>
<a href="{{ url('/') }}">← Retour accueil</a>
<script>
const c=document.getElementById('c'),ctx=c.getContext('2d');
const COLS=15,ROWS=15,CS=20;
let maze,player,exit_,timer,won,tInterval;

function genMaze(cols,rows){
  const m=Array.from({length:rows},()=>Array.from({length:cols},()=>({n:true,s:true,e:true,w:true,v:false})));
  function carve(x,y){
    m[y][x].v=true;
    const dirs=['n','s','e','w'].sort(()=>Math.random()-0.5);
    for(const d of dirs){
      let nx=x,ny=y,op;
      if(d==='n'){ny--;op='s'}else if(d==='s'){ny++;op='n'}else if(d==='e'){nx++;op='w'}else{nx--;op='e'}
      if(nx>=0&&nx<cols&&ny>=0&&ny<rows&&!m[ny][nx].v){
        m[y][x][d]=false;m[ny][nx][op]=false;carve(nx,ny);
      }
    }
  }
  carve(0,0);return m;
}

function init(){
  maze=genMaze(COLS,ROWS);
  player={x:0,y:0};exit_={x:COLS-1,y:ROWS-1};
  timer=0;won=false;
  clearInterval(tInterval);
  tInterval=setInterval(()=>{if(!won){timer++;document.getElementById('sc').textContent=timer;}},1000);
  draw();
}

function draw(){
  ctx.fillStyle='#0a0e1a';ctx.fillRect(0,0,300,300);
  const ox=(300-COLS*CS)/2,oy=(300-ROWS*CS)/2;
  ctx.strokeStyle='#1d4ed8';ctx.lineWidth=2;
  for(let y=0;y<ROWS;y++)for(let x=0;x<COLS;x++){
    const cell=maze[y][x],px=ox+x*CS,py=oy+y*CS;
    ctx.beginPath();
    if(cell.n){ctx.moveTo(px,py);ctx.lineTo(px+CS,py);}
    if(cell.s){ctx.moveTo(px,py+CS);ctx.lineTo(px+CS,py+CS);}
    if(cell.w){ctx.moveTo(px,py);ctx.lineTo(px,py+CS);}
    if(cell.e){ctx.moveTo(px+CS,py);ctx.lineTo(px+CS,py+CS);}
    ctx.stroke();
  }
  // Exit
  ctx.fillStyle='#22c55e';
  ctx.fillRect(ox+exit_.x*CS+3,oy+exit_.y*CS+3,CS-6,CS-6);
  ctx.fillStyle='#f59e0b';
  ctx.beginPath();ctx.arc(ox+player.x*CS+CS/2,oy+player.y*CS+CS/2,CS/2-3,0,Math.PI*2);ctx.fill();
  if(won){
    ctx.fillStyle='rgba(0,0,0,0.75)';ctx.fillRect(0,0,300,300);
    ctx.fillStyle='#22c55e';ctx.font='bold 20px Segoe UI';ctx.textAlign='center';
    ctx.fillText('SORTIE TROUVÉE !',150,130);
    ctx.fillStyle='#94a3b8';ctx.font='13px Segoe UI';
    ctx.fillText('Temps : '+timer+'s',150,158);
    ctx.fillText('ESPACE pour rejouer',150,182);
  }
}

function move(dx,dy){
  if(won)return;
  const x=player.x,y=player.y,cell=maze[y][x];
  if(dx===1&&!cell.e)player.x++;
  if(dx===-1&&!cell.w)player.x--;
  if(dy===1&&!cell.s)player.y++;
  if(dy===-1&&!cell.n)player.y--;
  if(player.x===exit_.x&&player.y===exit_.y){won=true;clearInterval(tInterval);}
  draw();
}

document.addEventListener('keydown',e=>{
  if(e.key==='ArrowUp')move(0,-1);
  if(e.key==='ArrowDown')move(0,1);
  if(e.key==='ArrowLeft')move(-1,0);
  if(e.key==='ArrowRight')move(1,0);
  if(e.key===' '){e.preventDefault();init();}
});
init();
</script>
</body>
</html>
