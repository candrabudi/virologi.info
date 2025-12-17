<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LIVE CYBER THREAT MAP</title>

    <style>
        @import url("https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;400;500;700&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap");

        * {
            box-sizing: border-box
        }
        .panel.mobile-attacks {
            display: none;
        }
        :root {
            --bg: #070b14;
            --panel: #0b1220;
            --panel2: #0a1020;

            --grid: rgba(0, 180, 255, .10);
            --dot: rgba(255, 255, 255, .10);

            --neon: #00b4ff;
            --neon2: #38d4ff;

            --cyan: #00e5ff;
            --green: #4ade80;
            --red: #ff6b6b;

            --text: #ffffff;
            --muted: #94a3b8;
            --muted2: #64748b;

            --stroke: #1e293b;
            --shadow: rgba(0, 0, 0, .6);

            --glow: rgba(0, 180, 255, .35);
            --glowC: rgba(56, 212, 255, .45);
        }


        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            background:
                radial-gradient(1200px 800px at 50% 20%, rgba(0, 180, 255, .14), transparent 60%),
                radial-gradient(1200px 800px at 55% 40%, rgba(0, 229, 255, .10), transparent 65%),
                var(--bg);
            font-family: Inter, system-ui, -apple-system, Segoe UI, sans-serif;
            overflow: hidden;
            color: var(--text);
        }


        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(to right, var(--grid) 1px, transparent 1px),
                radial-gradient(circle, var(--dot) 1px, transparent 1px);
            background-size: 90px 90px, 22px 22px;
            opacity: .35;
            pointer-events: none;
            z-index: 1
        }

        body::after {
            content: "";
            position: fixed;
            inset: 0;
            background:
                linear-gradient(to bottom, rgba(0, 0, 0, .55), transparent 30%, transparent 70%, rgba(0, 0, 0, .65));
            pointer-events: none;
            z-index: 1
        }

        #app {
            position: relative;
            width: 100vw;
            height: 100vh;
            z-index: 2
        }

        .topbar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 92px;
            display: grid;
            grid-template-columns: 340px 1fr 340px;
            align-items: center;
            padding: 14px 24px;
            border-top: 5px solid var(--neon);
            background: linear-gradient(to bottom, rgba(10, 10, 14, .88), rgba(10, 10, 14, .35));
            backdrop-filter: blur(12px);
            z-index: 50
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            user-select: none
        }

        .brand-badge {
            background: radial-gradient(circle at 30% 30%, rgba(0, 180, 255, .95), rgba(0, 180, 255, .25));
            box-shadow:
                0 0 28px rgba(0, 180, 255, .55),
                inset 0 0 18px rgba(0, 0, 0, .35);
        }

        .brand-badge::before {
            content: "";
            position: absolute;
            inset: 10px;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, .18)
        }

        .brand-name {
            font-family: "JetBrains Mono", monospace;
            letter-spacing: .22em;
            font-weight: 700;
            font-size: 16px
        }

        .brand-sub {
            font-size: 11px;
            color: var(--muted2);
            letter-spacing: .18em;
            margin-top: 2px
        }

        .centerTitle {
            text-align: center;
            user-select: none
        }

        .centerTitle h1 {
            margin: 0;
            font-family: "JetBrains Mono", monospace;
            font-size: 26px;
            letter-spacing: .22em;
            font-weight: 700
        }

        .centerTitle p {
            margin: 6px 0 0 0;
            font-family: "JetBrains Mono", monospace;
            letter-spacing: .08em;
            color: var(--neon);
            font-weight: 600
        }

        .cta {
            justify-self: end;
            border: 1px solid rgba(0, 180, 255, .85);
            background: linear-gradient(135deg,
                    rgba(0, 180, 255, .18),
                    rgba(0, 0, 0, .12));
            box-shadow:
                0 0 0 1px rgba(0, 180, 255, .18),
                0 10px 30px rgba(0, 0, 0, .45);
            padding: 10px 12px;
            border-radius: 10px;
            font-family: "JetBrains Mono", monospace;
            text-transform: uppercase;
            font-size: 12px;
            line-height: 1.2;
            letter-spacing: .08em;
            color: #fff;
            text-align: right;
            user-select: none
        }

        .cta span {
            display: inline-block;
            margin-top: 6px;
            color: var(--neon);
            font-weight: 700
        }

        .shell {
            position: absolute;
            top: 92px;
            bottom: 0;
            left: 0;
            right: 0;
            display: grid;
            grid-template-columns: 340px 1fr 340px;
            gap: 18px;
            padding: 18px 18px 18px 18px
        }

        .panel {
            background: linear-gradient(to bottom, rgba(17, 17, 24, .92), rgba(17, 17, 24, .55));
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: 16px;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .45);
            backdrop-filter: blur(12px);
            overflow: hidden;
            position: relative
        }

        .panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(900px 500px at 20% 0%, rgba(0, 180, 255, .22), transparent 60%),
                radial-gradient(700px 400px at 80% 0%, rgba(0, 229, 255, .18), transparent 60%);
            pointer-events: none
        }

        .panelInner {
            position: relative;
            height: 100%;
            padding: 14px 14px 12px 14px;
            display: flex;
            flex-direction: column;
            gap: 12px
        }

        .panelTitle {
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            letter-spacing: .14em;
            color: rgba(255, 255, 255, .88);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .panelTitle small {
            color: var(--muted2);
            letter-spacing: .10em;
            font-weight: 500
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, .10), transparent)
        }

        .canvasWrap {
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .06);
            background: linear-gradient(135deg, rgba(255, 30, 109, .10), rgba(0, 0, 0, .12));
            padding: 10px
        }

        #miniChart {
            width: 100%;
            height: 130px;
            display: block
        }

        .attackHeader {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px
        }

        .attackHeader .rate {
            display: flex;
            align-items: center;
            gap: 10px;
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            color: var(--muted)
        }

        .pill {
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(0, 0, 0, .12);
            color: rgba(255, 255, 255, .85)
        }

        .stepper {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .stepper button {
            width: 28px;
            height: 28px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(0, 0, 0, .12);
            color: #fff;
            cursor: pointer;
            font-weight: 700;
            line-height: 1
        }

        .stepper button:active {
            transform: scale(.97)
        }

        #attackdiv {
            flex: 1;
            min-height: 0;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .06);
            background: linear-gradient(135deg, rgba(0, 0, 0, .22), rgba(0, 0, 0, .06));
            padding: 10px;
            overflow: auto;
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            line-height: 1.6
        }

        #attackdiv::-webkit-scrollbar {
            width: 6px
        }

        #attackdiv::-webkit-scrollbar-track {
            background: rgba(0, 180, 255, .85);
            border-radius: 999px
        }

        #attackdiv::-webkit-scrollbar-thumb {
            background: rgba(0, 229, 255, .95);
            border-radius: 999px
        }

        #attackdiv::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 229, 255, .95);
        }

        .attack-source {
            color: var(--neon2);
            font-weight: 600
        }

        .attack-target {
            color: var(--green);
            font-weight: 600
        }

        .attack-type {
            color: var(--cyan);
            font-weight: 500
        }

        .attack-ip {
            color: rgba(255, 255, 255, .72)
        }

        .list {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 10px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .06);
            background: rgba(0, 0, 0, .12)
        }

        .item .left {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0
        }

        .dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .25);
            box-shadow: 0 0 0 6px rgba(255, 255, 255, .06)
        }

        .flag {
            width: 26px;
            height: 18px;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, .10);
            background: rgba(255, 255, 255, .06);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px
        }

        .name {
            font-size: 13px;
            color: rgba(255, 255, 255, .90);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis
        }

        .meta {
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            color: var(--muted);
            white-space: nowrap
        }

        .mapWrap {
            position: relative;
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .06);
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .45);
            background: linear-gradient(180deg, rgba(0, 0, 0, .25), rgba(0, 0, 0, .10));
            min-height: 0;
        }

        #container1 {
            position: absolute;
            inset: 0
        }

        .legend {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: 18px;
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 10px 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .08);
            background: rgba(10, 10, 14, .55);
            backdrop-filter: blur(12px);
            z-index: 10
        }

        .leg {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            color: rgba(255, 255, 255, .85)
        }

        .leg i {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            display: inline-block;
            box-shadow: 0 0 0 6px rgba(255, 255, 255, .06)
        }

        .leg .m {
            background: #00b4ff;
        }

        .leg .p {
            background: #38d4ff;
        }

        .leg .e {
            background: #22d3ee;
        }

        .hoverinfo {
            color: #fff !important;
            background: rgba(16, 16, 24, .92) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, .10) !important;
            border-radius: 10px !important;
            padding: 10px 12px !important;
            font-family: Inter, sans-serif !important;
            font-size: 13px !important;
            box-shadow: 0 18px 40px rgba(0, 0, 0, .45) !important
        }

        .datamaps-subunit {
            stroke: rgba(255, 255, 255, .12) !important;
            stroke-width: .7px !important;
            stroke-dasharray: 2, 4 !important;
            fill: rgba(255, 255, 255, .06) !important
        }

        @keyframes impact-pulse {
            0% {
                r: 0;
                opacity: 1
            }

            55% {
                opacity: .55
            }

            100% {
                r: 26;
                opacity: 0
            }
        }

        @keyframes impact-core {
            0% {
                r: 0;
                opacity: 1
            }

            60% {
                r: 9;
                opacity: 1
            }

            100% {
                r: 13;
                opacity: 0
            }
        }

        .impact-ripple {
            animation: impact-pulse 2.05s ease-out forwards;
            stroke-width: 2;
            fill: none;
            pointer-events: none
        }

        .impact-core {
            animation: impact-core 1.55s ease-out forwards;
            pointer-events: none
        }

        .impact-glow {
            filter: url(#glow)
        }

        @media (max-width:1100px) {

            body {
                overflow-y: auto;
            }

            .topbar {
                grid-template-columns: 1fr;
                height: auto;
            }

            .brand,
            .cta {
                display: none;
            }

            .shell {
                grid-template-columns: 1fr;
                gap: 12px;
                padding: 12px;
            }

            .panel {
                display: block;
            }

            .panel.left,
            .panel.right {
                display: none;
            }

            .panel.mobile-attacks {
                display: block;
            }

            .mapWrap {
                height: 62vh;
            }

            #container1 {
                transform: scale(1.35) translateY(-6%);
                transform-origin: center center;
            }
        }


        .country-panel {
            position: absolute;
            top: 140px;
            left: 50%;
            transform: translateX(-50%);
            width: 320px;
            background: linear-gradient(to bottom, #0b0b10, #050509);
            border: 1px solid rgba(255, 255, 255, .08);
            border-left: 6px solid #ff1e6d;
            border-radius: 14px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .7);
            color: #fff;
            z-index: 999
        }

        .country-panel.hidden {
            display: none
        }

        .cp-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px
        }

        .cp-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
            font-weight: 600
        }

        #cp-close {
            background: none;
            border: none;
            color: #aaa;
            font-size: 18px;
            cursor: pointer
        }

        .cp-section {
            padding: 10px 14px
        }

        .cp-label {
            font-family: "JetBrains Mono", monospace;
            font-size: 11px;
            letter-spacing: .15em
        }

        .cp-sub {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 2px
        }

        .cp-divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #222, transparent);
            margin: 6px 0
        }

        .cp-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-top: 10px
        }

        .spark {
            margin-top: 4px
        }

        .country-panel {
            opacity: 0;
            transform: translateX(-50%) translateY(12px) scale(.96);
            transition: opacity .35s ease, transform .45s cubic-bezier(.16, 1, .3, 1);
        }

        .country-panel.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0) scale(1);
        }

        .cp-loading {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(to bottom, #0b0b10, #050509);
            border-radius: 14px;
            z-index: 2
        }

        .spinner {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, .15);
            border-top-color: #ff1e6d;
            animation: spin 1s linear infinite
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        .skeleton {
            position: relative;
            overflow: hidden;
            background: rgba(255, 255, 255, .08);
            border-radius: 6px
        }

        .skeleton::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, .18) 50%, transparent 70%);
            animation: shimmer 1.4s infinite
        }

        @keyframes shimmer {
            from {
                transform: translateX(-100%)
            }

            to {
                transform: translateX(100%)
            }
        }

        .fade-in {
            animation: fadeIn .5s ease forwards
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @media (max-width: 768px) {

            body {
                overflow-y: auto;
            }

            .topbar {
                grid-template-columns: 1fr;
                height: auto;
                padding: 12px 14px;
                gap: 8px;
            }

            .brand,
            .cta {
                display: none;
            }

            .centerTitle h1 {
                font-size: 18px;
                letter-spacing: .18em;
            }

            .centerTitle p {
                font-size: 12px;
            }

            .shell {
                grid-template-columns: 1fr;
                padding: 12px;
                gap: 12px;
            }

            .panel {
                display: none;
            }

            .mapWrap {
                height: 65vh;
                border-radius: 18px;
                overflow: hidden;
            }

            #container1 {
                transform: scale(1.35) translateY(-6%);
                transform-origin: center center;
            }

            .legend {
                bottom: 10px;
                gap: 12px;
                font-size: 11px;
            }

            #attackdiv {
                font-size: 11px;
                padding: 10px;
                height: 30vh;
            }

            .country-panel {
                top: 110px;
                width: calc(100vw - 28px);
                left: 50%;
                transform: translateX(-50%);
            }
        }

        .map-zoom {
            position: absolute;
            right: 14px;
            top: 14px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            z-index: 20
        }

        .map-zoom button {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, .15);
            background: rgba(10, 10, 20, .65);
            color: #00b4ff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 0 1px rgba(0, 180, 255, .12),
                0 12px 30px rgba(0, 0, 0, .45)
        }

        .map-zoom button:active {
            transform: scale(.96)
        }

        .mobile-attacks {
            margin-top: 12px;
        }

        #attackdiv-mobile {
            max-height: 38vh;
            overflow-y: auto;
            font-family: "JetBrains Mono", monospace;
            font-size: 12px;
            line-height: 1.6;
            padding: 10px;
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, .06);
            background: linear-gradient(135deg, rgba(0, 0, 0, .22), rgba(0, 0, 0, .06));
        }
    </style>


    <script src="https://d3js.org/d3.v3.min.js"></script>
    <script src="https://d3js.org/topojson.v1.min.js"></script>
    <script src="https://d3js.org/d3.geo.projection.v0.min.js"></script>
    <script src="https://datamaps.github.io/scripts/datamaps.world.min.js?v=1"></script>
