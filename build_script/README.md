# Enree Minimal Theme Builder

A minimal Node.js build script for packaging the Enree Minimal WordPress theme.

## Features

- Creates a clean zip file of the theme
- Excludes development files (.git, .vscode, .DS_Store, etc.)
- Excludes documentation files (.gitignore, README.md)
- Outputs to `.release/` directory
- Colored console output
- Clean build option

## Installation

```bash
cd build_script
npm install
```

## Usage

```bash
# Build the theme
npm run build

# Clean build directory first, then build
npm run clean

# Show help
node build.js --help
```

## Output

The build script creates:
- `build_script/.release/enree-minimal.zip` - The packaged theme ready for WordPress installation

## Excluded Files/Folders

- `.git/` - Git repository
- `.vscode/` - VS Code settings
- `.DS_Store` - macOS system files
- `Thumbs.db` - Windows system files
- `desktop.ini` - Windows system files
- `.gitignore` - Git ignore file
- `README.md` - Documentation
- `node_modules/` - Node.js dependencies
- `build_script/` - Build script itself
- `.release/` - Build output directory
