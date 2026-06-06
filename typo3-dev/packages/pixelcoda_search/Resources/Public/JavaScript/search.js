/**
 * PixelCoda Search - Filter Functions & Autocomplete
 */

// Debounce function for performance
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Autocomplete functionality
class SearchAutocomplete {
    constructor(inputElement) {
        this.input = inputElement;
        this.suggestionsContainer = null;
        this.currentFocus = -1;
        this.suggestions = [];
        this.isOpen = false;

        this.init();
    }

    init() {
        // Create suggestions container
        this.createSuggestionsContainer();

        // Add event listeners
        this.input.addEventListener('input', debounce((e) => this.handleInput(e), 300));
        this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
        this.input.addEventListener('focus', () => this.handleFocus());

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!this.input.contains(e.target) && !this.suggestionsContainer.contains(e.target)) {
                this.closeSuggestions();
            }
        });
    }

    createSuggestionsContainer() {
        this.suggestionsContainer = document.createElement('div');
        this.suggestionsContainer.className = 'autocomplete-suggestions';
        this.suggestionsContainer.style.display = 'none';

        // Position it after the input wrapper
        const wrapper = this.input.closest('.search-input-wrapper') || this.input.parentElement;
        wrapper.style.position = 'relative';
        wrapper.appendChild(this.suggestionsContainer);
    }

    async handleInput(e) {
        const query = e.target.value.trim();

        if (query.length < 2) {
            this.closeSuggestions();
            return;
        }

        try {
            const response = await fetch(`/index.php?eID=search_suggest&q=${encodeURIComponent(query)}`);
            const suggestions = await response.json();

            if (suggestions && suggestions.length > 0) {
                this.displaySuggestions(suggestions);
            } else {
                this.closeSuggestions();
            }
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            this.closeSuggestions();
        }
    }

    displaySuggestions(suggestions) {
        this.suggestions = suggestions;
        this.suggestionsContainer.innerHTML = '';

        suggestions.forEach((item, index) => {
            const div = document.createElement('div');
            div.className = 'suggestion-item';
            div.dataset.index = index;

            // Create content based on type
            let content = '';
            const icon = this.getIconForType(item.type);

            if (item.subtitle) {
                content = `
                    <span class="suggestion-icon">${icon}</span>
                    <div class="suggestion-content">
                        <div class="suggestion-title">${this.highlightMatch(item.title)}</div>
                        <div class="suggestion-subtitle">${item.subtitle}</div>
                    </div>
                `;
            } else {
                content = `
                    <span class="suggestion-icon">${icon}</span>
                    <div class="suggestion-content">
                        <div class="suggestion-title">${this.highlightMatch(item.title)}</div>
                    </div>
                `;
            }

            div.innerHTML = content;

            // Add click handler
            div.addEventListener('click', () => {
                this.selectSuggestion(index);
            });

            // Add hover handler
            div.addEventListener('mouseenter', () => {
                this.currentFocus = index;
                this.updateActive();
            });

            this.suggestionsContainer.appendChild(div);
        });

        this.suggestionsContainer.style.display = 'block';
        this.isOpen = true;
        this.currentFocus = -1;
    }

    getIconForType(type) {
        switch (type) {
        case 'page':
            return '📄';
        case 'content':
            return '📝';
        case 'search':
            return '🔍';
        default:
            return '📋';
        }
    }

    highlightMatch(text) {
        const query = this.input.value.trim();
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    handleKeydown(e) {
        if (!this.isOpen) return;

        switch (e.key) {
        case 'ArrowDown':
            e.preventDefault();
            this.currentFocus++;
            if (this.currentFocus >= this.suggestions.length) {
                this.currentFocus = 0;
            }
            this.updateActive();
            break;

        case 'ArrowUp':
            e.preventDefault();
            this.currentFocus--;
            if (this.currentFocus < 0) {
                this.currentFocus = this.suggestions.length - 1;
            }
            this.updateActive();
            break;

        case 'Enter':
            e.preventDefault();
            if (this.currentFocus > -1) {
                this.selectSuggestion(this.currentFocus);
            }
            break;

        case 'Escape':
            this.closeSuggestions();
            break;
        }
    }

    handleFocus() {
        if (this.input.value.length >= 2) {
            this.handleInput({
                target: this.input
            });
        }
    }

    updateActive() {
        const items = this.suggestionsContainer.querySelectorAll('.suggestion-item');
        items.forEach((item, index) => {
            if (index === this.currentFocus) {
                item.classList.add('active');
                // Scroll into view if needed
                item.scrollIntoView({
                    block: 'nearest'
                });
            } else {
                item.classList.remove('active');
            }
        });
    }

    selectSuggestion(index) {
        const suggestion = this.suggestions[index];
        if (suggestion) {
            if (suggestion.type === 'search') {
                // For search suggestions, fill the input
                this.input.value = suggestion.title;
                this.closeSuggestions();
                // Optionally submit the form
                const form = this.input.closest('form');
                if (form) {
                    form.submit();
                }
            } else {
                // For pages/content, navigate directly
                window.location.href = suggestion.url;
            }
        }
    }

    closeSuggestions() {
        this.suggestionsContainer.style.display = 'none';
        this.isOpen = false;
        this.currentFocus = -1;
        this.suggestions = [];
    }
}

