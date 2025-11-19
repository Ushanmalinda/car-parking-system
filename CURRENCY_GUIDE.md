# 💰 Currency Management Guide

## Overview
The Car Parking System now supports **90+ world currencies** with automatic detection based on your location and manual selection options.

## Features

### ✨ Key Features
- **90+ Currencies**: All major world currencies supported
- **Auto-Detection**: Automatically detect currency based on your IP location
- **Manual Selection**: Choose any currency manually from dropdown
- **Regional Grouping**: Currencies organized by region (Americas, Europe, Asia, Middle East, Africa, Oceania)
- **Live Preview**: See currency symbol and details before saving
- **Timezone Support**: Configure timezone along with currency

## How to Use

### 1. Access Settings
1. Login as Admin
2. Go to **Settings** from the sidebar
3. Find the **Currency Settings** section

### 2. Auto-Detect Currency (Recommended)
1. Click the **🌍 Auto-Detect** button
2. System will detect your location and suggest appropriate currency
3. Confirm the detection and save

### 3. Manual Currency Selection
1. Use the dropdown menu to browse all currencies
2. Currencies are grouped by region for easy navigation
3. Each option shows:
   - Currency Code (e.g., USD, EUR, INR)
   - Currency Name (e.g., US Dollar)
   - Currency Symbol (e.g., $, €, ₹)

### 4. Save Changes
1. After selecting currency, click **💾 Save Settings**
2. Currency will be applied system-wide immediately

## Supported Currencies by Region

### Americas (9 Currencies)
- **USD** - US Dollar ($) - United States
- **CAD** - Canadian Dollar (C$) - Canada
- **MXN** - Mexican Peso ($) - Mexico
- **BRL** - Brazilian Real (R$) - Brazil
- **ARS** - Argentine Peso ($) - Argentina
- **CLP** - Chilean Peso ($) - Chile
- **COP** - Colombian Peso ($) - Colombia
- **PEN** - Peruvian Sol (S/) - Peru
- **UYU** - Uruguayan Peso ($U) - Uruguay

### Europe (14 Currencies)
- **EUR** - Euro (€) - Eurozone
- **GBP** - British Pound (£) - United Kingdom
- **CHF** - Swiss Franc (Fr) - Switzerland
- **NOK** - Norwegian Krone (kr) - Norway
- **SEK** - Swedish Krona (kr) - Sweden
- **DKK** - Danish Krone (kr) - Denmark
- **PLN** - Polish Zloty (zł) - Poland
- **CZK** - Czech Koruna (Kč) - Czech Republic
- **HUF** - Hungarian Forint (Ft) - Hungary
- **RON** - Romanian Leu (lei) - Romania
- **BGN** - Bulgarian Lev (лв) - Bulgaria
- **HRK** - Croatian Kuna (kn) - Croatia
- **RUB** - Russian Ruble (₽) - Russia
- **UAH** - Ukrainian Hryvnia (₴) - Ukraine
- **TRY** - Turkish Lira (₺) - Turkey

### Asia (20 Currencies)
- **INR** - Indian Rupee (₹) - India
- **CNY** - Chinese Yuan (¥) - China
- **JPY** - Japanese Yen (¥) - Japan
- **KRW** - South Korean Won (₩) - South Korea
- **SGD** - Singapore Dollar (S$) - Singapore
- **HKD** - Hong Kong Dollar (HK$) - Hong Kong
- **TWD** - Taiwan Dollar (NT$) - Taiwan
- **MYR** - Malaysian Ringgit (RM) - Malaysia
- **THB** - Thai Baht (฿) - Thailand
- **IDR** - Indonesian Rupiah (Rp) - Indonesia
- **PHP** - Philippine Peso (₱) - Philippines
- **VND** - Vietnamese Dong (₫) - Vietnam
- **PKR** - Pakistani Rupee (₨) - Pakistan
- **BDT** - Bangladeshi Taka (৳) - Bangladesh
- **LKR** - Sri Lankan Rupee (Rs) - Sri Lanka
- **NPR** - Nepalese Rupee (Rs) - Nepal
- **MMK** - Myanmar Kyat (K) - Myanmar
- **KHR** - Cambodian Riel (៛) - Cambodia
- **LAK** - Lao Kip (₭) - Laos
- **BND** - Brunei Dollar (B$) - Brunei

### Middle East (12 Currencies)
- **AED** - UAE Dirham (د.إ) - UAE
- **SAR** - Saudi Riyal (﷼) - Saudi Arabia
- **QAR** - Qatari Riyal (﷼) - Qatar
- **KWD** - Kuwaiti Dinar (د.ك) - Kuwait
- **BHD** - Bahraini Dinar (د.ب) - Bahrain
- **OMR** - Omani Rial (﷼) - Oman
- **JOD** - Jordanian Dinar (د.ا) - Jordan
- **ILS** - Israeli Shekel (₪) - Israel
- **IQD** - Iraqi Dinar (د.ع) - Iraq
- **IRR** - Iranian Rial (﷼) - Iran
- **LBP** - Lebanese Pound (ل.ل) - Lebanon
- **SYP** - Syrian Pound (£S) - Syria

