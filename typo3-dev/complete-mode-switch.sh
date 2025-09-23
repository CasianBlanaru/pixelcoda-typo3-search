#!/bin/bash

# TYPO3 Complete Mode Switch with Database Update
# ================================================

echo "🔄 TYPO3 Complete Mode Switcher"
echo "================================"
echo ""

# Check current mode
CURRENT_MODE=$(grep "renderingMode:" config/sites/main/config.yaml | awk '{print $2}')
echo "Current mode: $CURRENT_MODE"
echo ""

echo "Select new mode:"
echo "1) Headless (JSON output)"
echo "2) Standard (HTML output)"
echo ""
read -p "Enter choice [1-2]: " choice

case $choice in
    1)
        echo ""
        echo "🚀 Switching to HEADLESS mode..."
        
        # Update site config
        sed -i.bak "s/renderingMode: .*/renderingMode: headless/" config/sites/main/config.yaml
        
        # Activate headless extension
        cp config/system/PackageStates.php.headless config/system/PackageStates.php
        
        # Update database templates for Headless
        ddev mysql -e "
        UPDATE sys_template 
        SET include_static_file = 'EXT:headless/Configuration/TypoScript,EXT:headless/Configuration/TypoScript/Mixed,EXT:pixelcoda_search/Configuration/TypoScript'
        WHERE uid = 3;
        
        UPDATE sys_template 
        SET include_static_file = 'EXT:headless/Configuration/TypoScript,EXT:pixelcoda_search/Configuration/TypoScript'
        WHERE uid = 4;
        "
        
        echo "✅ Headless mode activated"
        MODE="headless"
        ;;
        
    2)
        echo ""
        echo "📄 Switching to STANDARD mode..."
        
        # Update site config
        sed -i.bak "s/renderingMode: .*/renderingMode: standard/" config/sites/main/config.yaml
        
        # Deactivate headless extension
        cp config/system/PackageStates.php.standard config/system/PackageStates.php
        
        # Update database templates for Standard
        ddev mysql -e "
        UPDATE sys_template 
        SET include_static_file = 'EXT:fluid_styled_content/Configuration/TypoScript/,EXT:pixelcoda_search/Configuration/TypoScript'
        WHERE uid = 3;
        
        UPDATE sys_template 
        SET include_static_file = 'EXT:fluid_styled_content/Configuration/TypoScript/'
        WHERE uid = 4;
        "
        
        echo "✅ Standard mode activated"
        MODE="standard"
        ;;
        
    *)
        echo "Invalid choice. Exiting."
        exit 1
        ;;
esac

# Clear all caches
echo ""
echo "🧹 Clearing all caches..."
rm -rf var/cache/*
rm -rf var/log/*
ddev exec typo3 cache:flush 2>/dev/null || true

# Restart DDEV for clean state
echo "🔄 Restarting DDEV..."
ddev restart

echo ""
echo "✨ Successfully switched to $MODE mode!"
echo ""

if [ "$MODE" == "headless" ]; then
    echo "📌 Headless Mode Active:"
    echo "   - JSON API: https://pixelcoda-typo3-dev.ddev.site/"
    echo "   - All pages return JSON"
    echo "   - Connect your React/Vue/Next.js frontend"
    echo ""
    echo "Test with: curl https://pixelcoda-typo3-dev.ddev.site/ | jq"
else
    echo "📌 Standard Mode Active:"
    echo "   - HTML output: https://pixelcoda-typo3-dev.ddev.site/"
    echo "   - Traditional TYPO3 with Fluid templates"
    echo "   - SEO-friendly HTML pages"
    echo ""
    echo "Open in browser: https://pixelcoda-typo3-dev.ddev.site/"
fi

echo ""
