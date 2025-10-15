/**
 * Search Preview Functionality
 */
(function($) {
    'use strict';

    let searchTimeout;
    let currentQuery = '';
    let selectedIndex = -1;

    // Initialize search preview
    function initSearchPreview() {
        const $searchField = $('#search-field');
        const $searchPreview = $('#search-preview');
        const $searchForm = $('.search-form');

        if (!$searchField.length || !$searchPreview.length) {
            return;
        }

        // Handle input events
        $searchField.on('input keyup', function(e) {
            const query = $(this).val().trim();
            
            // Clear previous timeout
            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            // Handle special keys
            if (e.key === 'Escape') {
                hideSearchPreview();
                return;
            }

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                navigateResults('down');
                return;
            }

            if (e.key === 'ArrowUp') {
                e.preventDefault();
                navigateResults('up');
                return;
            }

            if (e.key === 'Enter' && selectedIndex >= 0) {
                e.preventDefault();
                const $selectedResult = $('.search-preview-result.selected');
                if ($selectedResult.length) {
                    window.location.href = $selectedResult.find('a').attr('href');
                }
                return;
            }

            // Show/hide preview based on query length
            if (query.length >= 2) {
                searchTimeout = setTimeout(function() {
                    performSearch(query);
                }, 300);
            } else {
                hideSearchPreview();
            }
        });

        // Handle focus events
        $searchField.on('focus', function() {
            if ($(this).val().trim().length >= 2) {
                showSearchPreview();
            }
        });

        // Handle blur events (with delay to allow clicking on results)
        $searchField.on('blur', function() {
            setTimeout(function() {
                if (!$searchPreview.is(':hover') && !$searchField.is(':focus')) {
                    hideSearchPreview();
                }
            }, 150);
        });

        // Handle preview hover
        $searchPreview.on('mouseenter', function() {
            $(this).addClass('hovered');
        }).on('mouseleave', function() {
            $(this).removeClass('hovered');
        });

        // Handle close button
        $('.search-preview-close').on('click', function() {
            hideSearchPreview();
            $searchField.focus();
        });

        // Handle view all link
        $('.search-preview-view-all').on('click', function(e) {
            e.preventDefault();
            if (currentQuery) {
                window.location.href = $searchForm.attr('action') + '?s=' + encodeURIComponent(currentQuery);
            }
        });

        // Handle result clicks
        $(document).on('click', '.search-preview-result a', function(e) {
            e.preventDefault();
            window.location.href = $(this).attr('href');
        });

        // Handle clicks outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-form, .search-preview').length) {
                hideSearchPreview();
            }
        });
    }

    // Perform AJAX search
    function performSearch(query) {
        if (query === currentQuery) {
            return;
        }

        currentQuery = query;
        selectedIndex = -1;

        const $preview = $('#search-preview');
        const $results = $('.search-preview-results');
        const $loading = $('.search-preview-loading');

        showSearchPreview();
        $loading.show();

        $.ajax({
            url: enree_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'enree_search',
                query: query,
                nonce: enree_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    displaySearchResults(response.data);
                } else {
                    displayNoResults();
                }
            },
            error: function() {
                displayError();
            },
            complete: function() {
                $loading.hide();
            }
        });
    }

    // Display search results
    function displaySearchResults(data) {
        const $results = $('.search-preview-results');
        let html = '';

        if (data.results.length > 0) {
            data.results.forEach(function(result, index) {
                const thumbnail = result.thumbnail ? 
                    `<div class="search-result-thumbnail">
                        <img src="${result.thumbnail}" alt="${result.title}" />
                    </div>` : '';

                html += `
                    <div class="search-preview-result" data-index="${index}">
                        <a href="${result.url}">
                            ${thumbnail}
                            <div class="search-result-content">
                                <h4 class="search-result-title">${result.title}</h4>
                                <p class="search-result-excerpt">${result.excerpt}</p>
                                <div class="search-result-meta">
                                    <span class="search-result-date">${result.date}</span>
                                    <span class="search-result-type">${result.type}</span>
                                </div>
                            </div>
                        </a>
                    </div>
                `;
            });

            // Update view all link
            $('.search-preview-view-all').attr('href', 
                $('.search-form').attr('action') + '?s=' + encodeURIComponent(data.query)
            );
        } else {
            html = '<div class="search-preview-no-results">No results found for "' + data.query + '"</div>';
        }

        $results.html(html);
    }

    // Display no results message
    function displayNoResults() {
        const $results = $('.search-preview-results');
        $results.html('<div class="search-preview-no-results">No results found</div>');
    }

    // Display error message
    function displayError() {
        const $results = $('.search-preview-results');
        $results.html('<div class="search-preview-error">Error during search. Please try again later.</div>');
    }

    // Show search preview
    function showSearchPreview() {
        $('#search-preview').show();
    }

    // Hide search preview
    function hideSearchPreview() {
        $('#search-preview').hide();
        selectedIndex = -1;
        $('.search-preview-result').removeClass('selected');
    }

    // Navigate results with keyboard
    function navigateResults(direction) {
        const $results = $('.search-preview-result');
        
        if ($results.length === 0) {
            return;
        }

        // Remove current selection
        $results.removeClass('selected');

        if (direction === 'down') {
            selectedIndex = Math.min(selectedIndex + 1, $results.length - 1);
        } else if (direction === 'up') {
            selectedIndex = Math.max(selectedIndex - 1, -1);
        }

        // Add selection to current item
        if (selectedIndex >= 0) {
            $results.eq(selectedIndex).addClass('selected');
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        initSearchPreview();
    });

})(jQuery);
