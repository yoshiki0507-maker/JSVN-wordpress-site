# 訪問看護 空き枠管理アプリ - 引き継ぎメモ

## これは何か
訪問看護ステーションの「空き枠管理」「新規利用者の受け入れ提案」「紹介元分析」を行う
社内向けツール。管理者（スタッフ）が共通のパスワードでログインし、全員で同じデータを見る・
編集する形で使うことを想定している。

## 技術構成
- フロントエンド：Vanilla JS + HTML + CSS（`index.php`）。フレームワーク・ビルド工程なし。
- バックエンド：PHP + MySQL（CoreServerなどの一般的な共有レンタルサーバーで動く構成）。
- データはMySQLの `app_state` テーブルに1行（JSON文字列）として保存し、
  `api/state.php` 経由でフロントエンドと共有する（全管理者が同じデータを見る）。
- 認証は管理者共通の1つのパスワード（`config.php` にハッシュを保存、PHPセッションでログイン状態を管理）。
- デプロイ手順は `DEPLOY.md` を参照。

### ファイル構成
```
index.php          … アプリ本体（要ログイン）
login.php           … ログイン画面
logout.php          … ログアウト処理
lib/auth.php         … ログイン必須ページ用のセッションチェック
lib/db.php           … MySQL接続（config.phpを読む）
api/state.php        … 状態の取得(GET)・保存(POST)を行うJSON API（要ログイン）
config.sample.php    … 設定ファイルのひな形（実際は config.php としてサーバー側で作成、Gitには含めない）
schema.sql           … MySQLのテーブル定義
DEPLOY.md            … CoreServerへのデプロイ手順
```

## データモデル（index.php内のJS、MySQLの app_state.data カラムにJSON文字列として保存）
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
1. **同時編集は後勝ち（last-write-wins）**：複数管理者がほぼ同時に保存すると、後から保存した方の
   内容で上書きされる（競合検知や警告表示はまだ実装していない）。編集がぶつかることが実際に
   問題になるようなら、楽観ロック（保存時のタイムスタンプ比較）の追加を検討。
2. **入力値のエスケープ**：利用者名・疾患名などは画面表示時にHTMLエスケープしていない
   （信頼できる内部スタッフのみが入力する想定のため、現状は許容している）。外部にも公開する場合は要対応。
3. **Excelとの連携なし**：元のExcel（訪問予定表_最新版.xlsx）とこのアプリは同期していない。
   どちらかをメインの記録にするか、連携の仕組みを作るか要検討。
4. **紹介元分析の「割合」の定義**：現状は「自社の現在の利用者全体に占める、その紹介元のシェア」
   として計算している（各事業所側の全利用者数に対する割合ではない）。要件次第で変更が必要。
5. **利用者の同一性判定が名前ベース**：同姓同名がいると紹介元分析などで名寄せが誤る可能性がある。
   本格運用する場合はID採番を検討。
6. **管理者ログインは共通パスワード1つ**：誰がいつ編集したかは記録されない。個人ごとの操作履歴が
   必要になった場合は、アカウントを分ける仕組みへの変更を検討。

## 次にやりたいこと（例）
- [x] `window.storage` を `localStorage` に置き換え、単体のWebページとして動くようにする
- [x] 簡易バックエンド（PHP + MySQL）を追加し、複数端末・複数人での共有を可能にする（CoreServer想定）
- [ ] （任意）Excelとの連携方法を検討する
- [ ] （任意）同時編集の競合検知・警告表示
