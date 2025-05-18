/* jshint esversion: 6 */
jQuery( document ).ready( function ( $ )
{
	"use strict";

	const CACHE_EXPIRATION = PreloadEverything.cacheLifetime * 1000;
	const CACHE_PREFIX = "preload_cache_";
	const enableLazyLoading = PreloadEverything.enableLazyLoading;

	const CacheManager = {
		memoryCache:
		{},

		set: function ( key, value )
		{
			const item = {
				value: value,
				timestamp: Date.now() + CACHE_EXPIRATION,
			};

			try
			{
				localStorage.setItem( CACHE_PREFIX + key, JSON.stringify( item ) );
			}
			catch ( e )
			{
				try
				{
					sessionStorage.setItem( CACHE_PREFIX + key, JSON.stringify( item ) );
				}
				catch ( e )
				{
					this.memoryCache[ key ] = item;
				}
			}
		},

		get: function ( key )
		{
			let data = null;

			try
			{
				data = JSON.parse( localStorage.getItem( CACHE_PREFIX + key ) );
			}
			catch ( e )
			{
				try
				{
					data = JSON.parse( sessionStorage.getItem( CACHE_PREFIX + key ) );
				}
				catch ( e )
				{
					data = this.memoryCache[ key ] || null;
				}
			}

			if ( data && ( Date.now() - data.timestamp ) < CACHE_EXPIRATION )
			{
				return data.value;
			}

			this.remove( key );
			return null;
		},

		remove: function ( key )
		{
			localStorage.removeItem( CACHE_PREFIX + key );
			sessionStorage.removeItem( CACHE_PREFIX + key );
			delete this.memoryCache[ key ];
		},

		clearExpired: function ()
		{
			Object.keys( localStorage ).forEach( key =>
			{
				if ( key.startsWith( CACHE_PREFIX ) )
				{
					const data = JSON.parse( localStorage.getItem( key ) );
					if ( data && ( Date.now() - data.timestamp ) >= CACHE_EXPIRATION )
					{
						localStorage.removeItem( key );
					}
				}
			} );

			Object.keys( sessionStorage ).forEach( key =>
			{
				if ( key.startsWith( CACHE_PREFIX ) )
				{
					const data = JSON.parse( sessionStorage.getItem( key ) );
					if ( data && ( Date.now() - data.timestamp ) >= CACHE_EXPIRATION )
					{
						sessionStorage.removeItem( key );
					}
				}
			} );
		}
	};

	function hashURL( url )
	{
		return btoa( url ).replace( /\//g, "_" );
	}

	function preloadContentSequentially( urls ) {
		if ( urls.length === 0 ) return;

		const url = urls.shift();
		$.get( url, function ( data ) {
			const cacheKey = hashURL( url );
			CacheManager.set( cacheKey, data );
			preloadContentSequentially( urls );
		} );
	}

	function handleLinkPreload() {
		const allowedHosts = PreloadEverything.allowedHosts;
		const preloadQueue = [];

		const observer = new IntersectionObserver( ( entries, observer ) => {
			entries.forEach( entry => {
				if ( entry.isIntersecting ) {
					const href = $( entry.target ).attr( "href" );

					if ( href && ! CacheManager.get( hashURL( href ) ) ) {
						preloadQueue.push( href );
					}

					observer.unobserve( entry.target );
				}
			} );

			if ( preloadQueue.length > 0 ) {
				preloadContentSequentially( preloadQueue );
			}
		} );

		$( "a[href]" ).each( function () {
			const href = $( this ).attr( "href" );
			if ( ! href || href.includes( "wp-login.php" ) || href.includes( "wp-admin/" ) ) return;

			const isInternal = href.includes( window.location.origin );
			let shouldPreload = false;

			if ( allowedHosts === "internal" && isInternal ) shouldPreload = true;
			else if ( allowedHosts === "external" && !isInternal ) shouldPreload = true;
			else if ( allowedHosts === "internal_external" ) shouldPreload = true;

			if ( shouldPreload && enableLazyLoading ) {
				observer.observe( this );
			}
			else if ( shouldPreload ) {
				preloadQueue.push( href );
			}
		} );

		if ( preloadQueue.length > 0 && ! enableLazyLoading ) {
			preloadContentSequentially( preloadQueue );
		}
	}

	setTimeout( handleLinkPreload, 2000 );

	$( "a" ).on( "click", function ( event )
	{
		const href = $( this ).attr( "href" );

		if ( ! href ) return;

		const cacheKey = hashURL( href );
		const cachedData = CacheManager.get( cacheKey );

		if ( cachedData )
		{
			event.preventDefault();
			history.pushState( { path: href }, "", href );
			document.open();
			document.write( cachedData );
			document.close();
		}
	} );

	window.onpopstate = function ()
	{
		const href = window.location.href;
		const cacheKey = hashURL( href );
		const cachedData = CacheManager.get( cacheKey );

		if ( cachedData )
		{
			document.open();
			document.write( cachedData );
			document.close();
		}
		else
		{
			window.location.reload();
		}
	};

	CacheManager.clearExpired();
} );