# Email Configuration Guide for Quotation System

## Overview
The quotation system has been configured to automatically send email notifications to **mohammedshn2002@gmail.com** whenever a quotation is generated or shared via WhatsApp.

## What's Been Implemented

### 1. Email Functionality
- **QuotationMail** class created (`app/Mail/QuotationMail.php`)
- Email template created (`resources/views/emails/quotation.blade.php`)
- Both "Generate Quotation" and "Share via WhatsApp" buttons now trigger email notifications
- PDF quotation is automatically attached to the email

### 2. Email Content
Each email includes:
- Quotation number and date
- Customer information (name and phone)
- Complete list of items with details (size, color, louver, quantity, prices)
- Summary with subtotal, charges, and grand total
- Additional notes (if any)
- Terms and conditions
- PDF attachment of the quotation

## Setup Instructions

### Option 1: Using Gmail (Recommended)

1. **Open the `.env` file** in the root directory of your project

2. **Update the following email settings:**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail-address@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-gmail-address@gmail.com"
MAIL_FROM_NAME="AKM Aluminium Fabrication"
```

3. **Generate Gmail App Password:**
   - Go to your Google Account: https://myaccount.google.com/
   - Navigate to Security
   - Enable 2-Step Verification (if not already enabled)
   - Go to "App passwords" (search for it in the security settings)
   - Select "Mail" and "Windows Computer" (or Other)
   - Click "Generate"
   - Copy the 16-character password
   - Use this password in `MAIL_PASSWORD` (without spaces)

4. **Replace placeholders:**
   - Replace `your-gmail-address@gmail.com` with your actual Gmail address
   - Replace `your-app-specific-password` with the generated app password

### Option 2: Using Other Email Services

#### For Outlook/Hotmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@outlook.com"
MAIL_FROM_NAME="AKM Aluminium Fabrication"
```

#### For Yahoo:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@yahoo.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@yahoo.com"
MAIL_FROM_NAME="AKM Aluminium Fabrication"
```

## Testing the Email Functionality

1. **Clear the configuration cache:**
   ```bash
   php artisan config:clear
   ```

2. **Test by creating a quotation:**
   - Go to the quotation builder
   - Add customer details and items
   - Click either "Generate Quotation" or "Share via WhatsApp"
   - Check the email inbox at mohammedshn2002@gmail.com

3. **Check for errors:**
   - If email fails, check `storage/logs/laravel.log` for error messages
   - Common issues:
     - Incorrect credentials
     - App password not generated
     - Firewall blocking SMTP port 587

## Changing the Recipient Email

To change the email address that receives quotations:

1. Open `app/Livewire/QuotationBuilder.php`
2. Find these two lines (around line 146 and 221):
   ```php
   Mail::to('mohammedshn2002@gmail.com')->send(new QuotationMail($quotation));
   ```
3. Replace `mohammedshn2002@gmail.com` with your desired email address

## Sending to Multiple Recipients

To send to multiple email addresses:

```php
Mail::to('mohammedshn2002@gmail.com')
    ->cc('another-email@example.com')
    ->bcc('third-email@example.com')
    ->send(new QuotationMail($quotation));
```

Or send to multiple primary recipients:

```php
Mail::to([
    'mohammedshn2002@gmail.com',
    'another-email@example.com'
])->send(new QuotationMail($quotation));
```

## Troubleshooting

### Email not sending?

1. **Check `.env` configuration:**
   - Ensure all MAIL_* variables are set correctly
   - No extra spaces or quotes (except for FROM_NAME)

2. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Check logs:**
   - Look in `storage/logs/laravel.log` for error messages

4. **Test SMTP connection:**
   ```bash
   php artisan tinker
   Mail::raw('Test email', function($msg) {
       $msg->to('mohammedshn2002@gmail.com')->subject('Test');
   });
   ```

### Common Errors:

- **"Connection refused"**: Check firewall or use port 465 with SSL
- **"Authentication failed"**: Verify username/password, use app password for Gmail
- **"Could not instantiate mail function"**: PHP mail() not configured, use SMTP instead

## Features

✅ Automatic email on quotation generation  
✅ Automatic email on WhatsApp share  
✅ PDF attachment included  
✅ Professional email template  
✅ Complete quotation details in email body  
✅ Error handling (won't break if email fails)  
✅ Logged errors for debugging  

## Notes

- Emails are sent asynchronously to avoid slowing down the quotation process
- If email sending fails, the quotation is still created successfully
- All email errors are logged in `storage/logs/laravel.log`
- The system uses Laravel's built-in Mail facade for reliability

---

**Need Help?**
If you encounter any issues, check the Laravel logs or contact your developer.
