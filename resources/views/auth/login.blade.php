<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | ProfitNiti Lite — AI Financial Insights</title>

  <!-- ── Public CDN replacements for the FRMS-hosted assets ── -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css">

  <!-- ── Google Fonts ── -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
  /* ============================================================
     DESIGN TOKENS
  ============================================================ */
  :root {
    --pn-navy:          #060d1e;
    --pn-navy-mid:      #0b1730;
    --pn-navy-deep:     #0c1d44;
    --pn-blue:          #1a5cff;
    --pn-blue-dark:     #0044cc;
    --pn-blue-glow:     rgba(26, 92, 255, 0.08);
    --pn-green:         #00d68f;
    --pn-text:          #0f172a;
    --pn-muted:         #64748b;
    --pn-hint:          #94a3b8;
    --pn-border:        #e2e8f0;
    --pn-surface:       #f8fafc;
    --pn-white:         #ffffff;
    --pn-shadow-btn:    0 4px 18px rgba(26,92,255,0.32);
    --pn-shadow-btn-hv: 0 8px 26px rgba(26,92,255,0.44);
  }

  /* ============================================================
     RESET & BASE
  ============================================================ */
  *, *::before, *::after { box-sizing: border-box; }

  body {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    background: var(--pn-navy) !important;
    margin: 0;
  }

  /* ============================================================
     OUTER SHELL — section.auth
  ============================================================ */
  section.auth {
    min-height: 100vh;
    display: flex !important;
    flex-wrap: nowrap !important;
    align-items: stretch;
    overflow: hidden;
  }

  /* ============================================================
     LEFT PANEL — .auth-left
  ============================================================ */
  .auth-left {
    flex: 0 0 60% !important;
    width: 60% !important;
    background: linear-gradient(160deg,
      var(--pn-navy) 0%,
      var(--pn-navy-mid) 50%,
      var(--pn-navy-deep) 100%
    ) !important;
    position: relative;
    overflow: hidden;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
  }

  /* Dot-grid texture */
  .auth-left::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.055) 1px, transparent 1px);
    background-size: 30px 30px;
    pointer-events: none;
    z-index: 0;
  }

  /* Ambient glow orbs */
  .auth-left::after {
    content: '';
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 55% 45% at 20% 25%, rgba(26,92,255,0.18) 0%, transparent 70%),
      radial-gradient(ellipse 50% 40% at 80% 75%, rgba(0,214,143,0.11) 0%, transparent 65%),
      radial-gradient(ellipse 40% 30% at 60% 20%, rgba(26,92,255,0.06) 0%, transparent 60%);
    pointer-events: none;
    z-index: 0;
  }

  /* Inner centering wrapper */
  .auth-left > div {
    position: relative;
    z-index: 1;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 48px 52px;
    gap: 0;
  }

  /* Hide the broken <img> tag gracefully */
  .auth-left img {
    display: none !important;
  }

  /* ── Illustrated content injected via the wrapper div ──
     Since we cannot change HTML tags, we use ::before on the
     inner centering div to render a headline + visual block  */
  .auth-left .d-flex.align-items-center.flex-column {
    width: 100%;
    max-width: 480px;
  }

  /* ── Brand badge (top of left panel) ── */
  .auth-left .d-flex.align-items-center.flex-column::before {
    content: '';
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    inset: 0;
    pointer-events: none;
  }

  /* ============================================================
     LEFT PANEL ILLUSTRATED CONTENT
     Injected entirely via CSS — no HTML changes
  ============================================================ */

  /* Brand pill */
  .auth-left-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    position: absolute;
    top: 40px;
    left: 44px;
    z-index: 3;
    animation: pn-fadeUp 0.6s ease both;
  }

  /* Heading block */
  .auth-left-copy {
    position: absolute;
    top: 50%;
    left: 44px;
    right: 44px;
    transform: translateY(-58%);
    z-index: 3;
    animation: pn-fadeUp 0.6s 0.1s ease both;
  }

  /* Stats row at bottom */
  .auth-left-stats {
    position: absolute;
    bottom: 36px;
    left: 44px;
    right: 44px;
    z-index: 3;
    animation: pn-fadeUp 0.6s 0.3s ease both;
  }

  /* ============================================================
     RIGHT PANEL — .auth-right
  ============================================================ */
  .auth-right {
    flex: 0 0 40% !important;
    width: 40% !important;
    min-width: 0 !important;
    background: var(--pn-white) !important;
    padding: 52px 48px !important;
    overflow-y: auto;
    display: flex !important;
    flex-direction: column !important;
    justify-content: center !important;
    position: relative;
  }

  /* Subtle corner glow */
  .auth-right::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(26,92,255,0.04) 0%, transparent 70%);
    pointer-events: none;
    z-index: 0;
  }

  /* Inner form container */
  .auth-right .max-w-464-px {
    max-width: 360px !important;
    margin: 0 auto;
    width: 100%;
    position: relative;
    z-index: 1;
    animation: pn-fadeUp 0.5s 0.2s ease both;
  }

  /* ── Logo area ── */
  .auth-right a.mb-40 {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 28px !important;
    text-decoration: none !important;
  }

  /* Show actual brand logo on right panel */
  .auth-right a.mb-40 img {
    display: block !important;
    content: url("assets/images/logo.png");
    height: 52px;
    width: auto;
    object-fit: contain;
  }

  .auth-right a.mb-40::before { display: none; }
  .auth-right a.mb-40::after  { display: none; }

  /* ── Heading ── */
  .auth-right h4.mb-12 {
    font-family: 'DM Serif Display', serif !important;
    font-size: 28px !important;
    font-weight: 700 !important;
    color: var(--pn-text) !important;
    letter-spacing: -0.02em !important;
    line-height: 1.15 !important;
    margin-bottom: 6px !important;
  }

  /* ── Sub-heading ── */
  .auth-right p.mb-32,
  .text-secondary-light {
    font-size: 13.5px !important;
    color: var(--pn-muted) !important;
    line-height: 1.55 !important;
    margin-bottom: 28px !important;
  }

  /* ============================================================
     FORM FIELDS
  ============================================================ */

  .icon-field {
    position: relative !important;
    margin-bottom: 13px !important;
  }

  .icon-field .icon {
    position: absolute !important;
    left: 14px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    z-index: 2;
    display: flex;
    align-items: center;
    color: var(--pn-hint) !important;
    font-size: 17px;
    transition: color 0.15s;
    pointer-events: none;
  }

  .icon-field:focus-within .icon {
    color: var(--pn-blue) !important;
  }

  .form-control,
  .form-control.h-56-px,
  .form-control.bg-neutral-50,
  .form-control.radius-12 {
    width: 100% !important;
    height: 48px !important;
    padding: 0 42px 0 44px !important;
    border: 1.5px solid var(--pn-border) !important;
    border-radius: 11px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 13.5px !important;
    color: var(--pn-text) !important;
    background: var(--pn-surface) !important;
    outline: none !important;
    box-shadow: none !important;
    transition: border-color 0.15s, background 0.15s, box-shadow 0.15s !important;
    -webkit-appearance: none;
  }

  .form-control::placeholder { color: var(--pn-hint) !important; }

  .form-control:hover {
    border-color: #cbd5e1 !important;
    background: var(--pn-white) !important;
  }


  .form-control:focus {
    border-color: var(--pn-blue) !important;
    background: var(--pn-white) !important;
    box-shadow: 0 0 0 3px var(--pn-blue-glow) !important;
  }

  .position-relative.mb-20 {
    position: relative !important;
    margin-bottom: 14px !important;
  }

  .toggle-password {
    position: absolute !important;
    right: 13px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    cursor: pointer !important;
    color: var(--pn-hint) !important;
    font-size: 17px !important;
    z-index: 3;
    transition: color 0.15s !important;
    line-height: 1;
  }

  .toggle-password:hover { color: var(--pn-text) !important; }

  /* ============================================================
     REMEMBER ME + FORGOT PASSWORD
  ============================================================ */
  .auth-right .d-flex.justify-content-between {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
  }

  .form-check.style-check {
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
    padding-left: 0 !important;
  }

  .form-check-input {
    width: 17px !important;
    height: 17px !important;
    border: 1.5px solid var(--pn-border) !important;
    border-radius: 4px !important;
    background-color: white !important;
    cursor: pointer !important;
    flex-shrink: 0;
    margin: 0 !important;
    box-shadow: none !important;
    transition: background-color 0.15s, border-color 0.15s !important;
    -webkit-appearance: none;
    appearance: none;
    position: relative;
  }

  .form-check-input:checked {
    background-color: var(--pn-blue) !important;
    border-color: var(--pn-blue) !important;
  }

  .form-check-input:checked::after {
    content: '';
    position: absolute;
    left: 3px; top: 1px;
    width: 9px; height: 6px;
    border-left: 2px solid white;
    border-bottom: 2px solid white;
    transform: rotate(-45deg);
  }

  .form-check-input:focus {
    box-shadow: 0 0 0 3px var(--pn-blue-glow) !important;
    border-color: var(--pn-blue) !important;
  }

  .form-check-label {
    font-size: 12.5px !important;
    font-weight: 500 !important;
    color: var(--pn-muted) !important;
    cursor: pointer;
    margin: 0 !important;
    user-select: none;
  }

  .text-primary-600,
  a.text-primary-600 {
    font-size: 12.5px !important;
    font-weight: 700 !important;
    color: var(--pn-blue) !important;
    text-decoration: none !important;
    transition: opacity 0.15s !important;
  }

  .text-primary-600:hover { opacity: 0.75 !important; }

  /* ============================================================
     SUBMIT BUTTON
  ============================================================ */
  .btn.btn-primary,
  button.btn-primary {
    width: 100% !important;
    height: 50px !important;
    padding: 0 !important;
    margin-top: 24px !important;
    background: linear-gradient(135deg, var(--pn-blue), var(--pn-blue-dark)) !important;
    border: none !important;
    border-radius: 12px !important;
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    font-size: 14.5px !important;
    font-weight: 700 !important;
    color: var(--pn-white) !important;
    letter-spacing: -0.01em !important;
    cursor: pointer !important;
    transition: transform 0.2s, box-shadow 0.2s !important;
    box-shadow: var(--pn-shadow-btn) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    position: relative;
    overflow: hidden;
  }

  .btn.btn-primary::before,
  button.btn-primary::before {
    content: '';
    position: absolute;
    top: 0; left: -100%; width: 60%; height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.12), transparent);
    transition: left 0.45s ease;
  }

  .btn.btn-primary:hover,
  button.btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: var(--pn-shadow-btn-hv) !important;
    color: var(--pn-white) !important;
  }

  .btn.btn-primary:hover::before { left: 150%; }
  .btn.btn-primary:active { transform: translateY(0) !important; }

  /* ============================================================
     SPACING OVERRIDES
  ============================================================ */
  .auth-right .mb-16 { margin-bottom: 13px !important; }
  .auth-right .mb-20 { margin-bottom: 14px !important; }
  .auth-right .mb-32 { margin-bottom: 24px !important; }
  .auth-right .mb-40 { margin-bottom: 28px !important; }
  .auth-right .mt-32 { margin-top: 0 !important; }

  /* ============================================================
     LEFT PANEL — ILLUSTRATED OVERLAY COMPONENTS
     Built entirely in CSS/HTML added below the original section
  ============================================================ */

  /* Brand + headline + stats are appended OUTSIDE auth-left
     so we can position them absolutely over the panel.
     See the <div id="left-overlay"> block below */

  #left-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 60%;
    height: 100%;
    pointer-events: none;
    z-index: 10;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 40px 44px 40px;
  }

  /* ── Brand row ── */
  .ov-brand {
    display: flex;
    align-items: center;
    animation: pn-fadeUp 0.6s ease both;
    pointer-events: auto;
    margin-bottom: 0;
  }

  /* Logo on dark left panel — invert to white */
  .ov-logo-img {
    height: 74px;
    width: auto;
    object-fit: contain;

  }

  /* ── Centre copy block ── */
  .ov-center {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    animation: pn-fadeUp 0.6s 0.1s ease both;
    max-width: 460px;
    min-height: 0;
    padding-top: 20px;
  }

  .ov-eyebrow {
    display: inline-flex; align-items: center; gap: 7px;
    background: rgba(0,214,143,0.1);
    border: 1px solid rgba(0,214,143,0.2);
    color: var(--pn-green);
    font-size: 10.5px; font-weight: 700;
    letter-spacing: 0.1em; text-transform: uppercase;
    padding: 5px 12px; border-radius: 100px;
    margin-bottom: 20px; width: fit-content;
  }

  .ov-eyebrow::before {
    content: '';
    width: 5px; height: 5px;
    background: var(--pn-green);
    border-radius: 50%;
  }

  .ov-headline {
    font-family: 'DM Serif Display', serif;
    font-size: clamp(30px, 2.8vw, 42px);
    line-height: 1.12; color: white;
    letter-spacing: -0.025em;
    margin-bottom: 12px;
  }

  .ov-headline em {
    font-style: italic;
    color: var(--pn-green);
  }

  .ov-sub {
    font-size: 13.5px; color: rgba(255,255,255,0.45);
    line-height: 1.65; max-width: 380px;
    margin-bottom: 18px;
  }

  /* ── Scrolling ticker ── */
  .ov-ticker-wrap {
    flex: 1;
    position: relative;
    overflow: hidden;
    min-height: 0;
    /* Fade edges top & bottom */
    -webkit-mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
    mask-image: linear-gradient(to bottom, transparent 0%, black 10%, black 90%, transparent 100%);
  }

  .ov-ticker-track {
    display: flex;
    flex-direction: column;
    gap: 9px;
    animation: ov-scroll 40s linear infinite;
    will-change: transform;
  }

  .ov-ticker-track:hover { animation-play-state: paused; }

  @keyframes ov-scroll {
    0%   { transform: translateY(0); }
    100% { transform: translateY(-50%); }
  }

  /* ── Report insight card ── */
  .ri-card {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 13px;
    padding: 12px 14px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    transition: background .2s, border-color .2s;
    cursor: default;
    pointer-events: auto;
  }

  .ri-card:hover {
    background: rgba(255,255,255,0.07);
    border-color: rgba(255,255,255,0.14);
  }

  /* Left accent bar — colour set via --accent on each card */
  .ri-card::before {
    content: '';
    position: absolute;
    left: 0; top: 10px; bottom: 10px;
    width: 3px; border-radius: 0 3px 3px 0;
    background: var(--accent, rgba(255,255,255,0.15));
  }

  .ri-icon {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; flex-shrink: 0;
    background: var(--icon-bg, rgba(255,255,255,0.06));
  }

  .ri-body { flex: 1; min-width: 0; }

  .ri-tag {
    font-size: 13px; font-weight: 700; letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--tag-color, rgba(255,255,255,0.35));
    margin-bottom: 2px;
  }

  .ri-title {
    font-size: 18px; font-weight: 700; color: white;
    line-height: 1.3; margin-bottom: 2px; letter-spacing: -0.01em;
  }

  .ri-desc {
    font-size: 14.5px; color: rgba(255,255,255,0.36); line-height: 1.5;
  }
  
    
  .register-box{
      margin-top:20px;
  }

  /* ============================================================
     ANIMATIONS
  ============================================================ */
  @keyframes pn-fadeUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* ============================================================
     RESPONSIVE
  ============================================================ */
  @media (max-width: 992px) {
    section.auth { flex-wrap: wrap !important; flex-direction: column; }
    .auth-left { flex: none !important; width: 100% !important; min-height: 300px; }
    .auth-right { width: 100% !important; min-width: 0 !important; padding: 40px 28px !important; }
    #left-overlay { position: relative; width: 100%; height: auto; }
  }

  @media (max-width: 480px) {
    .auth-right { padding: 32px 20px !important; }
    .auth-right h4.mb-12 { font-size: 24px !important; }
  }
  </style>