### Africa (12 Currencies)
- **ZAR** - South African Rand (R) - South Africa
- **NGN** - Nigerian Naira (₦) - Nigeria
- **EGP** - Egyptian Pound (£) - Egypt
- **KES** - Kenyan Shilling (KSh) - Kenya
- **GHS** - Ghanaian Cedi (₵) - Ghana
- **TZS** - Tanzanian Shilling (TSh) - Tanzania
- **UGX** - Ugandan Shilling (USh) - Uganda
- **MAD** - Moroccan Dirham (د.م.) - Morocco
- **TND** - Tunisian Dinar (د.ت) - Tunisia
- **DZD** - Algerian Dinar (د.ج) - Algeria
- **ETB** - Ethiopian Birr (Br) - Ethiopia
- **ZMW** - Zambian Kwacha (ZK) - Zambia

### Oceania (4 Currencies)
- **AUD** - Australian Dollar (A$) - Australia
- **NZD** - New Zealand Dollar (NZ$) - New Zealand
- **FJD** - Fijian Dollar (FJ$) - Fiji
- **PGK** - Papua New Guinea Kina (K) - Papua New Guinea

## Technical Details

### Currency Detection
The system uses IP geolocation to automatically detect your currency:
- Uses **ip-api.com** service (free, no API key required)
- Falls back to USD if detection fails
- Works for all public IP addresses
- Localhost defaults to USD

### Database Storage
Currency settings are stored in the `system_settings` table:
```sql
setting_key: 'currency'
setting_value: 'USD' (or any ISO currency code)
```

### Currency Formatting
The system automatically formats amounts based on selected currency:
- Proper decimal places
- Correct currency symbol placement
- Regional number formatting

### PHP Functions

#### Get All Currencies
```php
$currencies = getAllCurrencies();
// Returns array of all 90+ currencies
```

#### Get Currency by Code
```php
$currency = getCurrencyByCode('USD');
// Returns: ['name' => 'US Dollar', 'symbol' => '$', 'region' => 'Americas', ...]
```

#### Get Currencies by Region
```php
$asiaCurrencies = getCurrenciesByRegion('Asia');
// Returns all Asian currencies
```

#### Auto-Detect Currency
```php
$detected = detectCurrencyFromIP();
// Returns: 'USD', 'EUR', 'INR', etc.
```

#### Format Currency
```php
$formatted = formatCurrencyValue(100.50, 'USD');
// Returns: "$ 100.50"

$formatted = formatCurrencyValue(100.50, 'EUR');
// Returns: "€ 100.50"
```

### JavaScript Integration
The currency code is automatically passed to JavaScript:
```javascript
// Get system currency
const currency = document.body.getAttribute('data-currency');

// Format amounts
const formatted = formatCurrency(100.50, currency);
```

## Best Practices

### 1. Set Currency During Initial Setup
- Configure currency when first setting up the system
- Choose currency that matches your business location
- Consider your primary customer base

### 2. Use Auto-Detection for Accuracy
- Auto-detection is more accurate than manual guessing
- Works based on your actual server/IP location
- Good for multi-location businesses

### 3. Update Parking Rates After Changing Currency
- Remember to adjust parking rates when changing currency
- Different currencies have different values
- Example: $10 USD ≠ ₹10 INR ≠ €10 EUR

### 4. Test After Currency Change
- Create a test booking after changing currency
- Verify amounts display correctly
- Check reports and invoices

## Troubleshooting

### Currency Not Displaying Correctly?
1. Clear browser cache
2. Reload the page
3. Check Settings > Currency Settings
4. Verify currency code is valid

### Auto-Detection Not Working?
1. Make sure you're not on localhost
2. Check internet connection
3. Try manual selection instead
4. Contact support if issue persists

### Symbol Showing Incorrectly?
- Some currency symbols require UTF-8 encoding
- Make sure your database uses UTF-8 charset
- Browser should support Unicode characters

## Examples

### Example 1: Setting USD for US Business
1. Go to Settings
2. Select **USD - US Dollar ($)**
3. Set timezone to **America/New_York**
4. Save settings
5. Set parking rates (e.g., $5.00 for two-wheelers)

### Example 2: Setting INR for Indian Business
1. Go to Settings
2. Select **INR - Indian Rupee (₹)**
3. Set timezone to **Asia/Kolkata**
4. Save settings
5. Set parking rates (e.g., ₹20.00 for two-wheelers)

### Example 3: Setting EUR for European Business
1. Go to Settings
2. Select **EUR - Euro (€)**
3. Set timezone to **Europe/Paris**
4. Save settings
5. Set parking rates (e.g., €8.00 for two-wheelers)

## Support

Need help with currency settings?
1. Check this guide first
2. Review the system documentation
3. Test with different currencies
4. Contact system administrator

---

**Last Updated:** November 2025  
**Version:** 1.0  
**Supported Currencies:** 90+
