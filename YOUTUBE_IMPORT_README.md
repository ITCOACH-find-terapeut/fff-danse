# YouTube Import System - Documentation

## Overview

The YouTube import system for FFF Danse plugin has been built with the following components:

## Components Built

### 1. Parser (`/includes/class-parser.php`)

Advanced parser that extracts structured data from YouTube video descriptions.

**Features:**
- Parses structured blocks: `Intro:`, `SE1:`, `SE2:`, `SE3:`, `LÆR1:`, `LÆR2:`, `LÆR3:`, `DANS1:`, `DANS2:`, `DANS3:`
- Extracts key:value pairs from description
- Normalizes video IDs from various YouTube URL formats
- Maps structured blocks to WordPress field keys

**Usage:**
```php
use FFF_Danse\Includes\Parser;

$parsed = Parser::parse( $description, $title, $video_id );
// Returns array of field_key => value pairs
```

**Structured Block Format:**
The parser looks for blocks in YouTube descriptions like:
```
Intro: This is the intro content
SE1: Sequence 1 content
LÆR1: Learn sequence 1 content
DANS1: Dance sequence 1 content
```

### 2. Importer (`/includes/class-importer.php`)

Main import class that handles YouTube API communication and data import.

**Features:**
- Fetches video data from YouTube Data API v3
- Respects settings (only imports allowed fields)
- Handles overwrite logic
- Can import single videos or batch imports
- Creates posts automatically if needed

**Key Methods:**
- `import_video( $post_id, $video_input, $overwrite )` - Import single video
- `fetch_video_data( $video_id )` - Fetch from YouTube API
- `import_all( $video_ids, $create_posts )` - Batch import

### 3. WP-CLI Commands (`/includes/class-wpcli.php`)

Command-line interface for importing videos.

**Commands:**

**Import single video:**
```bash
wp fff import <video_id> <post_id> [--overwrite=true]
```

Examples:
```bash
wp fff import dQw4w9WgXcQ 123
wp fff import "https://www.youtube.com/watch?v=dQw4w9WgXcQ" 123 --overwrite=true
```

**Import all videos:**
```bash
wp fff import-all [--file=/path/to/videos.txt] [--create] [--limit=10]
```

Examples:
```bash
# Import all existing posts with video_videofil meta
wp fff import-all

# Import from file and create new posts
wp fff import-all --file=/path/to/videos.txt --create

# Test with limit
wp fff import-all --limit=10
```

### 4. AJAX Handler (`/includes/class-ajax.php`)

Handles AJAX requests from the admin interface.

**Features:**
- Permissions checking
- Nonce verification
- Error handling
- Returns structured JSON responses

**AJAX Action:** `fff_danse_fetch_youtube`

### 5. Admin JavaScript (`/assets/js/fff-danse-admin.js`)

Frontend JavaScript for the admin meta box.

**Features:**
- AJAX import button
- Loading states
- Error handling
- Auto-reload after successful import
- Enter key support

## How It Works

### Import Flow

1. **User provides video URL/ID** (via admin UI or WP-CLI)
2. **Video ID extracted** using `Parser::extract_video_id()`
3. **Data fetched** from YouTube API using `Importer::fetch_video_data()`
4. **Description parsed** using `Parser::parse()` to extract:
   - Structured blocks (Intro:, SE1:, LÆR1:, etc.)
   - Key:value pairs
5. **Fields filtered** by allowed import settings
6. **Data saved** to post meta respecting overwrite settings
7. **Post title updated** if `danse_navn` field was imported

### Structured Block Parsing

The parser recognizes these block headers:
- `Intro:` → maps to `video_intro` field
- `SE1:`, `SE2:`, `SE3:` → maps to `video_se1`, `video_se2`, `video_se3`
- `LÆR1:`, `LÆR2:`, `LÆR3:` → maps to `video_lær1`, `video_lær2`, `video_lær3`
- `DANS1:`, `DANS2:`, `DANS3:` → maps to `video_dans1`, `video_dans2`, `video_dans3`

### Field Mapping

The parser automatically maps:
- Video title → `danse_navn` (if allowed)
- Video ID → `video_videofil` (if allowed)
- Structured blocks → corresponding `video_*` fields
- Key:value pairs → matching field keys

## Configuration

### Required Settings

1. **YouTube API Key** - Set in Settings → FFF Danse
2. **Allowed Import Fields** - Check fields that can be imported/overwritten

### Field Control

Only fields checked in "Hente fra YouTube" settings will be:
- Shown in backend meta boxes
- Imported from YouTube
- Overwritten during import

## Error Handling

The system handles:
- Missing API key
- Invalid video IDs
- YouTube API errors
- Network timeouts
- Missing field definitions
- Permission errors

All errors are logged and returned to the user in a user-friendly format.

## Testing

### Test Parser
```php
$description = "Intro: This is intro\nSE1: Sequence 1\nLÆR1: Learn 1";
$parsed = Parser::parse( $description );
// Check $parsed['video_intro'], $parsed['video_se1'], etc.
```

### Test Importer
```php
$result = Importer::import_video( 123, 'dQw4w9WgXcQ', true );
if ( is_wp_error( $result ) ) {
    echo $result->get_error_message();
} else {
    echo "Updated: " . implode( ', ', $result['updated_fields'] );
}
```

### Test WP-CLI
```bash
# Dry run with test video
wp fff import dQw4w9WgXcQ 123
```

## Next Steps

To complete the integration:
1. Update main plugin file to initialize all components
2. Create admin meta boxes class
3. Create settings page class
4. Create frontend template
5. Register all hooks and filters