</head>

<body>
    <div id="app">
        <div class="topbar">
            <div class="brand">
                <div class="brand-badge"></div>
                <div>
                    <div class="brand-name">VIROLOGI</div>
                    <div class="brand-sub">SECURITY INTELLIGENCE</div>
                </div>
            </div>

            <div class="centerTitle">
                <h1>LIVE CYBER THREAT MAP</h1>
                <p><span id="attackCounter">0</span> ATTACKS ON THIS DAY</p>
            </div>

            <div class="cta">
                DON'T WAIT TO BE ATTACKED<br />
                PREVENTION STARTS <span>NOW →</span>
            </div>
        </div>

        <div class="shell">
            <aside class="panel">
                <div class="panelInner">
                    <div class="panelTitle">RECENT DAILY ATTACKS <small>Last 14 days</small></div>
                    <div class="divider"></div>
                    <div class="canvasWrap">
                        <canvas id="miniChart" width="600" height="240"></canvas>
                    </div>

                    <div class="attackHeader">
                        <div class="panelTitle" style="margin:0">ATTACKS</div>
                        <div class="rate">
                            <span class="pill">Current rate</span>
                            <span id="rateValue" class="pill">1.2/s</span>
                            <span class="stepper">
                                <button id="rateMinus">−</button>
                                <button id="ratePlus">+</button>
                            </span>
                        </div>
                    </div>
                    <div class="map-zoom">
                        <button id="zoomIn">＋</button>
                        <button id="zoomOut">－</button>
                        <button id="zoomReset">⟳</button>
                    </div>
                    <div id="attackdiv"></div>
                </div>
            </aside>

            <main class="mapWrap">
                <div id="container1"></div>
                <div class="legend">
                    <div class="leg"><i class="m"></i> Malware</div>
                    <div class="leg"><i class="p"></i> Phishing</div>
                    <div class="leg"><i class="e"></i> Exploit</div>
                </div>
            </main>

            <div class="panel mobile-attacks">
                <div class="panelInner">
                    <div class="panelTitle">
                        <span>Recent Attacks</span>
                        <small>Live</small>
                    </div>

                    <div class="divider"></div>

                    <div id="attackdiv-mobile"></div>
                </div>
            </div>


            <aside class="panel">
                <div class="panelInner">
                    <div class="panelTitle">TOP TARGETED COUNTRIES <small>Last day</small></div>
                    <div class="divider"></div>
                    <div class="list" id="topCountries"></div>

                    <div class="panelTitle" style="margin-top:10px">TOP TARGETED INDUSTRIES <small>Last day</small>
                    </div>
                    <div class="divider"></div>
                    <div class="list" id="topIndustries"></div>

                    <div class="panelTitle" style="margin-top:10px">TOP MALWARE TYPES <small>Last day</small></div>
                    <div class="divider"></div>
                    <div class="list" id="topMalware"></div>
                </div>
            </aside>
        </div>
    </div>

    <div id="countryPanel" class="country-panel hidden">
        <div class="cp-loading" id="cpLoading">
            <div class="spinner"></div>
        </div>

        <div class="cp-header fade-in">
            <div class="cp-title">
                <span id="cp-flag">🏳️</span>
                <span id="cp-name"></span>
            </div>
            <button id="cp-close">✕</button>
        </div>

        <div class="cp-section fade-in">
            <div class="cp-label">ATTACK TREND</div>
            <div class="cp-sub">Last 30 days</div>
            <canvas id="cpTrend" width="260" height="90"></canvas>
        </div>

        <div class="cp-divider"></div>

        <div class="cp-section fade-in">
            <div class="cp-label">MALWARE TYPE TRENDS</div>

            <div class="cp-row"><span>Botnet</span><span id="m1">6.2%</span></div>
            <canvas class="spark" width="260" height="22"></canvas>

            <div class="cp-row"><span>Infostealer</span><span id="m2">3.0%</span></div>
            <canvas class="spark" width="260" height="22"></canvas>

            <div class="cp-row"><span>Ransomware</span><span id="m3">2.7%</span></div>
            <canvas class="spark" width="260" height="22"></canvas>
        </div>
    </div>

    <script>
        var attackCount = 9237384;
        var baseRate = 1.2;
        var rateMin = .4;
        var rateMax = 6.0;

        function formatNumber(n) {
            var s = Math.floor(n).toString();
            var out = "";
            for (var i = 0; i < s.length; i++) {
                var idx = s.length - 1 - i;
                out = s[idx] + out;
                if (i % 3 === 2 && idx !== 0) out = "," + out
            }
            return out
        }

        function setCounter() {
            document.getElementById("attackCounter").textContent = formatNumber(attackCount)
        }

        setCounter();

        function setRateLabel() {
            document.getElementById("rateValue").textContent = baseRate.toFixed(1) + "/s"
        }

        setRateLabel();

        document.getElementById("rateMinus").addEventListener("click", function() {
            baseRate = Math.max(rateMin, baseRate - 0.4);
            setRateLabel();
            rebuildTimer();
        });

        document.getElementById("ratePlus").addEventListener("click", function() {
            baseRate = Math.min(rateMax, baseRate + 0.4);
            setRateLabel();
            rebuildTimer();
        });

        var chartData = [];

        function seedChart() {
            chartData = [];
            var v = 12.5;
            for (var i = 0; i < 14; i++) {
                v = Math.max(5, Math.min(20, v + (Math.random() * 4 - 2)));
                chartData.push(v)
            }
        }
        seedChart();

        function drawMiniChart() {
            var c = document.getElementById("miniChart");
            var ctx = c.getContext("2d");
            var w = c.width,
                h = c.height;
            ctx.clearRect(0, 0, w, h);

            ctx.globalAlpha = 1;
            ctx.fillStyle = "rgba(255,255,255,.03)";
            ctx.fillRect(0, 0, w, h);

            ctx.strokeStyle = "rgba(255,255,255,.08)";
            ctx.lineWidth = 1;
            for (var gx = 0; gx <= 6; gx++) {
                var x = Math.floor(gx * w / 6);
                ctx.beginPath();
                ctx.moveTo(x, 0);
                ctx.lineTo(x, h);
                ctx.stroke();
            }
            for (var gy = 0; gy <= 4; gy++) {
                var y = Math.floor(gy * h / 4);
                ctx.beginPath();
                ctx.moveTo(0, y);
                ctx.lineTo(w, y);
                ctx.stroke();
            }

            var max = 0;
            for (var i = 0; i < chartData.length; i++) max = Math.max(max, chartData[i]);
            max = Math.max(max, 22);

            var pad = 14;
            var innerW = w - pad * 2;
            var innerH = h - pad * 2;

            ctx.beginPath();
            for (var i = 0; i < chartData.length; i++) {
                var x = pad + (i * (innerW / (chartData.length - 1)));
                var y = pad + innerH - (chartData[i] / max) * innerH;
                if (i === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            }
            ctx.lineWidth = 3;
            ctx.strokeStyle = "rgba(255,30,109,.95)";
            ctx.shadowColor = "rgba(255,30,109,.35)";
            ctx.shadowBlur = 14;
            ctx.stroke();
            ctx.shadowBlur = 0;

            ctx.lineTo(pad + innerW, pad + innerH);
            ctx.lineTo(pad, pad + innerH);
            ctx.closePath();
            var grad = ctx.createLinearGradient(0, pad, 0, pad + innerH);
            grad.addColorStop(0, "rgba(255,30,109,.35)");
            grad.addColorStop(1, "rgba(255,30,109,0)");
            ctx.fillStyle = grad;
            ctx.fill();

            ctx.fillStyle = "rgba(255,255,255,.75)";
            ctx.font = "12px JetBrains Mono, monospace";
            ctx.fillText("20,000,000", 10, 18);
            ctx.fillStyle = "rgba(255,255,255,.55)";
            ctx.fillText("10,000,000", 10, Math.floor(h / 2));
            ctx.fillText("5,000,000", 10, h - 10);
        }
        drawMiniChart();

        setInterval(function() {
            chartData.shift();
            var last = chartData[chartData.length - 1];
            var next = Math.max(5, Math.min(20, last + (Math.random() * 4 - 2)));
            chartData.push(next);
            drawMiniChart();
        }, 2400);

        var map = new Datamap({
            scope: "world",
            element: document.getElementById("container1"),
            projection: "winkel3",
            fills: {
                defaultFill: "rgba(255,255,255,.02)"
            },
            geographyConfig: {
                hideAntarctica: true,
                borderWidth: .6,
                borderColor: "rgba(255,255,255,.12)",
                popupTemplate: function(g) {
                    return '<div class="hoverinfo">' + g.properties.name + "</div>"
                },
                highlightOnHover: true,
                highlightFillColor: "rgba(0,212,255,.08)",
                highlightBorderColor: "rgba(255,30,109,.75)",
                highlightBorderWidth: 1
            },
            done: function(d) {
                var defs = d.svg.append("defs");

                var glow = defs.append("filter")
                    .attr("id", "glow")
                    .attr("x", "-50%")
                    .attr("y", "-50%")
                    .attr("width", "200%")
                    .attr("height", "200%");

                glow.append("feGaussianBlur")
                    .attr("stdDeviation", "3")
                    .attr("result", "blur");

                var m = glow.append("feMerge");
                m.append("feMergeNode").attr("in", "blur");
                m.append("feMergeNode").attr("in", "SourceGraphic")
            }
        });

        function rIP() {
            return Math.floor(Math.random() * 256) + "." + Math.floor(Math.random() * 256) + "." + Math.floor(Math
                .random() * 256) + "." + Math.floor(Math.random() * 256)
        }

        function impact(lat, lon, color) {
            var c = map.projection([lon, lat]);
            if (!c || isNaN(c[0]) || isNaN(c[1])) return;
            var g = map.svg.append("g").attr("transform", "translate(" + c[0] + "," + c[1] + ")");
            for (var i = 0; i < 3; i++) {
                g.append("circle").attr("class", "impact-ripple").attr("stroke", color).style("animation-delay", i * .25 +
                    "s")
            }
            g.append("circle").attr("class", "impact-core impact-glow").attr("fill", color);
            setTimeout(function() {
                g.remove()
            }, 2200)
        }

        function arcGradient(id, from, to) {
            var defs = map.svg.select("defs");
            var grad = defs.append("linearGradient")
                .attr("id", id)
                .attr("gradientUnits", "userSpaceOnUse")
                .attr("x1", from[0]).attr("y1", from[1])
                .attr("x2", to[0]).attr("y2", to[1]);
            grad.append("stop").attr("offset", "0%").attr("stop-color", "rgba(255,30,109,.0)");
            grad.append("stop").attr("offset", "30%").attr("stop-color", "rgba(255,30,109,.85)");
            grad.append("stop").attr("offset", "70%").attr("stop-color", "rgba(0,212,255,.85)");
            grad.append("stop").attr("offset", "100%").attr("stop-color", "rgba(0,212,255,0)");
            return "url(#" + id + ")"
        }

        function smoothArc(slat, slon, dlat, dlon, kind) {
            var p1 = map.projection([slon, slat]);
            var p2 = map.projection([dlon, dlat]);
            if (!p1 || !p2) return;

            var midX = (p1[0] + p2[0]) / 2;
            var midY = (p1[1] + p2[1]) / 2 - 90;

            var pathData = "M" + p1[0] + "," + p1[1] + " Q" + midX + "," + midY + " " + p2[0] + "," + p2[1];

            var col = kind === "malware" ? "rgba(255,107,107,.95)" : (kind === "phishing" ? "rgba(168,85,247,.95)" :
                "rgba(245,158,11,.95)");
            var gid = "g" + Math.floor(Math.random() * 1e9);
            var stroke = arcGradient(gid, p1, p2);

            var path = map.svg.append("path")
                .attr("d", pathData)
                .attr("fill", "none")
                .attr("stroke", stroke)
                .attr("stroke-width", 2.2)
                .attr("stroke-linecap", "round")
                .attr("filter", "url(#glow)")
                .attr("opacity", .95);

            var length = path.node().getTotalLength();

            path
                .attr("stroke-dasharray", length + " " + length)
                .attr("stroke-dashoffset", length)
                .transition()
                .duration(1100)
                .ease("cubic-in-out")
                .attr("stroke-dashoffset", 0)
                .transition()
                .duration(900)
                .ease("linear")
                .attr("opacity", 0)
                .remove();

            setTimeout(function() {
                impact(dlat, dlon, col)
            }, 820)
        }


        function addLog(srcName, dstName, type) {
            var line =
                '<span class="attack-source">' + srcName + '</span> ' +
                '<span class="attack-ip">(' + rIP() + ')</span> ' +
                '<span class="attack-target">attacks</span> ' +
                '<span class="attack-source">' + dstName + '</span> ' +
                '<span class="attack-ip">(' + rIP() + ')</span> ' +
                '<span class="attack-type">(' + type + ')</span><br>';

            var log = document.getElementById("attackdiv");
            if (log) {
                log.innerHTML += line;
                log.scrollTop = log.scrollHeight;
            }

            var logMobile = document.getElementById("attackdiv-mobile");
            if (logMobile) {
                logMobile.innerHTML += line;
                logMobile.scrollTop = logMobile.scrollHeight;
            }
        }


        var points = [{
                name: "USA",
                flag: "🇺🇸",
                lat: 37.09,
                lon: -95.71,
                ind: "Telecommunications"
            },
            {
                name: "China",
                flag: "🇨🇳",
                lat: 35.86,
                lon: 104.19,
                ind: "Government"
            },
            {
                name: "Russia",
                flag: "🇷🇺",
                lat: 61.52,
                lon: 105.31,
                ind: "Government"
            },
            {
                name: "Germany",
                flag: "🇩🇪",
                lat: 51.16,
                lon: 10.45,
                ind: "Education"
            },
            {
                name: "UK",
                flag: "🇬🇧",
                lat: 55.37,
                lon: -3.43,
                ind: "Telecommunications"
            },
            {
                name: "Brazil",
                flag: "🇧🇷",
                lat: -14.23,
                lon: -51.92,
                ind: "Education"
            },
            {
                name: "India",
                flag: "🇮🇳",
                lat: 20.59,
                lon: 78.96,
                ind: "Telecommunications"
            },
            {
                name: "Japan",
                flag: "🇯🇵",
                lat: 36.2,
                lon: 138.25,
                ind: "Manufacturing"
            },
            {
                name: "Indonesia",
                flag: "🇮🇩",
                lat: -2.54,
                lon: 118.01,
                ind: "Government"
            },
            {
                name: "Australia",
                flag: "🇦🇺",
                lat: -25.27,
                lon: 133.77,
                ind: "Education"
            },
            {
                name: "Canada",
                flag: "🇨🇦",
                lat: 56.13,
                lon: -106.35,
                ind: "Education"
            },
            {
                name: "France",
                flag: "🇫🇷",
                lat: 46.23,
                lon: 2.21,
                ind: "Government"
            },
            {
                name: "Turkey",
                flag: "🇹🇷",
                lat: 38.96,
                lon: 35.24,
                ind: "Telecommunications"
            },
            {
                name: "Mexico",
                flag: "🇲🇽",
                lat: 23.63,
                lon: -102.55,
                ind: "Education"
            },
            {
                name: "South Africa",
                flag: "🇿🇦",
                lat: -30.56,
                lon: 22.94,
                ind: "Manufacturing"
            }
        ];

        var types = [{
                label: "Malware",
                kind: "malware"
            },
            {
                label: "Phishing",
                kind: "phishing"
            },
            {
                label: "Exploit",
                kind: "exploit"
            },
            {
                label: "Ransomware",
                kind: "malware"
            },
            {
                label: "Brute Force",
                kind: "exploit"
            },
            {
                label: "Botnet",
                kind: "malware"
            }
        ];

        var countryScores = {};
        var industryScores = {};
        var malwareScores = {};

        function bump(obj, key, by) {
            obj[key] = (obj[key] || 0) + by;
        }

        function topEntries(obj, n) {
            var arr = [];
            for (var k in obj) arr.push([k, obj[k]]);
            arr.sort(function(a, b) {
                return b[1] - a[1]
            });
            return arr.slice(0, n);
        }

        function renderTop(listEl, rows, kind) {
            var el = document.getElementById(listEl);
            el.innerHTML = "";
            for (var i = 0; i < rows.length; i++) {
                var name = rows[i][0];
                var score = rows[i][1];
                var left = '<div class="left"><div class="dot" style="background:' + (kind === "country" ?
                        "rgba(255,30,109,.75)" : (kind === "industry" ? "rgba(0,212,255,.75)" : "rgba(245,158,11,.75)")) +
                    '; box-shadow:0 0 0 6px rgba(255,255,255,.06), 0 0 18px ' + (kind === "country" ?
                        "rgba(255,30,109,.22)" : (kind === "industry" ? "rgba(0,212,255,.18)" : "rgba(245,158,11,.18)")) +
                    '"></div>' + (kind === "country" ? ('<div class="flag">' + (name.split(" ")[0] || "") + '</div>') :
                        "") + '<div class="name">' + (kind === "country" ? name.replace(/^[^\s]+\s/, "") : name) +
                    '</div></div>';
                var right = '<div class="meta">' + score.toFixed(1) + '</div>';
                var row = document.createElement("div");
                row.className = "item";
                row.innerHTML = left + right;
                el.appendChild(row)
            }
        }

        function updateRightPanels() {
            var tc = topEntries(countryScores, 5);
            var ti = topEntries(industryScores, 3);
            var tm = topEntries(malwareScores, 3);

            var tc2 = [];
            for (var i = 0; i < tc.length; i++) {
                var label = tc[i][0];
                tc2.push([label, tc[i][1]])
            }
            renderTop("topCountries", tc2, "country");
            renderTop("topIndustries", ti, "industry");
            renderTop("topMalware", tm, "malware");
        }

        function pickTwo() {
            var a = points[Math.floor(Math.random() * points.length)];
            var b = points[Math.floor(Math.random() * points.length)];
            if (a === b) return pickTwo();
            return [a, b]
        }

        function randType() {
            return types[Math.floor(Math.random() * types.length)]
        }

        function fireAttack() {
            var ab = pickTwo();
            var a = ab[0],
                b = ab[1];
            var t = randType();

            smoothArc(a.lat, a.lon, b.lat, b.lon, t.kind);
            addLog(a.name, b.name, t.label);

            attackCount += Math.max(1, Math.round(baseRate * 1.6));
            setCounter();

            bump(countryScores, b.flag + " " + b.name, Math.random() * 2.2 + 0.4);
            bump(industryScores, b.ind, Math.random() * 1.6 + 0.3);
            bump(malwareScores, t.label, Math.random() * 1.8 + 0.4);

            updateRightPanels();
        }

        for (var i0 = 0; i0 < 18; i0++) {
            bump(countryScores, points[Math.floor(Math.random() * points.length)].flag + " " + points[Math.floor(Math
                .random() * points.length)].name, Math.random() * 12 + 2);
            bump(industryScores, ["Education", "Government", "Telecommunications", "Manufacturing"][Math.floor(Math
                .random() * 4)], Math.random() * 8 + 2);
            bump(malwareScores, ["Malware", "Phishing", "Exploit", "Ransomware", "Botnet"][Math.floor(Math.random() * 5)],
                Math.random() * 10 + 2);
        }
        updateRightPanels();

        var timer = null;

        function rebuildTimer() {
            if (timer) clearInterval(timer);
            var interval = Math.max(140, Math.floor(1000 / Math.max(.2, baseRate)));
            timer = setInterval(fireAttack, interval);
        }

        rebuildTimer();

        setInterval(function() {
            attackCount += Math.max(1, Math.round(baseRate));
            setCounter();
        }, 1000);

        d3.select(window).on("resize", function() {
            location.reload()
        });
    </script>
    <script>
        var panel = document.getElementById("countryPanel");
        var flagEl = document.getElementById("cp-flag");
        var nameEl = document.getElementById("cp-name");
        var loadingEl = document.getElementById("cpLoading");

        document.getElementById("cp-close").onclick = function() {
            panel.classList.remove("show");
            setTimeout(function() {
                panel.classList.add("hidden");
            }, 300);
        };

        function randomSeries(n, min, max) {
            var arr = [],
                v = (min + max) / 2;
            for (var i = 0; i < n; i++) {
                v = Math.max(min, Math.min(max, v + (Math.random() - .5) * (max - min) * .3));
                arr.push(v);
            }
            return arr;
        }

        function drawArea(canvas, data) {
            var ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            var w = canvas.width;
            var h = canvas.height;
            var max = Math.max.apply(null, data);

            ctx.beginPath();
            for (var i = 0; i < data.length; i++) {
                var x = i * (w / (data.length - 1));
                var y = h - (data[i] / max) * h;
                i ? ctx.lineTo(x, y) : ctx.moveTo(x, y);
            }
            ctx.strokeStyle = "#ff1e6d";
            ctx.lineWidth = 2;
            ctx.stroke();

            ctx.lineTo(w, h);
            ctx.lineTo(0, h);
            ctx.closePath();
            var grad = ctx.createLinearGradient(0, 0, 0, h);
            grad.addColorStop(0, "rgba(255,30,109,.4)");
            grad.addColorStop(1, "rgba(255,30,109,0)");
            ctx.fillStyle = grad;
            ctx.fill();
        }

        function drawSpark(canvas) {
            var data = randomSeries(24, 1, 10);
            var ctx = canvas.getContext("2d");
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.beginPath();
            for (var i = 0; i < data.length; i++) {
                var x = i * (canvas.width / (data.length - 1));
                var y = canvas.height - (data[i] / 10) * canvas.height;
                i ? ctx.lineTo(x, y) : ctx.moveTo(x, y);
            }
            ctx.strokeStyle = "#ff1e6d";
            ctx.lineWidth = 1.5;
            ctx.stroke();
        }

        map.svg.selectAll(".datamaps-subunit")
            .on("click", function(geo) {
                panel.classList.remove("hidden");
                panel.classList.remove("show");
                loadingEl.style.display = "flex";

                setTimeout(function() {
                    flagEl.textContent = "🏳️";
                    nameEl.textContent = geo.properties.name;

                    drawArea(document.getElementById("cpTrend"), randomSeries(30, 20, 100));
                    document.querySelectorAll(".spark").forEach(drawSpark);

                    loadingEl.style.display = "none";
                    panel.classList.add("show");
                }, 600 + Math.random() * 400);
            });
    </script>

    <script>
        function adjustMapZoom() {
            var mapEl = document.getElementById("container1");
            if (!mapEl) return;

            if (window.innerWidth <= 768) {
                var isPortrait = window.innerHeight > window.innerWidth;
                mapEl.style.transform = isPortrait ?
                    "scale(1.4) translateY(-8%)" :
                    "scale(1.25) translateY(-4%)";
            } else {
                mapEl.style.transform = "none";
            }
        }

        window.addEventListener("resize", adjustMapZoom);
        adjustMapZoom();
    </script>
    <script>
        var svg = map.svg;
        var g = svg.select("g");

        var zoom = d3.behavior.zoom()
            .scaleExtent([1, 4])
            .on("zoom", function() {
                g.attr("transform",
                    "translate(" + d3.event.translate + ") scale(" + d3.event.scale + ")"
                );
            });

        svg.call(zoom);

        document.getElementById("zoomIn").onclick = function() {
            zoom.scale(zoom.scale() * 1.25);
            zoom.event(svg.transition().duration(300));
        };

        document.getElementById("zoomOut").onclick = function() {
            zoom.scale(zoom.scale() / 1.25);
            zoom.event(svg.transition().duration(300));
        };

        document.getElementById("zoomReset").onclick = function() {
            zoom.scale(1);
            zoom.translate([0, 0]);
            zoom.event(svg.transition().duration(400));
        };
    </script>



</body>

</html>
