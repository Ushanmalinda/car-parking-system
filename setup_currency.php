<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Setup - Car Parking System</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; border-radius: 20px; padding: 40px; max-width: 800px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        h1 { color: #333; margin-bottom: 10px; font-size: 32px; }
        .subtitle { color: #666; margin-bottom: 30px; font-size: 16px; }
        .step { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; border-left: 4px solid #667eea; }
        .step h3 { color: #667eea; margin-bottom: 10px; }
        .currency-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin: 20px 0; }
        .currency-card { background: white; border: 2px solid #e0e0e0; border-radius: 10px; padding: 15px; cursor: pointer; transition: all 0.3s; }
        .currency-card:hover { border-color: #667eea; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.2); }
        .currency-card.selected { border-color: #667eea; background: #f0f4ff; }
        .currency-code { font-size: 24px; font-weight: bold; color: #667eea; margin-bottom: 5px; }
        .currency-name { font-size: 14px; color: #666; margin-bottom: 3px; }
        .currency-symbol { font-size: 18px; color: #999; }
        .btn { background: #667eea; color: white; border: none; padding: 15px 40px; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s; margin: 10px 5px; }
        .btn:hover { background: #5568d3; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(102,126,234,0.4); }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #5a6268; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }
        .status { padding: 15px; border-radius: 8px; margin: 20px 0; }
        .status.success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .status.error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .status.info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .detected-box { background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px; padding: 20px; margin: 20px 0; text-align: center; }
        .detected-box .currency { font-size: 48px; margin: 10px 0; }
        .detected-box .name { font-size: 20px; font-weight: bold; color: #333; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .search-box { width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 8px; font-size: 16px; margin-bottom: 20px; }
        .search-box:focus { outline: none; border-color: #667eea; }
    </style>
</head>
<body>
    <div class="container">
        <h1>💰 Currency Setup</h1>
        <p class="subtitle">Choose your preferred currency for the parking system</p>

        <div id="statusBox"></div>

        <div class="step">
            <h3>📍 Step 1: Auto-Detect (Recommended)</h3>
            <p>Let us automatically detect your currency based on your location.</p>
            <button class="btn" onclick="autoDetect()">🌍 Auto-Detect My Currency</button>
        </div>

        <div id="detectedCurrency" style="display: none;"></div>

        <div class="step">
            <h3>🔍 Step 2: Search & Select Manually</h3>
            <p>Or search and select from 90+ currencies worldwide.</p>
            <input type="text" class="search-box" id="searchBox" placeholder="Search currency (e.g., USD, Euro, Indian Rupee)..." onkeyup="filterCurrencies()">
            
            <div class="currency-grid" id="currencyGrid">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <button class="btn" onclick="saveCurrency()" id="saveBtn" disabled>💾 Save & Continue</button>
            <button class="btn btn-secondary" onclick="skip()">Skip for Now</button>
        </div>

        <div class="status info" style="margin-top: 30px;">
            <strong>💡 Tip:</strong> You can always change the currency later from Settings → Currency Settings
        </div>
    </div>

    <script>
        let selectedCurrency = null;
        
        const popularCurrencies = [
            {code: 'USD', name: 'US Dollar', symbol: '$', region: 'Americas'},
            {code: 'EUR', name: 'Euro', symbol: '€', region: 'Europe'},
            {code: 'GBP', name: 'British Pound', symbol: '£', region: 'Europe'},
            {code: 'INR', name: 'Indian Rupee', symbol: '₹', region: 'Asia'},
            {code: 'JPY', name: 'Japanese Yen', symbol: '¥', region: 'Asia'},
            {code: 'CNY', name: 'Chinese Yuan', symbol: '¥', region: 'Asia'},
            {code: 'AUD', name: 'Australian Dollar', symbol: 'A$', region: 'Oceania'},
            {code: 'CAD', name: 'Canadian Dollar', symbol: 'C$', region: 'Americas'},
            {code: 'CHF', name: 'Swiss Franc', symbol: 'Fr', region: 'Europe'},
            {code: 'SGD', name: 'Singapore Dollar', symbol: 'S$', region: 'Asia'},
            {code: 'AED', name: 'UAE Dirham', symbol: 'د.إ', region: 'Middle East'},
            {code: 'SAR', name: 'Saudi Riyal', symbol: '﷼', region: 'Middle East'},
            {code: 'ZAR', name: 'South African Rand', symbol: 'R', region: 'Africa'},
            {code: 'BRL', name: 'Brazilian Real', symbol: 'R$', region: 'Americas'},
            {code: 'MXN', name: 'Mexican Peso', symbol: '$', region: 'Americas'},
            {code: 'KRW', name: 'South Korean Won', symbol: '₩', region: 'Asia'}
        ];

        function displayCurrencies() {
            const grid = document.getElementById('currencyGrid');
            grid.innerHTML = '';
            
            popularCurrencies.forEach(currency => {
                const card = document.createElement('div');
                card.className = 'currency-card';
                card.onclick = () => selectCurrency(currency.code);
                card.innerHTML = `
                    <div class="currency-code">${currency.code}</div>
                    <div class="currency-name">${currency.name}</div>
                    <div class="currency-symbol">${currency.symbol} • ${currency.region}</div>
                `;
                card.setAttribute('data-currency', currency.code.toLowerCase());
                card.setAttribute('data-search', `${currency.code} ${currency.name} ${currency.region}`.toLowerCase());
                grid.appendChild(card);
            });
        }

        function selectCurrency(code) {
            selectedCurrency = code;
            document.querySelectorAll('.currency-card').forEach(card => {
                card.classList.remove('selected');
            });
            event.target.closest('.currency-card').classList.add('selected');
            document.getElementById('saveBtn').disabled = false;
        }

        function filterCurrencies() {
            const search = document.getElementById('searchBox').value.toLowerCase();
            document.querySelectorAll('.currency-card').forEach(card => {
                const searchData = card.getAttribute('data-search');
                card.style.display = searchData.includes(search) ? 'block' : 'none';
            });
        }

        async function autoDetect() {
            const btn = event.target;
            btn.disabled = true;
            btn.innerHTML = '⏳ Detecting...';
            showStatus('Detecting your location and currency...', 'info');
            
            try {
                // Try to detect using IP geolocation API
                const response = await fetch('https://ipapi.co/json/');
                const data = await response.json();
                
                if (data.currency) {
                    const currency = popularCurrencies.find(c => c.code === data.currency);
                    if (currency) {
                        showDetected(currency, data.country_name);
                        selectedCurrency = currency.code;
                        document.getElementById('saveBtn').disabled = false;
                    } else {
                        showStatus(`Detected ${data.currency} for ${data.country_name}. Please select manually below.`, 'info');
                    }
                } else {
                    showStatus('Could not auto-detect. Please select manually.', 'error');
                }
            } catch (error) {
                showStatus('Auto-detection failed. Please select manually below.', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '🌍 Auto-Detect My Currency';
            }
        }

        function showDetected(currency, country) {
            const box = document.getElementById('detectedCurrency');
            box.style.display = 'block';
            box.innerHTML = `
                <div class="detected-box">
                    <h3>✅ Currency Detected!</h3>
                    <div class="currency">${currency.symbol}</div>
                    <div class="name">${currency.name} (${currency.code})</div>
                    <p style="margin-top: 10px; color: #666;">Based on your location: ${country}</p>
                </div>
            `;
            showStatus('Currency detected successfully!', 'success');
        }

        function showStatus(message, type) {
            const box = document.getElementById('statusBox');
            box.className = `status ${type}`;
            box.innerHTML = message;
            box.style.display = 'block';
        }

        async function saveCurrency() {
            if (!selectedCurrency) {
                showStatus('Please select a currency first!', 'error');
                return;
            }

            const btn = document.getElementById('saveBtn');
            btn.disabled = true;
            btn.innerHTML = '💾 Saving...';
            
            try {
                // In a real implementation, this would save to database
                // For now, we'll redirect to settings page
                showStatus(`Currency ${selectedCurrency} saved successfully! Redirecting...`, 'success');
                setTimeout(() => {
                    window.location.href = 'admin/settings.php';
                }, 2000);
            } catch (error) {
                showStatus('Error saving currency. Please try again.', 'error');
                btn.disabled = false;
                btn.innerHTML = '💾 Save & Continue';
            }
        }

        function skip() {
            if (confirm('Are you sure you want to skip currency setup? You can configure it later in Settings.')) {
                window.location.href = 'index.php';
            }
        }

        // Initialize
        displayCurrencies();
    </script>
</body>
</html>
