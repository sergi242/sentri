<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>401 — SENTRI</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#0a0e1a;color:#fff;font-family:'Segoe UI',Arial,sans-serif;display:flex;flex-direction:column;align-items:center;justify-content:center;min-height:100vh}
.logo{font-size:52px;font-weight:900;letter-spacing:10px;text-shadow:0 0 30px rgba(59,130,246,0.9);margin-bottom:4px}
.sub{font-size:10px;letter-spacing:3px;color:#475569;text-transform:uppercase;margin-bottom:6px}
.err{font-size:13px;color:#a855f7;letter-spacing:2px;margin-bottom:8px}
.msg{font-size:13px;color:#64748b;margin-bottom:14px}
.grid{display:grid;grid-template-columns:repeat(4,64px);gap:8px;margin-bottom:10px}
.card{width:64px;height:64px;border-radius:8px;cursor:pointer;position:relative;perspective:600px}
.card-inner{width:100%;height:100%;position:relative;transform-style:preserve-3d;transition:transform 0.35s}
.card.flip .card-inner,.card.matched .card-inner{transform:rotateY(180deg)}
.front,.back{position:absolute;inset:0;border-radius:8px;backface-visibility:hidden;display:flex;align-items:center;justify-content:center;font-size:26px}
.front{background:#1e293b;border:2px solid #7c3aed}
.back{background:#2e1065;border:2px solid #a855f7;transform:rotateY(180deg)}
.card.matched .back{background:#1e1b4b;border-color:#22c55e}
.score{font-size:14px;color:#a855f7;margin-bottom:10px}
.info{font-size:11px;color:#334155;margin-top:6px}
a{display:inline-block;margin-top:12px;padding:8px 22px;background:#7c3aed;color:#fff;border-radius:6px;text-decoration:none;font-size:12px}
</style>
</head>
<body>
<div class="logo">SENTRI</div>
<div class="sub">Système d'Enregistrement National</div>
<div class="err">401 — NON AUTORISÉ</div>
<div class="msg">Prouvez votre mémoire pour accéder !</div>
<div class="score">Paires : <span id="sc">0</span>/8 | Essais : <span id="tries">0</span></div>
<div class="grid" id="grid"></div>
<div class="info">Cliquez pour retourner les cartes · <button onclick="init()" style="background:#7c3aed;border:none;color:#fff;padding:3px 10px;border-radius:4px;cursor:pointer;font-size:11px">Nouvelle partie</button></div>
<a href="{{ url('/') }}">← Retour accueil</a>
<script>
const EMOJIS=['🔐','🛡️','🔑','👁️','🏛️','⚖️','📋','🗂️'];
let cards=[],first=null,second=null,matched=0,tries=0,locked=false;

function shuffle(a){return a.sort(()=>Math.random()-0.5)}

function init(){
  matched=0;tries=0;first=null;second=null;locked=false;
  document.getElementById('sc').textContent='0';
  document.getElementById('tries').textContent='0';
  const grid=document.getElementById('grid');grid.innerHTML='';
  cards=shuffle([...EMOJIS,...EMOJIS]).map((emoji,i)=>{
    const div=document.createElement('div');div.className='card';
    div.innerHTML=`<div class="card-inner"><div class="front">🔒</div><div class="back">${emoji}</div></div>`;
    div.dataset.emoji=emoji;div.dataset.idx=i;
    div.addEventListener('click',()=>flip(div));
    grid.appendChild(div);return div;
  });
}

function flip(card){
  if(locked||card.classList.contains('flip')||card.classList.contains('matched'))return;
  card.classList.add('flip');
  if(!first){first=card;return;}
  second=card;locked=true;tries++;
  document.getElementById('tries').textContent=tries;
  if(first.dataset.emoji===second.dataset.emoji){
    first.classList.add('matched');second.classList.add('matched');
    matched++;document.getElementById('sc').textContent=matched;
    first=null;second=null;locked=false;
    if(matched===8)setTimeout(()=>alert('🎉 Bravo ! '+tries+' essais'),300);
  }else{
    setTimeout(()=>{
      first.classList.remove('flip');second.classList.remove('flip');
      first=null;second=null;locked=false;
    },900);
  }
}
init();
</script>
</body>
</html>
