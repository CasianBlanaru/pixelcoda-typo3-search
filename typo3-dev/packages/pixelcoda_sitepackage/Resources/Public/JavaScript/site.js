document.documentElement.classList.add('js');

(function () {
    const demoLinks = [
        ['Suche testen', '/search-demo', 'Autocomplete, Trefferliste und Pagination'],
        ['Facetten testen', '/search-facets-demo', 'Filter nach Typ und Kategorie'],
        ['KI-Antwort testen', '/search-ai-demo', 'Antworten mit Quellen aus dem Index']
    ];

    function isBackendRoute() {
        return window.location.pathname.startsWith('/typo3');
    }

    function hasEditorSession() {
        return Boolean(
            document.querySelector('.pc-fe-toolbar, .pc-fe-drawer, [data-pc-fe-editor], .typo3-adminPanel')
        );
    }

    function getDefaultFrontendWrapper() {
        return document.querySelector('body > div[style*="max-width: 800px"]') || document.querySelector('.site-main');
    }

    function createNotice() {
        const section = document.createElement('section');
        section.className = 'pixelcoda-demo-login-notice';
        section.setAttribute('aria-labelledby', 'pixelcoda-demo-login-title');
        section.innerHTML = `
            <div>
                <span class="pixelcoda-demo-login-notice__eyebrow">Demo-Modus</span>
                <h2 id="pixelcoda-demo-login-title">Plugins im TYPO3 Backend testen</h2>
                <p>Melde dich als Redakteur an, um pixelcoda Search, Frontend Editing und GSAP-Animationen direkt in der Testumgebung auszuprobieren.</p>
                <dl>
                    <div><dt>Benutzer</dt><dd>pixelcoda-editor</dd></div>
                    <div><dt>Passwort</dt><dd>PixelcodaDemo2026!</dd></div>
                    <div><dt>Rolle</dt><dd>Redakteur, kein Admin</dd></div>
                </dl>
            </div>
            <a class="pixelcoda-demo-login-notice__button" href="/typo3/" rel="nofollow">Zum TYPO3 Login</a>
        `;
        return section;
    }

    function createDemoNavigation() {
        const nav = document.createElement('nav');
        nav.className = 'pixelcoda-demo-navigation';
        nav.setAttribute('aria-label', 'Pixelcoda Demo-Seiten');
        nav.innerHTML = `
            <span class="pixelcoda-demo-navigation__label">Demo-Seiten</span>
            <div>
                ${demoLinks.map(([title, url, description]) => `
                    <a href="${url}">
                        <strong>${title}</strong>
                        <small>${description}</small>
                    </a>
                `).join('')}
            </div>
        `;
        return nav;
    }

    function updateDemoChrome() {
        if (isBackendRoute()) {
            return;
        }

        const wrapper = getDefaultFrontendWrapper();
        if (!wrapper) {
            return;
        }

        const loggedIn = hasEditorSession();
        const existingNotice = document.querySelector('.pixelcoda-demo-login-notice');
        if (loggedIn) {
            existingNotice?.remove();
        } else if (!existingNotice) {
            wrapper.insertBefore(createNotice(), wrapper.firstElementChild);
        }

        if (!document.querySelector('.pixelcoda-demo-navigation')) {
            const firstSearch = wrapper.querySelector('.frame-type-pixelcodasearch_search, .pixelcoda-search-container');
            const navigation = createDemoNavigation();
            if (firstSearch?.parentElement === wrapper) {
                wrapper.insertBefore(navigation, firstSearch);
            } else {
                const notice = document.querySelector('.pixelcoda-demo-login-notice');
                wrapper.insertBefore(navigation, notice?.nextElementSibling || wrapper.firstElementChild);
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', updateDemoChrome);
    } else {
        updateDemoChrome();
    }

    const observer = new MutationObserver(updateDemoChrome);
    observer.observe(document.documentElement, { childList: true, subtree: true });
})();
