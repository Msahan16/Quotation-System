<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Size Converter - AKM Aluminium</title>
    <style>
        :root {
            --bg-start: #f1f5f9;
            --bg-end: #e2e8f0;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text: #0f172a;
            --muted: #475569;
            --primary: #0f172a;
            --accent: #14b8a6;
            --accent-strong: #0f766e;
            --border: #cbd5e1;
            --shadow: 0 20px 45px rgba(15, 23, 42, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            min-height: 100vh;
            background: radial-gradient(circle at top left, #dbeafe 0%, transparent 45%),
                        radial-gradient(circle at bottom right, #ccfbf1 0%, transparent 40%),
                        linear-gradient(140deg, var(--bg-start), var(--bg-end));
            padding: 24px;
        }

        .container {
            max-width: 980px;
            margin: 0 auto;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
        }

        .title-wrap h1 {
            margin: 0;
            font-size: clamp(1.5rem, 2.5vw, 2.1rem);
            letter-spacing: -0.02em;
        }

        .title-wrap p {
            margin: 8px 0 0;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 600;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.25);
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.28);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        .card {
            background: linear-gradient(160deg, var(--surface) 0%, var(--surface-soft) 100%);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 24px;
            box-shadow: var(--shadow);
        }

        .card h2 {
            margin: 0;
            font-size: 1.05rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--accent-strong);
        }

        .input-row {
            margin-top: 16px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--muted);
        }

        input,
        select {
            width: 100%;
            padding: 13px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            outline: none;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        input:focus,
        select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
        }

        .input-split {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .hidden {
            display: none;
        }

        .result {
            margin-top: 16px;
            padding: 14px;
            border-radius: 12px;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            font-weight: 700;
            font-size: 1.02rem;
            min-height: 54px;
            display: flex;
            align-items: center;
        }

        .helper {
            margin-top: 10px;
            color: var(--muted);
            font-size: 0.86rem;
        }

        .footer-note {
            margin-top: 18px;
            color: var(--muted);
            font-size: 0.88rem;
            text-align: center;
        }

        @media (max-width: 640px) {
            body {
                padding: 16px;
            }

            .top-bar {
                flex-direction: column;
                align-items: stretch;
                margin-bottom: 20px;
            }

            .back-btn {
                width: 100%;
            }

            .card {
                padding: 18px;
            }

            .input-split {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="top-bar">
            <div class="title-wrap">
                <h1>Size Converter</h1>
                <p>Quick conversion between centimeters and feet for quotation measurements.</p>
            </div>
            <a href="{{ route('quotation.builder') }}" class="back-btn">Back to Home</a>
        </div>

        <div class="grid">
            <section class="card">
                <h2>Universal Converter</h2>
                <div class="input-row">
                    <label for="conversionType">Select conversion type</label>
                    <select id="conversionType">
                        <option value="cm_to_ft">CM to Feet</option>
                        <option value="ft_to_cm">Feet to CM</option>
                        <option value="cm_to_in">CM to Inch</option>
                        <option value="in_to_cm">Inch to CM</option>
                        <option value="ft_in_to_cm">Feet + Inch to CM</option>
                    </select>
                </div>
                <div class="input-row">
                    <label id="inputLabel" for="primaryValue">Enter value</label>
                    <input id="primaryValue" type="number" step="0.01" min="0" placeholder="Example: 120">
                </div>

                <div class="input-row hidden" id="feetInchRow">
                    <div class="input-split">
                        <div>
                            <label for="feetPartValue">Feet part (ft)</label>
                            <input id="feetPartValue" type="number" step="0.01" min="0" placeholder="Example: 5">
                        </div>
                        <div>
                            <label for="inchPartValue">Inch part (in)</label>
                            <input id="inchPartValue" type="number" step="0.01" min="0" placeholder="Example: 8">
                        </div>
                    </div>
                </div>

                <div class="result" id="conversionResult">Result: 0</div>
                <div class="result hidden" id="conversionExtraResult">Result: 0 in</div>
                <div class="helper" id="formulaText">Formula: feet = cm / 30.48</div>
            </section>
        </div>

        <div class="footer-note">Tip: You can use decimals for accurate panel and frame measurements.</div>
    </div>

    <script>
        const conversionType = document.getElementById('conversionType');
        const inputLabel = document.getElementById('inputLabel');
        const primaryValue = document.getElementById('primaryValue');
        const feetInchRow = document.getElementById('feetInchRow');
        const feetPartValue = document.getElementById('feetPartValue');
        const inchPartInput = document.getElementById('inchPartValue');
        const conversionResult = document.getElementById('conversionResult');
        const conversionExtraResult = document.getElementById('conversionExtraResult');
        const formulaText = document.getElementById('formulaText');

        function toNumber(value) {
            const parsed = parseFloat(value);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function updateLayout() {
            const mode = conversionType.value;

            if (mode === 'ft_in_to_cm') {
                feetInchRow.classList.remove('hidden');
                primaryValue.parentElement.classList.add('hidden');
                conversionExtraResult.classList.add('hidden');
                formulaText.textContent = 'Formula: cm = ((feet x 12) + inch) x 2.54';
                calculate();
                return;
            }

            feetInchRow.classList.add('hidden');
            primaryValue.parentElement.classList.remove('hidden');

            if (mode === 'cm_to_ft') {
                inputLabel.textContent = 'Enter centimeters (cm)';
                primaryValue.placeholder = 'Example: 120';
                formulaText.textContent = 'Formula: feet = cm / 30.48';
                conversionExtraResult.classList.remove('hidden');
            } else if (mode === 'ft_to_cm') {
                inputLabel.textContent = 'Enter feet (ft)';
                primaryValue.placeholder = 'Example: 8.5';
                formulaText.textContent = 'Formula: cm = feet x 30.48';
                conversionExtraResult.classList.add('hidden');
            } else if (mode === 'cm_to_in') {
                inputLabel.textContent = 'Enter centimeters (cm)';
                primaryValue.placeholder = 'Example: 183';
                formulaText.textContent = 'Formula: inch = cm / 2.54';
                conversionExtraResult.classList.add('hidden');
            } else {
                inputLabel.textContent = 'Enter inches (in)';
                primaryValue.placeholder = 'Example: 72';
                formulaText.textContent = 'Formula: cm = inch x 2.54';
                conversionExtraResult.classList.add('hidden');
            }

            calculate();
        }

        function calculate() {
            const mode = conversionType.value;
            let resultValue = 0;
            let resultUnit = '';

            if (mode === 'ft_in_to_cm') {
                const feet = toNumber(feetPartValue.value);
                const inch = toNumber(inchPartInput.value);
                resultValue = ((feet * 12) + inch) * 2.54;
                resultUnit = 'cm';
            } else {
                const value = toNumber(primaryValue.value);
                if (mode === 'cm_to_ft') {
                    resultValue = value / 30.48;
                    resultUnit = 'ft';
                    const inches = value / 2.54;
                    conversionExtraResult.textContent = `Result: ${inches.toFixed(2)} in`;
                } else if (mode === 'ft_to_cm') {
                    resultValue = value * 30.48;
                    resultUnit = 'cm';
                } else if (mode === 'cm_to_in') {
                    resultValue = value / 2.54;
                    resultUnit = 'in';
                } else if (mode === 'in_to_cm') {
                    resultValue = value * 2.54;
                    resultUnit = 'cm';
                }
            }

            conversionResult.textContent = `Result: ${resultValue.toFixed(4)} ${resultUnit}`;
        }

        conversionType.addEventListener('change', updateLayout);
        primaryValue.addEventListener('input', calculate);
        feetPartValue.addEventListener('input', calculate);
        inchPartInput.addEventListener('input', calculate);

        updateLayout();
    </script>
</body>
</html>
