<?php
declare(strict_types=1);
require __DIR__ . '/lib/auth.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>空き枠管理・受け入れ提案</title>
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
    width:200px; flex:0 0 200px; background:var(--teal-deep); color:#EAF3F0;
    padding:24px 0; display:flex; flex-direction:column; gap:2px;
  }
  .nav h1{ font-size:15px; font-weight:700; letter-spacing:.02em; padding:0 20px 18px; margin:0; color:#fff; line-height:1.5; border-bottom:1px solid rgba(255,255,255,.15); margin-bottom:10px;}
  .nav button{
    all:unset; cursor:pointer; padding:12px 20px; font-size:13.5px; color:#CFE3DE;
    border-left:3px solid transparent; font-family:var(--font-ui);
  }
  .nav button:hover{ background:rgba(255,255,255,.06); color:#fff; }
  .nav button.active{ background:rgba(255,255,255,.1); color:#fff; border-left-color:#8FD4C1; font-weight:700; }
  .nav button:focus-visible{ outline:2px solid #8FD4C1; outline-offset:-2px; }
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
  .strip .blk:hover{ transform:scale(1.18); }
  .strip .blk:focus-visible{ outline:2px solid var(--teal); outline-offset:1px; }

  .staff-row-item{ display:flex; align-items:center; justify-content:space-between; background:var(--surface); border:1px solid var(--line); border-radius:8px; padding:9px 12px; margin-bottom:6px; }
  .role-pill{ font-size:10px; font-weight:700; padding:2px 9px; border-radius:999px; margin-left:8px; }
  .role-pill.nurse{ background:var(--teal-tint); color:var(--teal-deep); }
  .role-pill.therapist{ background:var(--indigo-tint); color:var(--indigo); }

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
    .nav h1{ display:none; }
    .nav button{ border-left:none; border-bottom:3px solid transparent; white-space:nowrap; }
    .nav button.active{ border-bottom-color:#8FD4C1; }
    main{ padding:18px 16px 50px; }
    form.intake{ grid-template-columns:1fr; }
  }
</style>
</head>
<body>
<div class="app">
  <nav class="nav">
    <h1>訪問看護<br>空き枠管理</h1>
    <button data-panel="overview-nurse" class="active">① 空き状況〈看護師〉</button>
    <button data-panel="overview-therapist">② 空き状況〈セラピスト〉</button>
    <button data-panel="intake">③ 新規登録・提案</button>
    <button data-panel="end">④ 終了処理</button>
    <button data-panel="referral">⑤ 紹介元分析</button>
    <button data-panel="report">⑥ 月次レポート</button>
    <button data-panel="settings">⑦ 設定</button>
  </nav>
  <main>

    <section id="panel-overview-nurse" class="panel active">
      <div class="ov-head ov-head-nurse">
        <div class="ov-icon">🩺</div>
        <div>
          <h2 class="page-title" style="margin:0;">看護師の空き状況</h2>
          <p class="page-sub" style="margin:2px 0 0;">枠の並びは左から <span id="slotHint-看護師"></span> の順です。タップすると詳細が見られます。</p>
        </div>
      </div>
      <div class="summary-row" id="summaryRow-看護師"></div>
      <div class="legend" id="legend-看護師"><span><i style="background:var(--sage-tint);border:1px solid var(--sage)"></i>空き</span>
        <span><i style="background:var(--amber-tint);border:1px solid var(--amber)"></i>一部空き（隔週・月次ローテーション）</span>
        <span><i style="background:var(--brick-tint);border:1px solid var(--brick)"></i>使用中（満枠）</span>
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
      </div>
      <div class="summary-row" id="summaryRow-セラピスト"></div>
      <div class="legend" id="legend-セラピスト"><span><i style="background:var(--sage-tint);border:1px solid var(--sage)"></i>空き</span>
        <span><i style="background:var(--amber-tint);border:1px solid var(--amber)"></i>一部空き（隔週・月次ローテーション）</span>
        <span><i style="background:var(--brick-tint);border:1px solid var(--brick)"></i>使用中（満枠）</span>
        <span><i style="background:#EEEFEC;border:1px solid #D8DAD5"></i>非勤務日</span></div>
      <div style="overflow-x:auto;">
        <table class="grid ov-table" id="overviewTable-セラピスト"></table>
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
        <div>
          <label for="f-pattern">訪問頻度パターン</label>
          <select id="f-pattern">
            <option value="weekly">毎週（曜日を指定・複数日選択可）</option>
            <option value="biweekly_13">隔週（第1・3週）</option>
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
          </select>
        </div>
        <div class="full">
          <label id="dayChipsLabel">希望曜日（未選択＝指定なし）</label>
          <div class="chip-group" id="dayChips"></div>
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
      <h2 class="page-title">終了処理</h2>
      <p class="page-sub">現在「使用中」になっている枠の一覧です。訪問終了・利用終了になったものを解除すると、空き状況に反映されます。</p>
      <input type="text" id="endSearch" placeholder="利用者名で検索…" style="max-width:280px;margin-bottom:14px;padding:8px 10px;border:1px solid var(--line);border-radius:7px;font-size:13.5px;font-family:var(--font-ui);">
      <div id="endList"></div>
    </section>

    <section id="panel-referral" class="panel">
      <h2 class="page-title">紹介元分析</h2>
      <p class="page-sub">「居宅介護支援事業所」「医療機関（訪問看護指示書発行元）」ごとに、現在の実利用者数・全体に占める割合・直近の新規紹介数を集計します。※ここでの「割合」は自社の現在の利用者全体に占めるシェアという意味で計算しています（各事業所が抱える利用者全体の中での割合ではありません）。</p>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:22px 0 8px;">居宅介護支援事業所別</h3>
      <table class="grid" id="cmTable"></table>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:26px 0 8px;">医療機関別（訪問看護指示書発行元）</h3>
      <table class="grid" id="hospTable"></table>
    </section>

    <section id="panel-report" class="panel">
      <h2 class="page-title">月次レポート</h2>
      <p class="page-sub">このアプリ上で登録・終了処理をした件数を月ごとに集計しています（Excel側の既存データは対象になりません）。</p>
      <div class="report-grid" id="reportGrid"></div>
      <table class="grid" id="reportTable"></table>
    </section>

    <section id="panel-settings" class="panel">
      <h2 class="page-title">設定</h2>
      <p class="page-sub">時間帯の枠構成や、居宅介護支援事業所・医療機関のマスタを編集できます。</p>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:8px 0 8px;">スタッフ管理</h3>
      <p class="page-sub" style="margin-bottom:10px;">看護師・セラピストの追加や削除ができます。削除は、そのスタッフに現在ご利用中の予定がない場合のみ行えます。</p>
      <div id="staffList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;flex-wrap:wrap;">
        <input type="text" id="newStaffName" placeholder="スタッフ名" style="max-width:180px;">
        <select id="newStaffRole" style="max-width:140px;">
          <option value="看護師">看護師</option>
          <option value="セラピスト">セラピスト</option>
        </select>
        <button class="btn btn-ghost btn-small" id="addStaffBtn">＋ スタッフを追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">時間帯（枠）の設定</h3>
      <p class="page-sub" style="margin-bottom:10px;">名前の変更・並べ替え・追加・削除ができます。削除は、その枠に現在ご利用中の予定がない場合のみ行えます。</p>
      <div id="slotSettingsList"></div>
      <div style="display:flex;gap:8px;margin-top:10px;">
        <input type="text" id="newSlotLabel" placeholder="新しい枠の名前（例：16:00〜）" style="max-width:240px;">
        <button class="btn btn-ghost btn-small" id="addSlotBtn">＋ 枠を追加</button>
      </div>

      <h3 style="font-size:14px;color:var(--teal-deep);margin:30px 0 8px;">看護師の勤務曜日</h3>
      <p class="page-sub" style="margin-bottom:10px;">土曜・日曜に勤務するスタッフはチェックを入れてください（未チェックの曜日は空き状況・提案の対象になりません）。</p>
      <div style="overflow-x:auto;"><table class="grid" id="workdayTable-看護師"></table></div>
      <h3 style="font-size:14px;color:var(--teal-deep);margin:24px 0 8px;">セラピストの勤務曜日</h3>
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
    </section>

    <div class="footer-actions">
      <a href="logout.php" class="btn btn-ghost btn-small" style="text-decoration:none;margin-right:8px;">ログアウト</a>
      <button class="btn btn-ghost btn-small" id="resetBtn">共有データを初期状態に戻す（全員に影響します）</button>
    </div>
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
const WEEKS = [1,2,3,4];
const ROLES = ['看護師','セラピスト'];

const PATTERNS = {
  weekly:      { label:'毎週',            kind:'weekly' },
  biweekly_13: { label:'隔週（第1・3週）', kind:'rotation', weeks:[1,3] },
  biweekly_24: { label:'隔週（第2・4週）', kind:'rotation', weeks:[2,4] },
  monthly_1:   { label:'月1回（第1週）',   kind:'rotation', weeks:[1] },
  monthly_2:   { label:'月1回（第2週）',   kind:'rotation', weeks:[2] },
  monthly_3:   { label:'月1回（第3週）',   kind:'rotation', weeks:[3] },
  monthly_4:   { label:'月1回（第4週）',   kind:'rotation', weeks:[4] },
};

let state = null; // { staff, slotLabels, staffWorkdays, bookings, eventLog, referralSources }
let uidCounter = 0;
function newId(){ uidCounter++; return Date.now()+'-'+uidCounter+'-'+Math.random().toString(36).slice(2,6); }

function todayStr(){
  const d = new Date();
  return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
}
function monthKey(dateStr){ return dateStr.slice(0,7); }
function slotCount(){ return state.slotLabels.length; }
function staffNames(role){
  return state.staff.filter(s=>!role || s.role===role).map(s=>s.name);
}
function staffInfo(name){
  return state.staff.find(s=>s.name===name) || {name, role:'看護師'};
}
function labelForWeeks(weeks){
  const sorted = (weeks||[1,2,3,4]).slice().sort((a,b)=>a-b);
  if(sorted.length>=4) return '毎週';
  const key = sorted.join(',');
  if(key==='1,3') return '隔週（第1・3週）';
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
  const staffWorkdays = {};
  staff.forEach(s=>{ staffWorkdays[s.name] = (SEED.staffWorkdays[s.name]||[]).slice(); });
  const bookings = {};
  Object.entries(SEED.legacyOccupancy || {}).forEach(([key, slotArray])=>{
    const [staffName, day] = key.split('|');
    slotArray.forEach((occupants, slotIdx)=>{
      occupants.forEach(occ=>{
        const id = newId();
        bookings[id] = {
          staff: staffName, day, slotIdx,
          weeks: (occ.weeks && occ.weeks.length ? occ.weeks.slice() : WEEKS.slice()),
          patternValue: null,
          name:'', disease:'', alone:'不明', careManager:'', hospital:'',
          timeNote: occ.timeNote || '', note:'（Excel取り込み時点の既存予定／お名前は未登録です）',
          startDate: todayStr()
        };
      });
    });
  });
  return {
    staff,
    slotLabels: SEED.slotLabels.slice(),
    staffWorkdays,
    bookings,
    eventLog: [],
    referralSources: { careManagers: [], hospitals: [] }
  };
}

function migrateState(loaded){
  // スタッフ一覧が無い旧バージョンの場合、SEEDの看護師一覧として補完
  if(!loaded.staff){
    const names = new Set(SEED.staffOrder);
    Object.keys(loaded.staffWorkdays||{}).forEach(n=>names.add(n));
    loaded.staff = Array.from(names).map(name=>({name, role:'看護師'}));
  }
  if(!loaded.staffWorkdays){
    loaded.staffWorkdays = {};
    loaded.staff.forEach(s=>{ loaded.staffWorkdays[s.name] = (SEED.staffWorkdays[s.name]||[]).slice(); });
  }
  if(!loaded.referralSources) loaded.referralSources = { careManagers: [], hospitals: [] };
  if(!loaded.slotLabels) loaded.slotLabels = SEED.slotLabels.slice();

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

function worksOn(staff, day){
  return (state.staffWorkdays[staff]||[]).includes(day);
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
  intake: ()=>{ populateReferralSelects(); updatePatternUI(); },
  end: renderEndList,
  referral: renderReferralAnalysis,
  report: renderReport,
  settings: renderSettings
};
document.querySelectorAll('.nav button').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('.nav button').forEach(b=>b.classList.remove('active'));
    document.querySelectorAll('.panel').forEach(p=>p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-'+btn.dataset.panel).classList.add('active');
    const fn = RENDERERS[btn.dataset.panel];
    if(fn) fn();
  });
});

function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.classList.add('show');
  setTimeout(()=>t.classList.remove('show'), 2600);
}

// ---------- モーダル（タップで詳細） ----------
function closeModal(){ document.getElementById('slotModal').hidden = true; }
document.getElementById('modalClose').addEventListener('click', closeModal);
document.getElementById('slotModal').addEventListener('click', (e)=>{
  if(e.target.id==='slotModal') closeModal();
});

function patternLabelOf(b){ return labelForWeeks(b.weeks); }

async function endBookingById(id){
  const b = state.bookings[id];
  if(!b) return;
  if(!confirm(`${b.day}曜 ${state.slotLabels[b.slotIdx]}〜（${b.staff}／${b.name||'名前未登録'}）を終了にします。よろしいですか？`)) return;
  state.eventLog.push({
    id:newId(), type:'終了', date: todayStr(), name: b.name||'', staff:b.staff, day:b.day, slot:b.slotIdx,
    careManager: b.careManager||'', hospital: b.hospital||''
  });
  delete state.bookings[id];
  await saveState();
  showToast('終了処理を反映しました');
  closeModal();
  renderOverview('看護師'); renderOverview('セラピスト');
  if(document.getElementById('panel-end').classList.contains('active')) renderEndList();
}

function openSlotModal(staff, day, slotIdx){
  const modal = document.getElementById('slotModal');
  const content = document.getElementById('modalContent');
  const bks = bookingsAt(staff, day, slotIdx);
  const used = occupiedWeeksAt(staff, day, slotIdx);
  const freeWeeks = WEEKS.filter(w=>!used.has(w));
  let html = `<div class="modal-head">${day}曜　${state.slotLabels[slotIdx]}〜</div><div class="modal-sub">担当：${staff}（${staffInfo(staff).role}）</div>`;
  if(!bks.length){
    html += `<div class="free-note">この枠は現在すべての週で空いています</div>`;
  }else{
    bks.forEach(b=>{
      html += `<div class="booking-card">
        <div class="btag">${patternLabelOf(b)}</div>
        <div class="bname">${b.name || '（名前未登録）'}</div>
        <div class="brow">疾患：${b.disease||'―'}／独居：${b.alone||'―'}</div>
        <div class="brow">居宅：${b.careManager||'―'}</div>
        <div class="brow">医療機関：${b.hospital||'―'}</div>
        ${b.timeNote?`<div class="brow">時刻メモ：${b.timeNote}</div>`:''}
        ${b.note?`<div class="brow">備考：${b.note}</div>`:''}
        <div class="brow">登録日：${b.startDate||'―'}</div>
        <button class="btn btn-danger btn-small" style="margin-top:8px;" data-end="${b.id}">この予定を終了する</button>
      </div>`;
    });
    if(freeWeeks.length){
      html += `<div class="free-note">空いている週：第${freeWeeks.join('・第')}週</div>`;
    }
  }
  content.innerHTML = html;
  content.querySelectorAll('[data-end]').forEach(btn=>{
    btn.addEventListener('click', ()=>endBookingById(btn.dataset.end));
  });
  modal.hidden = false;
}

// ---------- ① ② 空き状況（看護師／セラピスト） ----------
function renderOverview(role){
  const names = staffNames(role);
  const hintEl = document.getElementById('slotHint-'+role);
  if(hintEl) hintEl.textContent = state.slotLabels.join(' → ');

  const summaryRow = document.getElementById('summaryRow-'+role);
  summaryRow.innerHTML = '';
  DAYS.forEach(day=>{
    let free = 0, total = 0;
    names.forEach(staff=>{
      if(!worksOn(staff, day)) return;
      for(let i=0;i<slotCount();i++){
        total++;
        if(slotVisualState(staff,day,i)!=='busy') free++;
      }
    });
    if(total===0) return;
    const card = document.createElement('div');
    card.className = 'summary-card';
    card.innerHTML = `<div class="day">${day}曜</div><div class="num">${free}</div><div class="unit">枠 空き余地</div>`;
    summaryRow.appendChild(card);
  });

  const table = document.getElementById('overviewTable-'+role);
  if(!names.length){
    table.innerHTML = `<tr><th>担当スタッフ</th></tr><tr><td style="color:var(--ink-soft);">${role}がまだ登録されていません。⑦設定の「スタッフ管理」から追加してください。</td></tr>`;
    return;
  }
  let html = '<tr><th>担当スタッフ</th>' + DAYS.map(d=>`<th>${d}</th>`).join('') + '</tr>';
  names.forEach(staff=>{
    const avatarClass = role==='セラピスト' ? 'therapist' : 'nurse';
    html += `<tr><td><div class="staff-cell"><div class="avatar ${avatarClass}">${staff.trim()[0]||'?'}</div><span class="staff-name">${staff}</span></div></td>`;
    DAYS.forEach(day=>{
      if(!worksOn(staff, day)){
        html += `<td><div class="strip">${state.slotLabels.map(()=>`<div class="blk off"></div>`).join('')}</div></td>`;
      }else{
        const blks = state.slotLabels.map((label,i)=>{
          const st = slotVisualState(staff,day,i);
          return `<button type="button" class="blk ${st==='free'?'':st}" data-staff="${staff}" data-day="${day}" data-slot="${i}" aria-label="${day}曜${label} ${staff}"></button>`;
        }).join('');
        html += `<td><div class="strip">${blks}</div></td>`;
      }
    });
    html += '</tr>';
  });
  table.innerHTML = html;
  table.querySelectorAll('.blk[data-staff]').forEach(el=>{
    el.addEventListener('click', ()=>openSlotModal(el.dataset.staff, el.dataset.day, Number(el.dataset.slot)));
  });
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
  const slotWrap = document.getElementById('slotChips');
  selectedSlots = selectedSlots.filter(i=>i < slotCount());
  slotWrap.innerHTML = state.slotLabels.map((s,i)=>`<span class="chip ${selectedSlots.includes(i)?'on':''}" data-slot="${i}">${s}</span>`).join('');
  slotWrap.querySelectorAll('.chip').forEach(chip=>{
    chip.addEventListener('click', ()=>{
      const i = Number(chip.dataset.slot);
      if(selectedSlots.includes(i)){ selectedSlots = selectedSlots.filter(x=>x!==i); }
      else{ selectedSlots.push(i); }
      buildSlotChips();
    });
  });
}
function updatePatternUI(){
  const val = document.getElementById('f-pattern').value;
  const isRotation = PATTERNS[val].kind === 'rotation';
  document.getElementById('freqWeeklyWrap').style.display = isRotation ? 'none' : '';
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
  const opts = (list)=> `<option value="">未設定</option>` + list.map(n=>`<option value="${n}">${n}</option>`).join('');
  cm.innerHTML = opts(state.referralSources.careManagers);
  hosp.innerHTML = opts(state.referralSources.hospitals);
  buildSlotChips();
}

function orderedDays(preferred){
  if(!preferred || !preferred.length) return DAYS.slice();
  return DAYS.filter(d=>preferred.includes(d)).concat(DAYS.filter(d=>!preferred.includes(d)));
}
function orderedSlots(preferred){
  const all = state.slotLabels.map((_,i)=>i);
  if(!preferred || !preferred.length) return all;
  const pref = preferred.filter(i=>i<slotCount()).sort((a,b)=>a-b);
  return pref.concat(all.filter(i=>!pref.includes(i)));
}

function findWeeklySuggestions(freq, preferredDays, preferredSlots, role){
  const dayOrder = orderedDays(preferredDays);
  const slotOrder = orderedSlots(preferredSlots);
  const pool = staffNames(role);
  const results = [];
  let tier1Map = new Map();
  pool.forEach(staff=>{
    const workdays = dayOrder.filter(d=>worksOn(staff,d));
    if(workdays.length < freq) return;
    for(const slotIdx of slotOrder){
      const freeDays = workdays.filter(d=>isFullyFree(staff,d,slotIdx));
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
      const workdays = dayOrder.filter(d=>worksOn(staff,d));
      if(workdays.length < freq) return;
      const picks = [];
      workdays.forEach(d=>{
        for(const s of slotOrder){
          if(isFullyFree(staff,d,s)){ picks.push({day:d, slot:s}); break; }
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
        if(!worksOn(staff,d)) continue;
        let found = -1;
        for(const s of slotOrder){ if(isFullyFree(staff,d,s)){ found = s; break; } }
        if(found>=0){ picks.push({day:d, slot:found, staff}); break; }
      }
    }
    if(picks.length >= freq){
      results.push({tier:3, picks: picks.slice(0,freq), load:0});
    }
  }
  return results.slice(0,3);
}

function findRotationSuggestions(candWeeks, preferredDays, preferredSlots, role){
  const dayOrder = orderedDays(preferredDays);
  const slotOrder = orderedSlots(preferredSlots);
  const pool = staffNames(role).slice().sort((a,b)=>totalLoad(a)-totalLoad(b));
  const found = [];
  const seenStaff = new Set();
  for(const staff of pool){
    if(seenStaff.has(staff)) continue;
    for(const day of dayOrder){
      if(!worksOn(staff,day)) continue;
      for(const slotIdx of slotOrder){
        if(isRotationFree(staff,day,slotIdx,candWeeks)){
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

function labelSuggestion(s, preferredDays){
  if(s.tier==='rotation'){
    return { tag:'候補', text: `${s.day}曜　${state.slotLabels[s.slotIdx]}〜`, sub: `担当：${s.staff}` };
  }
  if(s.tier===1){
    const daysTxt = s.days.slice().sort((a,b)=>DAYS.indexOf(a)-DAYS.indexOf(b)).join('・');
    const note = extraDaysNote(s.days, preferredDays);
    return {
      tag:'第一候補（同じ担当・同じ時間）',
      text: `${daysTxt}　${state.slotLabels[s.slotIdx]}〜`,
      sub: `担当：${s.staff}` + (note?`　${note}`:'')
    };
  }
  if(s.tier===2){
    const sorted = s.picks.slice().sort((a,b)=>DAYS.indexOf(a.day)-DAYS.indexOf(b.day));
    const txt = sorted.map(p=>`${p.day}${state.slotLabels[p.slot]}〜`).join('・');
    const note = extraDaysNote(sorted.map(p=>p.day), preferredDays);
    return { tag:'第二候補（同じ担当・時間は日によって異なる）', text: txt, sub:`担当：${s.staff}` + (note?`　${note}`:'') };
  }
  const sorted = s.picks.slice().sort((a,b)=>DAYS.indexOf(a.day)-DAYS.indexOf(b.day));
  const txt = sorted.map(p=>`${p.day}${state.slotLabels[p.slot]}〜(${p.staff})`).join('・');
  return { tag:'代替案（複数の担当者に分かれます）', text: txt, sub:'継続性より、まず枠の確保を優先した案です' };
}

async function confirmSuggestion(s, patient, patternValue){
  const date = todayStr();
  const pattern = PATTERNS[patternValue];
  const create = (staff, day, slotIdx, weeks)=>{
    const id = newId();
    state.bookings[id] = {
      staff, day, slotIdx, weeks, patternValue,
      name: patient.name, disease: patient.disease, alone: patient.alone, note: patient.note,
      careManager: patient.careManager, hospital: patient.hospital, timeNote: patient.timeNote,
      startDate: date
    };
    state.eventLog.push({
      id: newId(), type:'新規', date, name: patient.name,
      staff, day, slot: slotIdx, careManager: patient.careManager, hospital: patient.hospital
    });
  };
  if(s.tier==='rotation'){ create(s.staff, s.day, s.slotIdx, pattern.weeks); }
  else if(s.tier===1){ s.days.forEach(d=>create(s.staff, d, s.slotIdx, WEEKS.slice())); }
  else if(s.tier===2){ s.picks.forEach(p=>create(s.staff, p.day, p.slot, WEEKS.slice())); }
  else { s.picks.forEach(p=>create(p.staff, p.day, p.slot, WEEKS.slice())); }
  await saveState();
  showToast(`${patient.name || '利用者様'} を登録しました`);
  document.getElementById('suggestions').innerHTML = '';
  document.getElementById('intakeForm').reset();
  selectedDays = []; selectedSlots = [];
  updatePatternUI();
  buildSlotChips();
  renderOverview('看護師'); renderOverview('セラピスト');
}

document.getElementById('intakeForm').addEventListener('submit', (e)=>{
  e.preventDefault();
  const role = document.getElementById('f-role').value;
  const patternValue = document.getElementById('f-pattern').value;
  const pattern = PATTERNS[patternValue];
  const freq = Number(document.getElementById('f-freq').value);
  const patient = {
    name: document.getElementById('f-name').value.trim(),
    disease: document.getElementById('f-disease').value.trim(),
    alone: document.getElementById('f-alone').value,
    careManager: document.getElementById('f-cm').value,
    hospital: document.getElementById('f-hosp').value,
    timeNote: document.getElementById('f-timenote').value.trim(),
    note: document.getElementById('f-note').value.trim(),
    freq
  };
  const sugg = pattern.kind==='weekly'
    ? findWeeklySuggestions(freq, selectedDays, selectedSlots, role)
    : findRotationSuggestions(pattern.weeks, selectedDays, selectedSlots, role);
  const box = document.getElementById('suggestions');
  if(!sugg.length){
    box.innerHTML = `<div class="empty-msg">条件に合う空き枠が見つかりませんでした。${role}の登録がない、または曜日・時間の希望が合っていない可能性があります。</div>`;
    return;
  }
  box.innerHTML = '';
  sugg.forEach(s=>{
    const info = labelSuggestion(s, selectedDays);
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

// ---------- ④ 終了処理 ----------
function renderEndList(){
  const wrap = document.getElementById('endList');
  const searchInput = document.getElementById('endSearch');
  const kw = (searchInput.value||'').trim();
  let rows = Object.entries(state.bookings).map(([id,b])=>Object.assign({id}, b));
  if(kw) rows = rows.filter(b=> (b.name||'').includes(kw));
  rows.sort((a,b)=> DAYS.indexOf(a.day)-DAYS.indexOf(b.day) || a.slotIdx-b.slotIdx);

  if(!rows.length){
    wrap.innerHTML = `<p class="page-sub">${kw ? '一致する利用者様が見つかりません。' : '現在、使用中の枠はありません。'}</p>`;
    return;
  }
  wrap.innerHTML = '';
  rows.forEach(b=>{
    const el = document.createElement('div');
    el.className = 'end-row';
    const nameTxt = b.name || '（名前未入力）';
    const role = staffInfo(b.staff).role;
    let metaTxt = `${role}／${patternLabelOf(b)}／疾患：${b.disease||'―'}／独居：${b.alone||'―'}／居宅：${b.careManager||'―'}／医療機関：${b.hospital||'―'}`;
    if(b.timeNote) metaTxt += `／時刻メモ：${b.timeNote}`;
    el.innerHTML = `
      <div>
        <div><strong>${b.day}曜 ${state.slotLabels[b.slotIdx]}〜</strong>　担当：${b.staff}　－　${nameTxt}</div>
        <div class="meta">${metaTxt}</div>
      </div>
      <button class="btn btn-danger btn-small">終了する</button>
    `;
    el.querySelector('button').addEventListener('click', ()=>endBookingById(b.id));
    wrap.appendChild(el);
  });
}
document.getElementById('endSearch').addEventListener('input', renderEndList);

// ---------- ⑤ 紹介元分析 ----------
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

// ---------- ⑥ 月次レポート ----------
function renderReport(){
  const byMonth = {};
  state.eventLog.forEach(ev=>{
    const m = monthKey(ev.date);
    if(!byMonth[m]) byMonth[m] = {new:0, end:0};
    if(ev.type==='新規') byMonth[m].new++; else byMonth[m].end++;
  });
  const months = Object.keys(byMonth).sort();
  const grid = document.getElementById('reportGrid');
  const table = document.getElementById('reportTable');
  if(!months.length){
    grid.innerHTML = '<p class="page-sub">まだ登録・終了の記録がありません。③④の操作をすると、ここに集計されます。</p>';
    table.innerHTML = '';
    return;
  }
  const max = Math.max(...months.map(m=>Math.max(byMonth[m].new, byMonth[m].end)), 1);
  grid.innerHTML = months.map(m=>{
    const v = byMonth[m];
    return `<div class="month-card">
      <div class="m">${m}</div>
      <div class="bar-row"><span class="lbl">新規</span><div class="bar-track"><div class="bar-fill new" style="width:${v.new/max*100}%"></div></div><span class="val">${v.new}</span></div>
      <div class="bar-row"><span class="lbl">終了</span><div class="bar-track"><div class="bar-fill end" style="width:${v.end/max*100}%"></div></div><span class="val">${v.end}</span></div>
    </div>`;
  }).join('');

  let html = '<tr><th>年月</th><th>新規</th><th>終了</th><th>純増減</th></tr>';
  months.forEach(m=>{
    const v = byMonth[m];
    html += `<tr><td>${m}</td><td>${v.new}</td><td>${v.end}</td><td>${v.new-v.end>=0?'+':''}${v.new-v.end}</td></tr>`;
  });
  table.innerHTML = html;
}

// ---------- ⑦ 設定 ----------
async function addStaffMember(name, role){
  if(state.staff.some(s=>s.name===name)){
    alert('同じ名前のスタッフが既に登録されています。');
    return;
  }
  state.staff.push({name, role});
  state.staffWorkdays[name] = [];
  await saveState();
  renderSettings();
}
async function removeStaffMember(name){
  const hasBooking = Object.values(state.bookings).some(b=>b.staff===name);
  if(hasBooking){
    alert('このスタッフには現在ご利用中の予定があるため削除できません。先に「終了処理」で予定を終了してから削除してください。');
    return;
  }
  if(!confirm(`「${name}」を削除します。よろしいですか？`)) return;
  state.staff = state.staff.filter(s=>s.name!==name);
  delete state.staffWorkdays[name];
  await saveState();
  renderSettings();
  renderOverview('看護師'); renderOverview('セラピスト');
}

async function addSlot(label){
  state.slotLabels.push(label);
  await saveState();
  renderSettings();
}
async function renameSlot(idx, newLabel){
  state.slotLabels[idx] = newLabel;
  await saveState();
}
async function removeSlot(idx){
  const hasBooking = Object.values(state.bookings).some(b=>b.slotIdx===idx);
  if(hasBooking){
    alert('この枠には現在ご利用中の予定があるため削除できません。先に「終了処理」でその予定を終了してから削除してください。');
    return;
  }
  if(!confirm(`「${state.slotLabels[idx]}」枠を削除します。よろしいですか？`)) return;
  state.slotLabels.splice(idx,1);
  Object.values(state.bookings).forEach(b=>{ if(b.slotIdx > idx) b.slotIdx -= 1; });
  await saveState();
  renderSettings();
}
async function moveSlot(idx, dir){
  const newIdx = idx+dir;
  if(newIdx<0 || newIdx>=slotCount()) return;
  const labels = state.slotLabels;
  [labels[idx], labels[newIdx]] = [labels[newIdx], labels[idx]];
  Object.values(state.bookings).forEach(b=>{
    if(b.slotIdx===idx) b.slotIdx=newIdx;
    else if(b.slotIdx===newIdx) b.slotIdx=idx;
  });
  await saveState();
  renderSettings();
}

function renderSettings(){
  renderStaffList();

  const list = document.getElementById('slotSettingsList');
  list.innerHTML = '';
  state.slotLabels.forEach((label, idx)=>{
    const row = document.createElement('div');
    row.className = 'setting-row';
    row.innerHTML = `
      <span class="idx">${idx+1}</span>
      <input type="text" value="${label.replace(/"/g,'')}">
      <button class="icon-btn" data-act="up" ${idx===0?'disabled':''}>↑</button>
      <button class="icon-btn" data-act="down" ${idx===slotCount()-1?'disabled':''}>↓</button>
      <button class="icon-btn danger" data-act="del">削除</button>
    `;
    const input = row.querySelector('input');
    input.addEventListener('change', ()=>renameSlot(idx, input.value.trim() || label));
    row.querySelector('[data-act="up"]').addEventListener('click', ()=>moveSlot(idx,-1));
    row.querySelector('[data-act="down"]').addEventListener('click', ()=>moveSlot(idx,1));
    row.querySelector('[data-act="del"]').addEventListener('click', ()=>removeSlot(idx));
    list.appendChild(row);
  });

  renderMasterList('cmList', state.referralSources.careManagers, 'careManagers');
  renderMasterList('hospList', state.referralSources.hospitals, 'hospitals');
  renderWorkdayTable('看護師');
  renderWorkdayTable('セラピスト');
}

function renderStaffList(){
  const wrap = document.getElementById('staffList');
  if(!state.staff.length){ wrap.innerHTML = '<p class="page-sub">スタッフが登録されていません。</p>'; return; }
  wrap.innerHTML = '';
  state.staff.forEach(s=>{
    const row = document.createElement('div');
    row.className = 'staff-row-item';
    const pillClass = s.role==='セラピスト' ? 'therapist' : 'nurse';
    row.innerHTML = `<span>${s.name}<span class="role-pill ${pillClass}">${s.role}</span></span><button class="icon-btn danger">削除</button>`;
    row.querySelector('button').addEventListener('click', ()=>removeStaffMember(s.name));
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

function renderWorkdayTable(role){
  const table = document.getElementById('workdayTable-'+role);
  const names = staffNames(role);
  if(!names.length){
    table.innerHTML = `<tr><th>${role}</th></tr><tr><td style="color:var(--ink-soft);">まだ登録がありません</td></tr>`;
    return;
  }
  let html = '<tr><th>スタッフ</th>' + DAYS.map(d=>`<th>${d}</th>`).join('') + '</tr>';
  names.forEach(staff=>{
    html += `<tr><td class="staff-name">${staff}</td>`;
    DAYS.forEach(day=>{
      const checked = worksOn(staff,day) ? 'checked' : '';
      html += `<td style="text-align:center;"><input type="checkbox" data-staff="${staff}" data-day="${day}" ${checked}></td>`;
    });
    html += '</tr>';
  });
  table.innerHTML = html;
  table.querySelectorAll('input[type=checkbox]').forEach(cb=>{
    cb.addEventListener('change', async ()=>{
      const staff = cb.dataset.staff, day = cb.dataset.day;
      const set = new Set(state.staffWorkdays[staff]||[]);
      if(cb.checked) set.add(day); else set.delete(day);
      state.staffWorkdays[staff] = DAYS.filter(d=>set.has(d));
      await saveState();
      renderOverview('看護師'); renderOverview('セラピスト');
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
document.getElementById('addSlotBtn').addEventListener('click', ()=>{
  const input = document.getElementById('newSlotLabel');
  const v = input.value.trim();
  if(!v) return;
  addSlot(v);
  input.value = '';
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

// ---------- リセット ----------
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
})();

</script>
</body>
</html>
