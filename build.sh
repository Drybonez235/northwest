#!/bin/bash
echo "🚀 Preparing theme for production..."

# 1. Build CSS
npx tailwindcss -i ./src/style.css -o ./dist/style.css --minify

# 2. Clean up PHP dependencies
composer install --no-dev --optimize-autoloader

git add .
echo "✅ Ready! Upload northwest to your server."