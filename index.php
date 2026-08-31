<?php
declare(strict_types=1);
require __DIR__ . '/lib/auth.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>訪問看護　業務管理ステーション｜日本訪問看護学会</title>
<style>
  :root{
    --bg:#F3F6F4;
    --surface:#FFFFFF;
    --ink:#1E2A28;
    --ink-soft:#5B6B67;
    --line:#DCE5E1;
    --teal:#2C6E64;
    --teal-deep:#1D4B44;
    --teal-tint:#E3F0EC;
    --amber:#B8862F;
    --amber-tint:#FBF0DD;
    --brick:#A24B3E;
    --brick-tint:#F7E4E0;
    --indigo:#4C5B8C;
    --indigo-tint:#E7E9F4;
    --sage:#5F8A66;
    --sage-tint:#E7F1E7;
    --radius:10px;
    --font-ui:"Zen Kaku Gothic New","Hiragino Kaku Gothic ProN","Yu Gothic Medium","Meiryo",sans-serif;
    --font-mono:"IBM Plex Mono","SFMono-Regular",Consolas,monospace;
  }
  @media (prefers-reduced-motion: reduce){
    *{ animation-duration:0.001ms !important; transition-duration:0.001ms !important; }
  }
  *{ box-sizing:border-box; }
  html,body{ margin:0; padding:0; background:var(--bg); color:var(--ink); font-family:var(--font-ui); }
  body{ padding:0; min-height:100vh; }
  .app{ display:flex; min-height:100vh; }
  .nav{
    width:222px; flex:0 0 222px; background:var(--teal-deep); color:#EAF3F0;
    padding:24px 0; display:flex; flex-direction:column; gap:2px;
  }
  .nav .brand{ display:flex; align-items:center; gap:8px; padding:0 16px 18px; margin:0 0 10px; border-bottom:1px solid rgba(255,255,255,.15); }
  .nav .brand-logo-wrap{ flex:0 0 auto; width:36px; height:36px; border-radius:9px; background:var(--sage-tint); display:flex; align-items:center; justify-content:center; }
  .nav .brand-logo{ width:28px; height:28px; display:block; }
  .nav .brand-org{ font-size:10px; font-weight:600; letter-spacing:.02em; color:rgba(255,255,255,.72); margin:0 0 2px; white-space:nowrap; }
  .nav .brand-title{ font-size:12.5px; font-weight:700; letter-spacing:0; color:#fff; line-height:1.5; margin:0; white-space:nowrap; }
  .nav button{
    all:unset; cursor:pointer; padding:12px 20px; font-size:13.5px; color:#CFE3DE;
    border-left:3px solid transparent; font-family:var(--font-ui);
  }
  .nav button:hover{ background:rgba(255,255,255,.06); color:#fff; }
  .nav button.active{ background:rgba(255,255,255,.1); color:#fff; border-left-color:#8FD4C1; font-weight:700; }
  .nav button:focus-visible{ outline:2px solid #8FD4C1; outline-offset:-2px; }
  .nav-logout{
    all:unset; cursor:pointer; margin-top:auto; padding:14px 20px; font-size:12.5px; color:#CFE3DE;
    border-top:1px solid rgba(255,255,255,.15); font-family:var(--font-ui); box-sizing:border-box; width:100%;
  }
  .nav-logout:hover{ background:rgba(255,255,255,.06); color:#fff; }
  main{ flex:1; padding:28px 34px 60px; max-width:1100px; }
  .panel{ display:none; }
  .panel.active{ display:block; animation:fade .25s ease; }
  @keyframes fade{ from{opacity:0; transform:translateY(4px);} to{opacity:1; transform:none;} }

  h2.page-title{ font-size:20px; margin:0 0 4px; color:var(--teal-deep); }
  p.page-sub{ font-size:12.5px; color:var(--ink-soft); margin:0 0 22px; }

  .summary-row{ display:flex; gap:10px; margin-bottom:22px; flex-wrap:wrap; }
  .summary-card{
    background:var(--surface); border:1px solid var(--line); border-radius:var(--radius);
    padding:12px 16px; min-width:88px; text-align:center;
  }
  .summary-card .day{ font-size:12px; color:var(--ink-soft); }
  .summary-card .num{ font-family:var(--font-mono); font-size:22px; font-weight:700; color:var(--teal-deep); }
  .summary-card .unit{ font-size:10.5px; color:var(--ink-soft); }

  .ov-body{ display:flex; gap:20px; align-items:flex-start; }
  .ov-main{ flex:0 0 auto; max-width:620px; }
  .ov-alert{
    flex:1; min-width:260px; background:var(--surface); border:1px solid var(--line);
    border-radius:var(--radius); padding:14px; box-sizing:border-box;
    display:flex; flex-direction:column; min-height:110px;
  }
  .ov-alert-title{ font-size:13px; font-weight:700; color:var(--teal-deep); margin:0 0 4px; flex:0 0 auto; }
  .ov-alert-sub{ font-size:11px; color:var(--ink-soft); margin:0 0 10px; flex:0 0 auto; }
  .ov-alert-list{ list-style:none; margin:0; padding:0; display:flex; flex-wrap:wrap; align-content:flex-start; gap:8px; flex:1; min-height:0; overflow-y:auto; }
  .ov-alert-list li{
    flex:0 0 auto; min-width:130px; font-size:12px; background:var(--sage-tint); border:1px solid var(--sage);
    border-radius:7px; padding:6px 9px; display:flex; justify-content:space-between; align-items:center; gap:8px;
  }
  .ov-alert-list li .cnt{ font-weight:700; color:var(--sage); white-space:nowrap; }
  .ov-alert-empty{ font-size:12px; color:var(--ink-soft); margin:0; }

  table.grid{ border-collapse:collapse; width:100%; background:var(--surface); border-radius:var(--radius); overflow:hidden; border:1px solid var(--line);}
  table.grid th, table.grid td{ padding:9px 10px; border-bottom:1px solid var(--line); font-size:12.5px; text-align:left; vertical-align:middle;}
  table.grid th{ background:var(--teal-tint); color:var(--teal-deep); font-weight:700; font-size:11.5px; }
  table.grid tr:last-child td{ border-bottom:none; }
  .staff-name{ white-space:nowrap; font-weight:600; }
  .legend{ display:flex; gap:16px; margin:10px 0 20px; font-size:11.5px; color:var(--ink-soft); flex-wrap:wrap;}
  .legend span{ display:inline-flex; align-items:center; gap:5px;}
  .legend i{ width:12px; height:12px; border-radius:3px; display:inline-block; }

  .modal-overlay{
    position:fixed; inset:0; background:rgba(20,28,26,.45); display:flex; align-items:center; justify-content:center;
    z-index:100; padding:20px;
  }
  .modal-overlay[hidden]{ display:none; }
  .modal-box{
    background:var(--surface); border-radius:14px; max-width:440px; width:100%; max-height:80vh; overflow-y:auto;
    padding:24px 24px 20px; position:relative; box-shadow:0 20px 50px rgba(0,0,0,.25);
  }
  .modal-close{ all:unset; position:absolute; top:14px; right:16px; cursor:pointer; font-size:18px; color:var(--ink-soft); line-height:1; padding:4px; }
  .modal-close:hover{ color:var(--brick); }
  .modal-head{ font-size:15px; font-weight:700; color:var(--teal-deep); margin:0 0 4px; }
  .modal-sub{ font-size:12px; color:var(--ink-soft); margin:0 0 16px; }
  .booking-card{ background:var(--bg); border:1px solid var(--line); border-radius:10px; padding:12px 14px; margin-bottom:10px; }
  .booking-card .bname{ font-weight:700; font-size:14px; margin-bottom:4px; }
  .booking-card .brow{ font-size:12px; color:var(--ink-soft); margin-bottom:2px; }
  .booking-card .btag{ display:inline-block; font-size:10.5px; font-weight:700; color:var(--teal); background:var(--teal-tint); padding:2px 8px; border-radius:999px; margin-bottom:6px; }
  .free-note{ font-size:12.5px; color:var(--sage); font-weight:700; background:var(--sage-tint); padding:8px 12px; border-radius:8px; }

  .ov-head{ display:flex; align-items:center; gap:14px; margin-bottom:18px; padding:16px 20px; border-radius:14px; }
  .ov-head-nurse{ background:linear-gradient(135deg, var(--teal-tint), #fff); border:1px solid var(--teal-tint); }
  .ov-head-therapist{ background:linear-gradient(135deg, var(--indigo-tint), #fff); border:1px solid var(--indigo-tint); }
  .ov-icon{ font-size:26px; width:48px; height:48px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 6px rgba(0,0,0,.08); flex-shrink:0; }

  table.ov-table{ box-shadow:0 3px 14px rgba(30,42,40,.06); }
  table.ov-table tr:hover td{ background:#FAFBFA; }
  .staff-cell{ display:flex; align-items:center; gap:9px; }
  .avatar{
    width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center;
    font-size:11.5px; font-weight:700; color:#fff; flex-shrink:0;
  }
  .avatar.nurse{ background:var(--teal); }
  .avatar.therapist{ background:var(--indigo); }

  .strip{ display:flex; gap:4px; }
  .strip .blk{
    width:20px; height:20px; border-radius:6px; background:var(--sage-tint); border:1px solid var(--sage);
    position:relative; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:9px;
    transition:transform .12s ease;
  }
  .strip .blk.busy{ background:var(--brick-tint); border-color:var(--brick); color:var(--brick); }
  .strip .blk.busy::after{ content:'●'; font-size:7px; }
  .strip .blk.partial{ background:var(--amber-tint); border-color:var(--amber); color:var(--amber); }
  .strip .blk.partial::after{ content:'◐'; font-size:10px; }
  .strip .blk.off{ background:#EEEFEC; border-color:#D8DAD5; cursor:default; }
  .strip .blk.hospitalized{ background:#2A2A2A; border-color:#000; color:#fff; }
  .strip .blk.hospitalized::after{ content:'入'; font-size:9px; font-weight:700; }
  .strip .blk:hover{ transform:scale(1.18); }
  .strip .blk:focus-visible{ outline:2px solid var(--teal); outline-offset:1px; }
  table.ov-table th, table.ov-table td{ padding:6px 6px; }
  table.ov-table .strip{ gap:3px; }
  table.ov-table .strip .blk{ width:17px; height:17px; border-radius:5px; }
  table.ov-table .staff-cell{ gap:6px; }
  table.ov-table .avatar{ width:22px; height:22px; font-size:10px; }

  .staff-row-item{ display:flex; align-items:center; justify-content:space-between; background:var(--surface); border:1px solid var(--line); border-radius:8px; padding:9px 12px; margin-bottom:6px; }
  .role-pill{ font-size:10px; font-weight:700; padding:2px 9px; border-radius:999px; margin-left:8px; }
  .role-pill.nurse{ background:var(--teal-tint); color:var(--teal-deep); }
  .role-pill.therapist{ background:var(--indigo-tint); color:var(--indigo); }
  .role-pill.office{ background:#EDEDF0; color:#5B6B67; }
  .qual-chip{
    display:inline-flex; align-items:center; gap:4px; font-size:10px; font-weight:700; color:var(--sage);
    background:var(--sage-tint); padding:2px 8px; border-radius:999px; margin-left:6px; margin-top:2px;
  }
  .qual-chip button{ all:unset; cursor:pointer; font-weight:700; line-height:1; padding:0 1px; }
  .qual-chip button:hover{ color:var(--brick); }
  .qual-add-row{ display:flex; gap:6px; flex-wrap:wrap; align-items:center; margin-top:6px; width:100%; }
  .qual-add-row select, .qual-add-row input[type=text]{
    width:auto; max-width:170px; padding:5px 8px; font-size:11.5px; border:1px solid var(--line); border-radius:6px;
    font-family:var(--font-ui);
  }

  form.intake{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:20px 22px; display:grid; grid-template-columns:1fr 1fr; gap:16px 22px; }
  form.intake .full{ grid-column:1/-1; }
  label{ display:block; font-size:12px; color:var(--ink-soft); margin-bottom:5px; font-weight:600;}
  input[type=text], select, textarea{
    width:100%; padding:8px 10px; border:1px solid var(--line); border-radius:7px; font-size:13.5px;
    font-family:var(--font-ui); background:#fcfdfc;
  }
  input:focus-visible, select:focus-visible, textarea:focus-visible, button:focus-visible{
    outline:2px solid var(--teal); outline-offset:1px;
  }
  .chip-group{ display:flex; gap:6px; flex-wrap:wrap; }
  .chip{
    border:1px solid var(--line); background:#fcfdfc; border-radius:999px; padding:6px 13px; font-size:12.5px;
    cursor:pointer; user-select:none; color:var(--ink-soft);
  }
  .chip.on{ background:var(--teal); border-color:var(--teal); color:#fff; font-weight:700;}
  .btn{
    all:unset; cursor:pointer; display:inline-block; padding:10px 22px; border-radius:8px; font-size:13.5px;
    font-weight:700; font-family:var(--font-ui); text-align:center;
  }
  .btn-primary{ background:var(--teal); color:#fff; }
  .btn-primary:hover{ background:var(--teal-deep); }
  .btn-ghost{ background:transparent; border:1px solid var(--line); color:var(--ink-soft); }
  .btn-danger{ background:var(--brick); color:#fff; }
  .btn-small{ padding:6px 14px; font-size:12px; }

  .suggestions{ margin-top:22px; display:flex; flex-direction:column; gap:12px; }
  .sugg-card{
    background:var(--surface); border:1px solid var(--line); border-left:4px solid var(--teal);
    border-radius:var(--radius); padding:14px 18px; display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap;
  }
  .sugg-card.tier2{ border-left-color:var(--indigo); }
  .sugg-card.tier3{ border-left-color:var(--amber); }
  .sugg-tag{ font-size:10.5px; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:var(--teal); }
  .sugg-card.tier2 .sugg-tag{ color:var(--indigo); }
  .sugg-card.tier3 .sugg-tag{ color:var(--amber); }
  .sugg-text{ font-size:14.5px; font-weight:700; margin:3px 0 2px; font-family:var(--font-mono); }
  .sugg-sub{ font-size:12px; color:var(--ink-soft); }
  .empty-msg{ background:var(--brick-tint); color:var(--brick); border-radius:var(--radius); padding:16px 18px; font-size:13px; }

  .end-row{ display:flex; justify-content:space-between; align-items:center; background:var(--surface); border:1px solid var(--line); border-radius:8px; padding:11px 16px; margin-bottom:8px; font-size:13px; }
  .end-row .meta{ color:var(--ink-soft); font-size:11.5px; }

  .report-grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:12px; margin-bottom:26px; }
  .month-card{ background:var(--surface); border:1px solid var(--line); border-radius:var(--radius); padding:14px; }
  .month-card .m{ font-family:var(--font-mono); font-size:12px; color:var(--ink-soft); margin-bottom:8px;}
  .bar-row{ display:flex; align-items:center; gap:6px; margin-bottom:5px; }
  .bar-row .lbl{ width:34px; font-size:10.5px; color:var(--ink-soft); }
  .bar-track{ flex:1; background:#EEF1EF; border-radius:4px; height:12px; overflow:hidden; }
  .bar-fill{ height:100%; border-radius:4px; }
  .bar-fill.new{ background:var(--sage); }
  .bar-fill.end{ background:var(--brick); }
  .bar-row .val{ width:18px; font-family:var(--font-mono); font-size:11px; text-align:right; }

  .toast{
    position:fixed; bottom:24px; right:24px; background:var(--teal-deep); color:#fff; padding:12px 20px;
    border-radius:8px; font-size:13px; box-shadow:0 6px 20px rgba(0,0,0,.18); opacity:0; transform:translateY(8px);
    transition:all .25s ease; pointer-events:none; z-index:50;
  }
  .toast.show{ opacity:1; transform:none; }
  .footer-actions{ margin-top:34px; padding-top:16px; border-top:1px solid var(--line); display:flex; justify-content:flex-end;}
  .setting-row{ display:flex; align-items:center; gap:8px; background:var(--surface); border:1px solid var(--line); border-radius:8px; padding:9px 12px; margin-bottom:7px; }
  .setting-row input[type=text]{ flex:1; }
  .setting-row .idx{ font-family:var(--font-mono); font-size:11px; color:var(--ink-soft); width:18px; }
  .icon-btn{ all:unset; cursor:pointer; padding:5px 8px; border-radius:6px; font-size:12px; color:var(--ink-soft); border:1px solid var(--line); }
  .icon-btn:hover{ background:var(--teal-tint); color:var(--teal-deep); }
  .icon-btn.danger:hover{ background:var(--brick-tint); color:var(--brick); }
  .master-row{ display:flex; align-items:center; justify-content:space-between; background:var(--surface); border:1px solid var(--line); border-radius:8px; padding:8px 12px; margin-bottom:6px; font-size:13px; }
  @media (max-width:760px){
    .app{ flex-direction:column; }
    .nav{ width:100%; flex-direction:row; overflow-x:auto; padding:10px 6px; }
    .nav .brand{ display:none; }
    .nav button{ border-left:none; border-bottom:3px solid transparent; white-space:nowrap; }
    .nav button.active{ border-bottom-color:#8FD4C1; }
    main{ padding:18px 16px 50px; }
    form.intake{ grid-template-columns:1fr; }
    .ov-body{ flex-direction:column; }
    .ov-main{ width:100%; }
    .ov-alert{ width:100%; flex:1 1 auto; }
  }
  @media print{
    .no-print, .nav{ display:none !important; }
    .app{ display:block; }
    main{ max-width:none; padding:0; }
    .panel{ display:none !important; }
    .panel.active{ display:block !important; }
    body{ background:#fff; }
  }
</style>
</head>
<body>
<div class="app">
  <nav class="nav">
    <div class="brand">
      <div class="brand-logo-wrap"><img class="brand-logo" src="data:image/png;base64,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" alt="日本訪問看護学会">
      </div>
      <div class="brand-text">
        <p class="brand-org">日本訪問看護学会</p>
        <p class="brand-title">訪問看護<br>業務管理ステーション</p>
      </div>
    </div>
    <button data-panel="overview-nurse" class="active">① 空き状況〈看護師〉</button>
    <button data-panel="overview-therapist">② 空き状況〈セラピスト〉</button>
    <button data-panel="intake">③ 新規登録・提案</button>
    <button data-panel="end">④ 利用者検索</button>
    <button data-panel="inpatient">⑤ 入院管理表</button>
    <button data-panel="referral">⑥ リスト分析</button>
    <button data-panel="report">⑦ 月次レポート</button>
    <button data-panel="staff">⑧ スタッフ管理</button>
    <button data-panel="settings">⑨ 設定</button>
    <button type="button" class="nav-logout" id="navLogout">ログアウト</button>
  </nav>
  <main>

    <section id="panel-overview-nurse" class="panel active">
      <div class="ov-head ov-head-nurse">
        <div class="ov-icon">🩺</div>
        <div>
          <h2 class="page-title" style="margin:0;">看護師の空き状況</h2>
          <p class="page-sub" style="margin:2px 0 0;">枠の並びは左から <span id="slotHint-看護師"></span> の順です。タップすると詳細が見られます。</p>
        </div>
        <button type="button" class="btn btn-ghost btn-small print-btn no-print" style="margin-left:auto;">🖨 PDF出力</button>
      </div>
      <div class="ov-body">
        <div class="ov-main" id="ovMain-看護師">
          <div class="summary-row" id="summaryRow-看護師"></div>
          <p class="page-sub" style="margin:2px 0 6px;font-weight:700;color:var(--teal-deep);">月〜金 合計からの受け入れ可能人数</p>
          <div class="summary-row" id="capacityRow-看護師"></div>
          <p class="page-sub" id="bufferNote-看護師" style="margin:-6px 0 14px;"></p>
        </div>
        <aside class="ov-alert no-print" id="ovAlert-看護師">
          <p class="ov-alert-title">🔔 受け入れ枠アラート</p>
          <p class="ov-alert-sub">受け入れ枠が2件以上空いている曜日・時間帯です</p>
          <ul class="ov-alert-list" id="slotAlertList-看護師"></ul>
        </aside>
      </div>
      <div class="legend" id="legend-看護師"><span><i style="background:var(--sage-tint);border:1px solid var(--sage)"></i>空き</span>
        <span><i style="background:var(--amber-tint);border:1px solid var(--amber)"></i>一部空き（隔週・月次ローテーション）</span>
        <span><i style="background:var(--brick-tint);border:1px solid var(--brick)"></i>使用中（満枠）</span>
        <span><i style="background:#2A2A2A;border:1px solid #000"></i>入院中</span>
        <span><i style="background:#EEEFEC;border:1px solid #D8DAD5"></i>非勤務日</span></div>
      <div style="overflow-x:auto;">
        <table class="grid ov-table" id="overviewTable-看護師"></table>
      </div>
    </section>

    <section id="panel-overview-therapist" class="panel">
      <div class="ov-head ov-head-therapist">
        <div class="ov-icon">🧘</div>
        <div>
          <h2 class="page-title" style="margin:0;">セラピストの空き状況</h2>
          <p class="page-sub" style="margin:2px 0 0;">枠の並びは左から <span id="slotHint-セラピスト"></span> の順です。タップすると詳細が見られます。</p>
        </div>
        <button type="button" class="btn btn-ghost btn-small print-btn no-print" style="margin-left:auto;">🖨 PDF出力</button>
      </div>
      <div class="ov-body">
        <div class="ov-main" id="ovMain-セラピスト">
          <div class="summary-row" id="summaryRow-セラピスト"></div>
          <p class="page-sub" style="margin:2px 0 6px;font-weight:700;color:var(--teal-deep);">月〜金 合計からの受け入れ可能人数</p>
          <div class="summary-row" id="capacityRow-セラピスト"></div>
          <p class="page-sub" id="bufferNote-セラピスト" style="margin:-6px 0 14px;"></p>
        </div>
        <aside class="ov-alert no-print" id="ovAlert-セラピスト">
          <p class="ov-alert-title">🔔 受け入れ枠アラート</p>
          <p class="ov-alert-sub">受け入れ枠が2件以上空いている曜日・時間帯です</p>
          <ul class="ov-alert-list" id="slotAlertList-セラピスト"></ul>
        </aside>
      </div>
      <div class="legend" id="legend-セラピスト"><span><i style="background:var(--sage-tint);border:1px solid var(--sage)"></i>空き</span>
        <span><i style="background:var(--amber-tint);border:1px solid var(--amber)"></i>一部空き（隔週・月次ローテーション）</span>
        <span><i style="background:var(--brick-tint);border:1px solid var(--brick)"></i>使用中（満枠）</span>
        <span><i style="background:#2A2A2A;border:1px solid #000"></i>入院中</span>
        <span><i style="background:#EEEFEC;border:1px solid #D8DAD5"></i>非勤務日</span></div>
      <div style="overflow-x:auto;">
        <table class="grid ov-table" id="overviewTable-セラピスト"></table>
      </div>
    </section>

    <section id="panel-staff" class="panel">
      <h2 class="page-title">スタッフ管理</h2>
      <p class="page-sub">看護師・セラピスト・事務員の追加や削除ができます。削除は、そのスタッフに現在ご利用中の予定がない場合のみ行えます。↑↓で並び順を自由に変更できます（①②の表示順に反映されます）。名前はそのまま書き換えられます。資格・専門性はスタッフごとに複数登録できます。事務員は名簿に載るだけで、空き状況の対象にはなりません。</p>
      <div id="staffList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;align-items:center;">
        <input type="text" id="newStaffName" placeholder="スタッフ名" style="max-width:180px;">
        <select id="newStaffRole" style="max-width:140px;">
          <option value="看護師">看護師</option>
          <option value="セラピスト">セラピスト</option>
          <option value="事務員">事務員</option>
        </select>
        <button class="btn btn-ghost btn-small" id="addStaffBtn">＋ スタッフを追加</button>
      </div>
    </section>

    <section id="panel-intake" class="panel">
      <h2 class="page-title">新規登録・自動提案</h2>
      <p class="page-sub">条件を入れると、今の空き状況から候補枠を自動で探して提案します（第三者への送信は行わず、この画面内だけで計算しています）。</p>
      <form class="intake" id="intakeForm">
        <div>
          <label for="f-role">職種</label>
          <select id="f-role">
            <option value="看護師">看護師</option>
            <option value="セラピスト">セラピスト</option>
          </select>
        </div>
        <div>
          <label for="f-name">利用者様 氏名</label>
          <input type="text" id="f-name" placeholder="例：山田太郎">
        </div>
        <div class="full" id="existingPatientNotice" style="display:none;background:var(--amber-tint);border:1px solid var(--amber);border-radius:8px;padding:8px 12px;font-size:12.5px;"></div>
        <div>
          <label for="f-pattern">訪問頻度パターン</label>
          <select id="f-pattern">
            <option value="weekly">毎週（曜日を指定・複数日選択可）</option>
            <option value="daily">毎日（月〜金・週5回）</option>
            <option value="daily7">毎日（月〜日・週7回、土日訪問あり）</option>
            <option value="biweekly_13">隔週（第1・3週）</option>
            <option value="biweekly_135">隔週（第1・3・5週）</option>
            <option value="biweekly_24">隔週（第2・4週）</option>
            <option value="monthly_1">月1回（第1週）</option>
            <option value="monthly_2">月1回（第2週）</option>
            <option value="monthly_3">月1回（第3週）</option>
            <option value="monthly_4">月1回（第4週）</option>
          </select>
        </div>
        <div id="freqWeeklyWrap">
          <label for="f-freq">毎週の場合の回数（週）</label>
          <select id="f-freq">
            <option value="1">週1回</option>
            <option value="2" selected>週2回</option>
            <option value="3">週3回</option>
            <option value="4">週4回</option>
            <option value="5">週5回（毎日）</option>
            <option value="6">週6回</option>
            <option value="7">週7回（毎日・土日含む）</option>
          </select>
        </div>
        <div class="full">
          <label id="dayChipsLabel">希望曜日（未選択＝指定なし。土・日も選択できます）</label>
          <div class="chip-group" id="dayChips"></div>
        </div>
        <div class="full" style="background:var(--amber-tint);border:1px solid var(--amber);border-radius:8px;padding:8px 12px;">
          <label style="display:flex;align-items:center;gap:6px;font-weight:600;margin-bottom:0;">
            <input type="checkbox" id="f-weekend-exception" style="width:auto;">
            特例（通常の勤務体系に関わらず、指定した曜日・スタッフに土日訪問として登録する）
          </label>
          <p class="page-sub" style="margin:4px 0 0;">土曜・日曜への訪問がどうしても必要な方向けの特例登録です。チェックを入れると、⑨設定で土日勤務がOFFのスタッフも候補に含めて提案します。登録後は①②の土日欄に名前が表示されます。</p>
        </div>
        <div class="full">
          <label>希望時間帯（未選択＝指定なし）</label>
          <div class="chip-group" id="slotChips"></div>
        </div>
        <div>
          <label for="f-disease">疾患名</label>
          <input type="text" id="f-disease" placeholder="例：認知症">
        </div>
        <div>
          <label for="f-insurance">主保険</label>
          <select id="f-insurance">
            <option value="">選択してください</option>
            <option value="医療保険">医療保険</option>
            <option value="介護保険">介護保険</option>
            <option value="精神">精神</option>
            <option value="小児">小児</option>
          </select>
        </div>
        <div>
          <label for="f-duration">サービス時間</label>
          <select id="f-duration">
            <option value="30">30分</option>
            <option value="60" selected>60分</option>
            <option value="90">90分</option>
          </select>
        </div>
        <div class="full" id="companionOuterWrap" style="display:none;">
          <label style="display:flex;align-items:center;gap:6px;font-weight:600;margin-bottom:0;">
            <input type="checkbox" id="f-companion-enable" style="width:auto;">
            同じ枠にもう1名を登録する（ご夫婦など。例：主人11:45〜／妻12:15〜を同じ11:45枠として登録）
          </label>
        </div>
        <div class="full" id="companionFields" style="display:none;">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 22px;">
            <div>
              <label for="f-comp-name">もう1名の氏名</label>
              <input type="text" id="f-comp-name" placeholder="例：山田花子">
            </div>
            <div>
              <label for="f-comp-time">もう1名の実施時刻メモ</label>
              <input type="text" id="f-comp-time" placeholder="例：12:15〜">
            </div>
            <div>
              <label for="f-comp-disease">もう1名の疾患名</label>
              <input type="text" id="f-comp-disease" placeholder="例：高血圧症">
            </div>
            <div>
              <label for="f-comp-insurance">もう1名の主保険</label>
              <select id="f-comp-insurance">
                <option value="">選択してください</option>
                <option value="医療保険">医療保険</option>
                <option value="介護保険">介護保険</option>
                <option value="精神">精神</option>
                <option value="小児">小児</option>
              </select>
            </div>
          </div>
        </div>
        <div>
          <label for="f-alone">独居</label>
          <select id="f-alone">
            <option value="不明">不明</option>
            <option value="はい">はい</option>
            <option value="いいえ">いいえ</option>
          </select>
        </div>
        <div>
          <label for="f-cm">居宅介護支援事業所</label>
          <select id="f-cm"></select>
        </div>
        <div>
          <label for="f-hosp">医療機関（訪問看護指示書発行元）</label>
          <select id="f-hosp"></select>
        </div>
        <div>
          <label for="f-district">地区</label>
          <select id="f-district"></select>
        </div>
        <div class="full">
          <label for="f-timenote">実施時刻メモ（例外的な時間の場合のみ）</label>
          <input type="text" id="f-timenote" placeholder="例：本来15:30枠だが実際は16:00訪問、など">
        </div>
        <div class="full">
          <label for="f-note">備考</label>
          <textarea id="f-note" rows="2" placeholder="任意"></textarea>
        </div>
        <div class="full">
          <button type="submit" class="btn btn-primary">空き枠を探す</button>
        </div>
      </form>
      <div class="suggestions" id="suggestions"></div>
    </section>

    <section id="panel-end" class="panel">
      <h2 class="page-title">利用者検索</h2>
      <p class="page-sub">現在ご利用中の利用者様の一覧です。氏名で検索して、内容の確認・曜日や時間帯の変更・入院中の登録・終了処理がこの画面からまとめて行えます。</p>
      <input type="text" id="endSearch" placeholder="利用者名で検索…" style="max-width:280px;margin-bottom:14px;padding:8px 10px;border:1px solid var(--line);border-radius:7px;font-size:13.5px;font-family:var(--font-ui);">
      <div id="endList"></div>
    </section>

    <section id="panel-inpatient" class="panel">
      <h2 class="page-title">入院管理表</h2>
      <p class="page-sub">④利用者検索で「入院中」にチェックを入れた利用者様の一覧です。①②の空き状況では該当の枠が黒色で表示されます。退院された場合はここでチェックを外すと通常表示に戻ります。</p>
      <input type="text" id="inpatientSearch" placeholder="利用者名で検索…" style="max-width:280px;margin-bottom:14px;padding:8px 10px;border:1px solid var(--line);border-radius:7px;font-size:13.5px;font-family:var(--font-ui);">
      <div id="inpatientList"></div>
    </section>

    <section id="panel-referral" class="panel">
      <div style="display:flex;align-items:center;gap:14px;">
        <h2 class="page-title" style="margin:0;">リスト分析</h2>
        <div class="no-print" style="margin-left:auto;display:flex;gap:8px;">
          <button type="button" class="btn btn-ghost btn-small" id="csvExportReferral">📥 CSV出力</button>
          <button type="button" class="btn btn-ghost btn-small print-btn">🖨 PDF出力</button>
        </div>
      </div>
      <p class="page-sub">「居宅介護支援事業所」「医療機関（訪問看護指示書発行元）」ごとに、現在の実利用者数・全体に占める割合・直近の新規紹介数を集計します。※ここでの「割合」は自社の現在の利用者全体に占めるシェアという意味で計算しています（各事業所が抱える利用者全体の中での割合ではありません）。</p>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:22px 0 8px;">居宅介護支援事業所別</h3>
      <table class="grid" id="cmTable"></table>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">医療機関別（訪問看護指示書発行元）</h3>
      <table class="grid" id="hospTable"></table>
    </section>

    <section id="panel-report" class="panel">
      <div style="display:flex;align-items:center;gap:14px;">
        <h2 class="page-title" style="margin:0;">月次レポート</h2>
        <div class="no-print" style="margin-left:auto;display:flex;gap:8px;">
          <button type="button" class="btn btn-ghost btn-small" id="csvExportReport">📥 CSV出力</button>
          <button type="button" class="btn btn-ghost btn-small print-btn">🖨 PDF出力</button>
        </div>
      </div>
      <p class="page-sub">このアプリ上で登録・終了処理をした件数を月ごとに集計しています（Excel側の既存データは対象になりません）。</p>
      <div class="report-grid" id="reportGrid"></div>
      <table class="grid" id="reportTable"></table>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">終了理由の内訳（全期間）</h3>
      <table class="grid" id="reasonTable"></table>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">疾患名の内訳（現在の利用者）</h3>
      <div style="overflow-x:auto;"><table class="grid" id="diseaseTable"></table></div>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">主保険の内訳（現在の利用者）</h3>
      <div style="overflow-x:auto;"><table class="grid" id="insuranceTable"></table></div>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">独居の内訳（現在の利用者）</h3>
      <div style="overflow-x:auto;"><table class="grid" id="aloneTable"></table></div>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">地区の内訳（現在の利用者）</h3>
      <div style="overflow-x:auto;"><table class="grid" id="districtTable"></table></div>
    </section>

    <section id="panel-settings" class="panel">
      <h2 class="page-title">設定</h2>
      <p class="page-sub">時間帯の枠構成や、居宅介護支援事業所・医療機関のマスタを編集できます。</p>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:8px 0 8px;">空き枠数の計算調整</h3>
      <p class="page-sub" style="margin-bottom:10px;">出勤予定でも、休み明けや夜勤明けなどで実際には稼働できないスタッフがいます。①②の「空き余地」の数字を計算するとき、看護師・セラピストそれぞれ勤務予定人数から何名分を差し引くか設定できます（0なら差し引きなし）。事業所の実情に合わせて調整してください。</p>
      <div class="setting-row"><label style="margin:0;flex:0 0 auto;min-width:220px;">看護師：勤務予定から差し引く人数</label><input type="number" id="bufferNurse" min="0" style="max-width:100px;" value="0"></div>
      <div class="setting-row"><label style="margin:0;flex:0 0 auto;min-width:220px;">セラピスト：勤務予定から差し引く人数</label><input type="number" id="bufferTherapist" min="0" style="max-width:100px;" value="0"></div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">時間帯（枠）の設定</h3>
      <p class="page-sub" style="margin-bottom:10px;">看護師とセラピストで別々の時間帯を設定できます。名前の変更・並べ替え・追加・削除ができます。削除は、その枠に現在ご利用中の予定がない場合のみ行えます。</p>
      <h4 style="font-size:12.5px;color:var(--ink-soft);margin:14px 0 6px;font-weight:700;">看護師の時間帯</h4>
      <div id="slotSettingsList-看護師"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newSlotLabel-看護師" placeholder="新しい枠の名前（例：16:00〜）" style="max-width:240px;">
        <button class="btn btn-ghost btn-small" id="addSlotBtn-看護師">＋ 枠を追加</button>
      </div>
      <h4 style="font-size:12.5px;color:var(--ink-soft);margin:20px 0 6px;font-weight:700;">セラピストの時間帯</h4>
      <div id="slotSettingsList-セラピスト"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newSlotLabel-セラピスト" placeholder="新しい枠の名前（例：16:00〜）" style="max-width:240px;">
        <button class="btn btn-ghost btn-small" id="addSlotBtn-セラピスト">＋ 枠を追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">看護師の勤務体系（曜日・時間帯）</h3>
      <p class="page-sub" style="margin-bottom:10px;">①の空き状況と同じように、枠（時間帯）ごとにタップしてON/OFFを切り替えられます。お昼で帰るなど、曜日によって勤務する枠が違うスタッフにも対応できます。土曜・日曜も含め、勤務しない枠は空き状況・自動提案の対象になりません。</p>
      <div style="overflow-x:auto;"><table class="grid" id="workdayTable-看護師"></table></div>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:24px 0 8px;">セラピストの勤務体系（曜日・時間帯）</h3>
      <div style="overflow-x:auto;"><table class="grid" id="workdayTable-セラピスト"></table></div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">居宅介護支援事業所マスタ</h3>
      <div id="cmList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newCmName" placeholder="事業所名を入力">
        <button class="btn btn-ghost btn-small" id="addCmBtn">＋ 追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">医療機関マスタ（訪問看護指示書発行元）</h3>
      <div id="hospList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newHospName" placeholder="医療機関名を入力">
        <button class="btn btn-ghost btn-small" id="addHospBtn">＋ 追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">地区マスタ</h3>
      <p class="page-sub" style="margin-bottom:10px;">例：さいたま市緑区、川口市　など。登録しておくと③新規登録・提案で地区を選べるようになります。</p>
      <div id="districtList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newDistrictName" placeholder="地区名を入力（例：さいたま市緑区）">
        <button class="btn btn-ghost btn-small" id="addDistrictBtn">＋ 追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--brick);margin:34px 0 8px;">データ管理</h3>
      <p class="page-sub" style="margin-bottom:10px;">この操作は全員が共有しているデータに影響します。取り扱いに注意してください。</p>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn btn-ghost btn-small" id="clearCustomersBtn" style="border-color:var(--brick);color:var(--brick);">利用者情報を初期化（一括消去）</button>
        <button class="btn btn-ghost btn-small" id="resetBtn" style="border-color:var(--brick);color:var(--brick);">共有データを初期状態に戻す（全員に影響します）</button>
      </div>
      <p class="page-sub" style="margin-top:8px;">「利用者情報を初期化」はスタッフ・時間帯枠・設定はそのままに、登録済みの利用者情報だけを消去します。「共有データを初期状態に戻す」はスタッフ構成なども含めてExcel取り込み時点の状態に戻します。</p>
    </section>
  </main>
</div>
<div class="toast" id="toast"></div>
<div class="modal-overlay" id="slotModal" hidden>
  <div class="modal-box">
    <button class="modal-close" id="modalClose" aria-label="閉じる">×</button>
    <div id="modalContent"></div>
  </div>
</div>

<script>
const SEED = {"days": ["月", "火", "水", "木", "金"], "slotLabels": ["9:00", "10:30", "11:45", "14:00", "15:30", "16:30"], "staffOrder": ["渡辺 優花", "髙橋あつ子", "土屋 瑛介", "金子 昌美", "根岸 哲也", "長谷川 航", "唐崎 祐也", "市川 浩子", "逸見  渚", "三石 千春", "岡元摩希子", "原田 菜那", "藤原 照代", "鈴木 清香", "金井千鶴子", "齋藤 栄子", "茂呂恵理香", "伊藤 敦子"], "staffWorkdays": {"渡辺 優花": ["月", "火", "水", "木", "金"], "髙橋あつ子": ["月", "火", "水", "木", "金"], "土屋 瑛介": ["月", "火", "水", "木", "金"], "金子 昌美": ["月", "火", "水", "木", "金"], "根岸 哲也": ["月", "火", "水", "木", "金"], "長谷川 航": ["月", "火", "水", "木", "金"], "唐崎 祐也": ["月", "火", "水", "木", "金"], "市川 浩子": ["月", "火", "水", "木", "金"], "逸見  渚": ["月", "火", "水", "木", "金"], "三石 千春": ["月", "火", "水", "木", "金"], "岡元摩希子": ["月", "火", "水", "木", "金"], "原田 菜那": ["月", "火", "水", "木", "金"], "藤原 照代": ["月", "火", "水", "木", "金"], "鈴木 清香": ["月", "火", "水", "木", "金"], "金井千鶴子": ["月", "火", "水", "木", "金"], "齋藤 栄子": ["月", "火", "水"], "茂呂恵理香": ["月", "火"], "伊藤 敦子": ["水", "木", "金"]}, "legacyOccupancy": {"渡辺 優花|月": [[], [], [], [], [], []], "髙橋あつ子|月": [[], [], [], [], [], []], "土屋 瑛介|月": [[], [], [], [], [], []], "金子 昌美|月": [[], [], [], [], [], []], "根岸 哲也|月": [[], [], [], [], [{"weeks": [1], "timeNote": ""}, {"weeks": [3], "timeNote": ""}], []], "長谷川 航|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "唐崎 祐也|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "市川 浩子|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}, {"weeks": [2], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "逸見  渚|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "三石 千春|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}, {"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "岡元摩希子|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1], "timeNote": ""}, {"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "原田 菜那|月": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "藤原 照代|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "鈴木 清香|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "金井千鶴子|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], []], "齋藤 栄子|月": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], []], "茂呂恵理香|月": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}, {"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "渡辺 優花|火": [[], [], [], [], [], []], "髙橋あつ子|火": [[], [], [], [], [], []], "土屋 瑛介|火": [[], [], [], [], [], []], "金子 昌美|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [], [], []], "根岸 哲也|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [2, 4], "timeNote": ""}], [], []], "長谷川 航|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "唐崎 祐也|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "市川 浩子|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": "10:00"}, {"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "逸見  渚|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "三石 千春|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "岡元摩希子|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "原田 菜那|火": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [], []], "藤原 照代|火": [[{"weeks": [2, 4], "timeNote": ""}, {"weeks": [3], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "鈴木 清香|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [3], "timeNote": ""}, {"weeks": [4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "金井千鶴子|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [], []], "齋藤 栄子|火": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "茂呂恵理香|火": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "渡辺 優花|水": [[], [], [], [], [], []], "髙橋あつ子|水": [[], [], [], [], [], []], "土屋 瑛介|水": [[], [], [], [], [], []], "金子 昌美|水": [[], [], [], [], [], []], "根岸 哲也|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1], "timeNote": ""}, {"weeks": [2], "timeNote": ""}, {"weeks": [4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "長谷川 航|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "唐崎 祐也|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "市川 浩子|水": [[{"weeks": [2], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "逸見  渚|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "三石 千春|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}, {"weeks": [2], "timeNote": ""}, {"weeks": [4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "岡元摩希子|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1], "timeNote": ""}, {"weeks": [2], "timeNote": ""}, {"weeks": [3], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "原田 菜那|水": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [], []], "藤原 照代|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}, {"weeks": [2, 4], "timeNote": ""}], [], []], "鈴木 清香|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}, {"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "金井千鶴子|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1], "timeNote": ""}, {"weeks": [2, 4], "timeNote": ""}, {"weeks": [3], "timeNote": ""}], [{"weeks": [3], "timeNote": ""}, {"weeks": [3], "timeNote": ""}], [], [], []], "齋藤 栄子|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], []], "伊藤 敦子|水": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "渡辺 優花|木": [[], [], [], [], [], []], "髙橋あつ子|木": [[], [], [], [], [], []], "土屋 瑛介|木": [[], [], [], [], [], []], "金子 昌美|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}, {"weeks": [4], "timeNote": "09:30"}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "根岸 哲也|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "長谷川 航|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [2], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}], []], "唐崎 祐也|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [3], "timeNote": ""}], []], "市川 浩子|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "逸見  渚|木": [[{"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "三石 千春|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "岡元摩希子|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "原田 菜那|木": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "藤原 照代|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "鈴木 清香|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "金井千鶴子|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], []], "伊藤 敦子|木": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "渡辺 優花|金": [[], [], [], [], [], []], "髙橋あつ子|金": [[], [], [], [], [], []], "土屋 瑛介|金": [[], [], [], [], [], []], "金子 昌美|金": [[], [], [], [], [], []], "根岸 哲也|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "長谷川 航|金": [[], [], [], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "唐崎 祐也|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "市川 浩子|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "逸見  渚|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}, {"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "三石 千春|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "岡元摩希子|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []], "原田 菜那|金": [[], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], [{"weeks": [2], "timeNote": ""}, {"weeks": [3], "timeNote": ""}, {"weeks": [4], "timeNote": ""}], []], "藤原 照代|金": [[{"weeks": [2, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "鈴木 清香|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], []], "金井千鶴子|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [], [], []], "伊藤 敦子|金": [[{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], [{"weeks": [1, 3], "timeNote": ""}], [{"weeks": [1, 2, 3, 4], "timeNote": ""}], []]}};
</script>
<script>
// ---------- 基本データ ----------
const DAYS = ['月','火','水','木','金','土','日'];
const WEEKDAYS = ['月','火','水','木','金'];
function isWeekendDay(d){ return d==='土' || d==='日'; }
const WEEKS = [1,2,3,4,5];
const ROLES = ['看護師','セラピスト'];
const END_REASONS = ['入所','看取り','卒業'];
const INSURANCE_TYPES = ['医療保険','介護保険','精神','小児'];
const QUALIFICATION_PRESETS = ['理学療法士','作業療法士','言語聴覚士','特定行為看護師','ケアマネジャー'];
const QUALIFICATION_TEMPLATES = [
  { value:'__certified', suffix:'認定看護師', label:'○○認定看護師（自由記述）', placeholder:'例：皮膚・排泄ケア' },
  { value:'__specialist', suffix:'専門看護師', label:'○○専門看護師（自由記述）', placeholder:'例：がん看護' },
  { value:'__other', suffix:'', label:'他ライセンス（自由記述）', placeholder:'資格名を入力' },
];

const SERVICE_DURATIONS = ['30','60','90'];

const PATTERNS = {
  weekly:       { label:'毎週',              kind:'weekly' },
  daily:        { label:'毎日（月〜金・週5回）', kind:'weekly', fixedFreq:5 },
  daily7:       { label:'毎日（月〜日・週7回、土日訪問あり）', kind:'weekly', fixedFreq:7, includeWeekend:true },
  biweekly_13:  { label:'隔週（第1・3週）',   kind:'rotation', weeks:[1,3] },
  biweekly_135: { label:'隔週（第1・3・5週）', kind:'rotation', weeks:[1,3,5] },
  biweekly_24:  { label:'隔週（第2・4週）',   kind:'rotation', weeks:[2,4] },
  monthly_1:    { label:'月1回（第1週）',     kind:'rotation', weeks:[1] },
  monthly_2:    { label:'月1回（第2週）',     kind:'rotation', weeks:[2] },
  monthly_3:    { label:'月1回（第3週）',     kind:'rotation', weeks:[3] },
  monthly_4:    { label:'月1回（第4週）',     kind:'rotation', weeks:[4] },
};

let state = null; // { staff, slotLabels, staffWorkSlots, bookings, eventLog, referralSources }
let uidCounter = 0;
function newId(){ uidCounter++; return Date.now()+'-'+uidCounter+'-'+Math.random().toString(36).slice(2,6); }

function todayStr(){
  const d = new Date();
  return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
}
function monthKey(dateStr){ return dateStr.slice(0,7); }
function slotLabelsFor(role){ return (state.slotLabels && state.slotLabels[role]) || []; }
function slotCount(role){ return slotLabelsFor(role).length; }
function staffNames(role){
  return state.staff.filter(s=>!role || s.role===role).map(s=>s.name);
}
function staffInfo(name){
  return state.staff.find(s=>s.name===name) || {name, role:'看護師'};
}
function roleLabel(name){
  const info = staffInfo(name);
  const quals = (info.qualifications||[]).filter(Boolean);
  return quals.length ? quals.join('・') : info.role;
}
function labelForWeeks(weeks){
  const sorted = (weeks||WEEKS).slice().sort((a,b)=>a-b);
  if(sorted.length>=WEEKS.length) return '毎週';
  const key = sorted.join(',');
  if(key==='1,3') return '隔週（第1・3週）';
  if(key==='1,3,5') return '隔週（第1・3・5週）';
  if(key==='2,4') return '隔週（第2・4週）';
  if(sorted.length===1) return `月1回（第${sorted[0]}週）`;
  return `第${sorted.join('・')}週`;
}

// ---------- 状態の読み込み・初期化・移行 ----------
async function loadState(){
  try{
    const res = await fetch('api/state.php', { credentials:'same-origin' });
    if(res.status === 401){ location.href = 'login.php'; return; }
    if(res.ok){
      const json = await res.json();
      if(json.data){
        state = migrateState(JSON.parse(json.data));
        await saveState();
        return;
      }
    }
  }catch(e){ console.error('読み込みに失敗しました', e); }
  state = freshState();
  await saveState();
}

function freshState(){
  const staff = SEED.staffOrder.map(name=>({name, role:'看護師'}));
  const nSlots = SEED.slotLabels.length;
  const staffWorkSlots = {};
  staff.forEach(s=>{
    const obj = {};
    (SEED.staffWorkdays[s.name]||[]).forEach(d=>{ obj[d] = Array.from({length:nSlots},(_,i)=>i); });
    staffWorkSlots[s.name] = obj;
  });
  return {
    staff,
    slotLabels: { '看護師': SEED.slotLabels.slice(), 'セラピスト': SEED.slotLabels.slice() },
    staffWorkSlots,
    bookings: {},
    eventLog: [],
    referralSources: { careManagers: [], hospitals: [] },
    staffBuffer: { '看護師': 0, 'セラピスト': 0 },
    districts: []
  };
}

function migrateState(loaded){
  // スタッフ一覧が無い旧バージョンの場合、SEEDの看護師一覧として補完
  if(!loaded.staff){
    const names = new Set(SEED.staffOrder);
    Object.keys(loaded.staffWorkdays||loaded.staffWorkSlots||{}).forEach(n=>names.add(n));
    loaded.staff = Array.from(names).map(name=>({name, role:'看護師'}));
  }
  if(!loaded.slotLabels){
    loaded.slotLabels = { '看護師': SEED.slotLabels.slice(), 'セラピスト': SEED.slotLabels.slice() };
  }else if(Array.isArray(loaded.slotLabels)){
    // 旧形式（看護師・セラピスト共通の単一配列）からの移行
    loaded.slotLabels = { '看護師': loaded.slotLabels.slice(), 'セラピスト': loaded.slotLabels.slice() };
  }
  if(!loaded.staffWorkSlots){
    // 旧形式（曜日単位のON/OFF）からの移行：出勤日はその職種の全枠を勤務扱いにする
    loaded.staffWorkSlots = {};
    loaded.staff.forEach(s=>{
      const nSlots = (loaded.slotLabels[s.role] || loaded.slotLabels['看護師'] || []).length;
      const days = (loaded.staffWorkdays && loaded.staffWorkdays[s.name]) || (SEED.staffWorkdays[s.name]||[]);
      const obj = {};
      days.forEach(d=>{ obj[d] = Array.from({length:nSlots},(_,i)=>i); });
      loaded.staffWorkSlots[s.name] = obj;
    });
  }
  delete loaded.staffWorkdays;
  if(!loaded.referralSources) loaded.referralSources = { careManagers: [], hospitals: [] };
  if(!loaded.staffBuffer) loaded.staffBuffer = { '看護師': 0, 'セラピスト': 0 };
  if(!loaded.districts) loaded.districts = [];
  loaded.staff.forEach(s=>{
    if(!s.qualifications){ s.qualifications = s.jobTitle ? [s.jobTitle] : []; }
    delete s.jobTitle;
  });

  if(loaded.bookings) return loaded;

  // さらに古い形式(occupancy真偽配列 + assignments)からの移行
  const bookings = {};
  Object.entries(loaded.occupancy || {}).forEach(([key, flags])=>{
    const [staffName, day] = key.split('|');
    flags.forEach((busy, slotIdx)=>{
      if(busy){
        const asg = (loaded.assignments || {})[staffName+'|'+day+'|'+slotIdx] || {};
        const id = newId();
        bookings[id] = {
          staff: staffName, day, slotIdx, weeks: WEEKS.slice(), patternValue:'weekly',
          name: asg.name||'', disease: asg.disease||'', alone: asg.alone||'不明',
          careManager: asg.careManager||'', hospital: asg.hospital||'', timeNote: asg.timeNote||'',
          note: asg.note||'', startDate: asg.startDate || todayStr()
        };
      }
    });
  });
  loaded.bookings = bookings;
  return loaded;
}

async function saveState(){
  try{
    const res = await fetch('api/state.php', {
      method:'POST',
      credentials:'same-origin',
      headers:{ 'Content-Type':'application/json' },
      body: JSON.stringify({ data: JSON.stringify(state) })
    });
    if(res.status === 401){ location.href = 'login.php'; return; }
    if(!res.ok){
      console.error('保存に失敗しました', res.status);
      showToast('保存に失敗しました。通信状況をご確認ください。');
    }
  }catch(e){
    console.error('保存に失敗しました', e);
    showToast('保存に失敗しました。通信状況をご確認ください。');
  }
}

function workSlotsFor(staff, day){
  return (state.staffWorkSlots[staff] && state.staffWorkSlots[staff][day]) || [];
}
function worksOnSlot(staff, day, slotIdx){
  return workSlotsFor(staff, day).includes(slotIdx);
}
function worksOn(staff, day){
  return workSlotsFor(staff, day).length > 0;
}
function bookingsAt(staff, day, slotIdx){
  return Object.entries(state.bookings)
    .filter(([id,b])=>b.staff===staff && b.day===day && b.slotIdx===slotIdx)
    .map(([id,b])=>Object.assign({id}, b));
}
function occupiedWeeksAt(staff, day, slotIdx){
  const weeks = new Set();
  bookingsAt(staff,day,slotIdx).forEach(b=> b.weeks.forEach(w=>weeks.add(w)));
  return weeks;
}
function isFullyFree(staff, day, slotIdx){
  return bookingsAt(staff,day,slotIdx).length === 0;
}
function isRotationFree(staff, day, slotIdx, candWeeks){
  const used = occupiedWeeksAt(staff,day,slotIdx);
  return candWeeks.every(w=>!used.has(w));
}
function slotVisualState(staff, day, slotIdx){
  const bks = bookingsAt(staff,day,slotIdx);
  if(bks.length===0) return 'free';
  if(bks.some(b=>b.hospitalized)) return 'hospitalized';
  const used = occupiedWeeksAt(staff,day,slotIdx);
  if(WEEKS.every(w=>used.has(w))) return 'busy';
  return 'partial';
}
function totalLoad(staff){
  return Object.values(state.bookings).filter(b=>b.staff===staff).length;
}

// ---------- ナビゲーション ----------
const RENDERERS = {
  'overview-nurse': ()=>renderOverview('看護師'),
  'overview-therapist': ()=>renderOverview('セラピスト'),
  staff: renderStaffList,
  intake: ()=>{ populateReferralSelects(); updatePatternUI(); },
  end: renderEndList,
  inpatient: renderInpatientList,
  referral: renderReferralAnalysis,
  report: renderReport,
  settings: renderSettings
};
document.querySelectorAll('.nav button[data-panel]').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('.nav button[data-panel]').forEach(b=>b.classList.remove('active'));
    document.querySelectorAll('.panel').forEach(p=>p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-'+btn.dataset.panel).classList.add('active');
    const fn = RENDERERS[btn.dataset.panel];
    if(fn) fn();
  });
});
document.getElementById('navLogout').addEventListener('click', ()=>{ location.href = 'logout.php'; });
document.querySelectorAll('.print-btn').forEach(btn=>{
  btn.addEventListener('click', ()=>window.print());
});

function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'), 2600);
}

// ---------- CSV出力 ----------
function csvEscape(text){
  text = (text||'').replace(/\s+/g,' ').trim();
  if(/[",\n]/.test(text)) text = '"' + text.replace(/"/g,'""') + '"';
  return text;
}
function tableToCsvRows(table){
  return Array.from(table.querySelectorAll('tr')).map(tr=>
    Array.from(tr.querySelectorAll('th,td')).map(cell=>csvEscape(cell.textContent)).join(',')
  );
}
function exportPanelCsv(panelId, filename){
  const panel = document.getElementById(panelId);
  const tables = Array.from(panel.querySelectorAll('table.grid')).filter(t=>t.querySelector('tr'));
  if(!tables.length){ showToast('出力できるデータがありません'); return; }
  const lines = [];
  tables.forEach(table=>{
    let heading = table.previousElementSibling;
    if(!heading && table.parentElement && table.parentElement!==panel){
      heading = table.parentElement.previousElementSibling;
    }
    if(heading && /^H[1-6]$/.test(heading.tagName)) lines.push(csvEscape(heading.textContent));
    lines.push(...tableToCsvRows(table));
    lines.push('');
  });
  const blob = new Blob(['\uFEFF' + lines.join('\r\n')], {type:'text/csv;charset=utf-8;'});
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = filename;
  document.body.appendChild(a); a.click(); document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

// ---------- モーダル（タップで詳細） ----------
let editingBookingId = null;
function closeModal(){ document.getElementById('slotModal').hidden = true; editingBookingId = null; }
document.getElementById('modalClose').addEventListener('click', closeModal);
document.getElementById('slotModal').addEventListener('click', (e)=>{
  if(e.target.id==='slotModal') closeModal();
});

function patternLabelOf(b){ return labelForWeeks(b.weeks); }

async function endBookingById(id, reason){
  const b = state.bookings[id];
  if(!b) return;
  if(!reason){ alert('終了理由を選択してください。'); return; }
  const bSlotLabel = slotLabelsFor(staffInfo(b.staff).role)[b.slotIdx];
  if(!confirm(`${b.day}曜 ${bSlotLabel}〜（${b.staff}／${b.name||'名前未登録'}）を「${reason}」として終了にします。よろしいですか？`)) return;
  state.eventLog.push({
    id:newId(), type:'終了', date: todayStr(), name: b.name||'', staff:b.staff, day:b.day, slot:b.slotIdx,
    careManager: b.careManager||'', hospital: b.hospital||'', reason
  });
  delete state.bookings[id];
  await saveState();
  showToast('終了処理を反映しました');
  closeModal();
  renderOverview('看護師'); renderOverview('セラピスト');
  if(document.getElementById('panel-end').classList.contains('active')) renderEndList();
  if(document.getElementById('panel-inpatient').classList.contains('active')) renderInpatientList();
}

function refSelectOptions(list, current){
  return `<option value="">未設定</option>` + list.map(n=>`<option value="${n}" ${current===n?'selected':''}>${n}</option>`).join('');
}

function bookingReadView(b){
  const pairNote = (b.pairId && pairedBookingAt(b)) ? `
        <div class="brow" style="margin-top:6px;padding-top:6px;border-top:1px dashed var(--line);color:var(--ink-soft);">
          👫 同枠のご夫婦・ペア登録です（もう1名は別のカードに表示されています。編集・終了はそれぞれ個別に行えます）
        </div>` : '';
  return `
        <div class="btag">${patternLabelOf(b)}</div>
        <div class="bname">${b.name || '（名前未登録）'}</div>
        <div class="brow">主保険：${b.insuranceType||'―'}／サービス時間：${b.serviceDuration?b.serviceDuration+'分':'―'}</div>
        <div class="brow">疾患：${b.disease||'―'}／独居：${b.alone||'―'}</div>
        <div class="brow">居宅：${b.careManager||'―'}</div>
        <div class="brow">医療機関：${b.hospital||'―'}</div>
        <div class="brow">地区：${b.district||'―'}</div>
        ${b.timeNote?`<div class="brow">時刻メモ：${b.timeNote}</div>`:''}
        ${b.note?`<div class="brow">備考：${b.note}</div>`:''}
        <div class="brow">登録日：${b.startDate||'―'}</div>
        <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:var(--ink-soft);cursor:pointer;margin-top:4px;">
          <input type="checkbox" class="hosp-toggle-input" data-hosp-toggle="${b.id}" ${b.hospitalized?'checked':''}> 入院中（チェックすると①②の枠が黒色表示になります）
        </label>
        ${pairNote}
        <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
          <button class="btn btn-ghost btn-small" data-edit="${b.id}">編集する</button>
          <select class="end-reason-select" data-reason-for="${b.id}" style="padding:6px 8px;border:1px solid var(--line);border-radius:7px;font-size:12px;font-family:var(--font-ui);">
            <option value="">終了理由を選択</option>
            ${END_REASONS.map(r=>`<option value="${r}">${r}</option>`).join('')}
          </select>
          <button class="btn btn-danger btn-small" data-end="${b.id}">終了する</button>
        </div>`;
}

function bookingEditView(b){
  const role = staffInfo(b.staff).role;
  return `
        <div class="bname" style="margin-bottom:8px;">利用者情報を編集</div>
        <label>曜日</label><select class="edit-field" data-f="day">${DAYS.map(d=>`<option value="${d}" ${b.day===d?'selected':''}>${d}曜</option>`).join('')}</select>
        <label>時間帯（担当：${b.staff}／${role}の枠）</label>
        <select class="edit-field" data-f="slotIdx">${slotLabelsFor(role).map((label,i)=>`<option value="${i}" ${b.slotIdx===i?'selected':''}>${label}</option>`).join('')}</select>
        <label>氏名</label><input type="text" class="edit-field" data-f="name" value="${(b.name||'').replace(/"/g,'&quot;')}">
        <label>主保険</label><select class="edit-field" data-f="insuranceType">${refSelectOptions(INSURANCE_TYPES, b.insuranceType)}</select>
        <label>サービス時間</label>
        <select class="edit-field" data-f="serviceDuration">
          ${SERVICE_DURATIONS.map(d=>`<option value="${d}" ${b.serviceDuration===d?'selected':''}>${d}分</option>`).join('')}
        </select>
        <label>疾患名</label><input type="text" class="edit-field" data-f="disease" value="${(b.disease||'').replace(/"/g,'&quot;')}">
        <label>独居</label>
        <select class="edit-field" data-f="alone">
          <option value="不明" ${b.alone==='不明'?'selected':''}>不明</option>
          <option value="はい" ${b.alone==='はい'?'selected':''}>はい</option>
          <option value="いいえ" ${b.alone==='いいえ'?'selected':''}>いいえ</option>
        </select>
        <label>居宅介護支援事業所</label><select class="edit-field" data-f="careManager">${refSelectOptions(state.referralSources.careManagers, b.careManager)}</select>
        <label>医療機関</label><select class="edit-field" data-f="hospital">${refSelectOptions(state.referralSources.hospitals, b.hospital)}</select>
        <label>地区</label><select class="edit-field" data-f="district">${refSelectOptions(state.districts, b.district)}</select>
        <label>実施時刻メモ</label><input type="text" class="edit-field" data-f="timeNote" value="${(b.timeNote||'').replace(/"/g,'&quot;')}">
        <label>備考</label><textarea class="edit-field" data-f="note" rows="2">${b.note||''}</textarea>
        <div style="margin-top:8px;display:flex;gap:6px;">
          <button class="btn btn-primary btn-small" data-save="${b.id}">保存</button>
          <button class="btn btn-ghost btn-small" data-cancel="${b.id}">キャンセル</button>
        </div>`;
}

function openSlotModal(staff, day, slotIdx){
  const modal = document.getElementById('slotModal');
  const content = document.getElementById('modalContent');
  const role = staffInfo(staff).role;
  const bks = bookingsAt(staff, day, slotIdx);
  const used = occupiedWeeksAt(staff, day, slotIdx);
  const freeWeeks = WEEKS.filter(w=>!used.has(w));
  let html = `<div class="modal-head">${day}曜　${slotLabelsFor(role)[slotIdx]}〜</div><div class="modal-sub">担当：${staff}（${roleLabel(staff)}）</div>`;
  if(!bks.length){
    html += `<div class="free-note">この枠は現在すべての週で空いています</div>`;
  }else{
    bks.forEach(b=>{
      html += `<div class="booking-card">${editingBookingId===b.id ? bookingEditView(b) : bookingReadView(b)}</div>`;
    });
    if(freeWeeks.length){
      html += `<div class="free-note">空いている週：第${freeWeeks.join('・第')}週</div>`;
    }
  }
  content.innerHTML = html;
  content.querySelectorAll('[data-end]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const sel = content.querySelector(`[data-reason-for="${btn.dataset.end}"]`);
      endBookingById(btn.dataset.end, sel ? sel.value : '');
    });
  });
  content.querySelectorAll('[data-edit]').forEach(btn=>{
    btn.addEventListener('click', ()=>{ editingBookingId = btn.dataset.edit; openSlotModal(staff, day, slotIdx); });
  });
  content.querySelectorAll('[data-cancel]').forEach(btn=>{
    btn.addEventListener('click', ()=>{ editingBookingId = null; openSlotModal(staff, day, slotIdx); });
  });
  content.querySelectorAll('[data-hosp-toggle]').forEach(cb=>{
    cb.addEventListener('change', async ()=>{
      await toggleHospitalized(cb.dataset.hospToggle, cb.checked);
      openSlotModal(staff, day, slotIdx);
    });
  });
  content.querySelectorAll('[data-save]').forEach(btn=>{
    btn.addEventListener('click', async ()=>{
      const id = btn.dataset.save;
      const orig = state.bookings[id];
      const card = btn.closest('.booking-card');
      const updates = {};
      card.querySelectorAll('.edit-field').forEach(f=>{
        let val = (f.value||'').trim();
        if(f.dataset.f==='slotIdx') val = Number(val);
        updates[f.dataset.f] = val;
      });
      const newDay = updates.day !== undefined ? updates.day : orig.day;
      const newSlotIdx = updates.slotIdx !== undefined ? updates.slotIdx : orig.slotIdx;
      const moved = (newDay !== orig.day || newSlotIdx !== orig.slotIdx);
      if(moved && !isRotationFree(orig.staff, newDay, newSlotIdx, orig.weeks)){
        alert('その曜日・時間帯には既に予定が入っているため変更できません。');
        return;
      }
      Object.assign(state.bookings[id], updates);
      await saveState();
      showToast('利用者情報を更新しました');
      editingBookingId = null;
      if(moved){
        closeModal();
      }else{
        openSlotModal(staff, day, slotIdx);
      }
      renderOverview('看護師'); renderOverview('セラピスト');
      if(document.getElementById('panel-end').classList.contains('active')) renderEndList();
      if(document.getElementById('panel-inpatient').classList.contains('active')) renderInpatientList();
    });
  });
  modal.hidden = false;
}

// ---------- ① ② 空き状況（看護師／セラピスト） ----------
function computeSlotAlerts(role, threshold){
  // 曜日・時間帯（枠）ごとに、空き枠数の調整（バッファ）を差し引いたうえでの空き人数を数え、
  // しきい値以上空いている枠を一覧にする（①②の右側「受け入れ枠アラート」用）
  const names = staffNames(role);
  const nSlots = slotCount(role);
  const buffer = (state.staffBuffer && state.staffBuffer[role]) || 0;
  const slotLabels = slotLabelsFor(role);
  const alerts = [];
  WEEKDAYS.forEach(day=>{
    for(let i=0;i<nSlots;i++){
      let free = 0;
      names.forEach(staff=>{
        if(!worksOnSlot(staff,day,i)) return;
        if(slotVisualState(staff,day,i)!=='busy') free++;
      });
      const adjusted = Math.max(0, free - buffer);
      if(adjusted >= threshold){
        alerts.push({day, slotIdx:i, label:slotLabels[i], count:adjusted});
      }
    }
  });
  return alerts;
}
function renderSlotAlerts(role){
  const list = document.getElementById('slotAlertList-'+role);
  if(!list) return;
  const alerts = computeSlotAlerts(role, 2);
  if(!alerts.length){
    list.innerHTML = '<li class="ov-alert-empty" style="background:none;border:none;padding:0;">現在、2件以上空いている枠はありません</li>';
    return;
  }
  list.innerHTML = alerts.map(a=>
    `<li><span>${a.day}曜 ${a.label}〜</span><span class="cnt">空き${a.count}件</span></li>`
  ).join('');
}

function renderOverview(role){
  const names = staffNames(role);
  const hintEl = document.getElementById('slotHint-'+role);
  if(hintEl) hintEl.textContent = slotLabelsFor(role).join(' → ');
  renderSlotAlerts(role);

  const buffer = (state.staffBuffer && state.staffBuffer[role]) || 0;
  const nSlots = slotCount(role);
  const summaryRow = document.getElementById('summaryRow-'+role);
  summaryRow.innerHTML = '';
  let weekTotalFree = 0;
  let anyWeekday = false;
  WEEKDAYS.forEach(day=>{
    let free = 0, total = 0;
    names.forEach(staff=>{
      for(let i=0;i<nSlots;i++){
        if(!worksOnSlot(staff, day, i)) continue;
        total++;
        if(slotVisualState(staff,day,i)!=='busy') free++;
      }
    });
    if(total===0) return;
    anyWeekday = true;
    const adjustedFree = Math.max(0, free - buffer*nSlots);
    weekTotalFree += adjustedFree;
    const card = document.createElement('div');
    card.className = 'summary-card';
    card.innerHTML = `<div class="day">${day}曜</div><div class="num">${adjustedFree}</div><div class="unit">枠 空き余地</div>`;
    summaryRow.appendChild(card);
  });
  const capRow = document.getElementById('capacityRow-'+role);
  if(capRow){
    if(!anyWeekday){
      capRow.innerHTML = '';
    }else{
      const cap2 = Math.floor(weekTotalFree/2);
      capRow.innerHTML = `
        <div class="summary-card">
          <div class="day">週2（2枠）／人</div><div class="num">${cap2}</div><div class="unit">人（月〜金合計${weekTotalFree}枠から算出）</div>
        </div>
        <div class="summary-card">
          <div class="day">週1（1枠）／人</div><div class="num">${weekTotalFree}</div><div class="unit">人（月〜金合計から算出）</div>
        </div>`;
    }
  }
  const bufferNoteEl = document.getElementById('bufferNote-'+role);
  if(bufferNoteEl){
    bufferNoteEl.textContent = buffer>0
      ? `※${role} ${buffer}名分を勤務予定人数から差し引いて計算しています`
      : '';
  }

  const table = document.getElementById('overviewTable-'+role);
  if(!names.length){
    table.innerHTML = `<tr><th>担当スタッフ</th></tr><tr><td style="color:var(--ink-soft);">${role}がまだ登録されていません。⑧スタッフ管理から追加してください。</td></tr>`;
    syncOverviewAlertHeight(role);
    return;
  }
  const slotLabels = slotLabelsFor(role);
  let html = '<tr><th>担当スタッフ</th>' + DAYS.map(d=>`<th>${d}</th>`).join('') + '</tr>';
  names.forEach(staff=>{
    const avatarClass = role==='セラピスト' ? 'therapist' : 'nurse';
    html += `<tr><td><div class="staff-cell"><div class="avatar ${avatarClass}">${staff.trim()[0]||'?'}</div><span class="staff-name">${staff}</span></div></td>`;
    DAYS.forEach(day=>{
      const isWeekend = (day==='土' || day==='日');
      if(isWeekend){
        const dayBookings = [];
        for(let i=0;i<nSlots;i++){
          bookingsAt(staff,day,i).forEach(b=>dayBookings.push(Object.assign({slotIdx:i}, b)));
        }
        if(!worksOn(staff, day) && !dayBookings.length){
          // 通常は非勤務だが、「特例」登録により予約が入っている場合は下の枝で一覧表示する
          html += `<td style="color:var(--ink-soft);font-size:11px;">―</td>`;
        }else if(!dayBookings.length){
          html += `<td style="color:var(--sage);font-size:11px;">空き</td>`;
        }else{
          dayBookings.sort((a,b)=>a.slotIdx-b.slotIdx);
          const items = dayBookings.map(b=>
            `<li><button type="button" data-staff="${staff}" data-day="${day}" data-slot="${b.slotIdx}" style="all:unset;cursor:pointer;color:${b.hospitalized?'#2A2A2A;font-weight:700;':'var(--teal-deep);'}text-decoration:underline;">${slotLabels[b.slotIdx]}〜 ${b.name||'（名前未登録）'}${b.hospitalized?'（入院中）':''}</button></li>`
          ).join('');
          html += `<td><ul style="margin:0;padding-left:14px;font-size:11px;">${items}</ul></td>`;
        }
      }else{
        const blks = slotLabels.map((label,i)=>{
          if(!worksOnSlot(staff,day,i)) return `<div class="blk off"></div>`;
          const st = slotVisualState(staff,day,i);
          return `<button type="button" class="blk ${st==='free'?'':st}" data-staff="${staff}" data-day="${day}" data-slot="${i}" aria-label="${day}曜${label} ${staff}"></button>`;
        }).join('');
        html += `<td><div class="strip">${blks}</div></td>`;
      }
    });
    html += '</tr>';
  });
  table.innerHTML = html;
  table.querySelectorAll('[data-staff][data-day][data-slot]').forEach(el=>{
    el.addEventListener('click', ()=>openSlotModal(el.dataset.staff, el.dataset.day, Number(el.dataset.slot)));
  });
  syncOverviewAlertHeight(role);
}
function syncOverviewAlertHeight(role){
  // サマリー・受け入れ可能人数カードの高さに合わせて右側のアラート枠の高さを揃え、
  // 左右どちらかが極端に短くて下の勤務表との間に余白ができてしまうのを防ぐ
  const main = document.getElementById('ovMain-'+role);
  const alertBox = document.getElementById('ovAlert-'+role);
  if(!main || !alertBox) return;
  alertBox.style.height = main.offsetHeight + 'px';
}

// ---------- ③ 新規登録・提案 ----------
let selectedDays = [];
let selectedSlots = [];

function buildChips(){
  buildDayChips();
  buildSlotChips();
}
function buildDayChips(){
  const dayWrap = document.getElementById('dayChips');
  dayWrap.innerHTML = DAYS.map(d=>`<span class="chip ${selectedDays.includes(d)?'on':''}" data-day="${d}">${d}</span>`).join('');
  dayWrap.querySelectorAll('.chip').forEach(chip=>{
    chip.addEventListener('click', ()=>{
      const d = chip.dataset.day;
      const isRotation = PATTERNS[document.getElementById('f-pattern').value].kind==='rotation';
      if(isRotation){
        selectedDays = selectedDays.includes(d) ? [] : [d];
      }else{
        if(selectedDays.includes(d)) selectedDays = selectedDays.filter(x=>x!==d);
        else selectedDays.push(d);
      }
      buildDayChips();
    });
  });
}
function buildSlotChips(){
  const role = document.getElementById('f-role').value;
  const labels = slotLabelsFor(role);
  const slotWrap = document.getElementById('slotChips');
  selectedSlots = selectedSlots.filter(i=>i < labels.length);
  slotWrap.innerHTML = labels.map((s,i)=>`<span class="chip ${selectedSlots.includes(i)?'on':''}" data-slot="${i}">${s}</span>`).join('');
  slotWrap.querySelectorAll('.chip').forEach(chip=>{
    chip.addEventListener('click', ()=>{
      const i = Number(chip.dataset.slot);
      if(selectedSlots.includes(i)){ selectedSlots = selectedSlots.filter(x=>x!==i); }
      else{ selectedSlots.push(i); }
      buildSlotChips();
    });
  });
}
document.getElementById('f-role').addEventListener('change', ()=>{
  selectedSlots = [];
  buildSlotChips();
});
function updatePatternUI(){
  const val = document.getElementById('f-pattern').value;
  const pattern = PATTERNS[val];
  const isRotation = pattern.kind === 'rotation';
  document.getElementById('freqWeeklyWrap').style.display = (isRotation || pattern.fixedFreq) ? 'none' : '';
  if(pattern.fixedFreq) document.getElementById('f-freq').value = String(pattern.fixedFreq);
  document.getElementById('dayChipsLabel').textContent = isRotation
    ? '希望曜日（1日だけ選べます／未選択なら自動探索）'
    : '希望曜日（未選択＝指定なし）';
  if(isRotation && selectedDays.length>1) selectedDays = [selectedDays[0]];
  buildDayChips();
}
document.getElementById('f-pattern').addEventListener('change', updatePatternUI);

function populateReferralSelects(){
  const cm = document.getElementById('f-cm');
  const hosp = document.getElementById('f-hosp');
  const district = document.getElementById('f-district');
  const opts = (list)=> `<option value="">未設定</option>` + list.map(n=>`<option value="${n}">${n}</option>`).join('');
  cm.innerHTML = opts(state.referralSources.careManagers);
  hosp.innerHTML = opts(state.referralSources.hospitals);
  district.innerHTML = opts(state.districts);
  buildSlotChips();
}

function orderedDays(preferred, includeWeekend){
  // 土日は定休日のため、希望として明示的に選ばれていない限り自動探索の対象外にする
  // （「毎日（月〜日）」など土日訪問を含むパターンのときは includeWeekend=true で土日も探索対象にする）
  const base = includeWeekend ? DAYS.slice() : WEEKDAYS.slice();
  if(!preferred || !preferred.length) return base;
  return DAYS.filter(d=>preferred.includes(d)).concat(base.filter(d=>!preferred.includes(d)));
}
function orderedSlots(preferred, role){
  const all = slotLabelsFor(role).map((_,i)=>i);
  if(!preferred || !preferred.length) return all;
  const pref = preferred.filter(i=>i<all.length).sort((a,b)=>a-b);
  return pref.concat(all.filter(i=>!pref.includes(i)));
}

function suggestionPool(role){
  // 「空き枠数の計算調整」で差し引く人数分、勤務予定人数が最も少ない（＝一番空いて見える）
  // スタッフを新規提案の候補から除外し、実際の受け入れ余地を超えて提案しないようにする
  const names = staffNames(role);
  const buffer = (state.staffBuffer && state.staffBuffer[role]) || 0;
  if(buffer<=0) return names;
  const sorted = names.slice().sort((a,b)=>totalLoad(a)-totalLoad(b));
  const reserved = new Set(sorted.slice(0, Math.min(buffer, names.length)));
  return names.filter(n=>!reserved.has(n));
}

function findWeeklySuggestions(freq, preferredDays, preferredSlots, role, includeWeekend, weekendException){
  const dayOrder = orderedDays(preferredDays, includeWeekend || weekendException);
  const slotOrder = orderedSlots(preferredSlots, role);
  const pool = suggestionPool(role);
  // 「特例」がONのときは、土日に限り勤務体系（⑨設定のON/OFF）を無視して候補に含める
  const dayAllowed = (staff,d)=> worksOn(staff,d) || (weekendException && isWeekendDay(d));
  const slotAllowed = (staff,d,i)=> worksOnSlot(staff,d,i) || (weekendException && isWeekendDay(d));
  const results = [];
  let tier1Map = new Map();
  pool.forEach(staff=>{
    const workdays = dayOrder.filter(d=>dayAllowed(staff,d));
    if(workdays.length < freq) return;
    for(const slotIdx of slotOrder){
      const freeDays = workdays.filter(d=>slotAllowed(staff,d,slotIdx) && isFullyFree(staff,d,slotIdx));
      if(freeDays.length >= freq){
        tier1Map.set(staff, {tier:1, staff, slotIdx, days: freeDays.slice(0,freq), load: totalLoad(staff)});
        break;
      }
    }
  });
  let tier1 = Array.from(tier1Map.values()).sort((a,b)=>a.load-b.load);
  results.push(...tier1.slice(0,3));
  if(results.length < 3){
    let tier2Map = new Map();
    pool.forEach(staff=>{
      if(tier1Map.has(staff)) return;
      const workdays = dayOrder.filter(d=>dayAllowed(staff,d));
      if(workdays.length < freq) return;
      const picks = [];
      workdays.forEach(d=>{
        for(const s of slotOrder){
          if(slotAllowed(staff,d,s) && isFullyFree(staff,d,s)){ picks.push({day:d, slot:s}); break; }
        }
      });
      if(picks.length >= freq){
        tier2Map.set(staff, {tier:2, staff, picks: picks.slice(0,freq), load: totalLoad(staff)});
      }
    });
    let tier2 = Array.from(tier2Map.values()).sort((a,b)=>a.load-b.load);
    results.push(...tier2.slice(0, 3-results.length));
  }
  if(results.length < 1){
    const picks = [];
    for(const d of dayOrder){
      if(picks.length >= freq) break;
      for(const staff of pool){
        if(!dayAllowed(staff,d)) continue;
        let found = -1;
        for(const s of slotOrder){ if(slotAllowed(staff,d,s) && isFullyFree(staff,d,s)){ found = s; break; } }
        if(found>=0){ picks.push({day:d, slot:found, staff}); break; }
      }
    }
    if(picks.length >= freq){
      results.push({tier:3, picks: picks.slice(0,freq), load:0});
    }
  }
  return results.slice(0,3);
}

function findRotationSuggestions(candWeeks, preferredDays, preferredSlots, role, weekendException){
  const dayOrder = orderedDays(preferredDays, weekendException);
  const slotOrder = orderedSlots(preferredSlots, role);
  const pool = suggestionPool(role).slice().sort((a,b)=>totalLoad(a)-totalLoad(b));
  const dayAllowed = (staff,d)=> worksOn(staff,d) || (weekendException && isWeekendDay(d));
  const slotAllowed = (staff,d,i)=> worksOnSlot(staff,d,i) || (weekendException && isWeekendDay(d));
  const found = [];
  const seenStaff = new Set();
  for(const staff of pool){
    if(seenStaff.has(staff)) continue;
    for(const day of dayOrder){
      if(!dayAllowed(staff,day)) continue;
      for(const slotIdx of slotOrder){
        if(slotAllowed(staff,day,slotIdx) && isRotationFree(staff,day,slotIdx,candWeeks)){
          found.push({tier:'rotation', staff, day, slotIdx, load: totalLoad(staff)});
          seenStaff.add(staff);
          break;
        }
      }
      if(seenStaff.has(staff)) break;
    }
    if(found.length>=3) break;
  }
  return found.slice(0,3);
}

function extraDaysNote(days, preferredDays){
  if(!preferredDays || !preferredDays.length) return '';
  const outside = days.filter(d=>!preferredDays.includes(d));
  if(outside.length && outside.length===days.length){
    return '※ご希望の曜日には空きがなかったため、別日程でご案内しています';
  }
  if(outside.length){
    return '※ご希望の曜日だけでは頻度に届かないため、他の曜日も含めています';
  }
  return '';
}

function labelSuggestion(s, preferredDays, role){
  const slotLabels = slotLabelsFor(role);
  if(s.tier==='rotation'){
    return { tag:'候補', text: `${s.day}曜　${slotLabels[s.slotIdx]}〜`, sub: `担当：${s.staff}` };
  }
  if(s.tier===1){
    const daysTxt = s.days.slice().sort((a,b)=>DAYS.indexOf(a)-DAYS.indexOf(b)).join('・');
    const note = extraDaysNote(s.days, preferredDays);
    return {
      tag:'第一候補（同じ担当・同じ時間）',
      text: `${daysTxt}　${slotLabels[s.slotIdx]}〜`,
      sub: `担当：${s.staff}` + (note?`　${note}`:'')
    };
  }
  if(s.tier===2){
    const sorted = s.picks.slice().sort((a,b)=>DAYS.indexOf(a.day)-DAYS.indexOf(b.day));
    const txt = sorted.map(p=>`${p.day}${slotLabels[p.slot]}〜`).join('・');
    const note = extraDaysNote(sorted.map(p=>p.day), preferredDays);
    return { tag:'第二候補（同じ担当・時間は日によって異なる）', text: txt, sub:`担当：${s.staff}` + (note?`　${note}`:'') };
  }
  const sorted = s.picks.slice().sort((a,b)=>DAYS.indexOf(a.day)-DAYS.indexOf(b.day));
  const txt = sorted.map(p=>`${p.day}${slotLabels[p.slot]}〜(${p.staff})`).join('・');
  return { tag:'代替案（複数の担当者に分かれます）', text: txt, sub:'継続性より、まず枠の確保を優先した案です' };
}

function pairedBookingAt(b){
  if(!b.pairId) return null;
  return Object.entries(state.bookings)
    .filter(([id,o])=>o.pairId===b.pairId && id!==b.id)
    .map(([id,o])=>Object.assign({id}, o))[0] || null;
}

async function confirmSuggestion(s, patient, patternValue){
  const date = todayStr();
  const pattern = PATTERNS[patternValue];
  const pairId = patient.companion ? newId() : null;
  const create = (staff, day, slotIdx, weeks, person, linkId)=>{
    const id = newId();
    state.bookings[id] = {
      staff, day, slotIdx, weeks, patternValue,
      name: person.name, disease: person.disease, alone: person.alone, note: person.note,
      careManager: person.careManager, hospital: person.hospital, timeNote: person.timeNote,
      insuranceType: person.insuranceType, serviceDuration: person.serviceDuration,
      district: person.district || '',
      pairId: linkId || null,
      startDate: date
    };
    state.eventLog.push({
      id: newId(), type:'新規', date, name: person.name,
      staff, day, slot: slotIdx, careManager: person.careManager, hospital: person.hospital
    });
  };
  const createBoth = (staff, day, slotIdx, weeks)=>{
    create(staff, day, slotIdx, weeks, patient, pairId);
    if(patient.companion){
      create(staff, day, slotIdx, weeks, {
        name: patient.companion.name,
        disease: patient.companion.disease,
        insuranceType: patient.companion.insuranceType,
        timeNote: patient.companion.timeNote,
        serviceDuration: patient.serviceDuration,
        alone: patient.alone,
        careManager: patient.careManager,
        hospital: patient.hospital,
        district: patient.district,
        note: patient.note
      }, pairId);
    }
  };
  if(s.tier==='rotation'){ createBoth(s.staff, s.day, s.slotIdx, pattern.weeks); }
  else if(s.tier===1){ s.days.forEach(d=>createBoth(s.staff, d, s.slotIdx, WEEKS.slice())); }
  else if(s.tier===2){ s.picks.forEach(p=>createBoth(s.staff, p.day, p.slot, WEEKS.slice())); }
  else { s.picks.forEach(p=>createBoth(p.staff, p.day, p.slot, WEEKS.slice())); }
  await saveState();
  const companionMsg = patient.companion ? `（${patient.companion.name}様と合わせて2名）` : '';
  showToast(`${patient.name || '利用者様'} を登録しました${companionMsg}`);
  document.getElementById('suggestions').innerHTML = '';
  document.getElementById('intakeForm').reset();
  document.getElementById('companionOuterWrap').style.display = 'none';
  document.getElementById('companionFields').style.display = 'none';
  hideExistingPatientNotice();
  selectedDays = []; selectedSlots = [];
  updatePatternUI();
  buildSlotChips();
  renderOverview('看護師'); renderOverview('セラピスト');
}

document.getElementById('f-duration').addEventListener('change', ()=>{
  const isThirty = document.getElementById('f-duration').value === '30';
  document.getElementById('companionOuterWrap').style.display = isThirty ? '' : 'none';
  if(!isThirty){
    document.getElementById('f-companion-enable').checked = false;
    document.getElementById('companionFields').style.display = 'none';
  }
});
document.getElementById('f-companion-enable').addEventListener('change', (e)=>{
  document.getElementById('companionFields').style.display = e.target.checked ? '' : 'none';
});

// 既存利用者への「サービス内容の追加」対応：同姓同名の登録中の予約があれば案内し、内容の引き継ぎができるようにする
function findExistingBookingsByName(name){
  name = (name||'').trim();
  if(!name) return [];
  return Object.entries(state.bookings)
    .filter(([id,b])=>(b.name||'').trim()===name)
    .map(([id,b])=>Object.assign({id}, b));
}
function hideExistingPatientNotice(){
  const notice = document.getElementById('existingPatientNotice');
  notice.style.display = 'none';
  notice.innerHTML = '';
}
function updateExistingPatientNotice(){
  const name = document.getElementById('f-name').value.trim();
  const notice = document.getElementById('existingPatientNotice');
  const existing = findExistingBookingsByName(name);
  if(!name || !existing.length){ hideExistingPatientNotice(); return; }
  notice.style.display = '';
  notice.innerHTML = `⚠ 「${name}」は現在${existing.length}件の枠をご利用中です。ここで登録すると<strong>サービス内容の追加</strong>（新しい枠の追加）になります。ご利用中の内容を減らす場合は「④ 利用者検索」から個別に終了してください。`
    + `<button type="button" class="btn btn-ghost btn-small" id="prefillExistingBtn" style="margin-left:6px;">登録済みの内容を引き継ぐ</button>`;
  document.getElementById('prefillExistingBtn').addEventListener('click', ()=>{
    const b = existing[0];
    document.getElementById('f-disease').value = b.disease||'';
    document.getElementById('f-insurance').value = b.insuranceType||'';
    document.getElementById('f-duration').value = b.serviceDuration||'60';
    document.getElementById('f-duration').dispatchEvent(new Event('change'));
    document.getElementById('f-alone').value = b.alone||'不明';
    document.getElementById('f-cm').value = b.careManager||'';
    document.getElementById('f-hosp').value = b.hospital||'';
    document.getElementById('f-district').value = b.district||'';
    showToast('登録済みの内容を引き継ぎました');
  });
}
document.getElementById('f-name').addEventListener('input', updateExistingPatientNotice);

document.getElementById('intakeForm').addEventListener('submit', (e)=>{
  e.preventDefault();
  const role = document.getElementById('f-role').value;
  const patternValue = document.getElementById('f-pattern').value;
  const pattern = PATTERNS[patternValue];
  const freq = Number(document.getElementById('f-freq').value);
  const serviceDuration = document.getElementById('f-duration').value;
  let companion = null;
  if(serviceDuration==='30' && document.getElementById('f-companion-enable').checked){
    const compName = document.getElementById('f-comp-name').value.trim();
    if(compName){
      companion = {
        name: compName,
        disease: document.getElementById('f-comp-disease').value.trim(),
        insuranceType: document.getElementById('f-comp-insurance').value,
        timeNote: document.getElementById('f-comp-time').value.trim()
      };
    }
  }
  const patient = {
    name: document.getElementById('f-name').value.trim(),
    disease: document.getElementById('f-disease').value.trim(),
    insuranceType: document.getElementById('f-insurance').value,
    serviceDuration, companion,
    alone: document.getElementById('f-alone').value,
    careManager: document.getElementById('f-cm').value,
    hospital: document.getElementById('f-hosp').value,
    district: document.getElementById('f-district').value,
    timeNote: document.getElementById('f-timenote').value.trim(),
    note: document.getElementById('f-note').value.trim(),
    freq
  };
  const weekendException = document.getElementById('f-weekend-exception').checked;
  const sugg = pattern.kind==='weekly'
    ? findWeeklySuggestions(freq, selectedDays, selectedSlots, role, !!pattern.includeWeekend, weekendException)
    : findRotationSuggestions(pattern.weeks, selectedDays, selectedSlots, role, weekendException);
  const box = document.getElementById('suggestions');
  if(!sugg.length){
    box.innerHTML = `<div class="empty-msg">条件に合う空き枠が見つかりませんでした。${role}の登録がない、または曜日・時間の希望が合っていない可能性があります。</div>`;
    return;
  }
  box.innerHTML = '';
  sugg.forEach(s=>{
    const info = labelSuggestion(s, selectedDays, role);
    const card = document.createElement('div');
    card.className = 'sugg-card' + (s.tier===2?' tier2':s.tier===3?' tier3':'');
    card.innerHTML = `
      <div>
        <div class="sugg-tag">${info.tag}</div>
        <div class="sugg-text">${info.text}</div>
        <div class="sugg-sub">${info.sub}</div>
      </div>
      <button class="btn btn-primary btn-small">この案で確定</button>
    `;
    card.querySelector('button').addEventListener('click', ()=>confirmSuggestion(s, patient, patternValue));
    box.appendChild(card);
  });
});

// ---------- ④ 利用者検索 ----------
function buildBookingRow(b){
  const el = document.createElement('div');
  el.className = 'end-row';
  const nameTxt = b.name || '（名前未入力）';
  const roleTxt = roleLabel(b.staff);
  const bSlotLabel = slotLabelsFor(staffInfo(b.staff).role)[b.slotIdx];
  let metaTxt = `${roleTxt}／${patternLabelOf(b)}／疾患：${b.disease||'―'}／独居：${b.alone||'―'}／居宅：${b.careManager||'―'}／医療機関：${b.hospital||'―'}／地区：${b.district||'―'}`;
  if(b.timeNote) metaTxt += `／時刻メモ：${b.timeNote}`;
  if(b.pairId){
    const paired = pairedBookingAt(b);
    if(paired) metaTxt += `／同枠のペア：${paired.name||'（名前未登録）'}`;
  }
  el.innerHTML = `
    <div>
      <div><strong>${b.day}曜 ${bSlotLabel}〜</strong>　担当：${b.staff}　－　${nameTxt}</div>
      <div class="meta">${metaTxt}</div>
    </div>
    <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
      <label style="display:flex;align-items:center;gap:4px;font-size:12px;color:var(--ink-soft);cursor:pointer;">
        <input type="checkbox" class="hospitalized-check" ${b.hospitalized?'checked':''}> 入院中
      </label>
      <button class="btn btn-ghost btn-small edit-booking-btn">編集する</button>
      <select class="end-reason-select" style="padding:6px 8px;border:1px solid var(--line);border-radius:7px;font-size:12px;font-family:var(--font-ui);">
        <option value="">終了理由を選択</option>
        ${END_REASONS.map(r=>`<option value="${r}">${r}</option>`).join('')}
      </select>
      <button class="btn btn-danger btn-small end-booking-btn">終了する</button>
    </div>
  `;
  const reasonSel = el.querySelector('.end-reason-select');
  el.querySelector('.end-booking-btn').addEventListener('click', ()=>endBookingById(b.id, reasonSel.value));
  el.querySelector('.edit-booking-btn').addEventListener('click', ()=>openSlotModal(b.staff, b.day, b.slotIdx));
  el.querySelector('.hospitalized-check').addEventListener('change', (e)=>toggleHospitalized(b.id, e.target.checked));
  return el;
}
async function toggleHospitalized(id, checked){
  const b = state.bookings[id];
  if(!b) return;
  b.hospitalized = checked;
  await saveState();
  showToast(checked ? '入院中に設定しました（①②の該当枠が黒色表示になります）' : '入院中を解除しました');
  renderOverview('看護師'); renderOverview('セラピスト');
  if(document.getElementById('panel-end').classList.contains('active')) renderEndList();
  if(document.getElementById('panel-inpatient').classList.contains('active')) renderInpatientList();
}
function renderEndList(){
  const wrap = document.getElementById('endList');
  const searchInput = document.getElementById('endSearch');
  const kw = (searchInput.value||'').trim();
  let rows = Object.entries(state.bookings).map(([id,b])=>Object.assign({id}, b));
  if(kw) rows = rows.filter(b=> (b.name||'').includes(kw));
  rows.sort((a,b)=> DAYS.indexOf(a.day)-DAYS.indexOf(b.day) || a.slotIdx-b.slotIdx);

  if(!rows.length){
    wrap.innerHTML = `<p class="page-sub">${kw ? '一致する利用者様が見つかりません。' : '現在、ご利用中の利用者様はいません。'}</p>`;
    return;
  }
  wrap.innerHTML = '';
  rows.forEach(b=> wrap.appendChild(buildBookingRow(b)));
}
document.getElementById('endSearch').addEventListener('input', renderEndList);

// ---------- ⑤ 入院管理表 ----------
function renderInpatientList(){
  const wrap = document.getElementById('inpatientList');
  const searchInput = document.getElementById('inpatientSearch');
  const kw = (searchInput.value||'').trim();
  let rows = Object.entries(state.bookings)
    .map(([id,b])=>Object.assign({id}, b))
    .filter(b=>b.hospitalized);
  if(kw) rows = rows.filter(b=> (b.name||'').includes(kw));
  rows.sort((a,b)=> DAYS.indexOf(a.day)-DAYS.indexOf(b.day) || a.slotIdx-b.slotIdx);

  if(!rows.length){
    wrap.innerHTML = `<p class="page-sub">${kw ? '一致する利用者様が見つかりません。' : '現在、入院中として登録されている利用者様はいません。'}</p>`;
    return;
  }
  wrap.innerHTML = '';
  rows.forEach(b=> wrap.appendChild(buildBookingRow(b)));
}
document.getElementById('inpatientSearch').addEventListener('input', renderInpatientList);

// ---------- ⑥ リスト分析 ----------
function renderReferralAnalysisTable(tableId, field, label){
  const patients = new Map();
  Object.values(state.bookings).forEach(b=>{
    const key = b.name || '(名前未登録)';
    if(!patients.has(key)) patients.set(key, b[field] || '未設定');
  });
  const total = patients.size;
  const byGroup = {};
  patients.forEach(v=>{ byGroup[v] = (byGroup[v]||0)+1; });

  const thisMonth = monthKey(todayStr());
  const newThisMonth = {};
  const seenThisMonthNames = {};
  state.eventLog.filter(ev=>ev.type==='新規' && monthKey(ev.date)===thisMonth).forEach(ev=>{
    const g = ev[field] || '未設定';
    seenThisMonthNames[g] = seenThisMonthNames[g] || new Set();
    if(!seenThisMonthNames[g].has(ev.name)){
      seenThisMonthNames[g].add(ev.name);
      newThisMonth[g] = (newThisMonth[g]||0)+1;
    }
  });

  const groups = Object.keys(byGroup).sort((a,b)=>byGroup[b]-byGroup[a]);
  const table = document.getElementById(tableId);
  if(!groups.length){
    table.innerHTML = `<tr><th>${label}</th><th>現在の利用者数</th><th>割合</th><th>今月の新規</th></tr>
      <tr><td colspan="4" style="color:var(--ink-soft);">まだデータがありません</td></tr>`;
    return;
  }
  let html = `<tr><th>${label}</th><th>現在の利用者数</th><th>割合</th><th>今月の新規</th></tr>`;
  groups.forEach(g=>{
    const pct = total ? ((byGroup[g]/total)*100).toFixed(1) : '0.0';
    html += `<tr><td>${g}</td><td>${byGroup[g]}人</td><td>${pct}%</td><td>${newThisMonth[g]||0}人</td></tr>`;
  });
  table.innerHTML = html;
}
function renderReferralAnalysis(){
  renderReferralAnalysisTable('cmTable', 'careManager', '居宅介護支援事業所');
  renderReferralAnalysisTable('hospTable', 'hospital', '医療機関');
}
document.getElementById('csvExportReferral').addEventListener('click', ()=>{
  exportPanelCsv('panel-referral', `リスト分析_${todayStr()}.csv`);
});
document.getElementById('csvExportReport').addEventListener('click', ()=>{
  exportPanelCsv('panel-report', `月次レポート_${todayStr()}.csv`);
});

// ---------- ⑦ 月次レポート ----------
function renderReport(){
  const byMonth = {};
  state.eventLog.forEach(ev=>{
    const m = monthKey(ev.date);
    if(!byMonth[m]) byMonth[m] = { newSet:new Set(), end:0 };
    if(ev.type==='新規'){
      byMonth[m].newSet.add(ev.name ? ('n:'+ev.name) : ('n:__anon_'+ev.id));
    }else{
      byMonth[m].end++;
    }
  });
  const months = Object.keys(byMonth).sort();
  const grid = document.getElementById('reportGrid');
  const table = document.getElementById('reportTable');
  if(!months.length){
    grid.innerHTML = '<p class="page-sub">まだ登録・終了の記録がありません。③④の操作をすると、ここに集計されます。</p>';
    table.innerHTML = '';
    return;
  }
  const stats = {};
  months.forEach(m=>{ stats[m] = { new: byMonth[m].newSet.size, end: byMonth[m].end }; });
  const max = Math.max(...months.map(m=>Math.max(stats[m].new, stats[m].end)), 1);
  grid.innerHTML = months.map(m=>{
    const v = stats[m];
    return `<div class="month-card">
      <div class="m">${m}</div>
      <div class="bar-row"><span class="lbl">新規</span><div class="bar-track"><div class="bar-fill new" style="width:${v.new/max*100}%"></div></div><span class="val">${v.new}</span></div>
      <div class="bar-row"><span class="lbl">終了</span><div class="bar-track"><div class="bar-fill end" style="width:${v.end/max*100}%"></div></div><span class="val">${v.end}</span></div>
    </div>`;
  }).join('');

  let html = '<tr><th>年月</th><th>新規（利用者数）</th><th>終了</th><th>純増減</th></tr>';
  months.forEach(m=>{
    const v = stats[m];
    html += `<tr><td>${m}</td><td>${v.new}</td><td>${v.end}</td><td>${v.new-v.end>=0?'+':''}${v.new-v.end}</td></tr>`;
  });
  table.innerHTML = html;

  const reasonCounts = {};
  END_REASONS.forEach(r=>{ reasonCounts[r] = 0; });
  reasonCounts['未選択'] = 0;
  state.eventLog.forEach(ev=>{
    if(ev.type!=='終了') return;
    const r = ev.reason && reasonCounts.hasOwnProperty(ev.reason) ? ev.reason : '未選択';
    reasonCounts[r]++;
  });
  const reasonTable = document.getElementById('reasonTable');
  if(reasonTable){
    let rhtml = '<tr><th>終了理由</th><th>件数</th></tr>';
    Object.entries(reasonCounts).forEach(([k,v])=>{ rhtml += `<tr><td>${k}</td><td>${v}件</td></tr>`; });
    reasonTable.innerHTML = rhtml;
  }

  renderReferralAnalysisTable('diseaseTable', 'disease', '疾患名');
  renderReferralAnalysisTable('insuranceTable', 'insuranceType', '主保険');
  renderReferralAnalysisTable('aloneTable', 'alone', '独居');
  renderReferralAnalysisTable('districtTable', 'district', '地区');
}

// ---------- ⑧ スタッフ管理 ----------
async function addStaffMember(name, role){
  if(state.staff.some(s=>s.name===name)){
    alert('同じ名前のスタッフが既に登録されています。');
    return;
  }
  state.staff.push({name, role, qualifications: []});
  state.staffWorkSlots[name] = {};
  await saveState();
  renderStaffList();
}
async function moveStaff(idx, dir){
  const newIdx = idx+dir;
  if(newIdx<0 || newIdx>=state.staff.length) return;
  const arr = state.staff;
  [arr[idx], arr[newIdx]] = [arr[newIdx], arr[idx]];
  await saveState();
  renderStaffList();
  renderOverview('看護師'); renderOverview('セラピスト');
}
async function removeStaffMember(name){
  const hasBooking = Object.values(state.bookings).some(b=>b.staff===name);
  if(hasBooking){
    alert('このスタッフには現在ご利用中の予定があるため削除できません。先に「終了処理」で予定を終了してから削除してください。');
    return;
  }
  if(!confirm(`「${name}」を削除します。よろしいですか？`)) return;
  state.staff = state.staff.filter(s=>s.name!==name);
  delete state.staffWorkSlots[name];
  await saveState();
  renderStaffList();
  renderOverview('看護師'); renderOverview('セラピスト');
}
async function renameStaffMember(oldName, newName){
  newName = (newName||'').trim();
  if(!newName || newName===oldName) return;
  if(state.staff.some(s=>s.name===newName)){
    alert('その名前のスタッフは既に登録されています。');
    renderStaffList();
    return;
  }
  const staffObj = state.staff.find(s=>s.name===oldName);
  if(!staffObj) return;
  staffObj.name = newName;
  state.staffWorkSlots[newName] = state.staffWorkSlots[oldName] || {};
  delete state.staffWorkSlots[oldName];
  Object.values(state.bookings).forEach(b=>{ if(b.staff===oldName) b.staff = newName; });
  state.eventLog.forEach(ev=>{ if(ev.staff===oldName) ev.staff = newName; });
  await saveState();
  showToast(`「${oldName}」を「${newName}」に変更しました`);
  renderStaffList();
  renderOverview('看護師'); renderOverview('セラピスト');
}
async function addQualification(name, qual){
  qual = (qual||'').trim();
  if(!qual) return;
  const staffObj = state.staff.find(s=>s.name===name);
  if(!staffObj) return;
  if(!staffObj.qualifications) staffObj.qualifications = [];
  if(!staffObj.qualifications.includes(qual)) staffObj.qualifications.push(qual);
  await saveState();
  renderStaffList();
}
async function removeQualification(name, qual){
  const staffObj = state.staff.find(s=>s.name===name);
  if(!staffObj || !staffObj.qualifications) return;
  staffObj.qualifications = staffObj.qualifications.filter(q=>q!==qual);
  await saveState();
  renderStaffList();
}

// ---------- ⑨ 設定 ----------
async function addSlot(role, label){
  state.slotLabels[role].push(label);
  await saveState();
  renderSlotSettings(role);
}
async function renameSlot(role, idx, newLabel){
  state.slotLabels[role][idx] = newLabel;
  await saveState();
}
async function removeSlot(role, idx){
  const hasBooking = Object.values(state.bookings).some(b=>b.slotIdx===idx && staffInfo(b.staff).role===role);
  if(hasBooking){
    alert('この枠には現在ご利用中の予定があるため削除できません。先に「終了処理」でその予定を終了してから削除してください。');
    return;
  }
  if(!confirm(`「${state.slotLabels[role][idx]}」枠を削除します。よろしいですか？`)) return;
  state.slotLabels[role].splice(idx,1);
  Object.values(state.bookings).forEach(b=>{ if(staffInfo(b.staff).role===role && b.slotIdx > idx) b.slotIdx -= 1; });
  await saveState();
  renderSlotSettings(role);
}
async function moveSlot(role, idx, dir){
  const newIdx = idx+dir;
  if(newIdx<0 || newIdx>=slotCount(role)) return;
  const labels = state.slotLabels[role];
  [labels[idx], labels[newIdx]] = [labels[newIdx], labels[idx]];
  Object.values(state.bookings).forEach(b=>{
    if(staffInfo(b.staff).role!==role) return;
    if(b.slotIdx===idx) b.slotIdx=newIdx;
    else if(b.slotIdx===newIdx) b.slotIdx=idx;
  });
  await saveState();
  renderSlotSettings(role);
}

function renderSlotSettings(role){
  const list = document.getElementById('slotSettingsList-'+role);
  if(!list) return;
  list.innerHTML = '';
  state.slotLabels[role].forEach((label, idx)=>{
    const row = document.createElement('div');
    row.className = 'setting-row';
    row.innerHTML = `
      <span class="idx">${idx+1}</span>
      <input type="text" value="${label.replace(/"/g,'')}">
      <button class="icon-btn" data-act="up" ${idx===0?'disabled':''}>↑</button>
      <button class="icon-btn" data-act="down" ${idx===slotCount(role)-1?'disabled':''}>↓</button>
      <button class="icon-btn danger" data-act="del">削除</button>
    `;
    const input = row.querySelector('input');
    input.addEventListener('change', ()=>renameSlot(role, idx, input.value.trim() || label));
    row.querySelector('[data-act="up"]').addEventListener('click', ()=>moveSlot(role, idx,-1));
    row.querySelector('[data-act="down"]').addEventListener('click', ()=>moveSlot(role, idx,1));
    row.querySelector('[data-act="del"]').addEventListener('click', ()=>removeSlot(role, idx));
    list.appendChild(row);
  });
}

function renderSettings(){
  document.getElementById('bufferNurse').value = (state.staffBuffer && state.staffBuffer['看護師']) || 0;
  document.getElementById('bufferTherapist').value = (state.staffBuffer && state.staffBuffer['セラピスト']) || 0;

  renderSlotSettings('看護師');
  renderSlotSettings('セラピスト');

  renderMasterList('cmList', state.referralSources.careManagers, 'careManagers');
  renderMasterList('hospList', state.referralSources.hospitals, 'hospitals');
  renderDistrictList();
  renderWorkdayTable('看護師');
  renderWorkdayTable('セラピスト');
}

function renderStaffList(){
  const wrap = document.getElementById('staffList');
  if(!wrap) return;
  if(!state.staff.length){ wrap.innerHTML = '<p class="page-sub">スタッフが登録されていません。</p>'; return; }
  wrap.innerHTML = '';
  state.staff.forEach((s, idx)=>{
    const row = document.createElement('div');
    row.className = 'staff-row-item';
    row.style.flexWrap = 'wrap';
    const pillClass = s.role==='セラピスト' ? 'therapist' : s.role==='事務員' ? 'office' : 'nurse';
    const quals = s.qualifications || [];
    const qualChipsHtml = quals.map(q=>`<span class="qual-chip">${q}<button type="button" data-remove-qual="${q}">×</button></span>`).join('');
    const templateOptionsHtml = QUALIFICATION_TEMPLATES.map(t=>`<option value="${t.value}">${t.label}</option>`).join('');
    row.innerHTML = `
      <span style="display:flex;align-items:center;flex-wrap:wrap;flex:1;min-width:260px;">
        <input type="text" class="staff-name-input" value="${s.name.replace(/"/g,'&quot;')}" style="max-width:140px;padding:5px 8px;font-size:13px;">
        <span class="role-pill ${pillClass}">${s.role}</span>${qualChipsHtml}
        <div class="qual-add-row">
          <select class="qual-add-select">
            <option value="">＋資格を追加…</option>
            ${QUALIFICATION_PRESETS.map(p=>`<option value="${p}">${p}</option>`).join('')}
            ${templateOptionsHtml}
          </select>
          <input type="text" class="qual-add-text" style="display:none;">
          <button type="button" class="icon-btn" data-act="add-qual">追加</button>
        </div>
      </span>
      <span style="display:flex;gap:4px;flex-shrink:0;">
        <button class="icon-btn" data-act="up" ${idx===0?'disabled':''} title="上へ">↑</button>
        <button class="icon-btn" data-act="down" ${idx===state.staff.length-1?'disabled':''} title="下へ">↓</button>
        <button class="icon-btn danger" data-act="del">削除</button>
      </span>
    `;
    row.querySelector('[data-act="up"]').addEventListener('click', ()=>moveStaff(idx,-1));
    row.querySelector('[data-act="down"]').addEventListener('click', ()=>moveStaff(idx,1));
    row.querySelector('[data-act="del"]').addEventListener('click', ()=>removeStaffMember(s.name));
    row.querySelector('.staff-name-input').addEventListener('change', (e)=>renameStaffMember(s.name, e.target.value));
    row.querySelectorAll('[data-remove-qual]').forEach(btn=>{
      btn.addEventListener('click', ()=>removeQualification(s.name, btn.dataset.removeQual));
    });
    const qualSelect = row.querySelector('.qual-add-select');
    const qualText = row.querySelector('.qual-add-text');
    qualSelect.addEventListener('change', ()=>{
      const tmpl = QUALIFICATION_TEMPLATES.find(t=>t.value===qualSelect.value);
      if(tmpl){
        qualText.style.display = '';
        qualText.placeholder = tmpl.placeholder;
      }else{
        qualText.style.display = 'none';
        qualText.value = '';
      }
    });
    row.querySelector('[data-act="add-qual"]').addEventListener('click', ()=>{
      const tmpl = QUALIFICATION_TEMPLATES.find(t=>t.value===qualSelect.value);
      let qual;
      if(tmpl){
        const prefix = qualText.value.trim();
        if(!prefix){ alert('資格名を入力してください。'); return; }
        qual = tmpl.suffix ? prefix + tmpl.suffix : prefix;
      }else if(qualSelect.value){
        qual = qualSelect.value;
      }else{
        return;
      }
      addQualification(s.name, qual);
    });
    wrap.appendChild(row);
  });
}

function renderMasterList(containerId, arr, type){
  const wrap = document.getElementById(containerId);
  if(!arr.length){ wrap.innerHTML = '<p class="page-sub">まだ登録がありません。</p>'; return; }
  wrap.innerHTML = '';
  arr.forEach(name=>{
    const row = document.createElement('div');
    row.className = 'master-row';
    row.innerHTML = `<span>${name}</span><button class="icon-btn danger">削除</button>`;
    row.querySelector('button').addEventListener('click', async ()=>{
      state.referralSources[type] = state.referralSources[type].filter(n=>n!==name);
      await saveState();
      renderSettings();
    });
    wrap.appendChild(row);
  });
}

function renderDistrictList(){
  const wrap = document.getElementById('districtList');
  if(!state.districts.length){ wrap.innerHTML = '<p class="page-sub">まだ登録がありません。</p>'; return; }
  wrap.innerHTML = '';
  state.districts.forEach(name=>{
    const row = document.createElement('div');
    row.className = 'master-row';
    row.innerHTML = `<span>${name}</span><button class="icon-btn danger">削除</button>`;
    row.querySelector('button').addEventListener('click', async ()=>{
      state.districts = state.districts.filter(n=>n!==name);
      await saveState();
      renderSettings();
      populateReferralSelects();
    });
    wrap.appendChild(row);
  });
}

function renderWorkdayTable(role){
  const table = document.getElementById('workdayTable-'+role);
  const names = staffNames(role);
  if(!names.length){
    table.innerHTML = `<tr><th>${role}</th></tr><tr><td style="color:var(--ink-soft);">まだ登録がありません</td></tr>`;
    return;
  }
  const slotLabels = slotLabelsFor(role);
  let html = '<tr><th>スタッフ</th>' + DAYS.map(d=>`<th>${d}</th>`).join('') + '</tr>';
  names.forEach(staff=>{
    html += `<tr><td class="staff-name">${staff}</td>`;
    DAYS.forEach(day=>{
      const blks = slotLabels.map((label,i)=>{
        const on = worksOnSlot(staff,day,i);
        return `<button type="button" class="blk ${on?'':'off'}" data-staff="${staff}" data-day="${day}" data-slot="${i}" title="${day}曜 ${label} ${on?'勤務':'非勤務'}" aria-label="${staff} ${day}曜 ${label}（${on?'勤務':'非勤務'}、タップで切替）"></button>`;
      }).join('');
      html += `<td><div class="strip">${blks}</div></td>`;
    });
    html += '</tr>';
  });
  table.innerHTML = html;
  table.querySelectorAll('[data-staff][data-day][data-slot]').forEach(el=>{
    el.addEventListener('click', async ()=>{
      const staff = el.dataset.staff, day = el.dataset.day, slotIdx = Number(el.dataset.slot);
      if(!state.staffWorkSlots[staff]) state.staffWorkSlots[staff] = {};
      const set = new Set(state.staffWorkSlots[staff][day] || []);
      if(set.has(slotIdx)) set.delete(slotIdx); else set.add(slotIdx);
      state.staffWorkSlots[staff][day] = Array.from(set).sort((a,b)=>a-b);
      await saveState();
      renderWorkdayTable(role);
      renderOverview(role);
    });
  });
}

document.getElementById('addStaffBtn').addEventListener('click', ()=>{
  const nameInput = document.getElementById('newStaffName');
  const roleSelect = document.getElementById('newStaffRole');
  const name = nameInput.value.trim();
  if(!name) return;
  addStaffMember(name, roleSelect.value);
  nameInput.value = '';
});
document.getElementById('bufferNurse').addEventListener('change', async (e)=>{
  const v = Math.max(0, parseInt(e.target.value,10)||0);
  e.target.value = v;
  state.staffBuffer['看護師'] = v;
  await saveState();
  renderOverview('看護師');
});
document.getElementById('bufferTherapist').addEventListener('change', async (e)=>{
  const v = Math.max(0, parseInt(e.target.value,10)||0);
  e.target.value = v;
  state.staffBuffer['セラピスト'] = v;
  await saveState();
  renderOverview('セラピスト');
});
['看護師','セラピスト'].forEach(role=>{
  document.getElementById('addSlotBtn-'+role).addEventListener('click', ()=>{
    const input = document.getElementById('newSlotLabel-'+role);
    const v = input.value.trim();
    if(!v) return;
    addSlot(role, v);
    input.value = '';
  });
});
document.getElementById('addCmBtn').addEventListener('click', async ()=>{
  const input = document.getElementById('newCmName');
  const v = input.value.trim();
  if(!v) return;
  if(!state.referralSources.careManagers.includes(v)) state.referralSources.careManagers.push(v);
  await saveState();
  input.value = '';
  renderSettings();
});
document.getElementById('addHospBtn').addEventListener('click', async ()=>{
  const input = document.getElementById('newHospName');
  const v = input.value.trim();
  if(!v) return;
  if(!state.referralSources.hospitals.includes(v)) state.referralSources.hospitals.push(v);
  await saveState();
  input.value = '';
  renderSettings();
});
document.getElementById('addDistrictBtn').addEventListener('click', async ()=>{
  const input = document.getElementById('newDistrictName');
  const v = input.value.trim();
  if(!v) return;
  if(!state.districts.includes(v)) state.districts.push(v);
  await saveState();
  input.value = '';
  renderSettings();
  populateReferralSelects();
});

// ---------- リセット ----------
document.getElementById('clearCustomersBtn').addEventListener('click', async ()=>{
  if(!confirm('登録済みの利用者情報（予約・終了履歴）をすべて消去します。スタッフ・時間帯枠・設定などはそのまま残ります。よろしいですか？')) return;
  if(!confirm('本当によろしいですか？　この操作は取り消せません。')) return;
  state.bookings = {};
  state.eventLog = [];
  await saveState();
  showToast('利用者情報を初期化しました');
  renderOverview('看護師'); renderOverview('セラピスト');
});
document.getElementById('resetBtn').addEventListener('click', async ()=>{
  if(!confirm('全員で共有しているデータをすべて消し、Excelから読み込んだ最初の状態に戻します。この操作は取り消せません。よろしいですか？')) return;
  state = freshState();
  await saveState();
  showToast('初期状態に戻しました');
  renderOverview('看護師'); renderOverview('セラピスト');
});

// ---------- 初期化 ----------
(async function init(){
  await loadState();
  buildChips();
  populateReferralSelects();
  updatePatternUI();
  renderOverview('看護師');
  renderOverview('セラピスト');
  // サマリーカード列の高さが後から変わる場合（フォント読み込み・ウィンドウ幅の変更など）にも
  // 右側のアラート枠の高さがずれたままにならないよう、継続的に監視して揃え続ける
  if(window.ResizeObserver){
    ['看護師','セラピスト'].forEach(role=>{
      const main = document.getElementById('ovMain-'+role);
      if(!main) return;
      new ResizeObserver(()=>syncOverviewAlertHeight(role)).observe(main);
    });
  }
})();

</script>
</body>
</html>
