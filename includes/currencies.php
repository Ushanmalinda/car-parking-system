<?php
// World Currencies Database
// ISO 4217 Currency Codes with Symbols and Regional Information

function getAllCurrencies() {
    return [
        // Americas
        'USD' => ['name' => 'US Dollar', 'symbol' => '$', 'region' => 'Americas', 'countries' => 'United States'],
        'CAD' => ['name' => 'Canadian Dollar', 'symbol' => 'C$', 'region' => 'Americas', 'countries' => 'Canada'],
        'MXN' => ['name' => 'Mexican Peso', 'symbol' => '$', 'region' => 'Americas', 'countries' => 'Mexico'],
        'BRL' => ['name' => 'Brazilian Real', 'symbol' => 'R$', 'region' => 'Americas', 'countries' => 'Brazil'],
        'ARS' => ['name' => 'Argentine Peso', 'symbol' => '$', 'region' => 'Americas', 'countries' => 'Argentina'],
        'CLP' => ['name' => 'Chilean Peso', 'symbol' => '$', 'region' => 'Americas', 'countries' => 'Chile'],
        'COP' => ['name' => 'Colombian Peso', 'symbol' => '$', 'region' => 'Americas', 'countries' => 'Colombia'],
        'PEN' => ['name' => 'Peruvian Sol', 'symbol' => 'S/', 'region' => 'Americas', 'countries' => 'Peru'],
        'UYU' => ['name' => 'Uruguayan Peso', 'symbol' => '$U', 'region' => 'Americas', 'countries' => 'Uruguay'],
        
        // Europe
        'EUR' => ['name' => 'Euro', 'symbol' => '€', 'region' => 'Europe', 'countries' => 'Eurozone'],
        'GBP' => ['name' => 'British Pound', 'symbol' => '£', 'region' => 'Europe', 'countries' => 'United Kingdom'],
        'CHF' => ['name' => 'Swiss Franc', 'symbol' => 'Fr', 'region' => 'Europe', 'countries' => 'Switzerland'],
        'NOK' => ['name' => 'Norwegian Krone', 'symbol' => 'kr', 'region' => 'Europe', 'countries' => 'Norway'],
        'SEK' => ['name' => 'Swedish Krona', 'symbol' => 'kr', 'region' => 'Europe', 'countries' => 'Sweden'],
        'DKK' => ['name' => 'Danish Krone', 'symbol' => 'kr', 'region' => 'Europe', 'countries' => 'Denmark'],
        'PLN' => ['name' => 'Polish Zloty', 'symbol' => 'zł', 'region' => 'Europe', 'countries' => 'Poland'],
        'CZK' => ['name' => 'Czech Koruna', 'symbol' => 'Kč', 'region' => 'Europe', 'countries' => 'Czech Republic'],
        'HUF' => ['name' => 'Hungarian Forint', 'symbol' => 'Ft', 'region' => 'Europe', 'countries' => 'Hungary'],
        'RON' => ['name' => 'Romanian Leu', 'symbol' => 'lei', 'region' => 'Europe', 'countries' => 'Romania'],
        'BGN' => ['name' => 'Bulgarian Lev', 'symbol' => 'лв', 'region' => 'Europe', 'countries' => 'Bulgaria'],
        'HRK' => ['name' => 'Croatian Kuna', 'symbol' => 'kn', 'region' => 'Europe', 'countries' => 'Croatia'],
        'RUB' => ['name' => 'Russian Ruble', 'symbol' => '₽', 'region' => 'Europe', 'countries' => 'Russia'],
        'UAH' => ['name' => 'Ukrainian Hryvnia', 'symbol' => '₴', 'region' => 'Europe', 'countries' => 'Ukraine'],
        'TRY' => ['name' => 'Turkish Lira', 'symbol' => '₺', 'region' => 'Europe', 'countries' => 'Turkey'],
        
        // Asia
        'INR' => ['name' => 'Indian Rupee', 'symbol' => '₹', 'region' => 'Asia', 'countries' => 'India'],
        'CNY' => ['name' => 'Chinese Yuan', 'symbol' => '¥', 'region' => 'Asia', 'countries' => 'China'],
        'JPY' => ['name' => 'Japanese Yen', 'symbol' => '¥', 'region' => 'Asia', 'countries' => 'Japan'],
        'KRW' => ['name' => 'South Korean Won', 'symbol' => '₩', 'region' => 'Asia', 'countries' => 'South Korea'],
        'SGD' => ['name' => 'Singapore Dollar', 'symbol' => 'S$', 'region' => 'Asia', 'countries' => 'Singapore'],
        'HKD' => ['name' => 'Hong Kong Dollar', 'symbol' => 'HK$', 'region' => 'Asia', 'countries' => 'Hong Kong'],
        'TWD' => ['name' => 'Taiwan Dollar', 'symbol' => 'NT$', 'region' => 'Asia', 'countries' => 'Taiwan'],
        'MYR' => ['name' => 'Malaysian Ringgit', 'symbol' => 'RM', 'region' => 'Asia', 'countries' => 'Malaysia'],
        'THB' => ['name' => 'Thai Baht', 'symbol' => '฿', 'region' => 'Asia', 'countries' => 'Thailand'],
        'IDR' => ['name' => 'Indonesian Rupiah', 'symbol' => 'Rp', 'region' => 'Asia', 'countries' => 'Indonesia'],
        'PHP' => ['name' => 'Philippine Peso', 'symbol' => '₱', 'region' => 'Asia', 'countries' => 'Philippines'],
        'VND' => ['name' => 'Vietnamese Dong', 'symbol' => '₫', 'region' => 'Asia', 'countries' => 'Vietnam'],
        'PKR' => ['name' => 'Pakistani Rupee', 'symbol' => '₨', 'region' => 'Asia', 'countries' => 'Pakistan'],
        'BDT' => ['name' => 'Bangladeshi Taka', 'symbol' => '৳', 'region' => 'Asia', 'countries' => 'Bangladesh'],
        'LKR' => ['name' => 'Sri Lankan Rupee', 'symbol' => 'Rs', 'region' => 'Asia', 'countries' => 'Sri Lanka'],
        'NPR' => ['name' => 'Nepalese Rupee', 'symbol' => 'Rs', 'region' => 'Asia', 'countries' => 'Nepal'],
        'MMK' => ['name' => 'Myanmar Kyat', 'symbol' => 'K', 'region' => 'Asia', 'countries' => 'Myanmar'],
        'KHR' => ['name' => 'Cambodian Riel', 'symbol' => '៛', 'region' => 'Asia', 'countries' => 'Cambodia'],
        'LAK' => ['name' => 'Lao Kip', 'symbol' => '₭', 'region' => 'Asia', 'countries' => 'Laos'],
        'BND' => ['name' => 'Brunei Dollar', 'symbol' => 'B$', 'region' => 'Asia', 'countries' => 'Brunei'],
        
        // Middle East
        'AED' => ['name' => 'UAE Dirham', 'symbol' => 'د.إ', 'region' => 'Middle East', 'countries' => 'UAE'],
        'SAR' => ['name' => 'Saudi Riyal', 'symbol' => '﷼', 'region' => 'Middle East', 'countries' => 'Saudi Arabia'],
        'QAR' => ['name' => 'Qatari Riyal', 'symbol' => '﷼', 'region' => 'Middle East', 'countries' => 'Qatar'],
        'KWD' => ['name' => 'Kuwaiti Dinar', 'symbol' => 'د.ك', 'region' => 'Middle East', 'countries' => 'Kuwait'],
        'BHD' => ['name' => 'Bahraini Dinar', 'symbol' => 'د.ب', 'region' => 'Middle East', 'countries' => 'Bahrain'],
        'OMR' => ['name' => 'Omani Rial', 'symbol' => '﷼', 'region' => 'Middle East', 'countries' => 'Oman'],
        'JOD' => ['name' => 'Jordanian Dinar', 'symbol' => 'د.ا', 'region' => 'Middle East', 'countries' => 'Jordan'],
        'ILS' => ['name' => 'Israeli Shekel', 'symbol' => '₪', 'region' => 'Middle East', 'countries' => 'Israel'],
        'IQD' => ['name' => 'Iraqi Dinar', 'symbol' => 'د.ع', 'region' => 'Middle East', 'countries' => 'Iraq'],
        'IRR' => ['name' => 'Iranian Rial', 'symbol' => '﷼', 'region' => 'Middle East', 'countries' => 'Iran'],
        'LBP' => ['name' => 'Lebanese Pound', 'symbol' => 'ل.ل', 'region' => 'Middle East', 'countries' => 'Lebanon'],
        'SYP' => ['name' => 'Syrian Pound', 'symbol' => '£S', 'region' => 'Middle East', 'countries' => 'Syria'],
        
        // Africa
        'ZAR' => ['name' => 'South African Rand', 'symbol' => 'R', 'region' => 'Africa', 'countries' => 'South Africa'],
        'NGN' => ['name' => 'Nigerian Naira', 'symbol' => '₦', 'region' => 'Africa', 'countries' => 'Nigeria'],
        'EGP' => ['name' => 'Egyptian Pound', 'symbol' => '£', 'region' => 'Africa', 'countries' => 'Egypt'],
        'KES' => ['name' => 'Kenyan Shilling', 'symbol' => 'KSh', 'region' => 'Africa', 'countries' => 'Kenya'],
        'GHS' => ['name' => 'Ghanaian Cedi', 'symbol' => '₵', 'region' => 'Africa', 'countries' => 'Ghana'],
        'TZS' => ['name' => 'Tanzanian Shilling', 'symbol' => 'TSh', 'region' => 'Africa', 'countries' => 'Tanzania'],
        'UGX' => ['name' => 'Ugandan Shilling', 'symbol' => 'USh', 'region' => 'Africa', 'countries' => 'Uganda'],
        'MAD' => ['name' => 'Moroccan Dirham', 'symbol' => 'د.م.', 'region' => 'Africa', 'countries' => 'Morocco'],
        'TND' => ['name' => 'Tunisian Dinar', 'symbol' => 'د.ت', 'region' => 'Africa', 'countries' => 'Tunisia'],
        'DZD' => ['name' => 'Algerian Dinar', 'symbol' => 'د.ج', 'region' => 'Africa', 'countries' => 'Algeria'],
        'ETB' => ['name' => 'Ethiopian Birr', 'symbol' => 'Br', 'region' => 'Africa', 'countries' => 'Ethiopia'],
        'ZMW' => ['name' => 'Zambian Kwacha', 'symbol' => 'ZK', 'region' => 'Africa', 'countries' => 'Zambia'],
        
        // Oceania
        'AUD' => ['name' => 'Australian Dollar', 'symbol' => 'A$', 'region' => 'Oceania', 'countries' => 'Australia'],
        'NZD' => ['name' => 'New Zealand Dollar', 'symbol' => 'NZ$', 'region' => 'Oceania', 'countries' => 'New Zealand'],
        'FJD' => ['name' => 'Fijian Dollar', 'symbol' => 'FJ$', 'region' => 'Oceania', 'countries' => 'Fiji'],
        'PGK' => ['name' => 'Papua New Guinea Kina', 'symbol' => 'K', 'region' => 'Oceania', 'countries' => 'Papua New Guinea'],
    ];
}

