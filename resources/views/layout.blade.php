<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Graduate School') — SPUP</title>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --green-900: #0b3b26;
            --green-800: #0f4a30;
            --green-700: #16643f;
            --green-500: #2aa564;
            --gold-500: #f5c542;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            color: var(--white);
            background: #0a2b1d;
        }

        .page {
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            background-image:
                linear-gradient(100deg, rgba(11, 59, 38, 0.98) 0%, rgba(15, 74, 48, 0.92) 50%, rgba(15, 74, 48, 0.35) 80%),
                url("/images/landing/graduate-campus.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .page::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 75% 20%, rgba(255, 255, 255, 0.12), transparent 55%);
            pointer-events: none;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 36px 24px 64px;
            position: relative;
            z-index: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .header-2 {
            color: var(--gold-500);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .brand img {
            width: 20rem;
            height: auto;
            object-fit: contain;
        }

        .hero {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr;
            align-items: start;
            padding: 64px 0 24px;
            max-width: 820px;
        }

        .hero .content {
            text-align: left;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.75);
        }

        .eyebrow::before {
            content: "";
            width: 36px;
            height: 2px;
            background: var(--gold-500);
            display: inline-block;
        }

        h1 {
            font-size: clamp(2.4rem, 3.4vw, 3.8rem);
            line-height: 1.1;
            margin: 16px 0 12px;
        }

        p {
            font-size: 1rem;
            line-height: 1.7;
            color: rgba(255, 255, 255, 0.82);
            margin: 0 0 24px;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin: 24px 0 18px;
        }

        .step-card {
            background: rgba(7, 46, 27, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            padding: 16px;
            min-height: 120px;
        }

        .step-card span {
            display: block;
            font-size: 12px;
            letter-spacing: 0.18em;
            color: rgba(255, 255, 255, 0.65);
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .step-card strong {
            display: block;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .step-card p {
            margin: 0;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin: 8px 0 20px;
        }

        .primary-button {
            border-radius: 8px;
            padding: 10px 18px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--green-900);
            color: var(--white);
            border: none;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .primary-button:hover,
        .primary-button:focus {
            transform: scale(1.05);
            color: var(--gold-500);
        }

        .divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.12);
            margin: 0 0 24px;
        }

        /* ── Footer ── */
        .site-footer {
            position: relative;
            z-index: 1;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 40px 24px 28px;
            background: rgba(7, 35, 20, 0.7);
            backdrop-filter: blur(10px);
        }

        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 40px;
        }

        .footer-brand img {
            width: 14rem;
            height: auto;
            object-fit: contain;
            margin-bottom: 14px;
            display: block;
        }

        .footer-brand p {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.6;
            margin: 0;
        }

        .footer-col h4 {
            font-size: 11px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold-500);
            margin: 0 0 14px;
            font-weight: 700;
        }

        .footer-col ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .footer-col ul li {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.75);
            line-height: 1.5;
        }

        .footer-col ul li .icon {
            flex-shrink: 0;
            width: 16px;
            height: 16px;
            margin-top: 1px;
            opacity: 0.6;
        }

        .footer-col ul li a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-col ul li a:hover {
            color: var(--gold-500);
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 24px auto 0;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .footer-bottom p {
            margin: 0;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
        }

        .footer-motto {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.35);
            font-style: italic;
            letter-spacing: 0.05em;
        }

        @media (max-width: 960px) {
            .hero {
                max-width: 100%;
            }

            .steps {
                grid-template-columns: 1fr;
            }

            .footer-inner {
                grid-template-columns: 1fr;
                gap: 28px;
            }
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="container">

            <header>
                <div class="brand">
                    <img src="images/sys-spup.png" alt="SPUP Logo">
                </div>
                @yield('header_actions')
            </header>

            @yield('content')

        </div>
    </div>

    @yield('footer')

</body>
</html>