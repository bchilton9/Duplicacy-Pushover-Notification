![License](https://img.shields.io/github/license/bchilton9/Duplicacy-Pushover-Notification)
![Last Commit](https://img.shields.io/github/last-commit/bchilton9/Duplicacy-Pushover-Notification)
![Built with PHP](https://img.shields.io/badge/Built%20with-PHP-777bb4?logo=php&logoColor=white)

# Duplicacy to Pushover Webhook

A lightweight PHP script that receives webhook POSTs from [Duplicacy Web Edition](https://duplicacy.com) and sends formatted backup reports to your [Pushover](https://pushover.net) account.

Ideal for homelab setups, it gives you instant mobile notifications after each backup — including job status, host info, duration, and data uploaded.

___

## 📦 Features

- Parses Duplicacy Web JSON webhook payloads
- Formats results with emoji and clarity
- Sends report via Pushover (mobile push)
- Displays backup folder, host, duration, result, and uploaded size
- Logs last received payload for debugging

___

## 🚀 Setup Instructions

### 1. Clone or Copy

Place the `duplicacy-report.php` file into a PHP-enabled web server (Apache, Nginx with PHP, Caddy + PHP, etc).

Example path:
```
/var/www/html/dup2push/duplicacy-report.php
```

---

### 2. Configure Pushover

1. Create a free account at [pushover.net](https://pushover.net)
2. Register a new application to get your **API token**
3. Get your **User Key** from your dashboard

Edit the top of `duplicacy-report.php`:

```php
$userKey = 'YOUR_PUSHOVER_USER_KEY';
$appToken = 'YOUR_PUSHOVER_APP_TOKEN';
```

---

### 3. Set Permissions

Ensure the folder is writable so logs can be created:

```bash
chown -R www-data:www-data /var/www/html/dup2push
chmod -R 755 /var/www/html/dup2push
```

---

### 4. Set Webhook URL in Duplicacy

In **Duplicacy Web Edition**:

1. Go to `Backup → Options`
2. Check **"Send backup report after completion"**
3. Set the **URL** to:

```
https://yourdomain.com/dup2push/duplicacy-report.php
```

✅ You may check "Send report on failure" to get failed notifications too.

---

### 5. Test with Curl (Optional)

```bash
curl -X POST https://yourdomain.com/dup2push/duplicacy-report.php \
  -H "Content-Type: application/json" \
  -d '{"computer":"Test-PC","directory":"/home/user","result":"Success","start_time":1752937103,"end_time":1752937109,"uploaded_chunk_size":0,"uploaded_file_chunk_size":10485760,"uploaded_metadata_chunk_size":4096}'
```

---

## 📝 Files Created

| File                  | Description                     |
|-----------------------|----------------------------------|
| `duplicacy_last.json` | Raw JSON payload from Duplicacy |
| `dup-debug.txt`       | Parsed debug dump               |

___

## 🛠️ Notes

- `duration` is calculated from `start_time` and `end_time`.
- Upload size is calculated by summing all three upload chunk types.

___

## 🔐 Security

- Do **not** expose this script publicly without restricting access or authentication.
- Consider limiting requests by IP or adding a shared secret token if needed.

___

## 📲 Example Notification

```
🛡️ Duplicacy Backup Report
📂 Folder: /home/user
🖥️ Host: Test-PC
📅 Result: Success
⏱ Duration: 00:00:06
💾 Uploaded: 10.00 MB
```

___

## 📜 License

MIT – free to use and modify. Not affiliated with Pushover or Duplicacy.

___

## 🛠 Made By

[ChilSoft.com](https://chilsoft.com) with caffeine and questionable commits.

___

## ⚠️ Disclaimer

This site and its contents are provided for informational and educational purposes only.

Use any code, tools, or instructions at your own risk.  
We are **not responsible** for any damage to your device, data loss, or unintended consequences.

Always proceed with care -- and make backups.

© **2025 ChilSoft**. All rights reserved.