function getCurrencyByCode($code) {
    $currencies = getAllCurrencies();
    return $currencies[$code] ?? null;
}

function getCurrenciesByRegion($region) {
    $currencies = getAllCurrencies();
    return array_filter($currencies, function($currency) use ($region) {
        return $currency['region'] === $region;
    });
}

function getAllRegions() {
    return ['Americas', 'Europe', 'Asia', 'Middle East', 'Africa', 'Oceania'];
}

function detectCurrencyFromIP($ip = null) {
    if ($ip === null) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    // Skip local IPs
    if ($ip === '127.0.0.1' || $ip === '::1') {
        return 'USD'; // Default for localhost
    }
    
    try {
        // Using ip-api.com for geolocation (free, no API key needed)
        $url = "http://ip-api.com/json/{$ip}?fields=status,countryCode,currency";
        $context = stream_context_create([
            'http' => [
                'timeout' => 3,
                'ignore_errors' => true
            ]
        ]);
        
        $response = @file_get_contents($url, false, $context);
        
        if ($response) {
            $data = json_decode($response, true);
            if ($data && $data['status'] === 'success' && isset($data['currency'])) {
                return $data['currency'];
            }
        }
    } catch (Exception $e) {
        // Silently fail and return default
    }
    
    return 'USD'; // Default fallback
}

function formatCurrencyValue($amount, $currencyCode = 'USD', $decimals = 2) {
    $currency = getCurrencyByCode($currencyCode);
    if (!$currency) {
        return number_format($amount, $decimals);
    }
    
    $symbol = $currency['symbol'];
    $formatted = number_format($amount, $decimals);
    
    // Some currencies put symbol after amount
    $symbolAfter = ['SEK', 'NOK', 'DKK', 'CZK', 'HUF', 'PLN', 'RON', 'BGN', 'HRK', 'RUB', 'UAH'];
    
    if (in_array($currencyCode, $symbolAfter)) {
        return $formatted . ' ' . $symbol;
    }
    
    return $symbol . ' ' . $formatted;
}

function getCurrencySymbol($currencyCode) {
    $currency = getCurrencyByCode($currencyCode);
    return $currency ? $currency['symbol'] : '$';
}
?>
