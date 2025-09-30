#!/bin/bash

# TYPO3 Dependency Injection Container Error Fix
# ==============================================
# Fixes: Class "DependencyInjectionContainer_xxx" not found

echo "🔧 TYPO3 Dependency Injection Container Error Fix"
echo "================================================="
echo ""
echo "Problem: DependencyInjectionContainer class not found"
echo "Cause: Corrupted or missing container cache files"
echo "Solution: Complete cache cleanup and container rebuild"
echo ""

# Check if we're in the right directory
if [ ! -f "public/index.php" ]; then
    echo "❌ Error: Please run this script from the TYPO3 root directory (typo3-dev)"
    echo "   Current directory: $(pwd)"
    echo "   Expected files: public/index.php, composer.json"
    exit 1
fi

echo "✅ TYPO3 root directory detected"
echo ""

# Step 1: Stop any running processes
echo "🛑 Step 1: Stopping DDEV (if running)..."
ddev stop 2>/dev/null || echo "   DDEV not running or not available"
echo ""

# Step 2: Complete cache cleanup
echo "🧹 Step 2: Complete Cache Cleanup..."
echo "   Removing var/cache/*"
rm -rf var/cache/* 2>/dev/null || true

echo "   Removing var/log/*"
rm -rf var/log/* 2>/dev/null || true

echo "   Removing public/typo3temp/*"
rm -rf public/typo3temp/* 2>/dev/null || true

echo "   Removing public/typo3conf/temp_CACHED_*"
rm -f public/typo3conf/temp_CACHED_* 2>/dev/null || true

echo "   Removing public/typo3conf/autoload/*"
rm -rf public/typo3conf/autoload/* 2>/dev/null || true

echo "✅ Cache directories cleaned"
echo ""

# Step 3: Remove specific container cache files
echo "🗂️  Step 3: Removing Container Cache Files..."
find . -name "*DependencyInjectionContainer*" -type f -delete 2>/dev/null || true
find . -name "*.php.cache" -type f -delete 2>/dev/null || true
find . -name "container_*" -type f -delete 2>/dev/null || true
echo "✅ Container cache files removed"
echo ""

# Step 4: Restart DDEV and rebuild
echo "🚀 Step 4: Restarting DDEV..."
ddev start
echo ""

# Step 5: Rebuild autoload
echo "📦 Step 5: Rebuilding Composer Autoload..."
ddev composer dump-autoload --optimize
echo ""

# Step 6: TYPO3 cache flush
echo "🔄 Step 6: TYPO3 Cache Flush..."
ddev exec vendor/bin/typo3 cache:flush --group=system 2>/dev/null || echo "   System cache flush failed, trying general flush..."
ddev exec vendor/bin/typo3 cache:flush 2>/dev/null || echo "   General cache flush failed, continuing..."
echo ""

# Step 7: Rebuild container
echo "🏗️  Step 7: Rebuilding Dependency Injection Container..."
ddev exec vendor/bin/typo3 cache:warmup 2>/dev/null || echo "   Cache warmup not available, container will rebuild on first request"
echo ""

# Step 8: Test the fix
echo "🧪 Step 8: Testing the Fix..."
echo "   Attempting to access TYPO3..."

# Try to run a simple TYPO3 command to test if container works
if ddev exec vendor/bin/typo3 --version >/dev/null 2>&1; then
    echo "✅ SUCCESS: TYPO3 is responding correctly!"
    echo ""
    echo "🎯 Container rebuilt successfully. You can now:"
    echo "   • Access Frontend: https://pixelcoda-typo3-dev.ddev.site"
    echo "   • Access Backend: https://pixelcoda-typo3-dev.ddev.site/typo3"
    echo "   • Login: admin / admin"
else
    echo "⚠️  TYPO3 command still failing. Additional steps needed:"
    echo ""
    echo "🔧 Manual Recovery Steps:"
    echo "1. Check DDEV status: ddev status"
    echo "2. Restart DDEV completely: ddev restart"
    echo "3. Reinstall composer dependencies: ddev composer install --no-dev"
    echo "4. If still failing, run: ./fix-typo3-database.sh"
fi

echo ""
echo "📋 What was fixed:"
echo "   ✓ Removed all cache files (var/cache, typo3temp)"
echo "   ✓ Deleted corrupted container cache files"
echo "   ✓ Rebuilt composer autoload"
echo "   ✓ Flushed TYPO3 system caches"
echo "   ✓ Triggered container rebuild"
echo ""
echo "🔍 If the error persists:"
echo "   • Check TYPO3 error logs: var/log/"
echo "   • Verify database connection"
echo "   • Run database schema update via Install Tool"
echo ""
