/**
 * PixelCoda Search - Filter Functions
 */

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
document.addEventListener('DOMContentLoaded', function() {
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
            element.addEventListener('change', function() {
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

// Export functions for global use
window.toggleFilters = toggleFilters;
window.resetFilters = resetFilters;