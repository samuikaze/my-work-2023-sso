# 單一登入入口

> [返回根目錄](https://github.com/samuikaze/my-work-2023)

這是單一登入入口的專案，使用 Lumen Framework (PHP) 撰寫而成

## 說明

由於後端拆分許多專案，因此登入不能再倚賴 Session 或 Cookie 作為驗證的媒介，因而有此專案的產生。

> 目前專案雖然使用 JWT 作為 Bearer Token 的方案，但實作上可能仍有未遵循 RFC 的狀況發生

## 事前準備

使用本專案前請先安裝以下軟體

- php 8.1 或以上
- composer 2.0 或以上
- MySQL 或 MariaDB
- Nginx 或 Apache

## 線上展示

- [點此檢視 Swagger 展示](https://syskzworks.ddns.net/forwork/services/singlesignon/api/swagger)

## 本機除錯

可以遵循以下步驟在本機進行除錯或檢視

> ⚠️請注意，`.env` 檔中的相關設定請依據需求作修改

1. `git clone` 將本專案 clone 到本機
2. 打開終端機，切換到本專案資料夾
3. 執行指令 `composer install && composer dump-autoload`
4. 啟動 `nginx` 或 `Apache` 伺服器

  > 也可使用 `php artisan serve` 啟動服務，但此方式在 CORS 預檢請求會得到 404 回應，目前仍未找出問題...

## 參考資料

- [JSON Web Tokens](https://jwt.io/)
- [PHP Carbon class changing my original variable value](https://stackoverflow.com/a/49905830)
- [是誰在敲打我窗？什麼是 JWT ？](https://5xruby.tw/posts/what-is-jwt)
- [JWT “scp” claim vs “scope” claim](https://devforum.okta.com/t/jwt-scp-claim-vs-scope-claim/10155)
- [Get current domain](https://stackoverflow.com/a/50301646)
- [str_starts_with - PHP Manual](https://www.php.net/manual/zh/function.str-starts-with.php)
