<?php
/**
 * WWB — Bandeau cookies RGPD
 *
 * Bandeau léger sans plugin externe. Gère 3 niveaux :
 * - Essentiels (toujours actifs)
 * - Analytiques (Google Analytics)
 * - Marketing
 *
 * Le consentement est stocké dans un cookie `wwb_cookie_consent`
 * valide 13 mois (durée max CNIL).
 *
 * @package WWB_V2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'wp_footer', 'wwb_cookie_banner_html' );
add_action( 'wp_head', 'wwb_cookie_banner_css' );
add_action( 'wp_footer', 'wwb_cookie_banner_js' );

function wwb_cookie_banner_html() {
	?>
	<div id="wwb-cookie-banner" class="wwb-cookie-banner" role="dialog" aria-label="Gestion des cookies" style="display:none;">
		<div class="wwb-cookie-banner__inner">
			<div class="wwb-cookie-banner__text">
				<p><strong>Nous utilisons des cookies</strong> pour améliorer votre expérience, mesurer l'audience et personnaliser les contenus. <a href="<?php echo esc_url( home_url( '/politique-de-confidentialite/' ) ); ?>">En savoir plus</a></p>
			</div>
			<div class="wwb-cookie-banner__actions">
				<button type="button" class="wwb-cookie-btn wwb-cookie-btn--settings" onclick="wwbCookieToggleDetails()">Personnaliser</button>
				<button type="button" class="wwb-cookie-btn wwb-cookie-btn--reject" onclick="wwbCookieReject()">Tout refuser</button>
				<button type="button" class="wwb-cookie-btn wwb-cookie-btn--accept" onclick="wwbCookieAcceptAll()">Tout accepter</button>
			</div>
		</div>
		<div id="wwb-cookie-details" class="wwb-cookie-banner__details" style="display:none;">
			<label class="wwb-cookie-toggle">
				<input type="checkbox" checked disabled> <span>Essentiels</span>
				<small>Panier, session, connexion — toujours actifs</small>
			</label>
			<label class="wwb-cookie-toggle">
				<input type="checkbox" id="wwb-cookie-analytics"> <span>Analytiques</span>
				<small>Google Analytics — mesure d'audience anonymisée</small>
			</label>
			<label class="wwb-cookie-toggle">
				<input type="checkbox" id="wwb-cookie-marketing"> <span>Marketing</span>
				<small>Publicités personnalisées</small>
			</label>
			<button type="button" class="wwb-cookie-btn wwb-cookie-btn--save" onclick="wwbCookieSave()">Enregistrer mes choix</button>
		</div>
	</div>
	<?php
}

function wwb_cookie_banner_css() {
	?>
	<style id="wwb-cookie-banner-css">
	.wwb-cookie-banner{position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#fff;box-shadow:0 -4px 24px rgba(0,0,0,.12);border-top:3px solid var(--wp--preset--color--primary,#FF99DA);font-family:Inter,sans-serif;font-size:14px;color:#362C49;transition:transform .3s ease}
	.wwb-cookie-banner__inner{max-width:1280px;margin:0 auto;padding:20px 32px;display:flex;align-items:center;gap:24px;flex-wrap:wrap}
	.wwb-cookie-banner__text{flex:1;min-width:280px}
	.wwb-cookie-banner__text p{margin:0;line-height:1.5}
	.wwb-cookie-banner__text a{color:var(--wp--preset--color--primary-dark,#362C49);text-decoration:underline}
	.wwb-cookie-banner__actions{display:flex;gap:10px;flex-wrap:wrap}
	.wwb-cookie-btn{border:none;cursor:pointer;font-size:14px;font-weight:600;padding:10px 20px;border-radius:8px;transition:all .2s}
	.wwb-cookie-btn--accept{background:var(--wp--preset--color--primary,#FF99DA);color:var(--wp--preset--color--primary-dark,#362C49)}
	.wwb-cookie-btn--accept:hover{opacity:.85}
	.wwb-cookie-btn--reject{background:#f1f1f1;color:#362C49}
	.wwb-cookie-btn--reject:hover{background:#e0e0e0}
	.wwb-cookie-btn--settings{background:transparent;color:#362C49;text-decoration:underline;padding:10px 12px}
	.wwb-cookie-btn--save{background:var(--wp--preset--color--primary,#FF99DA);color:var(--wp--preset--color--primary-dark,#362C49);margin-top:8px}
	.wwb-cookie-banner__details{max-width:1280px;margin:0 auto;padding:0 32px 20px;display:flex;flex-wrap:wrap;gap:20px;align-items:flex-end}
	.wwb-cookie-toggle{display:flex;flex-direction:column;gap:2px;min-width:200px}
	.wwb-cookie-toggle input{margin-right:6px}
	.wwb-cookie-toggle span{font-weight:600}
	.wwb-cookie-toggle small{color:#888;font-size:12px;margin-left:22px}
	@media(max-width:768px){.wwb-cookie-banner__inner{flex-direction:column;text-align:center}.wwb-cookie-banner__actions{justify-content:center}.wwb-cookie-banner__details{flex-direction:column;align-items:stretch}}
	</style>
	<?php
}

function wwb_cookie_banner_js() {
	?>
	<script id="wwb-cookie-banner-js">
	(function(){
		var COOKIE_NAME='wwb_cookie_consent';
		var COOKIE_DAYS=395;// 13 mois CNIL

		function getCookie(n){var m=document.cookie.match(new RegExp('(^| )'+n+'=([^;]+)'));return m?decodeURIComponent(m[2]):null}
		function setCookie(n,v,d){var e=new Date;e.setDate(e.getDate()+d);document.cookie=n+'='+encodeURIComponent(v)+';expires='+e.toUTCString()+';path=/;SameSite=Lax'}

		function showBanner(){document.getElementById('wwb-cookie-banner').style.display='block'}
		function hideBanner(){document.getElementById('wwb-cookie-banner').style.display='none'}

		window.wwbCookieShowBanner=showBanner;

		window.wwbCookieToggleDetails=function(){
			var d=document.getElementById('wwb-cookie-details');
			d.style.display=d.style.display==='none'?'flex':'none';
		};

		window.wwbCookieAcceptAll=function(){
			setCookie(COOKIE_NAME,JSON.stringify({essential:true,analytics:true,marketing:true}),COOKIE_DAYS);
			hideBanner();
			wwbLoadAnalytics();
		};

		window.wwbCookieReject=function(){
			setCookie(COOKIE_NAME,JSON.stringify({essential:true,analytics:false,marketing:false}),COOKIE_DAYS);
			hideBanner();
		};

		window.wwbCookieSave=function(){
			var a=document.getElementById('wwb-cookie-analytics').checked;
			var m=document.getElementById('wwb-cookie-marketing').checked;
			setCookie(COOKIE_NAME,JSON.stringify({essential:true,analytics:a,marketing:m}),COOKIE_DAYS);
			hideBanner();
			if(a)wwbLoadAnalytics();
		};

		function wwbLoadAnalytics(){
			// Google Analytics — remplacer GA_MEASUREMENT_ID par le vrai ID
			// if(window.gtag)return;
			// var s=document.createElement('script');
			// s.src='https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID';
			// s.async=true;document.head.appendChild(s);
			// window.dataLayer=window.dataLayer||[];
			// function gtag(){dataLayer.push(arguments)}
			// gtag('js',new Date());gtag('config','GA_MEASUREMENT_ID');
		}

		// Init
		var consent=getCookie(COOKIE_NAME);
		if(!consent){
			showBanner();
		}else{
			try{var c=JSON.parse(consent);if(c.analytics)wwbLoadAnalytics();}catch(e){}
		}
	})();
	</script>
	<?php
}
