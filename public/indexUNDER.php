
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Site Under Maintenance</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background:
                radial-gradient(circle at 20% 20%, rgba(6, 182, 212, 0.12), transparent 30%),
                radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.12), transparent 30%),
                #07111f;
            color: #fff;
            overflow: hidden;
        }

        /* Background Glow */
        body::before,
        body::after {
            content: "";
            position: fixed;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.25;
            z-index: -1;
        }

        body::before {
            background: #06b6d4;
            top: -150px;
            left: -100px;
        }

        body::after {
            background: #2563eb;
            bottom: -150px;
            right: -100px;
        }

        .maintenance-wrapper {
            width: 100%;
            max-width: 850px;
            padding: 30px;
            text-align: center;
        }

        .maintenance-card {
            padding: 60px 40px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            box-shadow: 0 30px 80px rgba(0,0,0,0.35);
        }

        /* Icon */
        .maintenance-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 46px;
            background: rgba(6,182,212,0.10);
            border: 1px solid rgba(6,182,212,0.35);
            box-shadow:
                0 0 0 12px rgba(6,182,212,0.03),
                0 0 50px rgba(6,182,212,0.15);
            animation: pulse 2.5s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow:
                    0 0 0 12px rgba(6,182,212,0.03),
                    0 0 50px rgba(6,182,212,0.15);
            }

            50% {
                transform: scale(1.05);
                box-shadow:
                    0 0 0 20px rgba(6,182,212,0.02),
                    0 0 70px rgba(6,182,212,0.25);
            }
        }

        .badge {
            display: inline-block;
            padding: 8px 18px;
            margin-bottom: 22px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #67e8f9;
            background: rgba(6,182,212,0.10);
            border: 1px solid rgba(6,182,212,0.25);
        }

        h1 {
            font-size: 48px;
            line-height: 1.15;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        h1 span {
            color: #22d3ee;
        }

        .description {
            max-width: 620px;
            margin: 0 auto;
            color: #a9b7c7;
            font-size: 17px;
            line-height: 1.8;
        }

        .status {
            margin: 35px auto 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #cbd5e1;
            font-size: 14px;
        }

        .status-dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 15px rgba(34,197,94,0.7);
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% {
                opacity: 1;
            }

            50% {
                opacity: 0.35;
            }
        }

        .footer {
            margin-top: 35px;
            color: #64748b;
            font-size: 13px;
        }

        .footer strong {
            color: #94a3b8;
        }

        /* Mobile */
        @media (max-width: 600px) {

            .maintenance-wrapper {
                padding: 20px;
            }

            .maintenance-card {
                padding: 45px 22px;
                border-radius: 20px;
            }

            .maintenance-icon {
                width: 85px;
                height: 85px;
                font-size: 38px;
            }

            h1 {
                font-size: 34px;
            }

            .description {
                font-size: 15px;
            }
        }
    </style>
</head>

<body>

    <div class="maintenance-wrapper">

        <div class="maintenance-card">

            <div class="maintenance-icon">
                🛠️
            </div>

            <div class="badge">
                System Maintenance
            </div>

            <h1>
                We'll Be <span>Back Soon!</span>
            </h1>

            <p class="description">
                Our website is currently undergoing scheduled maintenance
                to improve your experience and bring you better performance,
                security, and new features.
            </p>

            <div class="status">
                <span class="status-dot"></span>
                Our team is working on it
            </div>

            <div class="footer">
                Thank you for your patience and understanding.
                <br>
                <strong>Please check back shortly.</strong>
            </div>

        </div>

    </div>

</body>
</html>

