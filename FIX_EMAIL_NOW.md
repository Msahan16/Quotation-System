# 🚨 URGENT: Gmail App Password Required!

## Why Email is Not Working:

Gmail is blocking your login because you're using your **regular password** instead of an **App Password**.

Error from logs:
```
"Application-specific password required"
```

---

## ✅ SOLUTION - Follow These Steps:

### Step 1: Enable 2-Step Verification

1. Go to: https://myaccount.google.com/security
2. Find "2-Step Verification"
3. Click and follow the setup (if not already enabled)
4. This is REQUIRED before you can create App Passwords

### Step 2: Generate App Password

1. After 2-Step is enabled, visit: https://myaccount.google.com/apppasswords
   
   OR
   
   - Go to https://myaccount.google.com/security
   - Search for "App passwords" in the search box
   - Click on "App passwords"

2. You'll see a page to generate app passwords:
   - Select app: **Mail**
   - Select device: **Windows Computer** (or Other)
   - Click **Generate**

3. Google will show you a **16-character password** like:
   ```
   abcd efgh ijkl mnop
   ```
   
4. **COPY THIS PASSWORD** (you won't see it again!)

### Step 3: Update .env File

1. Open your `.env` file (it's already open in your editor)

2. Find line 52:
   ```
   MAIL_PASSWORD=paste-your-16-character-app-password-here
   ```

3. Replace `paste-your-16-character-app-password-here` with the password you copied

4. **Remove all spaces** from the password. Example:
   ```
   MAIL_PASSWORD=abcdefghijklmnop
   ```

5. Save the file

### Step 4: Clear Cache and Test

Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
```

Then create a quotation and test!

---

## 📋 Quick Checklist:

- [ ] 2-Step Verification enabled on Gmail
- [ ] App Password generated from Google Account
- [ ] App Password copied (16 characters, no spaces)
- [ ] `.env` file updated with App Password
- [ ] `.env` file saved
- [ ] `php artisan config:clear` command run
- [ ] Test by creating a quotation

---

## 🎯 Current .env Settings (Should Look Like This):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=mohammedshn2002@gmail.com
MAIL_PASSWORD=abcdefghijklmnop  ← Your 16-char app password here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="mohammedshn2002@gmail.com"
MAIL_FROM_NAME="AKM Aluminium Fabrication"
```

---

## ⚠️ Common Mistakes to Avoid:

❌ Using your regular Gmail password  
❌ Including spaces in the app password  
❌ Not enabling 2-Step Verification first  
❌ Forgetting to run `php artisan config:clear`  

✅ Use the 16-character App Password  
✅ Remove all spaces from the password  
✅ Enable 2-Step Verification first  
✅ Clear cache after updating .env  

---

## 🔗 Direct Links:

- **Google Account Security:** https://myaccount.google.com/security
- **App Passwords:** https://myaccount.google.com/apppasswords
- **2-Step Verification:** https://myaccount.google.com/signinoptions/two-step-verification

---

## 📞 Still Not Working?

If you still have issues after following these steps:

1. Check `storage/logs/laravel.log` for new errors
2. Make sure you saved the `.env` file
3. Make sure you ran `php artisan config:clear`
4. Try generating a new App Password
5. Make sure your internet connection is working

---

**Once you complete these steps, emails will be sent automatically when you create or share quotations!**
