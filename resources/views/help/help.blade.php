<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Help & Support</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Serif+Display&display=swap"
        rel="stylesheet" />
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #f9c301;
            --primary-light: #eef2ff;
            --primary-dark: #f9c301;
            --text: #111827;
            --muted: #6b7280;
            --border: #e5e7eb;
            --bg: #f8fafc;
            --white: #ffffff;
            --success: #10b981;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        body::before {
            content: '';
            position: fixed;
            top: -120px;
            right: -120px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.12), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -100px;
            left: -100px;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.10), transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 8px 24px rgba(79, 70, 229, 0.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary) 0%, #6366f1 100%);
            padding: 32px 32px 28px;
            position: relative;
            overflow: hidden;
        }

        .card-header::before {
            content: '';
            position: absolute;
            top: -40px;
            right: -40px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .card-header::after {
            content: '';
            position: absolute;
            bottom: -60px;
            left: 60px;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }

        .card-header h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 1.6rem;
            color: #fff;
            font-weight: 400;
            margin-bottom: 6px;
            position: relative;
            z-index: 1;
        }

        .card-header p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.75);
            position: relative;
            z-index: 1;
        }

        .card-body {
            padding: 28px 32px 32px;
        }

        .info-chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.78rem;
            font-weight: 500;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 6px;
        }

        .field-inner {
            position: relative;
        }

        .field-inner svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 16px;
            height: 16px;
            color: var(--muted);
            pointer-events: none;
        }

        .field-inner input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.93rem;
            color: var(--text);
            background: #f9fafb;
            outline: none;
            cursor: default;
        }

        .field-badge {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary-light);
            color: var(--primary);
            font-size: 0.7rem;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 20px;
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 22px 0;
        }

        .availability {
            display: flex;
            align-items: center;
            gap: 10px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
        }

        .availability .dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            flex-shrink: 0;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            50% {
                box-shadow: 0 0 0 6px rgba(16, 185, 129, 0);
            }
        }

        .availability span {
            font-size: 0.83rem;
            color: #065f46;
            font-weight: 500;
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            padding: 12px 18px;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 1.5px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary-light);
            transform: translateY(-1px);
        }

        .footer-note {
            text-align: center;
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 18px;
        }

        .toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #111827;
            color: #fff;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s;
            z-index: 999;
        }

        .toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-header">
            <div class="header-icon">🎧</div>
            <h1>Help &amp; Support</h1>
            <p>Contact details for your support representative</p>
        </div>

        <div class="card-body">

            <div class="info-chips">
                <span class="chip">⚡ Instant Response</span>
                <span class="chip">🔒 Secure</span>
                <span class="chip">🕐 24 / 7 Available</span>
            </div>

            <!-- Name -->
            <div class="field">
                <label>Full Name</label>
                <div class="field-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <input type="text" value="Admin" readonly />
                </div>
            </div>

            <!-- Email -->
            <div class="field">
                <label>Email Address</label>
                <div class="field-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <input type="email" value="anshdspl@gmail.com" readonly />
                </div>
            </div>

            <!-- Mobile -->
            <div class="field">
                <label>Mobile Number</label>
                <div class="field-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <input type="number" value="8600418620" readonly />
                </div>
            </div>

            <div class="divider"></div>

            <div class="availability">
                <div class="dot"></div>
                <span>Support team is currently online and available</span>
            </div>

            <div class="btn-group">
                <button class="btn btn-outline" onclick="copyContact()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    Copy Info
                </button>
                <button class="btn btn-primary" onclick="callSupport()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498A1 1 0 0121 15.72V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </button>
            </div>

            <p class="footer-note">Response time is typically under 5 minutes during business hours.</p>
        </div>
    </div>

    <div class="toast" id="toast">✅ Contact info copied!</div>

    <script>
        function copyContact() {
            const text = `Name: Admin\nEmail: anshdspl@gmail.com\nMobile: 8600418620`;

            // ✅ Try modern clipboard API first
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showToast('✅ Contact info copied!');
                }).catch(() => fallbackCopy(text));
            } else {
                // ✅ Fallback for HTTP / older browsers
                fallbackCopy(text);
            }
        }

        function fallbackCopy(text) {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            textarea.style.top = '0';
            textarea.style.left = '0';
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            try {
                document.execCommand('copy');
                showToast('✅ Contact info copied!');
            } catch (err) {
                showToast('❌ Copy failed. Please copy manually.');
            }
            document.body.removeChild(textarea);
        }

        function callSupport() {
            window.location.href = 'tel:8600418620';
        }

        function showToast(msg) {
            const t = document.getElementById('toast');
            t.textContent = msg;
            t.classList.add('show');
            setTimeout(() => t.classList.remove('show'), 3000);
        }
    </script>

</body>

</html>