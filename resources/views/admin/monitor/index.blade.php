<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Moniteur — DMCE</title>
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        :root {
            --bg:   #080c16; --sf:  #0f1623; --sf2: #161f30; --sf3: #1c2840;
            --bd:   #1e2d45; --ac:  #00d4ff; --ac2: #6d28d9;
            --gn:   #10b981; --yw:  #f59e0b; --rd:  #ef4444; --bl: #3b82f6;
            --tx:   #e2e8f0; --mt:  #64748b;
            --mono: 'JetBrains Mono', monospace;
            --ui:   'Syne', sans-serif;
        }
        html, body { width:100vw; height:100vh; overflow:hidden; background:var(--bg); color:var(--tx); font-family:var(--ui); font-size:14px; }

        .app { display:grid; grid-template-rows:60px 60px 1fr; height:100vh; width:100vw; }

        /* TOPBAR */
        .topbar { background:var(--sf); border-bottom:2px solid var(--bd); display:flex; align-items:center; justify-content:space-between; padding:0 24px; z-index:100; }
        .t-left  { display:flex; align-items:center; gap:16px; }
        .t-right { display:flex; align-items:center; gap:12px; }
        .logo { font-size:14px; font-weight:800; letter-spacing:3px; text-transform:uppercase; color:var(--ac); display:flex; align-items:center; gap:10px; }
        .logo i { font-size:20px; }
        .live-pill { display:flex; align-items:center; gap:7px; font-size:12px; color:var(--gn); font-family:var(--mono); background:rgba(16,185,129,.1); padding:5px 14px; border-radius:20px; border:1px solid rgba(16,185,129,.25); }
        .live-dot { width:8px; height:8px; background:var(--gn); border-radius:50%; animation:blink 1.4s infinite; box-shadow:0 0 8px var(--gn); }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }
        .t-clock { font-family:var(--mono); color:var(--mt); display:flex; align-items:center; gap:16px; }
        .t-clock .tm { color:var(--ac); font-weight:700; font-size:18px; }
        .t-clock .dt { font-size:13px; }
        .sess-pill { font-family:var(--mono); font-size:12px; background:rgba(0,212,255,.08); border:1px solid rgba(0,212,255,.2); color:var(--ac); padding:5px 14px; border-radius:20px; }
        .ref-pill { font-size:12px; color:var(--mt); font-family:var(--mono); display:flex; align-items:center; gap:6px; }
        .ref-ring { width:16px; height:16px; border-radius:50%; border:2px solid var(--bd); border-top-color:var(--ac); animation:spin 5s linear infinite; }
        @keyframes spin { to{transform:rotate(360deg)} }
        .btn-back { display:flex; align-items:center; gap:8px; font-size:13px; font-weight:700; padding:8px 18px; border-radius:8px; background:linear-gradient(135deg,rgba(0,212,255,.15),rgba(0,212,255,.05)); border:1px solid rgba(0,212,255,.4); color:var(--ac); text-decoration:none; transition:all .2s; font-family:var(--ui); }
        .btn-back:hover { background:rgba(0,212,255,.25); border-color:var(--ac); color:#fff; }
        .btn-back i { font-size:16px; }

        /* STATS BAR */
        .sbar { display:grid; grid-template-columns:repeat(5,1fr); gap:1px; background:var(--bd); border-bottom:1px solid var(--bd); }
        .sc { background:var(--sf2); display:flex; align-items:center; gap:14px; padding:0 20px; transition:background .2s; cursor:default; }
        .sc:hover { background:var(--sf3); }
        .sc-ico { width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0; }
        .sc-val { font-family:var(--mono); font-size:26px; font-weight:700; line-height:1; }
        .sc-lbl { font-size:11px; color:var(--mt); text-transform:uppercase; letter-spacing:1px; margin-top:3px; }

        /* MAIN */
        .main { display:grid; grid-template-columns:300px 1fr 300px; overflow:hidden; }

        /* PANELS */
        .panel { display:flex; flex-direction:column; overflow:hidden; border-right:1px solid var(--bd); }
        .panel-r { border-right:none; border-left:1px solid var(--bd); }
        .ph { padding:12px 16px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:2px; color:var(--mt); border-bottom:1px solid var(--bd); background:var(--sf2); display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
        .pb { flex:1; overflow-y:auto; }

        /* USER CARDS */
        .uc { padding:12px 16px; border-bottom:1px solid var(--bd); cursor:pointer; transition:background .12s; }
        .uc:hover { background:var(--sf2); }
        .uc.sel { background:rgba(0,212,255,.06); border-left:3px solid var(--ac); }
        .uc-row { display:flex; align-items:center; gap:12px; }
        .uc-av { width:38px; height:38px; border-radius:50%; flex-shrink:0; background:linear-gradient(135deg,var(--ac2),var(--ac)); display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; color:#fff; position:relative; }
        .uc-dot { position:absolute; bottom:-1px; right:-1px; width:11px; height:11px; border-radius:50%; border:2px solid var(--sf); }
        .uc-dot.on  { background:var(--gn); box-shadow:0 0 6px var(--gn); }
        .uc-dot.off { background:var(--yw); }
        .uc-inf { flex:1; min-width:0; }
        .uc-nm { font-size:13px; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .uc-rl { font-size:11px; color:var(--mt); margin-top:1px; }
        .uc-la { font-size:11px; color:var(--ac); font-family:var(--mono); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:3px; }
        .uc-meta { display:flex; justify-content:space-between; margin-top:3px; align-items:center; }
        .uc-ip { font-size:10px; color:var(--mt); font-family:var(--mono); }
        .sess-cnt { font-size:11px; font-family:var(--mono); color:var(--gn); background:rgba(16,185,129,.1); padding:1px 8px; border-radius:10px; }

        /* FEED */
        .fp { display:flex; flex-direction:column; overflow:hidden; background:var(--bg); }
        .ff { display:flex; gap:6px; padding:10px 14px; flex-shrink:0; border-bottom:1px solid var(--bd); background:var(--sf); flex-wrap:wrap; align-items:center; }
        .fb { padding:5px 12px; border-radius:20px; border:1px solid var(--bd); background:transparent; color:var(--mt); font-size:11px; font-family:var(--ui); cursor:pointer; transition:all .12s; text-transform:uppercase; letter-spacing:1px; font-weight:600; }
        .fb:hover,.fb.on { background:var(--ac); color:var(--bg); border-color:var(--ac); font-weight:700; }
        .fb.paused { background:var(--yw); color:var(--bg); border-color:var(--yw); }
        .fs { flex:1; min-width:120px; max-width:200px; background:var(--sf2); border:1px solid var(--bd); color:var(--tx); font-family:var(--mono); font-size:12px; padding:5px 12px; border-radius:6px; outline:none; }
        .fs:focus { border-color:var(--ac); }
        .fs::placeholder { color:var(--mt); }
        .fbody { flex:1; overflow-y:auto; }

        /* LOG ENTRIES */
        .lg { display:flex; align-items:center; gap:8px; padding:7px 14px; border-bottom:1px solid rgba(30,45,69,.5); transition:background .1s; font-size:12px; }
        .lg:hover { background:var(--sf); }
        .lg.er  { background:rgba(239,68,68,.04); }
        .lg.fr  { background:rgba(0,212,255,.05); border-left:3px solid var(--ac); }
        .ld { width:6px; height:6px; border-radius:50%; flex-shrink:0; }
        .ld.success   { background:var(--gn); }
        .ld.failed    { background:var(--rd); box-shadow:0 0 5px var(--rd); }
        .ld.forbidden { background:var(--yw); }
        .lt { font-family:var(--mono); font-size:11px; color:var(--mt); min-width:58px; white-space:nowrap; }
        .bx { font-family:var(--mono); font-size:10px; font-weight:700; padding:2px 7px; border-radius:4px; white-space:nowrap; min-width:52px; text-align:center; flex-shrink:0; }
        .bx.GET    { background:rgba(59,130,246,.15); color:#60a5fa; }
        .bx.POST   { background:rgba(16,185,129,.15); color:#34d399; }
        .bx.PUT    { background:rgba(245,158,11,.15); color:#fbbf24; }
        .bx.PATCH  { background:rgba(245,158,11,.15); color:#fbbf24; }
        .bx.DELETE { background:rgba(239,68,68,.15);  color:#f87171; }
        .mx { font-size:11px; padding:2px 8px; border-radius:4px; background:var(--sf2); color:var(--mt); white-space:nowrap; flex-shrink:0; }
        .la { flex:1; color:var(--tx); font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .le { color:var(--mt); font-size:10px; font-family:var(--mono); font-weight:400; }
        .lu { color:var(--ac); white-space:nowrap; max-width:110px; overflow:hidden; text-overflow:ellipsis; cursor:pointer; font-size:11px; font-weight:600; }
        .lu:hover { text-decoration:underline; }

        /* LIEN CONSULTATION */
        .log-link { color:var(--mt); font-size:15px; flex-shrink:0; text-decoration:none; transition:all .15s; display:flex; align-items:center; justify-content:center; width:22px; }
        .log-link:hover { color:var(--ac); transform:scale(1.2); }
        .log-link-empty { width:22px; flex-shrink:0; }

        /* RIGHT PANEL */
        .hw { padding:12px 16px; border-bottom:1px solid var(--bd); }
        .cb-wrap { display:flex; align-items:flex-end; gap:2px; height:54px; }
        .cb { flex:1; background:var(--ac2); border-radius:2px 2px 0 0; min-height:2px; opacity:.65; cursor:pointer; position:relative; transition:height .3s,opacity .15s; }
        .cb:hover { opacity:1; background:var(--ac); }
        .cb .tip { position:absolute; bottom:100%; left:50%; transform:translateX(-50%); background:var(--sf2); color:var(--tx); font-size:10px; padding:2px 6px; border-radius:4px; white-space:nowrap; display:none; font-family:var(--mono); border:1px solid var(--bd); pointer-events:none; z-index:10; }
        .cb:hover .tip { display:block; }
        .mb-wrap { padding:8px 16px; border-bottom:1px solid var(--bd); }
        .mb-lbl { font-size:11px; color:var(--mt); display:flex; justify-content:space-between; margin-bottom:4px; }
        .mb-lbl span:last-child { color:var(--ac); font-family:var(--mono); font-weight:600; }
        .mb-trk { height:4px; background:var(--bd); border-radius:2px; overflow:hidden; }
        .mb-fil { height:100%; border-radius:2px; background:linear-gradient(90deg,var(--ac2),var(--ac)); transition:width .5s; }
        .leg { padding:12px 16px; border-top:1px solid var(--bd); }
        .lr { display:flex; align-items:center; gap:8px; margin-bottom:6px; }

        /* DRAWER */
        .ov { position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:400; display:none; backdrop-filter:blur(3px); }
        .ov.on { display:block; }
        .dr { position:fixed; top:0; right:0; width:640px; height:100vh; background:var(--sf); border-left:2px solid var(--bd); z-index:500; transform:translateX(100%); transition:transform .3s cubic-bezier(.4,0,.2,1); display:flex; flex-direction:column; }
        .dr.on { transform:translateX(0); }
        .dr-hd { padding:16px 20px; border-bottom:1px solid var(--bd); background:var(--sf2); display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
        .du { display:flex; align-items:center; gap:14px; }
        .dav { width:52px; height:52px; border-radius:50%; background:linear-gradient(135deg,var(--ac2),var(--ac)); display:flex; align-items:center; justify-content:center; font-size:20px; font-weight:700; color:#fff; position:relative; flex-shrink:0; }
        .dav-txt { line-height:1; }
        .dav-dot { position:absolute; bottom:2px; right:2px; width:13px; height:13px; border-radius:50%; border:2px solid var(--sf2); background:var(--gn); box-shadow:0 0 8px var(--gn); }
        .dn    { font-size:17px; font-weight:800; }
        .dr-rl { font-size:12px; color:var(--mt); margin-top:2px; }
        .dr-em { font-size:11px; color:var(--mt); font-family:var(--mono); margin-top:2px; }
        .sess-live { font-family:var(--mono); font-size:14px; color:var(--gn); background:rgba(16,185,129,.1); padding:6px 16px; border-radius:20px; border:1px solid rgba(16,185,129,.25); text-align:center; }
        .sess-live small { font-size:10px; color:var(--mt); display:block; margin-top:2px; }
        .dc { width:32px; height:32px; border-radius:50%; background:var(--bd); border:none; color:var(--tx); cursor:pointer; font-size:15px; display:flex; align-items:center; justify-content:center; transition:background .15s; }
        .dc:hover { background:var(--rd); }
        .dr-right { display:flex; flex-direction:column; align-items:flex-end; gap:10px; }
        .dt { display:flex; border-bottom:1px solid var(--bd); flex-shrink:0; }
        .dta { flex:1; padding:12px; text-align:center; font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:1px; cursor:pointer; color:var(--mt); border-bottom:2px solid transparent; transition:all .12s; }
        .dta:hover { color:var(--tx); }
        .dta.on { color:var(--ac); border-bottom-color:var(--ac); }
        .dp { display:flex; gap:6px; padding:10px 16px; border-bottom:1px solid var(--bd); background:var(--sf2); flex-shrink:0; }
        .pbtn { flex:1; padding:7px; border-radius:8px; border:1px solid var(--bd); background:transparent; color:var(--mt); font-size:12px; cursor:pointer; transition:all .12s; text-align:center; font-family:var(--ui); font-weight:600; }
        .pbtn:hover,.pbtn.on { background:var(--ac); color:var(--bg); border-color:var(--ac); font-weight:700; }
        .ds { display:grid; grid-template-columns:repeat(4,1fr); gap:1px; background:var(--bd); flex-shrink:0; }
        .dsc { background:var(--sf); padding:10px; text-align:center; }
        .dsv { font-family:var(--mono); font-size:20px; font-weight:700; }
        .dsl { font-size:10px; color:var(--mt); text-transform:uppercase; letter-spacing:1px; margin-top:3px; }
        .pres { display:flex; align-items:center; gap:12px; padding:10px 16px; border-bottom:1px solid var(--bd); background:rgba(16,185,129,.06); flex-shrink:0; }
        .prt { font-family:var(--mono); font-size:18px; font-weight:700; color:var(--gn); }
        .prl { font-size:11px; color:var(--mt); margin-top:2px; }
        .dbody { flex:1; overflow-y:auto; }
        .dlg { display:flex; align-items:center; gap:8px; padding:7px 16px; border-bottom:1px solid rgba(30,45,69,.4); font-size:12px; transition:background .1s; }
        .dlg:hover { background:var(--sf2); }
        .dla { flex:1; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .sr { padding:12px 16px; border-bottom:1px solid var(--bd); display:flex; gap:12px; align-items:flex-start; }
        .sbx { font-size:11px; padding:3px 10px; border-radius:10px; font-weight:700; white-space:nowrap; flex-shrink:0; }
        .sbx.active  { background:rgba(16,185,129,.15); color:var(--gn); }
        .sbx.logout  { background:rgba(75,94,122,.15);  color:var(--mt); }
        .sbx.expired { background:rgba(245,158,11,.15); color:var(--yw); }
        .si { flex:1; }
        .st { font-size:12px; font-family:var(--mono); }
        .sm { font-size:11px; color:var(--mt); margin-top:3px; }
        .mc { display:flex; align-items:flex-end; gap:2px; height:50px; }
        .mb { flex:1; background:var(--ac2); border-radius:1px 1px 0 0; min-height:2px; opacity:.75; }
        .cnt { background:var(--ac2); color:#fff; font-size:11px; padding:2px 8px; border-radius:10px; font-family:var(--mono); }
        .empty { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:10px; padding:40px; color:var(--mt); font-size:13px; flex:1; }
        .toast { position:fixed; bottom:20px; right:20px; background:var(--sf2); border:1px solid var(--ac); border-radius:10px; padding:10px 18px; font-size:13px; color:var(--ac); font-family:var(--mono); display:none; z-index:999; }
        ::-webkit-scrollbar { width:4px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:var(--bd); border-radius:2px; }
        .stl { font-size:11px; color:var(--mt); text-transform:uppercase; letter-spacing:1px; padding:10px 16px 6px; }
    </style>
</head>
<body>
<div class="app">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="t-left">
            <div class="logo"><i class="la la-tv"></i> DMCE — Moniteur de Surveillance</div>
            <div class="live-pill"><div class="live-dot"></div> EN DIRECT</div>
        </div>
        <div class="t-clock">
            <span class="tm" id="cTime">--:--:--</span>
            <span class="dt" id="cDate">--/--/----</span>
        </div>
        <div class="t-right">
            <div class="sess-pill">Ma session&nbsp;: <span id="mySess" style="color:var(--gn);font-weight:700">00:00:00</span></div>
            <div class="ref-pill"><div class="ref-ring"></div><span id="refCd">5s</span></div>
            <a href="{{ route('users.home') }}" class="btn-back">
                <i class="la la-arrow-left"></i> Retour à l'accueil
            </a>
        </div>
    </div>

    {{-- STATS BAR --}}
    <div class="sbar">
        <div class="sc"><div class="sc-ico" style="background:rgba(16,185,129,.12)"><i class="la la-users" style="color:var(--gn)"></i></div><div><div class="sc-val" id="sU" style="color:var(--gn)">0</div><div class="sc-lbl">Actifs maintenant</div></div></div>
        <div class="sc"><div class="sc-ico" style="background:rgba(0,212,255,.12)"><i class="la la-bolt" style="color:var(--ac)"></i></div><div><div class="sc-val" id="sA">0</div><div class="sc-lbl">Actions aujourd'hui</div></div></div>
        <div class="sc"><div class="sc-ico" style="background:rgba(59,130,246,.12)"><i class="la la-list" style="color:var(--bl)"></i></div><div><div class="sc-val" id="sT">0</div><div class="sc-lbl">Requêtes aujourd'hui</div></div></div>
        <div class="sc"><div class="sc-ico" style="background:rgba(239,68,68,.12)"><i class="la la-exclamation-circle" style="color:var(--rd)"></i></div><div><div class="sc-val" id="sE" style="color:var(--rd)">0</div><div class="sc-lbl">Erreurs aujourd'hui</div></div></div>
        <div class="sc"><div class="sc-ico" style="background:rgba(109,40,217,.12)"><i class="la la-sign-in-alt" style="color:var(--ac2)"></i></div><div><div class="sc-val" id="sS" style="color:var(--ac2)">0</div><div class="sc-lbl">Sessions aujourd'hui</div></div></div>
    </div>

    {{-- MAIN --}}
    <div class="main">
        {{-- PANEL GAUCHE --}}
        <div class="panel">
            <div class="ph"><span><i class="la la-wifi" style="color:var(--gn)"></i>&nbsp; Utilisateurs actifs</span><span class="cnt" id="uCnt">0</span></div>
            <div class="pb" id="uList"><div class="empty"><i class="la la-spinner la-spin" style="font-size:24px"></i><span>Chargement...</span></div></div>
        </div>

        {{-- FEED --}}
        <div class="fp">
            <div class="ff">
                <button class="fb on" data-f="all">Tout</button>
                <button class="fb" data-f="POST">Créations</button>
                <button class="fb" data-f="PUT">Modifications</button>
                <button class="fb" data-f="DELETE">Suppressions</button>
                <button class="fb" data-f="failed">Erreurs</button>
                <input class="fs" id="fSearch" placeholder="🔍 Rechercher...">
                <button class="fb" id="pauseBtn">⏸ Pause</button>
            </div>
            <div class="fbody" id="fBody"><div class="empty"><i class="la la-spinner la-spin" style="font-size:26px"></i><span>Connexion au moniteur...</span></div></div>
        </div>

        {{-- PANEL DROIT --}}
        <div class="panel panel-r">
            <div class="ph"><span><i class="la la-chart-bar" style="color:var(--ac)"></i>&nbsp; Statistiques</span></div>
            <div class="pb">
                <div class="hw">
                    <div class="stl" style="padding:0 0 8px">Activité horaire (aujourd'hui)</div>
                    <div class="cb-wrap" id="hourly"></div>
                    <div style="display:flex;justify-content:space-between;margin-top:4px;">
                        <span style="font-size:10px;color:var(--mt);font-family:var(--mono)">00h</span>
                        <span style="font-size:10px;color:var(--mt);font-family:var(--mono)">12h</span>
                        <span style="font-size:10px;color:var(--mt);font-family:var(--mono)">23h</span>
                    </div>
                </div>
                <div class="stl">Modules actifs</div>
                <div id="modStats"></div>
                <div class="leg">
                    <div class="stl" style="padding:0 0 10px">Légende</div>
                    @foreach([['GET','#60a5fa','Consultation'],['POST','#34d399','Création'],['PUT','#fbbf24','Modification'],['DELETE','#f87171','Suppression']] as [$m,$c,$l])
                    <div class="lr"><span class="bx {{ $m }}" style="min-width:54px">{{ $m }}</span><span style="font-size:12px;color:var(--mt)">{{ $l }}</span></div>
                    @endforeach
                    <div class="lr" style="margin-top:10px"><span style="width:10px;height:10px;background:var(--gn);border-radius:50%;display:inline-block;box-shadow:0 0 6px var(--gn);flex-shrink:0"></span><span style="font-size:12px;color:var(--mt)">En ligne (&lt; 5 min)</span></div>
                    <div class="lr"><span style="width:10px;height:10px;background:var(--yw);border-radius:50%;display:inline-block;flex-shrink:0"></span><span style="font-size:12px;color:var(--mt)">Inactif (5–15 min)</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DRAWER --}}
<div class="ov" id="ov" onclick="D.close()"></div>
<div class="dr" id="dr">
    <div class="dr-hd">
        <div class="du">
            <div class="dav"><span class="dav-txt" id="dAvTxt">??</span><div class="dav-dot" id="dDot" style="display:none"></div></div>
            <div><div class="dn" id="dNm">—</div><div class="dr-rl" id="dRl">—</div><div class="dr-em" id="dEm">—</div></div>
        </div>
        <div class="dr-right">
            <button class="dc" onclick="D.close()">✕</button>
            <div class="sess-live" id="dSLive" style="display:none"><span id="dSTimer">00:00:00</span><small>Session en cours</small></div>
        </div>
    </div>
    <div class="dt">
        <div class="dta on" data-t="activity" onclick="D.tab('activity')">Activité</div>
        <div class="dta"    data-t="sessions" onclick="D.tab('sessions')">Sessions</div>
        <div class="dta"    data-t="stats"    onclick="D.tab('stats')">Statistiques</div>
    </div>
    <div class="dp">
        <button class="pbtn on" data-p="day"   onclick="D.period('day')">Aujourd'hui</button>
        <button class="pbtn"    data-p="week"   onclick="D.period('week')">Semaine</button>
        <button class="pbtn"    data-p="month"  onclick="D.period('month')">Mois</button>
        <button class="pbtn"    data-p="year"   onclick="D.period('year')">Année</button>
    </div>
    <div class="ds">
        <div class="dsc"><div class="dsv" id="dT">—</div><div class="dsl">Total</div></div>
        <div class="dsc"><div class="dsv" id="dC" style="color:var(--gn)">—</div><div class="dsl">Créations</div></div>
        <div class="dsc"><div class="dsv" id="dM" style="color:var(--yw)">—</div><div class="dsl">Modifs</div></div>
        <div class="dsc"><div class="dsv" id="dEr" style="color:var(--rd)">—</div><div class="dsl">Erreurs</div></div>
    </div>
    <div class="pres">
        <i class="la la-clock" style="color:var(--gn);font-size:22px"></i>
        <div><div class="prt" id="dPres">—</div><div class="prl">Temps de présence sur la période</div></div>
    </div>
    <div class="dbody" id="dBody"><div class="empty"><i class="la la-spinner la-spin" style="font-size:22px"></i><span>Chargement...</span></div></div>
</div>

<div class="toast" id="toast"><i class="la la-bolt"></i> <span id="toastMsg"></span></div>

<script>
// ══════════════════════════════════════════════
// CLOCK
// ══════════════════════════════════════════════
const Clock = {
    start: Date.now(),
    init() { setInterval(() => this.tick(), 1000); this.tick(); },
    tick() {
        const now = new Date();
        document.getElementById('cTime').textContent = now.toLocaleTimeString('fr-FR');
        document.getElementById('cDate').textContent = now.toLocaleDateString('fr-FR');
        document.getElementById('mySess').textContent = this.fmt(Math.floor((Date.now()-this.start)/1000));
        document.querySelectorAll('[data-lts]').forEach(e => {
            const ts = parseInt(e.dataset.lts);
            if (ts) e.textContent = '⏱ ' + this.fmt(Math.floor(Date.now()/1000 - ts));
        });
        if (D.loginTs) {
            const el = document.getElementById('dSTimer');
            if (el) el.textContent = this.fmt(Math.floor(Date.now()/1000 - D.loginTs));
        }
    },
    fmt(s) {
        s = Math.max(0,s);
        const h=Math.floor(s/3600), m=Math.floor((s%3600)/60), sc=s%60;
        return (h>0?String(h).padStart(2,'0')+':':'')+String(m).padStart(2,'0')+':'+String(sc).padStart(2,'0');
    }
};

// ══════════════════════════════════════════════
// SESSION GUARD
// ══════════════════════════════════════════════
const Guard = {
    init() { setInterval(() => this.check(), 30000); },
    async check() {
        try {
            const r = await fetch('/monitor/ping', {credentials:'same-origin'});
            if (r.status===401||r.status===419||r.redirected) this.expire();
        } catch(e) {}
    },
    expire() {
        document.body.innerHTML = `
        <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;
                    height:100vh;gap:24px;background:var(--bg);color:var(--tx);font-family:var(--ui)">
            <div style="font-size:56px">⏱</div>
            <div style="font-size:24px;font-weight:800;color:var(--yw)">Session expirée</div>
            <div style="font-size:15px;color:var(--mt)">Votre session a expiré. Veuillez vous reconnecter.</div>
            <a href="/authenticate" style="padding:12px 28px;background:var(--ac);color:var(--bg);
               border-radius:10px;text-decoration:none;font-weight:800;font-size:15px">Se reconnecter</a>
        </div>`;
    }
};

// ══════════════════════════════════════════════
// LIEN DE CONSULTATION PAR MODULE
// ══════════════════════════════════════════════
function getLink(log) {
    const id  = log.entity_id;
    const mod = log.module_raw;
    if (!id) return null;
    const map = {
        'demandes'      : `/demandes/${id}/fiche`,
        'impetrants'    : `/reporting/impetrant-show?id=${id}`,
        'flux'          : `/flux/${id}/edit`,
        'frontieres'    : `/frontieres/${id}/edit`,
        'watchlist'     : `/watchlist/${id}`,
        'archives'      : `/impetrants/archivage/${id}`,
        'soit-transmis' : `/soit-transmis/${id}/show`,
        'users'         : `/users/users/${id}/show`,
        'roles'         : `/roles/${id}/edit`,
        'employeurs'    : `/employeurs/${id}/edit`,
    };
    return map[mod] || null;
}

function linkBtn(log) {
    const url = getLink(log);
    if (!url) return `<span class="log-link-empty"></span>`;
    return `<a href="${url}" target="_blank" class="log-link" title="Consulter la fiche">
                <i class="la la-external-link-alt"></i>
            </a>`;
}

// ══════════════════════════════════════════════
// MONITOR
// ══════════════════════════════════════════════
const M = {
    paused:false, filter:'all', search:'', lastId:0, logs:[], cd:5,
    init() {
        document.querySelectorAll('.fb[data-f]').forEach(b => b.addEventListener('click', () => {
            document.querySelectorAll('.fb[data-f]').forEach(x=>x.classList.remove('on'));
            b.classList.add('on'); this.filter=b.dataset.f; this.renderFeed();
        }));
        document.getElementById('fSearch').addEventListener('input', e => {
            this.search=e.target.value.toLowerCase(); this.renderFeed();
        });
        document.getElementById('pauseBtn').addEventListener('click', () => {
            this.paused=!this.paused;
            const btn=document.getElementById('pauseBtn');
            btn.classList.toggle('paused',this.paused);
            btn.textContent=this.paused?'▶ Reprendre':'⏸ Pause';
        });
        this.fetch();
        setInterval(() => {
            if (!this.paused) {
                this.cd--;
                document.getElementById('refCd').textContent=this.cd+'s';
                if (this.cd<=0) { this.cd=5; this.fetch(); }
            }
        }, 1000);
    },
    async fetch() {
        try {
            const r = await fetch('/monitor/feed', {credentials:'same-origin'});
            if (!r.ok) { if(r.status===401||r.status===419) Guard.expire(); return; }
            this.process(await r.json());
        } catch(e) { console.error(e); }
    },
    process(d) {
        document.getElementById('sU').textContent = d.stats.total_users;
        document.getElementById('sA').textContent = d.stats.total_actions;
        document.getElementById('sT').textContent = d.stats.total_today;
        document.getElementById('sE').textContent = d.stats.total_errors;
        document.getElementById('sS').textContent = d.stats.total_sessions;
        const fresh = d.logs.filter(l=>l.id>this.lastId);
        if (fresh.length && this.lastId>0) this.toast(fresh.length);
        if (d.logs.length) this.lastId=d.logs[0].id;
        this.logs=d.logs;
        if (!this.paused) this.renderFeed();
        this.renderUsers(d.active_users);
        this.renderMods(d.module_stats);
        this.renderHourly(d.hourly);
        if (D.uid && d.active_users) {
            const u=d.active_users.find(x=>x.user_id==D.uid);
            if (u?.login_ts) {
                D.loginTs=u.login_ts;
                document.getElementById('dSLive').style.display='block';
                document.getElementById('dDot').style.display='block';
            }
        }
    },
    renderFeed() {
        let list=this.logs;
        if (this.filter==='failed') list=list.filter(l=>l.status!=='success');
        else if (this.filter!=='all') list=list.filter(l=>l.method===this.filter);
        if (this.search) list=list.filter(l=>
            (l.user_name||'').toLowerCase().includes(this.search)||
            (l.url||'').toLowerCase().includes(this.search)||
            (l.module||'').toLowerCase().includes(this.search)||
            (l.action||'').toLowerCase().includes(this.search)
        );
        if (!list.length) {
            document.getElementById('fBody').innerHTML=`<div class="empty"><i class="la la-inbox" style="font-size:32px"></i><span>Aucune activité</span></div>`;
            return;
        }
        document.getElementById('fBody').innerHTML=list.map(l=>`
            <div class="lg ${l.status!=='success'?'er':''} ${l.id===this.lastId?'fr':''}">
                <div class="ld ${l.status}"></div>
                <div class="lt">${l.time}</div>
                <span class="bx ${l.method}">${l.method}</span>
                <span class="mx">${l.module}</span>
                <div class="la">${l.action}${l.entity_id?` <span class="le">#${l.entity_id}</span>`:''}</div>
                ${linkBtn(l)}
                <div class="lu"
                     data-uid="${l.user_id||''}"
                     data-name="${this.esc(l.user_name)}"
                     data-role="${this.esc(l.user_role)}"
                     onclick="D.open(this.dataset.uid,this.dataset.name,this.dataset.role)">
                    ${l.user_name||'Invité'}
                </div>
            </div>`).join('');
    },
    renderUsers(users) {
        document.getElementById('uCnt').textContent=users.length;
        if (!users.length) {
            document.getElementById('uList').innerHTML=`<div class="empty"><i class="la la-user-slash" style="font-size:26px"></i><span>Aucun utilisateur actif</span></div>`;
            return;
        }
        document.getElementById('uList').innerHTML=users.map(u=>{
            const ini=(u.user_name||'??').split(' ').map(w=>w[0]||'').join('').substring(0,2).toUpperCase();
            return `
            <div class="uc ${D.uid==u.user_id?'sel':''}"
                 data-uid="${u.user_id}" data-name="${this.esc(u.user_name)}" data-role="${this.esc(u.user_role)}"
                 onclick="D.open(this.dataset.uid,this.dataset.name,this.dataset.role)">
                <div class="uc-row">
                    <div class="uc-av">${ini}<div class="uc-dot ${u.online?'on':'off'}"></div></div>
                    <div class="uc-inf">
                        <div class="uc-nm">${u.user_name}</div>
                        <div class="uc-rl">${u.user_role}</div>
                        <div class="uc-la" title="${u.last_url||''}">${u.last_action}</div>
                        <div class="uc-meta">
                            <span class="uc-ip">${u.ip}</span>
                            ${u.login_ts?`<span class="sess-cnt" data-lts="${u.login_ts}">⏱ ...</span>`:`<span class="uc-ip">${u.session_duration}</span>`}
                        </div>
                    </div>
                </div>
            </div>`;
        }).join('');
    },
    renderMods(mods) {
        if (!mods?.length) return;
        const max=Math.max(...mods.map(m=>m.total),1);
        document.getElementById('modStats').innerHTML=mods.map(m=>`
            <div class="mb-wrap">
                <div class="mb-lbl"><span>${m.module||'système'}</span><span>${m.total}</span></div>
                <div class="mb-trk"><div class="mb-fil" style="width:${Math.round(m.total/max*100)}%"></div></div>
            </div>`).join('');
    },
    renderHourly(h) {
        if (!h) return;
        const vals=Object.values(h), max=Math.max(...vals,1);
        document.getElementById('hourly').innerHTML=vals.map((v,i)=>`
            <div class="cb" style="height:${Math.max(Math.round(v/max*100),2)}%">
                <div class="tip">${String(i).padStart(2,'0')}h : ${v}</div>
            </div>`).join('');
    },
    toast(n) {
        const t=document.getElementById('toast');
        document.getElementById('toastMsg').textContent=`${n} nouvelle${n>1?'s':''} activité${n>1?'s':''}`;
        t.style.display='block'; setTimeout(()=>t.style.display='none',3000);
    },
    esc(s) { return (s||'').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); }
};

// ══════════════════════════════════════════════
// DRAWER
// ══════════════════════════════════════════════
const D = {
    uid:null, tab_:'activity', period_:'day', data:null, loginTs:null,
    open(uid, name, role) {
        if (!uid||uid==='null'||uid==='') return;
        this.uid=uid; this.loginTs=null;
        const ini=(name||'??').split(' ').map(w=>w[0]||'').join('').substring(0,2).toUpperCase();
        document.getElementById('dAvTxt').textContent=ini;
        document.getElementById('dNm').textContent=name||'—';
        document.getElementById('dRl').textContent=role||'—';
        document.getElementById('dEm').textContent='';
        document.getElementById('dSLive').style.display='none';
        document.getElementById('dDot').style.display='none';
        document.querySelectorAll('.uc').forEach(c=>c.classList.toggle('sel',c.dataset.uid==uid));
        document.getElementById('dr').classList.add('on');
        document.getElementById('ov').classList.add('on');
        this.load();
    },
    close() {
        document.getElementById('dr').classList.remove('on');
        document.getElementById('ov').classList.remove('on');
        document.querySelectorAll('.uc').forEach(c=>c.classList.remove('sel'));
        this.uid=null; this.loginTs=null;
    },
    tab(t) {
        this.tab_=t;
        document.querySelectorAll('.dta').forEach(x=>x.classList.toggle('on',x.dataset.t===t));
        if (this.data) this.render();
    },
    period(p) {
        this.period_=p;
        document.querySelectorAll('.pbtn').forEach(x=>x.classList.toggle('on',x.dataset.p===p));
        this.load();
    },
    async load() {
        document.getElementById('dBody').innerHTML=`<div class="empty"><i class="la la-spinner la-spin" style="font-size:22px"></i><span>Chargement...</span></div>`;
        try {
            const r=await fetch(`/monitor/user/${this.uid}/activity?period=${this.period_}`,{credentials:'same-origin'});
            this.data=await r.json();
            document.getElementById('dEm').textContent=this.data.user?.email||'';
            if (this.data.active_login_ts) {
                this.loginTs=this.data.active_login_ts;
                document.getElementById('dSLive').style.display='block';
                document.getElementById('dDot').style.display='block';
            }
            this.updateStats(); this.render();
        } catch(e) { console.error(e); }
    },
    updateStats() {
        const s=this.data.stats;
        document.getElementById('dT').textContent  =s.total;
        document.getElementById('dC').textContent  =s.creations;
        document.getElementById('dM').textContent  =s.modifications;
        document.getElementById('dEr').textContent =s.errors;
        document.getElementById('dPres').textContent=this.data.total_presence||'0s';
    },
    render() {
        if (this.tab_==='activity') this.rActivity();
        else if (this.tab_==='sessions') this.rSessions();
        else this.rStats();
    },
    rActivity() {
        const logs=this.data.logs||[];
        if (!logs.length) {
            document.getElementById('dBody').innerHTML=`<div class="empty"><i class="la la-inbox" style="font-size:28px"></i><span>Aucune activité sur cette période</span></div>`;
            return;
        }
        document.getElementById('dBody').innerHTML=logs.map(l=>`
            <div class="dlg">
                <div class="ld ${l.status}"></div>
                <div class="lt" style="min-width:130px;font-size:10px">${l.date} ${l.time}</div>
                <span class="bx ${l.method}">${l.method}</span>
                <span class="mx">${l.module}</span>
                <div class="dla">${l.action}${l.entity_id?` <span class="le">#${l.entity_id}</span>`:''}</div>
                ${linkBtn(l)}
            </div>`).join('');
    },
    rSessions() {
        const ss=this.data.sessions||[];
        if (!ss.length) {
            document.getElementById('dBody').innerHTML=`<div class="empty"><i class="la la-sign-in-alt" style="font-size:28px"></i><span>Aucune session sur cette période</span></div>`;
            return;
        }
        document.getElementById('dBody').innerHTML=ss.map(s=>`
            <div class="sr">
                <span class="sbx ${s.status}">${s.status==='active'?'● En ligne':s.status==='logout'?'Déconnecté':'Expiré'}</span>
                <div class="si">
                    <div class="st"><span style="color:var(--gn)">${s.login_at}</span> &rarr; <span style="color:var(--mt)">${s.logout_at}</span></div>
                    <div class="sm">Durée : <span style="color:var(--tx);font-family:var(--mono)">${s.duration}</span> &nbsp;·&nbsp; IP : <span style="color:var(--tx);font-family:var(--mono)">${s.ip}</span></div>
                </div>
            </div>`).join('');
    },
    rStats() {
        const s=this.data.stats, mb=this.data.module_breakdown||{}, da=this.data.daily_activity||{};
        const maxDay=Math.max(...Object.values(da),1);
        const mini=Object.entries(da).map(([d,v])=>`<div class="mb" style="height:${Math.max(Math.round(v/maxDay*100),4)}%" title="${d}: ${v}"></div>`).join('');
        const maxMb=Math.max(...Object.values(mb),1);
        const mbHtml=Object.entries(mb).map(([mod,cnt])=>`
            <div class="mb-wrap">
                <div class="mb-lbl"><span>${mod||'système'}</span><span>${cnt}</span></div>
                <div class="mb-trk"><div class="mb-fil" style="width:${Math.round(cnt/maxMb*100)}%"></div></div>
            </div>`).join('');
        document.getElementById('dBody').innerHTML=`
            <div style="padding:14px 16px;border-bottom:1px solid var(--bd)">
                <div class="stl" style="padding:0 0 8px">Activité par jour</div>
                <div class="mc">${mini||'<span style="color:var(--mt);font-size:12px">Aucune donnée</span>'}</div>
            </div>
            <div style="padding:14px 16px;border-bottom:1px solid var(--bd)">
                <div class="stl" style="padding:0 0 10px">Répartition des actions</div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px">
                    ${[['Consultations',s.consultations,'#60a5fa'],['Créations',s.creations,'#34d399'],
                       ['Modifications',s.modifications,'#fbbf24'],['Suppressions',s.suppressions,'#f87171']]
                    .map(([l,v,c])=>`
                    <div style="background:var(--sf2);border-radius:8px;padding:12px;text-align:center">
                        <div style="font-family:var(--mono);font-size:22px;font-weight:700;color:${c}">${v}</div>
                        <div style="font-size:10px;color:var(--mt);text-transform:uppercase;letter-spacing:1px;margin-top:4px">${l}</div>
                    </div>`).join('')}
                </div>
            </div>
            <div>
                <div class="stl">Modules utilisés</div>
                ${mbHtml||'<div style="padding:12px 16px;font-size:12px;color:var(--mt)">Aucune donnée</div>'}
            </div>`;
    }
};

document.addEventListener('DOMContentLoaded', () => {
    Clock.init();
    M.init();
    Guard.init();
});
</script>
</body>
</html>