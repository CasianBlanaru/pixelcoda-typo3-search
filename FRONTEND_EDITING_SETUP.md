# TYPO3 Frontend Editing Extension - Installation & Configuration Guide

## Overview

This guide covers the complete setup of TYPO3 Frontend Editing for the headless architecture running on:
- **Backend**: https://web-production-581b4.up.railway.app/
- **Frontend**: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/

## What's Installed

### Backend (TYPO3)
- Extension: `pixelcoda/fe-editor` v1.2.5
- Location: `packages/typo3_fe_editing/packages/pixelcoda_fe_editor/`
- Repository: Local path repository in `composer.json`

### Frontend (Next.js)
- Component: `FrontendEditor.jsx` - Loads TYPO3 editor assets
- Component: `EditableContent.jsx` - React wrappers with edit markers
- Integration: Added to `layout.jsx` root layout

## Installation Steps

### 1. Backend Setup

The extension is already configured via path repository:

```bash
# Already in composer.json:
composer require pixelcoda/fe-editor:@dev

# Activate extension
vendor/bin/typo3 extension:setup

# Clear caches
vendor/bin/typo3 cache:flush
```

### 2. Frontend Integration

Frontend components are already created:

```
frontend/src/components/
├── FrontendEditor.jsx       # Asset loader
├── EditableContent.jsx      # React wrappers
└── GsapAnimatedContent.jsx

frontend/src/app/
└── layout.jsx               # Includes FrontendEditor
```

### 3. Environment Configuration

**Local Development** (`.ddev/.env.web`):
```bash
# Frontend Editing
TYPO3_FE_EDITING_ENABLED=1

# AI Configuration (Optional)
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4.1-mini
```

**Railway Backend Environment Variables**:
```
TYPO3_FE_EDITING_ENABLED=1
OPENAI_API_KEY=sk-...
OPENAI_MODEL=gpt-4.1-mini
```

**Railway Frontend Environment Variables**:
```
NEXT_PUBLIC_API_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_TYPO3_BASE_URL=https://web-production-581b4.up.railway.app
NEXT_PUBLIC_BASE_URL=https://nextjs-front-end-for-typo3-headless-production.up.railway.app
```

## Usage Examples

### Basic Content Element

```jsx
import { ContentElement, EditableHeadline, EditableBodytext } from '@/components/EditableContent';

export default async function BlogPost({ params }) {
  const content = await fetchFromTypo3(params.slug);
  
  return (
    <ContentElement uid={content.id} type={content.type}>
      <EditableHeadline uid={content.id} level="h1">
        {content.header}
      </EditableHeadline>
      
      <EditableBodytext uid={content.id}>
        {content.bodytext}
      </EditableBodytext>
    </ContentElement>
  );
}
```

### Custom Fields

```jsx
import { EditableContent } from '@/components/EditableContent';

<EditableContent uid={content.id} field="subheader">
  {content.subheader}
</EditableContent>

<EditableContent uid={content.id} field="teaser" table="pages">
  {page.teaser}
</EditableContent>
```

## Features

✅ **Inline Editing** - Click "Edit", modify text, autosave
✅ **Drag & Drop** - Reorder content elements
✅ **Image Editing** - Opens TYPO3 contextual editor
✅ **AI Assistant** - Optional rewriting with OpenAI/Claude/Mistral
✅ **Headless Support** - Works with JSON API responses
✅ **Accessibility** - WCAG 2.1 AA compliant
✅ **Mobile Ready** - Responsive toolbar
✅ **CSRF Protection** - Secure via TYPO3 FormProtection

## Backend User Requirements

1. Log into TYPO3 Backend first
2. User must have `tt_content` edit permissions
3. UserTSconfig can disable per user:
   ```typoscript
   tx_pixelcodafeeditor.disabled = 1
   ```

## CORS Configuration

Already configured in `packages/pixelcoda_sitepackage/Configuration/TypoScript/FrontendEditing/setup.typoscript`

Frontend domain is whitelisted for AJAX requests.

## Testing

### Local Testing
```bash
# Start DDEV
ddev start

# Access frontend
open https://typo3-inst.localhost

# Login to TYPO3
open https://typo3-inst.localhost/typo3

# Clear caches
ddev exec vendor/bin/typo3 cache:flush
```

### Railway Testing
1. Deploy to Railway
2. Login to backend: https://web-production-581b4.up.railway.app/typo3
3. Visit frontend: https://nextjs-front-end-for-typo3-headless-production.up.railway.app/
4. Toolbar should appear if logged in

## Troubleshooting

### Toolbar Not Visible
- Clear browser cache
- Check backend login status
- Verify user permissions
- Check browser console for errors
- Flush TYPO3 caches

### CORS Errors
- Verify frontend URL in TypoScript CORS headers
- Check Railway environment variables
- Ensure credentials mode enabled

### Changes Not Saving
- Check backend user has `tt_content` modify permissions
- Verify content comes from database, not hardcoded
- Check TYPO3 logs: `var/log/typo3_*.log`

### AI Not Working
- Set `OPENAI_API_KEY` in Railway environment
- Or configure in TYPO3: Admin Tools > Extension Configuration
- Check API key validity

## Git Workflow

```bash
# Stage changes
git add .

# Commit
git commit -m "Add TYPO3 Frontend Editing with headless support

- Install pixelcoda/fe-editor extension
- Add Next.js FrontendEditor component
- Add EditableContent React wrappers
- Configure CORS for Railway domains
- Add documentation and examples"

# Push to Railway (auto-deploys)
git push origin main
```

## Files Modified/Created

### Backend
- `composer.json` - Already has path repository
- `packages/pixelcoda_sitepackage/Configuration/TypoScript/FrontendEditing/setup.typoscript` - CORS config

### Frontend
- `frontend/src/components/FrontendEditor.jsx` - NEW
- `frontend/src/components/EditableContent.jsx` - NEW
- `frontend/src/app/layout.jsx` - MODIFIED (added FrontendEditor)
- `frontend/FRONTEND_EDITING.md` - NEW

### Documentation
- `FRONTEND_EDITING_SETUP.md` - NEW (this file)
- `.gitignore` - UPDATED (exclude cache/logs)

## Next Steps

1. **Test Locally**: Clear caches, login, verify toolbar appears
2. **Commit Changes**: Use git workflow above
3. **Deploy to Railway**: Push triggers auto-deployment
4. **Test Production**: Login to Railway backend, verify frontend editing
5. **Configure AI**: Add OpenAI key if needed

## Support

- Extension Issues: https://github.com/CasianBlanaru/pixelcoda-typo3-fe-editing/issues
- TYPO3 Docs: https://docs.typo3.org/
- Pixelcoda: casianus@me.com

## Security Notes

✅ All writes via TYPO3 DataHandler
✅ CSRF protection via backend FormProtection
✅ AI keys server-side only (never exposed to frontend)
✅ Requires authenticated backend user
✅ Respects TYPO3 user permissions
