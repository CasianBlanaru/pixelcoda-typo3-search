#!/bin/bash
# Extract fileadmin files on Railway
# Run this on Railway: bash deployment/extract-fileadmin.sh

set -e

echo "=========================================="
echo "Extracting fileadmin files"
echo "=========================================="

if [ ! -f "deployment/fileadmin-files.tar.gz" ]; then
    echo "ERROR: fileadmin-files.tar.gz not found"
    exit 1
fi

echo "Extracting files to public/ directory..."
cd public
tar -xzf ../deployment/fileadmin-files.tar.gz
cd ..

echo "Setting permissions..."
chmod -R 755 public/fileadmin

echo "=========================================="
echo "✓ Fileadmin extraction completed!"
echo "=========================================="
echo ""
echo "Files are now available at:"
echo "https://web-production-581b4.up.railway.app/fileadmin/"
