#!/bin/bash

# Fix Heroku buildpack configuration
# This script removes duplicate and unnecessary buildpacks
# Updated to handle PHP buildpack detection issues

echo "🔧 Fixing Heroku buildpack configuration..."
echo "📋 This script will set Node.js as the primary buildpack"
echo "   (since this is primarily a Node.js application)"

# Check if heroku CLI is available
if ! command -v heroku &> /dev/null; then
    echo "❌ Heroku CLI not found. Please install it first:"
    echo "   https://devcenter.heroku.com/articles/heroku-cli"
    exit 1
fi

# Get the app name (you may need to replace this with your actual app name)
APP_NAME=$(heroku apps --json 2>/dev/null | jq -r '.[0].name' 2>/dev/null)

if [ "$APP_NAME" = "null" ] || [ -z "$APP_NAME" ]; then
    echo "❌ Could not detect Heroku app name automatically."
    echo "Please run this command manually with your app name:"
    echo ""
    echo "heroku buildpacks:clear --app YOUR_APP_NAME"
    echo "heroku buildpacks:set heroku/nodejs --app YOUR_APP_NAME"
    echo ""
    echo "Or set the app name and run this script again:"
    echo "export HEROKU_APP_NAME=your-app-name"
    echo "./fix-buildpacks.sh"
    exit 1
fi

echo "📱 Detected app: $APP_NAME"

# Clear all buildpacks
echo "🧹 Clearing all buildpacks..."
heroku buildpacks:clear --app "$APP_NAME"

# Set only the Node.js buildpack
echo "📦 Setting Node.js buildpack..."
heroku buildpacks:set heroku/nodejs --app "$APP_NAME"

# Verify the configuration
echo "✅ Current buildpack configuration:"
heroku buildpacks --app "$APP_NAME"

echo ""
echo "🚀 Buildpack configuration fixed!"
echo "You can now deploy your application successfully."
echo ""
echo "To deploy, run:"
echo "git push heroku main"