</head>

<body>



<section class="auth bg-base d-flex flex-wrap">

  <!-- Left panel (image replaced by #left-overlay below) -->
  <div class="auth-left d-lg-block d-none">
    <div class="d-flex align-items-center flex-column h-100 justify-content-center">
      <img src="assets/images/auth/auth-img.png" alt="">
    </div>
  </div>

  <!-- Right panel — form unchanged -->
  <div class="auth-right py-32 px-24 d-flex flex-column justify-content-center">
    <div class="max-w-464-px mx-auto w-100">

      <div>
        <a href="/" class="mb-40 max-w-290-px">
          <img src="assets/images/logo.png" alt="">
        </a>
        <h4 class="mb-12">Welcome! 👋</h4>
        <p class="mb-32 text-secondary-light text-lg">Sign in to see what's happening with your money.</p>
      </div>

       @if (count($errors) > 0)
      <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="post" action="{{ route('login.perform') }}">

       @csrf

        <!-- Email field -->
        <div class="icon-field mb-16">
          <span class="icon top-50 translate-middle-y">
            <iconify-icon icon="mage:email"></iconify-icon>
          </span>
          <input type="text"
                 class="form-control h-56-px bg-neutral-50 radius-12"
                 name="username"
                 placeholder="Email or Username">
        </div>

        <!-- Password field -->
        <div class="position-relative mb-20">
          <div class="icon-field">
            <span class="icon top-50 translate-middle-y">
              <iconify-icon icon="solar:lock-password-outline"></iconify-icon>
            </span>
            <input type="password"
                   class="form-control h-56-px bg-neutral-50 radius-12"
                   id="your-password"
                   name="password"
                   placeholder="Password">
          </div>
          <span class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                data-toggle="#your-password"></span>
        </div>

        <!-- Remember + Forgot -->
        <div class="">
          <div class="d-flex justify-content-between gap-2">
            <div class="form-check style-check d-flex align-items-center">
              <input class="form-check-input border border-neutral-300"
                     type="checkbox" name="remember" value="yes" id="remeber">
              <label class="form-check-label" for="remeber">Remember me</label>
            </div>
            <a href="{{ route('forgot-password.show') }}" class="text-primary-600 fw-medium">Forgot Password?</a>
          </div>
        </div>

        <button type="submit"
                class="btn btn-primary text-sm btn-sm px-12 py-16 w-100 radius-12 mt-32">
          Sign In
        </button>
        
         <div class="mt-20 text-sm register-box text-center">
              Don't have an account?
              <a href="{{ route('register.show') }}" class="text-primary-600 fw-medium">Sign Up</a>
         </div>

      </form>
    </div>
  </div>

</section>

<!-- ══════════════════════════════════════════════════════════
     LEFT PANEL OVERLAY — Illustrated content layer
     Positioned absolutely over .auth-left via CSS
     (No modification to original HTML above)
══════════════════════════════════════════════════════════ -->

<div id="left-overlay">

  <!-- Brand -->
  <div class="ov-brand">
    <img src="assets/images/logo.png" alt="Profit Niti AI" class="ov-logo-img">
  </div>

  <!-- Centre headline + feature cards -->
  <div class="ov-center">

    <div class="ov-eyebrow">Made for Business Owners</div>

    <h2 class="ov-headline">
      Stop guessing.<br>
      Start knowing <em>exactly</em><br>
      where your money goes.
    </h2>

    <p class="ov-sub">
      ProfitNiti Lite reads your financial data and gives you
      10 plain-language reports in under 60 seconds —
      no CA needed, no jargon, no waiting.
    </p>

    <!-- Scrolling report insight ticker -->
    <div class="ov-ticker-wrap">
      <div class="ov-ticker-track">

        <!-- ── SET 1 of 10 ── -->
        <div class="ri-card" style="--accent:#1a5cff;--icon-bg:rgba(26,92,255,0.12);--tag-color:#4d84ff;">
          <div class="ri-icon">📊</div>
          <div class="ri-body">
            <div class="ri-tag">Balance Sheet</div>
            <div class="ri-title">Is your business truly solvent — or just busy?</div>
            <div class="ri-desc">See total assets vs liabilities at a glance. Know instantly whether your net worth is growing or silently shrinking.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#00d68f;--icon-bg:rgba(0,214,143,0.1);--tag-color:#00d68f;">
          <div class="ri-icon">📈</div>
          <div class="ri-body">
            <div class="ri-tag">Profit &amp; Loss</div>
            <div class="ri-title">Find exactly where revenue stops becoming profit</div>
            <div class="ri-desc">Track every rupee from turnover to net profit. See which cost line is eating your margin — with an EBITDA waterfall that makes it obvious.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#6366f1;--icon-bg:rgba(99,102,241,0.1);--tag-color:#818cf8;">
          <div class="ri-icon">📋</div>
          <div class="ri-body">
            <div class="ri-tag">Financial Summary</div>
            <div class="ri-title">The 3 numbers every business owner must know weekly</div>
            <div class="ri-desc">Combines P&amp;L, cash position and balance sheet into one executive view — no CA visit required. Know your health in under 30 seconds.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#0ea5e9;--icon-bg:rgba(14,165,233,0.1);--tag-color:#38bdf8;">
          <div class="ri-icon">💧</div>
          <div class="ri-body">
            <div class="ri-tag">Cash Flow</div>
            <div class="ri-title">Know before your account runs dry — not after</div>
            <div class="ri-desc">See operating, investing and financing cash separately. Spot the exact month cash will tighten — weeks in advance so you can act.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#10b981;--icon-bg:rgba(16,185,129,0.1);--tag-color:#34d399;">
          <div class="ri-icon">⚡</div>
          <div class="ri-body">
            <div class="ri-tag">Profit Power</div>
            <div class="ri-title">Is your profitability improving or quietly eroding?</div>
            <div class="ri-desc">12-month sparklines for gross margin, EBITDA margin and net margin. See the trend before it becomes a problem you can't reverse.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#f59e0b;--icon-bg:rgba(245,158,11,0.1);--tag-color:#fbbf24;">
          <div class="ri-icon">💰</div>
          <div class="ri-body">
            <div class="ri-tag">Cash Management</div>
            <div class="ri-title">How many days is your cash stuck in the business?</div>
            <div class="ri-desc">Working capital and cash conversion cycle in one view. Know exactly how long cash is tied up in stock, debtors and creditors — and how to free it faster.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#8b5cf6;--icon-bg:rgba(139,92,246,0.1);--tag-color:#a78bfa;">
          <div class="ri-icon">🏗️</div>
          <div class="ri-body">
            <div class="ri-tag">Capex</div>
            <div class="ri-title">Are your asset investments actually paying back?</div>
            <div class="ri-desc">Track capital expenditure vs asset turnover vs ROCE. Know whether money spent on machinery, vehicles or property is generating real returns.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#ef4444;--icon-bg:rgba(239,68,68,0.1);--tag-color:#f87171;">
          <div class="ri-icon">🏦</div>
          <div class="ri-body">
            <div class="ri-tag">Financing</div>
            <div class="ri-title">Can your business survive a slow quarter with its current debt?</div>
            <div class="ri-desc">Debt-to-equity, interest coverage ratio and cash runway in one place. Know if you're over-leveraged before your banker tells you.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#ec4899;--icon-bg:rgba(236,72,153,0.1);--tag-color:#f472b6;">
          <div class="ri-icon">❤️</div>
          <div class="ri-body">
            <div class="ri-tag">Business Health</div>
            <div class="ri-title">A 12-month health check — in one color-coded screen</div>
            <div class="ri-desc">All key indicators across a full year, color-coded green / amber / red. Spot which months your business was stressed — and why it happened.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#06b6d4;--icon-bg:rgba(6,182,212,0.1);--tag-color:#22d3ee;">
          <div class="ri-icon">✨</div>
          <div class="ri-body">
            <div class="ri-tag">Cashflow Quality</div>
            <div class="ri-title">What is silently draining your cash every month?</div>
            <div class="ri-desc">Identifies the specific drivers — slow collections, rising inventory, early payments — ranked by impact. Fix the right problem first, not the loudest one.</div>
          </div>
        </div>

        <!-- ── SET 2 — duplicate for seamless infinite loop ── -->
        <div class="ri-card" style="--accent:#1a5cff;--icon-bg:rgba(26,92,255,0.12);--tag-color:#4d84ff;">
          <div class="ri-icon">📊</div>
          <div class="ri-body">
            <div class="ri-tag">Balance Sheet</div>
            <div class="ri-title">Is your business truly solvent — or just busy?</div>
            <div class="ri-desc">See total assets vs liabilities at a glance. Know instantly whether your net worth is growing or silently shrinking.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#00d68f;--icon-bg:rgba(0,214,143,0.1);--tag-color:#00d68f;">
          <div class="ri-icon">📈</div>
          <div class="ri-body">
            <div class="ri-tag">Profit &amp; Loss</div>
            <div class="ri-title">Find exactly where revenue stops becoming profit</div>
            <div class="ri-desc">Track every rupee from turnover to net profit. See which cost line is eating your margin — with an EBITDA waterfall that makes it obvious.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#6366f1;--icon-bg:rgba(99,102,241,0.1);--tag-color:#818cf8;">
          <div class="ri-icon">📋</div>
          <div class="ri-body">
            <div class="ri-tag">Financial Summary</div>
            <div class="ri-title">The 3 numbers every business owner must know weekly</div>
            <div class="ri-desc">Combines P&amp;L, cash position and balance sheet into one executive view — no CA visit required. Know your health in under 30 seconds.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#0ea5e9;--icon-bg:rgba(14,165,233,0.1);--tag-color:#38bdf8;">
          <div class="ri-icon">💧</div>
          <div class="ri-body">
            <div class="ri-tag">Cash Flow</div>
            <div class="ri-title">Know before your account runs dry — not after</div>
            <div class="ri-desc">See operating, investing and financing cash separately. Spot the exact month cash will tighten — weeks in advance so you can act.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#10b981;--icon-bg:rgba(16,185,129,0.1);--tag-color:#34d399;">
          <div class="ri-icon">⚡</div>
          <div class="ri-body">
            <div class="ri-tag">Profit Power</div>
            <div class="ri-title">Is your profitability improving or quietly eroding?</div>
            <div class="ri-desc">12-month sparklines for gross margin, EBITDA margin and net margin. See the trend before it becomes a problem you can't reverse.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#f59e0b;--icon-bg:rgba(245,158,11,0.1);--tag-color:#fbbf24;">
          <div class="ri-icon">💰</div>
          <div class="ri-body">
            <div class="ri-tag">Cash Management</div>
            <div class="ri-title">How many days is your cash stuck in the business?</div>
            <div class="ri-desc">Working capital and cash conversion cycle in one view. Know exactly how long cash is tied up in stock, debtors and creditors — and how to free it faster.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#8b5cf6;--icon-bg:rgba(139,92,246,0.1);--tag-color:#a78bfa;">
          <div class="ri-icon">🏗️</div>
          <div class="ri-body">
            <div class="ri-tag">Capex</div>
            <div class="ri-title">Are your asset investments actually paying back?</div>
            <div class="ri-desc">Track capital expenditure vs asset turnover vs ROCE. Know whether money spent on machinery, vehicles or property is generating real returns.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#ef4444;--icon-bg:rgba(239,68,68,0.1);--tag-color:#f87171;">
          <div class="ri-icon">🏦</div>
          <div class="ri-body">
            <div class="ri-tag">Financing</div>
            <div class="ri-title">Can your business survive a slow quarter with its current debt?</div>
            <div class="ri-desc">Debt-to-equity, interest coverage ratio and cash runway in one place. Know if you're over-leveraged before your banker tells you.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#ec4899;--icon-bg:rgba(236,72,153,0.1);--tag-color:#f472b6;">
          <div class="ri-icon">❤️</div>
          <div class="ri-body">
            <div class="ri-tag">Business Health</div>
            <div class="ri-title">A 12-month health check — in one color-coded screen</div>
            <div class="ri-desc">All key indicators across a full year, color-coded green / amber / red. Spot which months your business was stressed — and why it happened.</div>
          </div>
        </div>

        <div class="ri-card" style="--accent:#06b6d4;--icon-bg:rgba(6,182,212,0.1);--tag-color:#22d3ee;">
          <div class="ri-icon">✨</div>
          <div class="ri-body">
            <div class="ri-tag">Cashflow Quality</div>
            <div class="ri-title">What is silently draining your cash every month?</div>
            <div class="ri-desc">Identifies the specific drivers — slow collections, rising inventory, early payments — ranked by impact. Fix the right problem first, not the loudest one.</div>
          </div>
        </div>

      </div><!-- /ov-ticker-track -->
    </div><!-- /ov-ticker-wrap -->
  </div>


</div>



  <!-- jQuery library js -->
  <script src="{{ asset('assets/js/lib/jquery-3.7.1.min.js') }}"></script>
  <!-- Bootstrap js -->
  <script src="{{ asset('assets/js/lib/bootstrap.bundle.min.js') }}"></script>
  <!-- Apex Chart js -->
  <script src="{{ asset('assets/js/lib/apexcharts.min.js') }}"></script>
  <!-- Data Table js -->
  <script src="{{ asset('assets/js/lib/dataTables.min.js') }}"></script>
  <!-- Iconify Font js -->
  <script src="{{ asset('assets/js/lib/iconify-icon.min.js') }}"></script>
  <!-- jQuery UI js -->
  <script src="{{ asset('assets/js/lib/jquery-ui.min.js') }}"></script>
  <!-- Vector Map js -->
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-2.0.5.min.js') }}"></script>
  <script src="{{ asset('assets/js/lib/jquery-jvectormap-world-mill-en.js') }}"></script>
  <!-- Popup js -->
  <script src="{{ asset('assets/js/lib/magnifc-popup.min.js') }}"></script>
  <!-- Slick Slider js -->
  <script src="{{ asset('assets/js/lib/slick.min.js') }}"></script>
  <!-- prism js -->
  <script src="{{ asset('assets/js/lib/prism.js') }}"></script>
  <!-- file upload js -->
  <script src="{{ asset('assets/js/lib/file-upload.js') }}"></script>
  <!-- audioplayer -->
  <script src="{{ asset('assets/js/lib/audioplayer.js') }}"></script>
  
  <!-- main js -->
  <script src="{{ asset('assets/js/app.js') }}"></script>

<script>
  /* ── Password show/hide (original logic, unchanged) ── */
  function initializePasswordToggle(toggleSelector) {
    $(toggleSelector).on('click', function () {
      $(this).toggleClass("ri-eye-off-line");
      var input = $($(this).attr("data-toggle"));
      if (input.attr("type") === "password") {
        input.attr("type", "text");
      } else {
        input.attr("type", "password");
      }
    });
  }
  initializePasswordToggle('.toggle-password');

  /* Resize: keep overlay width in sync with auth-left */
  function syncOverlay() {
    const left = document.querySelector('.auth-left');
    const overlay = document.getElementById('left-overlay');
    if (left && overlay) {
      overlay.style.width = left.offsetWidth + 'px';
    }
  }
  syncOverlay();
  window.addEventListener('resize', syncOverlay);
</script>

</body>
</html>