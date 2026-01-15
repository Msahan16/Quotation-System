# Quick Setup - Email Configuration

## IMPORTANT: You need to configure email settings before emails will work!

### Quick Steps:

1. **Open `.env` file** (in the root directory)

2. **Find these lines** (around line 47-55):
   ```
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.gmail.com
   MAIL_PORT=587
   MAIL_USERNAME=your-email@gmail.com
   MAIL_PASSWORD=your-app-password
   ```

3. **Replace with your Gmail credentials:**
   - `MAIL_USERNAME`: Your Gmail address
   - `MAIL_PASSWORD`: Your Gmail App Password (NOT your regular password!)

4. **Generate Gmail App Password:**
   - Visit: https://myaccount.google.com/apppasswords
   - Enable 2-Step Verification first if needed
   - Create new app password for "Mail"
   - Copy the 16-character password (no spaces)
   - Paste it in `MAIL_PASSWORD`

5. **Run this command:**
   ```bash
   php artisan config:clear
   ```

6. **Test it:**
   - Create a quotation
   - Click "Generate" or "Share" button
   - Check mohammedshn2002@gmail.com inbox

### Current Settings:
- ✅ Email recipient: mohammedshn2002@gmail.com
- ✅ Triggers: Both "Generate" and "Share" buttons
- ✅ Includes: Full quotation details + PDF attachment
- ✅ Error handling: Won't break if email fails

### Need to change recipient email?
Edit line 146 and 221 in `app/Livewire/QuotationBuilder.php`

---

**See EMAIL_SETUP_GUIDE.md for detailed instructions and troubleshooting**