// Toggle filter visibility
function toggleFilters() {
    const filterPanel = document.getElementById('searchFilters');
    const toggleButton = document.querySelector('.filter-toggle');

    if (filterPanel) {
        if (filterPanel.style.display === 'none' || filterPanel.style.display === '') {
            filterPanel.style.display = 'block';
            toggleButton.classList.add('active');
            // Save state to localStorage
            localStorage.setItem('searchFiltersOpen', 'true');
        } else {
            filterPanel.style.display = 'none';
            toggleButton.classList.remove('active');
            localStorage.setItem('searchFiltersOpen', 'false');
        }
    }
}

// Reset all filters
function resetFilters() {
    // Reset checkboxes
    document.querySelectorAll('.search-filters input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });

    // Reset selects
    document.querySelectorAll('.search-filters select').forEach(select => {
        select.selectedIndex = 0;
    });

    // Reset date inputs
    document.querySelectorAll('.search-filters input[type="date"]').forEach(input => {
        input.value = '';
    });

    // Keep search query
    const searchQuery = document.querySelector('input[name="q"]').value;

    // Redirect with only search query
    window.location.href = '?q=' + encodeURIComponent(searchQuery);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize autocomplete for all search inputs
    const searchInputs = document.querySelectorAll('.search-input');
    const autocompleteInstances = [];
    searchInputs.forEach(input => {
        autocompleteInstances.push(new SearchAutocomplete(input));
    });

    // Restore filter panel state from localStorage
    const filtersOpen = localStorage.getItem('searchFiltersOpen');
    if (filtersOpen === 'true') {
        const filterPanel = document.getElementById('searchFilters');
        const toggleButton = document.querySelector('.filter-toggle');
        if (filterPanel && toggleButton) {
            filterPanel.style.display = 'block';
            toggleButton.classList.add('active');
        }
    }

    // Add event listeners for filter changes (auto-submit option)
    const autoSubmit = false; // Set to true if you want auto-submit on filter change

    if (autoSubmit) {
        document.querySelectorAll('.search-filters select, .search-filters input[type="checkbox"]').forEach(element => {
            element.addEventListener('change', function () {
                document.querySelector('.search-form').submit();
            });
        });
    }

    // Highlight search terms in results
    const searchQuery = document.querySelector('input[name="q"]');
    if (searchQuery && searchQuery.value) {
        highlightSearchTerms(searchQuery.value);
    }
});

