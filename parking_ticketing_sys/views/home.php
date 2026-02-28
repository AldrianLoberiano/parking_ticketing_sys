<?php
session_start();
require_once '../includes/functions.php';

if (isLoggedIn()) {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            redirect('/tecketing/tecketing/parking_ticketing_sys/views/admin/dashboard.php');
            break;
        case 'guard':
            redirect('/tecketing/tecketing/parking_ticketing_sys/views/guard/dashboard.php');
            break;
        case 'employee':
            redirect('/tecketing/tecketing/parking_ticketing_sys/views/employee/dashboard.php');
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase — Parking Control and Monitoring System</title>
    <meta name="description" content="Modern parking ticketing and management system. Streamline operations with real-time monitoring and automated ticketing.">
    <link rel="icon" type="image/png" href="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --ink: #0f172a;
            --ink-light: #334155;
            --ink-muted: #64748b;
            --ink-faint: #94a3b8;
            --surface: #ffffff;
            --surface-dim: #f8fafc;
            --surface-muted: #f1f5f9;
            --border: #e2e8f0;
            --border-light: #f1f5f9;
            --accent: #2563eb;
            --accent-hover: #1d4ed8;
            --accent-light: rgba(37, 99, 235, 0.08);
            --emerald: #059669;
            --emerald-light: rgba(5, 150, 105, 0.08);
            --orange: #ea580c;
            --orange-light: rgba(234, 88, 12, 0.08);
            --violet: #7c3aed;
            --violet-light: rgba(124, 58, 237, 0.08);
            --rose: #e11d48;
            --rose-light: rgba(225, 29, 72, 0.08);
            --sky: #0284c7;
            --sky-light: rgba(2, 132, 199, 0.08);
            --radius: 14px;
            --radius-sm: 10px;
            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 24px rgba(15, 23, 42, 0.07);
            --shadow-lg: 0 12px 48px rgba(15, 23, 42, 0.1);
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--ink);
            background: var(--surface);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== NAVBAR ===== */
        .hp-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 1.15rem 0;
            transition: all 0.35s ease;
        }

        .hp-nav.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            box-shadow: 0 1px 0 var(--border);
            padding: 0.7rem 0;
        }

        .hp-nav-inner {
            max-width: 1140px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hp-logo {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            text-decoration: none;
            color: var(--ink);
        }

        .hp-logo img {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
        }

        .hp-logo-name {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.025em;
            line-height: 1.2;
        }

        .hp-logo-name small {
            display: block;
            font-size: 0.62rem;
            font-weight: 400;
            color: var(--ink-muted);
            letter-spacing: 0.02em;
        }

        .hp-nav-right {
            display: flex;
            align-items: center;
            gap: 2.5rem;
        }

        .hp-nav-links {
            display: flex;
            align-items: center;
            gap: 1.75rem;
            list-style: none;
            margin: 0;
        }

        .hp-nav-links a {
            text-decoration: none;
            color: var(--ink-muted);
            font-size: 0.84rem;
            font-weight: 500;
            transition: color 0.2s;
            letter-spacing: -0.01em;
        }

        .hp-nav-links a:hover {
            color: var(--ink);
        }

        .btn-nav-signin {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.55rem 1.2rem;
            background: var(--ink);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: -0.01em;
        }

        .btn-nav-signin:hover {
            background: var(--ink-light);
            color: #fff;
        }

        /* ===== HERO ===== */
        .hp-hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 8rem 2rem 5rem;
            position: relative;
            overflow: hidden;
            background: #ffffff;
        }

        /* Light Veil canvas */
        #veilCanvas {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        /* Light hero overlay for readability */
        .hero-overlay {
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 55% 60% at 30% 50%, rgba(255, 255, 255, 0.0) 0%, rgba(255, 255, 255, 0.3) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .hp-hero-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
            gap: 5rem;
            align-items: center;
            position: relative;
            z-index: 2;
            width: 100%;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: var(--accent-light);
            color: var(--accent);
            border: 1px solid rgba(37, 99, 235, 0.15);
            padding: 0.4rem 0.95rem;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1.75rem;
        }

        .hero-tag i {
            font-size: 0.65rem;
        }

        .hp-hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -0.04em;
            color: #000000;
            margin-bottom: 1.35rem;
        }

        .hp-hero h1 .accent-word {
            color: #0284c7;
            text-decoration: none;
        }

        .hero-desc {
            font-size: 1.08rem;
            line-height: 1.75;
            color: var(--ink-muted);
            margin-bottom: 2.5rem;
            max-width: 460px;
        }

        .hero-btns {
            display: flex;
            gap: 0.85rem;
            align-items: center;
            margin-bottom: 3rem;
        }

        .btn-primary-hero {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.8rem 1.65rem;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            letter-spacing: -0.01em;
        }

        .btn-primary-hero:hover {
            background: var(--accent-hover);
            color: #fff;
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-outline-hero {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.8rem 1.65rem;
            background: transparent;
            color: var(--ink-light);
            border: 1.5px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-outline-hero:hover {
            border-color: var(--ink-light);
            color: var(--ink);
        }

        /* Trusted By */
        .hero-trust {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .trust-avatars {
            display: flex;
        }

        .trust-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: 2px solid #fff;
            margin-left: -8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.6rem;
            font-weight: 700;
            color: #fff;
        }

        .trust-avatar:first-child {
            margin-left: 0;
        }

        .trust-avatar.a1 {
            background: var(--accent);
        }

        .trust-avatar.a2 {
            background: var(--emerald);
        }

        .trust-avatar.a3 {
            background: var(--orange);
        }

        .trust-avatar.a4 {
            background: var(--violet);
        }

        .trust-text {
            font-size: 0.76rem;
            color: var(--ink-muted);
            line-height: 1.4;
        }

        .trust-text strong {
            color: var(--ink-light);
            font-weight: 600;
        }

        /* Hero Preview Card */
        .hero-visual {
            position: relative;
        }

        .preview-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.75rem;
            box-shadow: var(--shadow-lg);
            position: relative;
        }

        .preview-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.35rem;
        }

        .preview-dots {
            display: flex;
            gap: 6px;
        }

        .preview-dots span {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .preview-dots .d-r {
            background: #ff5f57;
        }

        .preview-dots .d-y {
            background: #febc2e;
        }

        .preview-dots .d-g {
            background: #28c840;
        }

        .preview-title {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--ink-muted);
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
            margin-bottom: 1.15rem;
        }

        .p-stat {
            background: var(--surface-dim);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.65rem;
        }

        .p-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .p-stat-icon.ic-blue {
            background: var(--accent-light);
            color: var(--accent);
        }

        .p-stat-icon.ic-green {
            background: var(--emerald-light);
            color: var(--emerald);
        }

        .p-stat-icon.ic-orange {
            background: var(--orange-light);
            color: var(--orange);
        }

        .p-stat-icon.ic-violet {
            background: var(--violet-light);
            color: var(--violet);
        }

        .p-stat-num {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--ink);
            line-height: 1;
        }

        .p-stat-lbl {
            font-size: 0.62rem;
            color: var(--ink-faint);
            margin-top: 2px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .preview-activity {
            background: var(--surface-dim);
            border: 1px solid var(--border-light);
            border-radius: var(--radius-sm);
            padding: 0.9rem;
        }

        .pa-title {
            font-size: 0.68rem;
            font-weight: 600;
            color: var(--ink-light);
            margin-bottom: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .pa-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.45rem 0;
            border-bottom: 1px solid var(--border-light);
        }

        .pa-row:last-child {
            border-bottom: none;
        }

        .pa-left {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pa-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .pa-dot.active {
            background: var(--orange);
        }

        .pa-dot.done {
            background: var(--emerald);
        }

        .pa-plate {
            font-size: 0.72rem;
            font-weight: 600;
            color: var(--ink);
        }

        .pa-time {
            font-size: 0.62rem;
            color: var(--ink-faint);
        }

        /* floating badge */
        .float-badge {
            position: absolute;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 0.65rem 1rem;
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            gap: 0.55rem;
            animation: floatBadge 4s ease-in-out infinite;
        }

        .float-badge.fb-1 {
            top: -12px;
            right: -16px;
        }

        .float-badge.fb-2 {
            bottom: -14px;
            left: -16px;
        }

        .fb-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        .fb-icon.green {
            background: var(--emerald-light);
            color: var(--emerald);
        }

        .fb-icon.blue {
            background: var(--accent-light);
            color: var(--accent);
        }

        .fb-text {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.35;
        }

        .fb-text small {
            display: block;
            font-weight: 400;
            color: var(--ink-faint);
            font-size: 0.6rem;
        }

        @keyframes floatBadge {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }

        /* ===== FEATURES ===== */
        .hp-features {
            padding: 7rem 2rem;
            background: var(--surface-dim);
            border-top: 1px solid var(--border-light);
        }

        .section-wrap {
            max-width: 1140px;
            margin: 0 auto;
        }

        .section-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--accent);
            margin-bottom: 0.75rem;
        }

        .section-heading {
            font-size: 2.15rem;
            font-weight: 800;
            letter-spacing: -0.035em;
            color: var(--ink);
            margin-bottom: 0.5rem;
            line-height: 1.15;
        }

        .section-sub {
            color: var(--ink-muted);
            font-size: 0.95rem;
            max-width: 480px;
            margin-bottom: 3.5rem;
            line-height: 1.65;
        }

        .feat-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        .feat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2rem 1.75rem;
            transition: box-shadow 0.25s ease;
        }

        .feat-card:hover {
            box-shadow: var(--shadow-md);
        }

        .feat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            margin-bottom: 1.35rem;
        }

        .feat-icon.fc-blue {
            background: var(--accent-light);
            color: var(--accent);
        }

        .feat-icon.fc-green {
            background: var(--emerald-light);
            color: var(--emerald);
        }

        .feat-icon.fc-orange {
            background: var(--orange-light);
            color: var(--orange);
        }

        .feat-icon.fc-sky {
            background: var(--sky-light);
            color: var(--sky);
        }

        .feat-icon.fc-violet {
            background: var(--violet-light);
            color: var(--violet);
        }

        .feat-icon.fc-rose {
            background: var(--rose-light);
            color: var(--rose);
        }

        .feat-card h3 {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 0.5rem;
            letter-spacing: -0.01em;
        }

        .feat-card p {
            font-size: 0.84rem;
            color: var(--ink-muted);
            line-height: 1.65;
            margin: 0;
        }

        /* ===== ROLES ===== */
        .hp-roles {
            padding: 7rem 2rem;
            border-top: 1px solid var(--border-light);
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
        }

        .role-card {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 2.25rem 1.75rem;
            text-align: center;
            background: var(--surface);
            transition: box-shadow 0.25s ease;
        }

        .role-card:hover {
            box-shadow: var(--shadow-md);
        }

        .role-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            margin: 0 auto 1.35rem;
        }

        .role-icon.ri-admin {
            background: var(--accent-light);
            color: var(--accent);
        }

        .role-icon.ri-guard {
            background: var(--emerald-light);
            color: var(--emerald);
        }

        .role-icon.ri-emp {
            background: var(--orange-light);
            color: var(--orange);
        }

        .role-card h3 {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 0.55rem;
            color: var(--ink);
        }

        .role-card p {
            font-size: 0.84rem;
            color: var(--ink-muted);
            line-height: 1.65;
            margin: 0;
        }

        /* ===== STATS BANNER ===== */
        .hp-stats {
            padding: 4rem 2rem;
            background: var(--ink);
        }

        .stats-inner {
            max-width: 1140px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
            text-align: center;
        }

        .stat-item h3 {
            font-size: 2.4rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            margin-bottom: 0.3rem;
        }

        .stat-item p {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-weight: 500;
        }

        /* ===== CTA ===== */
        .hp-cta {
            padding: 6rem 2rem;
            text-align: center;
            background: var(--surface-dim);
            border-top: 1px solid var(--border-light);
        }

        .cta-box {
            max-width: 580px;
            margin: 0 auto;
        }

        .hp-cta h2 {
            font-size: 2.15rem;
            font-weight: 800;
            color: var(--ink);
            margin-bottom: 0.75rem;
            letter-spacing: -0.03em;
        }

        .hp-cta p {
            color: var(--ink-muted);
            font-size: 1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-cta {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            padding: 0.85rem 2.25rem;
            background: var(--ink);
            color: #fff;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-cta:hover {
            background: var(--ink-light);
            color: #fff;
            box-shadow: var(--shadow-md);
        }

        /* ===== FOOTER ===== */
        .hp-footer {
            padding: 1.75rem 2rem;
            text-align: center;
            color: var(--ink-faint);
            font-size: 0.75rem;
            border-top: 1px solid var(--border);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 991px) {
            .hp-hero-inner {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 3rem;
            }

            .hp-hero h1 {
                font-size: 2.6rem;
            }

            .hero-desc {
                margin-left: auto;
                margin-right: auto;
            }

            .hero-btns {
                justify-content: center;
            }

            .hero-trust {
                justify-content: center;
            }

            .hero-visual {
                display: none;
            }

            .feat-grid,
            .roles-grid {
                grid-template-columns: 1fr 1fr;
            }

            .stats-inner {
                grid-template-columns: repeat(2, 1fr);
            }

            .hp-nav-links {
                display: none;
            }
        }

        @media (max-width: 576px) {
            .hp-hero h1 {
                font-size: 2rem;
            }

            .feat-grid,
            .roles-grid {
                grid-template-columns: 1fr;
            }

            .stats-inner {
                grid-template-columns: 1fr 1fr;
                gap: 1.5rem;
            }

            .hero-btns {
                flex-direction: column;
            }

            .section-heading {
                font-size: 1.65rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="hp-nav" id="hpNav">
        <div class="hp-nav-inner">
            <a href="#" class="hp-logo">
                <img src="/tecketing/tecketing/parking_ticketing_sys/public/images/logo.png" alt="Logo">
                <div class="hp-logo-name">ParkEase<small>Parking Control and Monitoring</small></div>
            </a>
            <div class="hp-nav-right">
                <ul class="hp-nav-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#roles">Roles</a></li>
                    <li><a href="#stats">About</a></li>
                </ul>
                <a href="login.php" class="btn-nav-signin"><i class="fas fa-arrow-right"></i> Sign In</a>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hp-hero">
        <canvas id="veilCanvas"></canvas>
        <div class="hero-overlay"></div>
        <div class="hp-hero-inner">
            <div>
                <div class="hero-tag"><i class="fas fa-bolt"></i> Modern Parking Management</div>
                <h1>ParkEase<br><span class="accent-word">Parking Control and Monitoring</span><br>System</h1>
                <p class="hero-desc">
                    Easily manage every aspect of your parking workflow, from vehicle check-in to checkout, with real-time slot monitoring and role-based dashboards.
                </p>
                <div class="hero-btns">
                    <a href="login.php" class="btn-primary-hero"><i class="fas fa-arrow-right"></i> Get Started</a>
                    <a href="#features" class="btn-outline-hero"><i class="fas fa-grid-2"></i> Explore Features</a>
                </div>
                <div class="hero-trust">
                    <div class="trust-avatars">
                        <div class="trust-avatar a1">A</div>
                        <div class="trust-avatar a2">G</div>
                        <div class="trust-avatar a3">E</div>
                        <div class="trust-avatar a4">+</div>
                    </div>
                    <div class="trust-text"><strong>Multi‑role</strong> access for<br>Admins, Guards & Employees</div>
                </div>
            </div>

            <!-- Dashboard Preview -->
            <div class="hero-visual">
                <div class="preview-card">
                    <div class="preview-header">
                        <div class="preview-dots"><span class="d-r"></span><span class="d-y"></span><span class="d-g"></span></div>
                        <div class="preview-title">Dashboard</div>
                    </div>
                    <div class="preview-grid">
                        <div class="p-stat">
                            <div class="p-stat-icon ic-blue"><i class="fas fa-users"></i></div>
                            <div>
                                <div class="p-stat-num">124</div>
                                <div class="p-stat-lbl">Users</div>
                            </div>
                        </div>
                        <div class="p-stat">
                            <div class="p-stat-icon ic-green"><i class="fas fa-car"></i></div>
                            <div>
                                <div class="p-stat-num">89</div>
                                <div class="p-stat-lbl">Vehicles</div>
                            </div>
                        </div>
                        <div class="p-stat">
                            <div class="p-stat-icon ic-orange"><i class="fas fa-ticket-alt"></i></div>
                            <div>
                                <div class="p-stat-num">37</div>
                                <div class="p-stat-lbl">Active</div>
                            </div>
                        </div>
                        <div class="p-stat">
                            <div class="p-stat-icon ic-violet"><i class="fas fa-parking"></i></div>
                            <div>
                                <div class="p-stat-num">52</div>
                                <div class="p-stat-lbl">Available</div>
                            </div>
                        </div>
                    </div>
                    <div class="preview-activity">
                        <div class="pa-title">Recent Activity</div>
                        <div class="pa-row">
                            <div class="pa-left"><span class="pa-dot active"></span><span class="pa-plate">ABC-1234</span></div>
                            <span class="pa-time">2 min ago</span>
                        </div>
                        <div class="pa-row">
                            <div class="pa-left"><span class="pa-dot done"></span><span class="pa-plate">XYZ-5678</span></div>
                            <span class="pa-time">15 min ago</span>
                        </div>
                        <div class="pa-row">
                            <div class="pa-left"><span class="pa-dot done"></span><span class="pa-plate">DEF-9012</span></div>
                            <span class="pa-time">32 min ago</span>
                        </div>
                    </div>
                </div>
                <div class="float-badge fb-1">
                    <div class="fb-icon green"><i class="fas fa-check"></i></div>
                    <div class="fb-text">Slot Available<small>Zone A · Slot 14</small></div>
                </div>
                <div class="float-badge fb-2" style="animation-delay: -2s;">
                    <div class="fb-icon blue"><i class="fas fa-car"></i></div>
                    <div class="fb-text">New Check‑in<small>ABC-1234 · Just now</small></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="hp-features" id="features">
        <div class="section-wrap">
            <div class="section-eyebrow"><i class="fas fa-cubes"></i> Features</div>
            <h2 class="section-heading">Everything you need to<br>manage parking</h2>
            <p class="section-sub">From check‑in to checkout, our platform covers the full parking workflow with powerful, intuitive tools.</p>

            <div class="feat-grid">
                <div class="feat-card">
                    <div class="feat-icon fc-blue"><i class="fas fa-ticket-alt"></i></div>
                    <h3>Automated Ticketing</h3>
                    <p>Issue and manage parking tickets instantly with automatic check‑in / check‑out time tracking and status updates.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon fc-green"><i class="fas fa-map-marked-alt"></i></div>
                    <h3>Area & Slot Management</h3>
                    <p>Organize your facility into zones and slots. Monitor occupancy and quickly find available spaces.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon fc-orange"><i class="fas fa-users-cog"></i></div>
                    <h3>Multi‑Role Access</h3>
                    <p>Separate dashboards and permissions for Admin, Guard, and Employee — each tailored to their workflow.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon fc-sky"><i class="fas fa-car-side"></i></div>
                    <h3>Vehicle Registry</h3>
                    <p>Maintain a complete vehicle database with plates, models, colors, and ownership linked to user accounts.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon fc-violet"><i class="fas fa-database"></i></div>
                    <h3>Backup & Recovery</h3>
                    <p>One‑click database backups and easy restore to safeguard your data and ensure business continuity.</p>
                </div>
                <div class="feat-card">
                    <div class="feat-icon fc-rose"><i class="fas fa-shield-alt"></i></div>
                    <h3>Secure Auth</h3>
                    <p>Role‑based access control with secure session management to protect sensitive operations and data.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Banner -->
    <section class="hp-stats" id="stats">
        <div class="stats-inner">
            <div class="stat-item">
                <h3>3</h3>
                <p>User Roles</p>
            </div>
            <div class="stat-item">
                <h3>∞</h3>
                <p>Parking Zones</p>
            </div>
            <div class="stat-item">
                <h3>24/7</h3>
                <p>Monitoring</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Secure</p>
            </div>
        </div>
    </section>

    <!-- Roles -->
    <section class="hp-roles" id="roles">
        <div class="section-wrap">
            <div class="section-eyebrow"><i class="fas fa-user-shield"></i> Roles</div>
            <h2 class="section-heading">Built for every team member</h2>
            <p class="section-sub">Each role gets a purpose‑built dashboard designed for their daily tasks and responsibilities.</p>

            <div class="roles-grid">
                <div class="role-card">
                    <div class="role-icon ri-admin"><i class="fas fa-user-tie"></i></div>
                    <h3>Administrator</h3>
                    <p>Full system control — manage users, vehicles, areas, slots, and backups from a central command dashboard.</p>
                </div>
                <div class="role-card">
                    <div class="role-icon ri-guard"><i class="fas fa-shield-alt"></i></div>
                    <h3>Guard</h3>
                    <p>Handle vehicle check‑ins and check‑outs at gates. View active tickets and manage parking flow in real time.</p>
                </div>
                <div class="role-card">
                    <div class="role-icon ri-emp"><i class="fas fa-user"></i></div>
                    <h3>Employee</h3>
                    <p>Register vehicles, view personal tickets and parking history. Simple self‑service for daily parkers.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="hp-cta">
        <div class="cta-box">
            <h2>Ready to get started?</h2>
            <p>Sign in to begin managing your parking facility with a modern, streamlined system.</p>
            <a href="login.php" class="btn-cta"><i class="fas fa-sign-in-alt"></i> Sign In to Dashboard</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="hp-footer">
        <p>&copy; <?php echo date('Y'); ?> ParkEase — Parking Control and Monitoring System</p>
    </footer>

    <script>
        // ============================================================
        // BACKGROUND EFFECT — interactive particles + dot grid + aura blobs
        // Mouse cursor: particles flee, dots brighten, ripple on move
        // ============================================================
        (function() {
            const canvas = document.getElementById('veilCanvas');
            if (!canvas) return;
            const hero = canvas.closest('.hp-hero');
            const ctx = canvas.getContext('2d');

            let W, H;

            function resize() {
                W = canvas.width = hero.offsetWidth;
                H = canvas.height = hero.offsetHeight;
                initGrid();
            }

            // --- Mouse tracking ---
            let mouse = {
                x: -999,
                y: -999
            };
            let ripples = [];
            hero.addEventListener('mousemove', e => {
                const rect = canvas.getBoundingClientRect();
                mouse.x = e.clientX - rect.left;
                mouse.y = e.clientY - rect.top;
            });
            hero.addEventListener('mouseleave', () => {
                mouse.x = -999;
                mouse.y = -999;
            });
            hero.addEventListener('click', e => {
                const rect = canvas.getBoundingClientRect();
                ripples.push({
                    x: e.clientX - rect.left,
                    y: e.clientY - rect.top,
                    r: 0,
                    alpha: 0.5
                });
            });

            // --- Dot grid ---
            const CELL = 52;
            let dots = [];

            function initGrid() {
                dots = [];
                const cols = Math.ceil(W / CELL) + 1;
                const rows = Math.ceil(H / CELL) + 1;
                for (let r = 0; r < rows; r++) {
                    for (let c = 0; c < cols; c++) {
                        dots.push({
                            x: c * CELL,
                            y: r * CELL,
                            phase: Math.random() * Math.PI * 2
                        });
                    }
                }
            }

            // --- Floating particles ---
            const NUM_PARTICLES = 32;
            const particles = Array.from({
                length: NUM_PARTICLES
            }, () => ({
                x: Math.random() * 1200,
                y: Math.random() * 800,
                r: 2.5 + Math.random() * 4,
                vx: (Math.random() - 0.5) * 0.45,
                vy: (Math.random() - 0.5) * 0.45,
                baseVx: 0,
                baseVy: 0,
                opacity: 0.10 + Math.random() * 0.20,
                col: [Math.random() < 0.5 ? 99 : 37, Math.random() < 0.5 ? 102 : 99, Math.random() < 0.5 ? 241 : 235],
            }));
            // Store base velocities
            particles.forEach(p => {
                p.baseVx = p.vx;
                p.baseVy = p.vy;
            });

            // --- Aura blobs ---
            const auras = [{
                    rx: 0.15,
                    ry: 0.30,
                    r: 0.45,
                    a: 0.0018,
                    ph: 0.0,
                    col: [219, 234, 254]
                },
                {
                    rx: 0.80,
                    ry: 0.65,
                    r: 0.40,
                    a: 0.0022,
                    ph: 2.1,
                    col: [237, 233, 254]
                },
                {
                    rx: 0.50,
                    ry: 0.80,
                    r: 0.35,
                    a: 0.0014,
                    ph: 4.2,
                    col: [204, 251, 241]
                },
            ];

            let t = 0;

            function draw() {
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, W, H);

                // 1. Aura blobs
                auras.forEach(a => {
                    const cx = (a.rx + Math.sin(t * a.a + a.ph) * 0.12) * W;
                    const cy = (a.ry + Math.cos(t * a.a + a.ph + 1) * 0.10) * H;
                    const radius = a.r * Math.max(W, H);
                    const [r, g, b] = a.col;
                    const g2 = ctx.createRadialGradient(cx, cy, 0, cx, cy, radius);
                    g2.addColorStop(0, `rgba(${r},${g},${b},0.55)`);
                    g2.addColorStop(0.5, `rgba(${r},${g},${b},0.18)`);
                    g2.addColorStop(1, `rgba(${r},${g},${b},0)`);
                    ctx.fillStyle = g2;
                    ctx.fillRect(0, 0, W, H);
                });

                // 2. Mouse cursor glow
                if (mouse.x > 0) {
                    const mg = ctx.createRadialGradient(mouse.x, mouse.y, 0, mouse.x, mouse.y, 110);
                    mg.addColorStop(0, 'rgba(99,102,241,0.12)');
                    mg.addColorStop(0.5, 'rgba(99,102,241,0.05)');
                    mg.addColorStop(1, 'rgba(99,102,241,0)');
                    ctx.fillStyle = mg;
                    ctx.fillRect(0, 0, W, H);
                }

                // 3. Click ripples
                ripples = ripples.filter(rp => rp.alpha > 0.01);
                ripples.forEach(rp => {
                    ctx.save();
                    ctx.globalAlpha = rp.alpha;
                    ctx.strokeStyle = 'rgba(99,102,241,1)';
                    ctx.lineWidth = 1.5;
                    ctx.beginPath();
                    ctx.arc(rp.x, rp.y, rp.r, 0, Math.PI * 2);
                    ctx.stroke();
                    ctx.restore();
                    rp.r += 3.5;
                    rp.alpha *= 0.93;
                });

                // 4. Dot grid (brighten near cursor)
                dots.forEach(d => {
                    const mdx = d.x - mouse.x,
                        mdy = d.y - mouse.y;
                    const mdist = Math.sqrt(mdx * mdx + mdy * mdy);
                    const proximity = Math.max(0, 1 - mdist / 100);
                    const pulse = 0.5 + 0.5 * Math.sin(t * 0.018 + d.phase);
                    const alpha = 0.06 + pulse * 0.10 + proximity * 0.30;
                    const rad = 1.5 + pulse * 1.0 + proximity * 2.5;
                    ctx.beginPath();
                    ctx.arc(d.x, d.y, rad, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(99,102,241,${Math.min(alpha, 0.5)})`;
                    ctx.fill();
                });

                // 5. Particles — flee from cursor
                const FLEE_RADIUS = 120;
                const FLEE_FORCE = 2.5;
                particles.forEach(p => {
                    const dx = p.x - mouse.x;
                    const dy = p.y - mouse.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < FLEE_RADIUS && dist > 0) {
                        const force = (1 - dist / FLEE_RADIUS) * FLEE_FORCE;
                        p.vx += (dx / dist) * force * 0.08;
                        p.vy += (dy / dist) * force * 0.08;
                    }
                    // Dampen back to base velocity
                    p.vx += (p.baseVx - p.vx) * 0.04;
                    p.vy += (p.baseVy - p.vy) * 0.04;
                    // Speed cap
                    const spd = Math.sqrt(p.vx * p.vx + p.vy * p.vy);
                    if (spd > 3) {
                        p.vx = p.vx / spd * 3;
                        p.vy = p.vy / spd * 3;
                    }

                    p.x += p.vx;
                    p.y += p.vy;
                    if (p.x < -20) p.x = W + 20;
                    if (p.x > W + 20) p.x = -20;
                    if (p.y < -20) p.y = H + 20;
                    if (p.y > H + 20) p.y = -20;

                    const [r, g, b] = p.col;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${r},${g},${b},${p.opacity})`;
                    ctx.fill();
                });

                // 6. Connecting lines between close particles
                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 130) {
                            ctx.save();
                            ctx.globalAlpha = (1 - dist / 130) * 0.07;
                            ctx.strokeStyle = 'rgba(99,102,241,1)';
                            ctx.lineWidth = 0.8;
                            ctx.beginPath();
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                            ctx.restore();
                        }
                    }
                }

                t++;
                requestAnimationFrame(draw);
            }

            resize();
            particles.forEach(p => {
                p.x = Math.random() * W;
                p.y = Math.random() * H;
            });
            window.addEventListener('resize', resize);
            draw();
        })();

        // Navbar scroll glass effect
        const nav = document.getElementById('hpNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 40);
        });

        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const t = document.querySelector(a.getAttribute('href'));
                if (t) t.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });
    </script>
</body>

</html>