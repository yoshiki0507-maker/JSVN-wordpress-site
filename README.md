# 訪問看護 空き枠管理アプリ - 引き継ぎメモ

## これは何か
訪問看護ステーションの「空き枠管理」「新規利用者の受け入れ提案」「紹介元分析」を行う
社内向けツール。単一のHTMLファイル（`空き枠管理アプリ.html`）で完結している。

## 現在の技術構成（重要）
- Vanilla JS + HTML + CSS のみ。フレームワーク・ビルド工程なし。
- データ永続化には **`localStorage`** を使用している（ブラウザ単体で動作する）。
  → 保存先はブラウザ・端末ごとに独立しており、複数端末・複数人での共有はできない。
  → 共有まで対応するなら、簡単なバックエンド（例: SQLite + 小さめのAPIサーバー）を
    用意するのが望ましい（未実装）。

## データモデル（app.js内、localStorageキー"appState"）
```
state = {
  staff: [{name, role: '看護師'|'セラピスト'}, ...],
  slotLabels: string[],                 // 時間帯の名称（並び替え可能）
  staffWorkdays: { [staffName]: 曜日配列 },
  bookings: {
    [bookingId]: {
      staff, day, slotIdx,
      weeks: number[],                  // 1-4。[1,2,3,4]なら毎週、[1,3]なら隔週など
      name, disease, alone, careManager, hospital, timeNote, note, startDate
    }
  },
  eventLog: [{type:'新規'|'終了', date, name, staff, day, slot, careManager, hospital}, ...],
  referralSources: { careManagers: string[], hospitals: string[] }
}
```
- 1つの(staff, day, slotIdx)に対して複数の`booking`が共存できる（隔週・月次ローテーション対応）。
  週の重複チェックは`isRotationFree()` / `isFullyFree()`を参照。
- 初期データは`SEED`定数（HTML内に埋め込み済みJSON）。元のExcel（訪問予定表）から
  スタッフの勤務曜日と、既存予定の曜日パターン（第1週・隔週など）を抽出したもの。
  利用者名・疾患名などの個人情報はSEEDには一切含まれていない（プライバシー配慮のため）。

## 既知の制約・今後の検討事項
1. **保存先がブラウザ単体**：`localStorage`のため端末をまたいだ共有はできない。
   複数端末・複数人での共有をするなら簡易バックエンドの追加を検討。
2. **Excelとの連携なし**：元のExcel（訪問予定表_最新版.xlsx）とこのアプリは同期していない。
   どちらかをメインの記録にするか、連携の仕組みを作るか要検討。
3. **紹介元分析の「割合」の定義**：現状は「自社の現在の利用者全体に占める、その紹介元のシェア」
   として計算している（各事業所側の全利用者数に対する割合ではない）。要件次第で変更が必要。
4. **利用者の同一性判定が名前ベース**：同姓同名がいると紹介元分析などで名寄せが誤る可能性がある。
   本格運用する場合はID採番を検討。

## 次にやりたいこと（例）
- [x] `window.storage` を `localStorage` に置き換え、単体のWebページとして動くようにする
- [ ] （任意）簡易バックエンドを追加し、複数端末・複数人での共有を可能にする
- [ ] （任意）Excelとの連携方法を検討する