// Highlight search terms in results
function highlightSearchTerms(query) {
    if (!query) return;

    const terms = query.toLowerCase().split(' ').filter(term => term.length > 2);
    const resultItems = document.querySelectorAll('.search-result-item');

    resultItems.forEach(item => {
        const title = item.querySelector('.search-result-title');
        const abstract = item.querySelector('.search-result-abstract');

        terms.forEach(term => {
            if (title) {
                highlightTextNode(title, term);
            }
            if (abstract) {
                highlightTextNode(abstract, term);
            }
        });
    });
}

// Helper function to highlight text
function highlightTextNode(element, term) {
    const regex = new RegExp('(' + term + ')', 'gi');
    const originalText = element.textContent;
    const highlightedText = originalText.replace(regex, '<mark>$1</mark>');

    if (originalText !== highlightedText) {
        element.innerHTML = highlightedText;
    }
}

// Ask functionality
class AskWidget {
    constructor(container) {
        this.container = container;
        this.form = container.querySelector('.ask-form');
        this.questionInput = container.querySelector('.ask-input');
        this.contextInput = container.querySelector('.ask-context');
        this.submitButton = container.querySelector('.ask-submit');
        this.resultsContainer = container.querySelector('.ask-results');
        this.messageContainer = container.querySelector('.ask-message');
        this.questionDisplay = container.querySelector('.question-text');
        this.answerContent = container.querySelector('.answer-content');
        this.sourcesList = container.querySelector('.sources-list');
        this.sourcesContainer = container.querySelector('.answer-sources');

        this.init();
    }

    init() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }

        // Initialize if there's already a question in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const question = urlParams.get('q');
        if (question && this.questionInput) {
            this.questionInput.value = question;
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        const question = this.questionInput.value.trim();
        const context = this.contextInput.value.trim();

        if (question.length < 3) {
            this.showMessage('Please enter at least 3 characters', 'error');
            return;
        }

        this.setLoading(true);
        this.showMessage('Processing your question...', 'info');

        try {
            const response = await this.askQuestion(question, context);
            this.displayAnswer(question, response);
        } catch (error) {
            console.error('Ask error:', error);
            this.showMessage('Error processing your question. Please try again.', 'error');
        } finally {
            this.setLoading(false);
        }
    }

    async askQuestion(question, context = '') {
        const params = new URLSearchParams({
            q: question,
            context
        });

        const response = await fetch(`/index.php?type=1702&${params.toString()}`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    }

    displayAnswer(question, response) {
        if (this.questionDisplay) {
            this.questionDisplay.textContent = question;
        }

        if (response.data && response.data.attributes) {
            const { answer, sources } = response.data.attributes;

            if (this.answerContent) {
                this.answerContent.innerHTML = answer || 'No answer available.';
            }

            if (sources && sources.length > 0 && this.sourcesList) {
                this.sourcesList.innerHTML = '';
                sources.forEach(source => {
                    const li = document.createElement('li');
                    li.className = 'source-item';
                    li.innerHTML = `
                        <a href="${source.url}" class="source-link" target="_blank" rel="noopener">
                            <span class="source-title">${source.title || 'Source'}</span>
                            ${source.excerpt ? `<span class="source-excerpt">${source.excerpt}</span>` : ''}
                        </a>
                    `;
                    this.sourcesList.appendChild(li);
                });

                if (this.sourcesContainer) {
                    this.sourcesContainer.style.display = 'block';
                }
            }

            if (this.resultsContainer) {
                this.resultsContainer.style.display = 'block';
            }

            this.showMessage('Answer generated successfully', 'success');
        } else {
            this.showMessage('No answer available', 'warning');
        }
    }

    showMessage(message, type = 'info') {
        if (this.messageContainer) {
            this.messageContainer.textContent = message;
            this.messageContainer.className = `ask-message alert alert-${type}`;
            this.messageContainer.style.display = 'block';

            // Auto-hide after 5 seconds for success messages
            if (type === 'success') {
                setTimeout(() => {
                    this.messageContainer.style.display = 'none';
                }, 5000);
            }
        }
    }

    setLoading(loading) {
        if (this.submitButton) {
            const btnText = this.submitButton.querySelector('.btn-text');
            const btnLoading = this.submitButton.querySelector('.btn-loading');

            if (loading) {
                this.submitButton.disabled = true;
                if (btnText) btnText.style.display = 'none';
                if (btnLoading) btnLoading.style.display = 'inline';
            } else {
                this.submitButton.disabled = false;
                if (btnText) btnText.style.display = 'inline';
                if (btnLoading) btnLoading.style.display = 'none';
            }
        }
    }
}

// SSE Ask functionality for streaming
class AskStreamWidget {
    constructor(container) {
        this.container = container;
        this.form = container.querySelector('.ask-form');
        this.questionInput = container.querySelector('.ask-input');
        this.contextInput = container.querySelector('.ask-context');
        this.submitButton = container.querySelector('.ask-submit');
        this.resultsContainer = container.querySelector('.ask-results');
        this.answerContent = container.querySelector('.answer-content');

        this.init();
    }

    init() {
        if (this.form) {
            this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        const question = this.questionInput.value.trim();
        const context = this.contextInput.value.trim();

        if (question.length < 3) {
            return;
        }

        this.setLoading(true);
        this.clearResults();

        try {
            await this.streamAnswer(question, context);
        } catch (error) {
            console.error('Stream error:', error);
        } finally {
            this.setLoading(false);
        }
    }

    async streamAnswer(question, context = '') {
        const params = new URLSearchParams({
            q: question,
            context
        });

        const response = await fetch(`/index.php?type=1703&${params.toString()}`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        if (this.resultsContainer) {
            this.resultsContainer.style.display = 'block';
        }

        while (true) {
            const { done, value } = await reader.read();

            if (done) break;

            buffer += decoder.decode(value, { stream: true });
            const lines = buffer.split('\n');
            buffer = lines.pop(); // Keep incomplete line in buffer

            for (const line of lines) {
                if (line.startsWith('data: ')) {
                    const data = line.slice(6);
                    if (data === '[DONE]') {
                        return;
                    }

                    try {
                        const parsed = JSON.parse(data);
                        this.updateAnswer(parsed);
                    } catch (e) {
                        // Ignore parsing errors for incomplete chunks
                    }
                }
            }
        }
    }

    updateAnswer(data) {
        if (data.answer && this.answerContent) {
            this.answerContent.innerHTML = data.answer;
        }
    }

    clearResults() {
        if (this.answerContent) {
            this.answerContent.innerHTML = '';
        }
    }

    setLoading(loading) {
        if (this.submitButton) {
            this.submitButton.disabled = loading;
        }
    }
}

// Initialize Ask widgets on page load
document.addEventListener('DOMContentLoaded', function () {
    // Initialize regular Ask widgets
    const askWidgets = document.querySelectorAll('.pixelcoda-ask, .pixelcoda-ask-content-element');
    askWidgets.forEach(widget => {
        widget.pixelcodaAskWidget = new AskWidget(widget);
    });

    // Initialize streaming Ask widgets (if they have a specific class)
    const streamAskWidgets = document.querySelectorAll('.pixelcoda-ask-stream');
    streamAskWidgets.forEach(widget => {
        widget.pixelcodaAskStreamWidget = new AskStreamWidget(widget);
    });
});

// Export functions for global use
window.toggleFilters = toggleFilters;
window.resetFilters = resetFilters;

function createSearchResult(result) {
    const attributes = result.attributes || {};
    const item = document.createElement('article');
    item.className = 'pixelcoda-search-result';

    const title = document.createElement('h3');
    const link = document.createElement('a');
    link.href = attributes.url || '#';
    link.textContent = attributes.title || 'Ohne Titel';
    title.appendChild(link);

    const summary = document.createElement('p');
    summary.textContent = attributes.summary || attributes.content || 'Keine Beschreibung verfügbar';

    const meta = document.createElement('p');
    meta.className = 'pixelcoda-search-result-meta';
    const score = attributes.score ? ` · Score: ${Math.round(attributes.score * 100)}%` : '';
    meta.textContent = `${attributes.collection || 'Unbekannt'}${score}`;

    item.append(title, summary, meta);
    return item;
}

function parseCsv(value, fallback = []) {
    const parsed = String(value || '')
        .split(',')
        .map(item => item.trim())
        .filter(item => item && item !== 'Array');

    return parsed.length > 0 ? parsed : fallback;
}

function isEnabled(value, fallback = true) {
    if (value === undefined) {
        return fallback;
    }

    return !['0', 'false', 'off', 'no'].includes(String(value).toLowerCase());
}

function createFacetButton(label, count, active, onClick) {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = active ? 'pixelcoda-search-facet is-active' : 'pixelcoda-search-facet';
    button.setAttribute('aria-pressed', active ? 'true' : 'false');
    button.textContent = `${label} ${count}`;
    button.addEventListener('click', onClick);
    return button;
}

function renderFacetGroup(title, values, activeValue, onSelect) {
    const group = document.createElement('div');
    group.className = 'pixelcoda-search-facet-group';

    const label = document.createElement('strong');
    label.className = 'pixelcoda-search-facet-title';
    label.textContent = title;
    group.appendChild(label);

    group.appendChild(createFacetButton('Alle', Object.values(values).reduce((sum, count) => sum + count, 0), !activeValue, () => onSelect('')));

    Object.entries(values)
        .sort(([labelA], [labelB]) => labelA.localeCompare(labelB))
        .forEach(([facetLabel, count]) => {
            group.appendChild(createFacetButton(facetLabel, count, activeValue === facetLabel, () => onSelect(facetLabel)));
        });

    return group;
}

function renderFacets(container, facets, state, onChange) {
    if (!container) {
        return;
    }

    const collections = facets?.collections || {};
    const categories = facets?.categories || {};
    container.replaceChildren();

    if (Object.keys(collections).length === 0 && Object.keys(categories).length === 0) {
        container.hidden = true;
        return;
    }

    const heading = document.createElement('h3');
    heading.className = 'pixelcoda-search-facets-heading';
    heading.textContent = 'Ergebnisse filtern';
    container.appendChild(heading);

    if (Object.keys(collections).length > 0) {
        container.appendChild(renderFacetGroup('Typ', collections, state.collection, value => {
            state.collection = value;
            state.page = 1;
            onChange();
        }));
    }

    if (Object.keys(categories).length > 0) {
        container.appendChild(renderFacetGroup('Kategorie', categories, state.category, value => {
            state.category = value;
            state.page = 1;
            onChange();
        }));
    }

    container.hidden = false;
}

function renderPagination(container, pagination, state, onChange) {
    if (!container) {
        return;
    }

    const pages = Number(pagination?.pages || 1);
    const current = Number(pagination?.page || state.page || 1);
    const total = Number(pagination?.total || 0);
    container.replaceChildren();

    if (pages <= 1) {
        container.hidden = true;
        return;
    }

    const list = document.createElement('ul');
    list.className = 'pagination';

    const addPageButton = (label, page, active = false, disabled = false) => {
        const item = document.createElement('li');
        item.className = active ? 'page-item active' : 'page-item';
        const button = document.createElement('button');
        button.type = 'button';
        button.className = active ? 'page-link current' : 'page-link';
        button.textContent = label;
        button.disabled = disabled || active;
        if (active) {
            button.setAttribute('aria-current', 'page');
        }
        button.addEventListener('click', () => {
            state.page = page;
            onChange();
        });
        item.appendChild(button);
        list.appendChild(item);
    };

    addPageButton('Zurück', Math.max(1, current - 1), false, current <= 1);
    for (let page = 1; page <= pages; page += 1) {
        addPageButton(String(page), page, page === current);
    }
    addPageButton('Weiter', Math.min(pages, current + 1), false, current >= pages);

    const info = document.createElement('p');
    info.className = 'pagination-info';
    info.textContent = `Seite ${current} von ${pages} · ${total} Treffer`;

    container.append(list, info);
    container.hidden = false;
}

function displayAiAnswer(container, payload) {
    const answer = payload.data?.attributes || {};
    const title = document.createElement('h4');
    title.textContent = 'KI-Antwort';

    const text = document.createElement('p');
    text.textContent = answer.text || answer.answer || 'Für diese Frage ist keine Antwort verfügbar.';

    container.replaceChildren(title, text);

    if (Array.isArray(payload.included) && payload.included.length > 0) {
        const sourcesTitle = document.createElement('h5');
        sourcesTitle.textContent = 'Quellen';
        const sources = document.createElement('ul');

        payload.included.forEach(source => {
            const attributes = source.attributes || {};
            const item = document.createElement('li');
            const link = document.createElement('a');
            link.href = attributes.url || '/';
            link.textContent = attributes.title || 'Quelle';
            item.appendChild(link);
            sources.appendChild(item);
        });

        container.append(sourcesTitle, sources);
    }

    container.hidden = false;
}

function initContentElementSearch(container) {
    if (container.dataset.searchInitialized === 'true') {
        return;
    }
    container.dataset.searchInitialized = 'true';

    const uid = container.dataset.uid;
    const configuredApiUrl = container.dataset.apiUrl || '';
    const configuredApiHost = (() => {
        try {
            return new URL(configuredApiUrl).hostname;
        } catch {
            return '';
        }
    })();
    const browserHostname = window.location.hostname;
    const isLocalBrowser = ['localhost', '127.0.0.1', '::1'].includes(browserHostname) || browserHostname.endsWith('.localhost');
    const localApiHosts = ['localhost', '127.0.0.1', '::1', 'host.docker.internal'];
    const isInternalApiUrl = localApiHosts.includes(configuredApiHost);
    const apiUrl = isInternalApiUrl
        ? (isLocalBrowser ? 'http://localhost:8787' : '/search-api')
        : configuredApiUrl || (isLocalBrowser ? 'http://localhost:8787' : '/search-api');
    const apiKey = container.dataset.apiKey || 'pc_read_dev_key';
    const project = container.dataset.project || 'typo3';
    const resultsPerPage = Number.parseInt(container.dataset.resultsPerPage, 10) || 10;
    const collections = parseCsv(container.dataset.collections, ['pages', 'tt_content']);
    const enableFacets = isEnabled(container.dataset.enableFacets, true);
    const state = {
        page: 1,
        query: '',
        category: '',
        collection: '',
        sort: container.dataset.sortOrder || 'relevance'
    };
    const form = container.querySelector(`#search-form-${uid}`);
    const input = container.querySelector(`#search-input-${uid}`);
    const status = container.querySelector(`#search-status-${uid}`);
    const results = container.querySelector(`#search-results-${uid}`);
    const resultsContent = container.querySelector(`#results-content-${uid}`);
    const facetsContent = container.querySelector(`#facets-content-${uid}`);
    const paginationContent = container.querySelector(`#pagination-content-${uid}`);
    const apiStatus = container.querySelector(`#api-status-${uid}`);
    const aiForm = container.querySelector(`#ai-form-${uid}`);
    const aiInput = container.querySelector(`#ai-input-${uid}`);
    const aiStatus = container.querySelector(`#ai-status-${uid}`);
    const aiResult = container.querySelector(`#ai-result-${uid}`);

    if (!form || !input || !status || !results || !resultsContent || !apiStatus) {
        return;
    }

    fetch(`${apiUrl}/health`)
        .then(response => {
            if (!response.ok) {
                throw new Error('API not available');
            }
            apiStatus.textContent = 'Online';
            apiStatus.className = 'pixelcoda-search-status is-online';
        })
        .catch(() => {
            apiStatus.textContent = 'Offline';
            apiStatus.className = 'pixelcoda-search-status is-offline';
        });

    const runSearch = async () => {
        status.hidden = false;
        status.classList.remove('d-none');
        results.hidden = true;
        results.style.display = 'none';
        resultsContent.replaceChildren();
        if (facetsContent) {
            facetsContent.replaceChildren();
            facetsContent.hidden = true;
        }
        if (paginationContent) {
            paginationContent.replaceChildren();
            paginationContent.hidden = true;
        }

        try {
            const requestCollections = state.collection ? [state.collection] : collections;
            const response = await fetch(`${apiUrl}/v1/search/${encodeURIComponent(project)}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Authorization: `Bearer ${apiKey}`
                },
                body: JSON.stringify({
                    q: state.query,
                    limit: resultsPerPage,
                    per_page: resultsPerPage,
                    page: state.page,
                    category: state.category,
                    sort: state.sort,
                    collections: requestCollections
                })
            });

            if (!response.ok) {
                throw new Error(`Search API returned ${response.status}`);
            }

            const payload = await response.json();
            const resultItems = Array.isArray(payload.data) ? payload.data : [];
            const meta = payload.meta || {};

            if (resultItems.length === 0) {
                const message = document.createElement('p');
                message.className = 'pixelcoda-search-empty';
                message.textContent = `Keine Ergebnisse für „${state.query}“ gefunden.`;
                resultsContent.appendChild(message);
            } else {
                resultItems.forEach(result => resultsContent.appendChild(createSearchResult(result)));
            }

            if (enableFacets) {
                renderFacets(facetsContent, meta.facets, state, runSearch);
            }
            renderPagination(paginationContent, meta.pagination, state, runSearch);

            results.hidden = false;
            results.style.display = 'block';
        } catch (error) {
            const message = document.createElement('p');
            message.className = 'pixelcoda-search-error';
            message.textContent = 'Die Suche ist momentan nicht erreichbar. Bitte versuchen Sie es später erneut.';
            resultsContent.appendChild(message);
            results.hidden = false;
            results.style.display = 'block';
            console.error('Pixelcoda Search request failed:', error);
        } finally {
            status.hidden = true;
            status.classList.add('d-none');
        }
    };

    form.addEventListener('submit', async event => {
        event.preventDefault();
        state.query = input.value.trim();
        state.page = 1;
        state.category = '';
        state.collection = '';
        if (!state.query) {
            input.focus();
            return;
        }

        await runSearch();
    });

    if (aiForm && aiInput && aiStatus && aiResult) {
        aiForm.addEventListener('submit', async event => {
            event.preventDefault();
            const question = aiInput.value.trim();
            if (question.length < 3) {
                aiInput.focus();
                return;
            }

            aiStatus.hidden = false;
            aiResult.hidden = true;

            try {
                const response = await fetch(`${apiUrl}/v1/ask/${encodeURIComponent(project)}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Authorization: `Bearer ${apiKey}`
                    },
                    body: JSON.stringify({
                        q: question,
                        maxPassages: 6,
                        collections
                    })
                });

                if (!response.ok) {
                    throw new Error(`Ask API returned ${response.status}`);
                }

                displayAiAnswer(aiResult, await response.json());
            } catch (error) {
                aiResult.textContent = 'Die KI-Antwort ist momentan nicht erreichbar. Bitte versuchen Sie es später erneut.';
                aiResult.classList.add('is-error');
                aiResult.hidden = false;
                console.error('Pixelcoda AI request failed:', error);
            } finally {
                aiStatus.hidden = true;
            }
        });
    }
}

function initContentElementSearchElements() {
    document.querySelectorAll('.pixelcoda-search-container').forEach(initContentElementSearch);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initContentElementSearchElements);
} else {
    initContentElementSearchElements();
}
